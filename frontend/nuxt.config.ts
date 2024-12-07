// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-04-03',
  devtools: { enabled: true },
  runtimeConfig: {
    public: {
      baseUrl: process.env.NODE_ENV === "production" ? "https://api.voteright.fr" : "http://localhost:3333",
    },
  },
  css: [
    './assets/styles/main.scss', // chemin vers votre fichier SCSS ou CSS
  ],
  vite: {
    css: {
      preprocessorOptions: {
        scss: {
          api: "modern-compiler",
        },
      },
    },
  },
})
