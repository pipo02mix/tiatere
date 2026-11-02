const FROM = "Tiatere <noreply@tiatere.es>";
const TO = "info@tiatere.es";
const LOCALES = ["es", "en"];
const EMAIL_PATTERN = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

// These live here and not in a _redirects file, because Cloudflare skips
// _redirects for requests that no static asset matches, and none of these
// paths has one.
const REDIRECTS = {
  "/": ["/es", 302],
  "/es/about-me": ["/es", 301],
  "/en/about-me": ["/en", 301],
};

export default {
  async fetch(request, env) {
    const url = new URL(request.url);
    const redirect = REDIRECTS[url.pathname];
    if (redirect) {
      return Response.redirect(new URL(redirect[0], request.url), redirect[1]);
    }
    if (url.pathname === "/api/contact" && request.method === "POST") {
      return handleContact(request, env);
    }
    return env.ASSETS.fetch(request);
  },
};

async function handleContact(request, env) {
  const form = await request.formData();
  const name = String(form.get("persona") ?? "").trim();
  const email = String(form.get("email") ?? "").trim();
  const query = String(form.get("consulta") ?? "").trim();
  const locale = LOCALES.includes(form.get("locale")) ? form.get("locale") : "es";

  if (!name || !query || !EMAIL_PATTERN.test(email)) {
    return new Response("Invalid contact request", { status: 400 });
  }

  const response = await fetch("https://api.resend.com/emails", {
    method: "POST",
    headers: {
      Authorization: `Bearer ${env.RESEND_API_KEY}`,
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      from: FROM,
      to: [TO],
      reply_to: email,
      subject: `Tiatere Feedback ${name}`,
      text: `${name} <${email}>\n\n${query}`,
    }),
  });

  if (!response.ok) {
    console.error(`Resend failed with status ${response.status}: ${await response.text()}`);
    return new Response("The message could not be sent", { status: 502 });
  }

  return Response.redirect(new URL(`/${locale}/contact-done`, request.url), 303);
}
