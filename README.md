# tiatere

My personal website, in Spanish and English. It runs on Cloudflare Workers.

The pages are static HTML built with [Astro](https://astro.build). Blog posts are Markdown files in
`src/content/blog`. To publish one, add a file there with a `title`, `date` and `description` at the
top, and deploy. The file name becomes the post's URL. A small Worker in `worker/index.js` serves the pages and handles the contact form.
It sends each message to `info@tiatere.es` through [Resend](https://resend.com).

## Set up Resend

First, add `tiatere.es` as a domain in Resend and create its DNS records in Cloudflare. Then
create a Resend API key with sending access for that domain. Finally, store the key as a Worker
secret:

```sh
npx wrangler secret put RESEND_API_KEY
```

For `npm run preview`, put the key in a `.dev.vars` file instead:

```sh
RESEND_API_KEY=re_...
```

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

Note that this sends real email through Resend.

## Deploy

```sh
npx wrangler login
npm run deploy
```

This builds the site and deploys it as the `tiatere` Worker. To serve it on `tiatere.es`, add the
domain under the Worker's Settings > Domains & Routes in the Cloudflare dashboard.
