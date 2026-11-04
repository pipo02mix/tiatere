---
title: "Learning Envoy, part 1: how a request moves through Envoy"
date: 2026-08-20
description: The smallest useful Envoy config, how a request travels from the listener to the backend, and the admin endpoints that tell you what a running Envoy is doing.
---

At work I help teams run Envoy Gateway on Kubernetes. When something breaks, the answer is almost always in the Envoy config that Envoy Gateway generates. The problem is that this config is long, and it only makes sense once you know how Envoy itself is put together.

So I went back to the start. I built a set of small examples that run Envoy on its own in Docker, without Kubernetes, and only then added Envoy Gateway on top. They're all in [giantswarm/envoy-gateway-examples](https://github.com/giantswarm/envoy-gateway-examples). This series walks through them in order, with the tests I ran and what surprised me along the way.

This first post covers the basics: what an Envoy config looks like, and how to ask a running Envoy what it's doing.

### The smallest useful config

Every example runs with Docker Compose. There are two containers: Envoy, and a small Flask app called `helloworld` that answers on port 8080. You can start the first one like this:

```bash
cd 01-envoy-proxy/01-helloworld-static
make up
curl localhost:10000/
```

Here's the Envoy config, without the comments:

```yaml
admin:
  address:
    socket_address: { address: 0.0.0.0, port_value: 9901 }
static_resources:
  listeners:
    - name: listener_http
      address:
        socket_address: { address: 0.0.0.0, port_value: 10000 }
      filter_chains:
        - filters:
            - name: envoy.filters.network.http_connection_manager
              typed_config:
                "@type": type.googleapis.com/envoy.extensions.filters.network.http_connection_manager.v3.HttpConnectionManager
                stat_prefix: ingress_http
                http_filters:
                  - name: envoy.filters.http.router
                    typed_config:
                      "@type": type.googleapis.com/envoy.extensions.filters.http.router.v3.Router
                route_config:
                  virtual_hosts:
                    - name: local_service
                      domains: ["*"]
                      routes:
                        - match: { prefix: "/" }
                          route: { cluster: helloworld_cluster }
  clusters:
    - name: helloworld_cluster
      type: STRICT_DNS
      lb_policy: ROUND_ROBIN
      load_assignment:
        cluster_name: helloworld_cluster
        endpoints:
          - lb_endpoints:
              - endpoint:
                  address:
                    socket_address: { address: helloworld, port_value: 8080 }
```

It looks like a lot for "forward everything to one app", but every block has a job:

- A **listener** owns an address and port, here `:10000`.
- The listener has a **filter chain**. Its filters work on raw TCP bytes.
- The most important of those filters is the **HTTP connection manager**, or HCM. It parses HTTP off the wire.
- Inside the HCM there's a list of **HTTP filters**. They run in order on each request. The last one is always the **router**, which sends the request upstream.
- The HCM also holds the **route table**. A virtual host is picked by the `Host` header, and then the first route whose `match` fits the request wins.
- The route points at a **cluster**, a named group of backends. With `STRICT_DNS`, Envoy resolves `helloworld` through DNS and uses every address it gets back as an endpoint.

So a request goes listener → filter chain → HCM → HTTP filters → route → cluster → endpoint. This chain is the most useful thing to learn about Envoy. Every later feature plugs into one of these steps. Rate limits and authentication are HTTP filters. Retries and timeouts live on the route. Health checks and circuit breakers live on the cluster.

### Breaking it on purpose

The best test in this first example was breaking it. I changed the cluster address from `helloworld` to `doesnotexist` and restarted. The request now fails with a `503` and the body `no healthy upstream`.

The access log line has a `UH` in the response flags column, right after the status code:

```
[2026-11-04T11:32:41.543Z] "GET / HTTP/1.1" 503 UH 0 19 1 - "-" "curl/8.7.1" "c1e9dac8-31be-4f0b-8876-141357e5b4e7" "localhost:10000" "-"
```

Envoy writes these short codes on every log line, and they tell you why a request failed without reading any config. `UH` means there was no healthy host in the cluster. I'll come back to these flags in later posts, since they turn out to be the fastest way to debug Envoy.

### Asking Envoy what it's doing

The `admin` block at the top opens a second HTTP server on port 9901. It isn't on the request path. It's there so you can look inside a running Envoy:

```bash
curl -s localhost:9901/ready
curl -s localhost:9901/listeners
curl -s localhost:9901/clusters
curl -s localhost:9901/stats | grep helloworld_cluster
curl -s localhost:9901/config_dump | jq .
```

`/config_dump` returns the whole live config. On a static setup like this one, that's the same YAML you wrote. Once Envoy Gateway is in charge, it's the only place you can see what Envoy actually received. `/clusters` shows each endpoint with its health and request counters, and `/stats` has a counter for almost everything.

Note that some admin paths change state. `POST /logging?level=debug` changes the log level, `POST /runtime_modify` sets runtime values, and `POST /quitquitquit` stops Envoy. In production, don't expose the admin port beyond localhost.

### Routes are checked in order

The third example adds more routes, and two things caught me out.

First, routes are checked top to bottom and the first match wins. A catch-all `prefix: "/"` above a more specific route means the specific one never runs. The order of the list is the policy.

Second, `prefix_rewrite` only replaces the matched prefix. It keeps the rest of the path. I wanted every path under `/admin` to go to the backend's `/echo` endpoint, so I wrote this:

```yaml
- match: { prefix: "/admin" }
  route:
    cluster: helloworld_cluster
    prefix_rewrite: "/echo"
```

`/admin` became `/echo` as expected. But `/admin/users` became `/echo/users`, and the backend returned a `404`. To replace the whole path, use `regex_rewrite` instead:

```yaml
- match: { prefix: "/admin" }
  route:
    cluster: helloworld_cluster
    regex_rewrite:
      pattern: { regex: ".*" }
      substitution: "/echo"
```

My rule now is to use `prefix_rewrite` when I peel a fixed prefix off and forward the rest, and `regex_rewrite` when many paths should land on one endpoint.

### What's next

With the request path clear, the next post looks at what happens when backends misbehave: load balancing, health checks, retries, timeouts and circuit breakers. Each one is a small change to the route or the cluster, and each one has a test that shows it working.
