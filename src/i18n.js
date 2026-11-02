const translations = {
  es: {
    aboutme:
      "Soy un ingeniero del software con gusto por hacer las cosas bien hechas. Trabajo con diferentes tecnologías que me ayudan a realizar mi trabajo: PHP, Node, golang, ruby, kubernetes, MongoDB y HTML5. He trabajado como frontend developer creando interfaces interactivas apoyándome en diferentes frameworks de javascript (Angular, Knockout, Backbone, ...). Como backend, he construido alguna que otra API rest, aunque últimamente ando sumergido en el mundo de los micro servicios (DDD fan). Además, gracias a mi último trabajo mis habilidades como devOps se han visto drásticamente mejoradas, siendo capaz de gestionar nuestra plataforma, incluso automatizar tareas repetitivas y hacer que el proceso de desarrollo sea lo más ágil posible.",
    tiatere_why: "Por qué tiatere?",
    tiatere_why_explanation:
      "Porque creè  una marca personal inspirada en los valores que representa mi tía (bondad, esfuerzo y pasión)",
    "about-me": "Sobre mí",
    projects: "Proyectos",
    blog: "Blog",
    curriculum: "Curriculum",
    contact: "Contacto",
    contact_description:
      "Déjame un mensaje con la consulta o proposición y no tardaré en contestarte",
    who: "Quién eres?",
    email: "Tu email?",
    query: "Qué es lo que quieres?",
    send_button: "Envía",
    site_description:
      "Fernando Ripoll, ingeniero de software. Blog, trayectoria y contacto.",
  },
  en: {
    aboutme:
      "I am a software engineer who likes making things right. I have worked with a different set of technologies like: PHP, Node, golang, ruby, kubernetes, MongoDB or HTML5. My early days as a developer I was focussed in the frontend. I have created some rich user interfaces thanks to some javascript frameworks as Angular or Backbone. Over time I moved my interests to backend development. I have been involved in building some rest APIs and middleware components. Nowadays, I have walked away from pure development, and I have started to work more in processes and automation. I really like to read and practice about 'Domain Driven Design' and DevOps concepts. At the same time, I love to share knowledge either in meetups or workshops.",
    tiatere_why: "Why tiatere?",
    tiatere_why_explanation:
      "Because I created a personal brand with the values that represent my aunt Teresa (pasion, goodness, effort)",
    "about-me": "About me",
    projects: "Projects",
    blog: "Blog",
    curriculum: "Resume",
    contact: "Contact",
    contact_description: "Please leave your query and I will reply as soon as possible",
    who: "Who are you?",
    email: "Your email?",
    query: "What do you want?",
    send_button: "Send",
    site_description: "Fernando Ripoll, software engineer. Blog, resume and contact.",
  },
};

export const locales = Object.keys(translations);

export function localePaths() {
  return locales.map((locale) => ({ params: { locale } }));
}

export function useTranslations(locale) {
  return (key) => translations[locale][key];
}
