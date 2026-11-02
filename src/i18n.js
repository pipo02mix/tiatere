const translations = {
  es: {
    home_headline:
      "Ayudo a equipos a diseñar, operar y mejorar sus plataformas cloud native.",
    home_intro:
      "Soy Solution Architect en Giant Swarm. Mi trabajo va desde planificar migraciones y actualizaciones hasta encontrar la causa real cuando algo falla. Convierto lo que aprendo en documentación, runbooks y automatización, para que la próxima solución sea más rápida para todos.",
    latest_posts: "Últimos artículos",
    all_posts: "Todos los artículos",
    tiatere_why: "¿Por qué tiatere?",
    tiatere_why_explanation:
      "El nombre viene de mi tía Teresa. Representa lo que me enseñó: bondad, esfuerzo y pasión.",
    "about-me": "Sobre mí",
    projects: "Proyectos",
    projects_intro:
      "Proyectos personales y ejemplos que he construido a lo largo de los años. Hay más en",
    talks: "Charlas",
    talks_intro: "Charlas en meetups, conferencias y webinars.",
    link_video: "Vídeo",
    link_slides: "Diapositivas",
    link_webinar: "Webinar",
    link_event: "Evento",
    blog: "Blog",
    curriculum: "Curriculum",
    contact: "Contacto",
    contact_description:
      "Déjame un mensaje con la consulta o proposición y no tardaré en contestarte",
    who: "Quién eres?",
    email: "Tu email?",
    query: "Qué es lo que quieres?",
    send_button: "Envía",
    contact_done: "¡Encantado de conocerte! Te respondo en breve.",
    experience: "Experiencia",
    education: "Formación",
    download_pdf: "Descargar PDF",
    site_description:
      "Fernando Ripoll, Solution Architect en Giant Swarm. Blog, trayectoria y contacto.",
  },
  en: {
    home_headline: "I help teams design, run and improve their cloud native platforms.",
    home_intro:
      "I'm a Solution Architect at Giant Swarm. My work goes from planning migrations and upgrades to finding the real cause when something breaks. I turn what I learn into docs, runbooks and automation, so the next fix is faster for everyone.",
    latest_posts: "Latest posts",
    all_posts: "All posts",
    tiatere_why: "Why tiatere?",
    tiatere_why_explanation:
      "The name comes from my aunt Teresa. It stands for what she taught me: kindness, effort and passion.",
    "about-me": "About me",
    projects: "Projects",
    projects_intro: "Side projects and examples I've built over the years. There are more on",
    talks: "Talks",
    talks_intro: "Talks at meetups, conferences and webinars.",
    link_video: "Video",
    link_slides: "Slides",
    link_webinar: "Webinar",
    link_event: "Event",
    blog: "Blog",
    curriculum: "Resume",
    contact: "Contact",
    contact_description: "Please leave your query and I will reply as soon as possible",
    who: "Who are you?",
    email: "Your email?",
    query: "What do you want?",
    send_button: "Send",
    contact_done: "Nice to meet you! I'll get back to you soon.",
    experience: "Experience",
    education: "Education",
    download_pdf: "Download PDF",
    site_description: "Fernando Ripoll, Solution Architect at Giant Swarm. Blog, resume and contact.",
  },
};

export const locales = Object.keys(translations);

export function localePaths() {
  return locales.map((locale) => ({ params: { locale } }));
}

export function useTranslations(locale) {
  return (key) => translations[locale][key];
}
