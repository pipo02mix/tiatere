# tiatere

My personal website, in Spanish and English. It runs on Cloudflare Workers.

The pages are static HTML built with [Astro](https://astro.build). Blog posts come from the
[Medium feed](https://medium.com/feed/@tiatere) when the site is built, so new posts show up after
the next deploy. A small Worker in `worker/index.js` serves the pages and handles the contact form.
It sends each message to `info@tiatere.es` through Cloudflare Email Routing.

## Set up Cloudflare

The contact form needs Email Routing on the `tiatere.es` zone. First, turn on Email Routing for
the zone in the Cloudflare dashboard. Then add `info@tiatere.es` as a verified destination address.

## Develop

```sh
npm install
npm run dev
```

This serves the pages at `http://localhost:4321`. The contact form doesn't work here, because the
form is handled by the Worker.

To run the whole site with the Worker, use:

```sh
npm run preview
```

Note that `wrangler dev` doesn't send real email. It writes each message to a local file and prints
its path.

## Deploy

```sh
npx wrangler login
npm run deploy
```

This builds the site and deploys it as the `tiatere` Worker. To serve it on `tiatere.es`, add the
domain under the Worker's Settings > Domains & Routes in the Cloudflare dashboard.
