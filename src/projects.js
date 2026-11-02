// Each text is either a plain string, shared by both languages, or an
// object with one string per locale.

const github = (repo) => `https://github.com/pipo02mix/${repo}`;

export const projects = [
  {
    name: "envoy-gateway-examples",
    year: "2026",
    description: {
      es: "Ejemplos de cómo usar Envoy Gateway y cómo depurar los problemas más comunes, en Giant Swarm.",
      en: "Examples of how to use Envoy Gateway and how to debug common problems, at Giant Swarm.",
    },
    url: "https://github.com/giantswarm/envoy-gateway-examples",
  },
  {
    name: "build-your-own-platform",
    year: "2024",
    description: {
      es: "Una plataforma interna para desarrolladores, construida por diversión.",
      en: "An internal developer platform, built for fun.",
    },
    url: github("build-your-own-platform"),
  },
  {
    name: "paas-example",
    year: "2021",
    description: {
      es: "Un ejemplo de plataforma como código.",
      en: "An example of platform as code.",
    },
    url: github("paas-example"),
  },
  {
    name: "grumpy",
    year: "2018",
    description: {
      es: "Un ejemplo de admission controller de validación para Kubernetes, en Go.",
      en: "An example Kubernetes validating admission controller, written in Go.",
    },
    url: github("grumpy"),
  },
  {
    name: "why_k8s_can_make_our_life_easier",
    year: "2018",
    description: {
      es: "Conceptos de Kubernetes explicados con ejemplos.",
      en: "Kubernetes concepts explained through examples.",
    },
    url: github("why_k8s_can_make_our_life_easier"),
  },
  {
    name: "kubernetes-101",
    year: "2017",
    description: {
      es: "Una demo de cómo funciona Kubernetes.",
      en: "A demo of how Kubernetes works.",
    },
    url: github("kubernetes-101"),
  },
  {
    name: "kubernetes-node-example",
    year: "2016",
    description: {
      es: "Una aplicación web de demo en Node para desplegar en Kubernetes.",
      en: "A demo web app in Node to deploy on Kubernetes.",
    },
    url: github("kubernetes-node-example"),
  },
  {
    name: "devolvereldinerodetodos",
    year: "2026",
    description: {
      es: "Una página que muestra los casos de corrupción en España.",
      en: "A page that shows the corruption cases in Spain.",
    },
    url: github("devolvereldinerodetodos"),
  },
  {
    name: "certamen-micrometraje-mediona",
    year: "2022",
    description: {
      es: "La web del certamen de micrometrajes de Mediona.",
      en: "The website for the Mediona short film contest.",
    },
    url: github("certamen-micrometraje-mediona"),
  },
  {
    name: "celmediona.cat",
    year: "2023",
    description: {
      es: "La web de celmediona.cat.",
      en: "The website for celmediona.cat.",
    },
    url: github("celmediona.cat"),
  },
  {
    name: "symfony-docs-es",
    year: "2013",
    description: {
      es: "La documentación de Symfony 2 en español.",
      en: "The Symfony 2 documentation in Spanish.",
    },
    url: github("symfony-docs-es"),
  },
];
