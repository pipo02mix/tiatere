import { EmailMessage } from "cloudflare:email";
import { createMimeMessage } from "mimetext";

const FROM = "noreply@tiatere.es";
const TO = "info@tiatere.es";
const LOCALES = ["es", "en"];
const EMAIL_PATTERN = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

export default {
  async fetch(request, env) {
    const url = new URL(request.url);
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

  const message = createMimeMessage();
  message.setSender({ name: "Tiatere", addr: FROM });
  message.setRecipient(TO);
  message.setHeader("Reply-To", email);
  message.setSubject(`Tiatere Feedback ${name}`);
  message.addMessage({
    contentType: "text/plain",
    data: `${name} <${email}>\n\n${query}`,
  });

  await env.CONTACT_EMAIL.send(new EmailMessage(FROM, TO, message.asRaw()));

  return Response.redirect(new URL(`/${locale}/contact-done`, request.url), 303);
}
