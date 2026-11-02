// Newest first. Each text is either a plain string, shared by both
// languages, or an object with one string per locale.

export const talks = [
  {
    year: "2021",
    title: "How to set up a microservice infrastructure for AI services",
    event: "Digitale Leute Summit, with Puja Abbassi",
    links: [
      {
        label: "event",
        url: "https://digitale-leute.de/summit/21/schedule/how-to-set-up-a-microservice-infrastructure-for-ai-services-with-giant-swarm",
      },
    ],
  },
  {
    year: "2020",
    title: "Declaratively managing apps in a multi-cluster world",
    event: "CNCF webinar",
    links: [
      {
        label: "webinar",
        url: "https://www.cncf.io/webinars/declaratively-managing-apps-in-a-multi-cluster-world/",
      },
    ],
  },
  {
    year: "2019",
    title: "Leveraging Kubernetes to run Kubernetes",
    event: "DevOps Barcelona",
    links: [{ label: "video", url: "https://www.youtube.com/watch?v=hSxJXnb-P3U" }],
  },
  {
    year: "2018",
    title: "Istio",
    event: "Tarragona Developers",
    links: [],
  },
  {
    year: "2017",
    title: "Introduction to Kubernetes",
    event: { es: "Tarragona Developers, con Adrián Moreno", en: "Tarragona Developers, with Adrián Moreno" },
    links: [{ label: "slides", url: "http://adrianmo.github.io/slides/k8s-tgndevs" }],
  },
  {
    year: "2016",
    title: "Understanding the different Git workflows",
    event: "Tarragona Developers",
    links: [{ label: "slides", url: "http://pipo02mix.github.io/slides/git-workflows.html" }],
  },
  {
    year: "2016",
    title: "How to leverage Docker thanks to Kubernetes",
    event: "Tarragona Developers",
    links: [
      {
        label: "slides",
        url: "https://docs.google.com/presentation/d/1MuWJUB_bMxU6WspA1zggNE0QP-03uVI-vqbdDS25kIs/pub",
      },
    ],
  },
];
