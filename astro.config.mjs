import { defineConfig } from "astro/config";

export default defineConfig({
  // Emit es/about-me.html instead of es/about-me/index.html, so Cloudflare
  // serves /es/about-me without redirecting to a trailing slash.
  build: { format: "file" },
  markdown: {
    shikiConfig: { themes: { light: "github-light", dark: "github-dark" } },
  },
});
