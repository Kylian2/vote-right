// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-04-03',
  devtools: { enabled: true },
  runtimeConfig: {
    public: {
      baseUrl: process.env.NODE_ENV === "production" ? "https://api.voteright.fr" : "http://localhost:3333",
      adminUrl: process.env.NODE_ENV === "production" ? "https://admin.voteright.fr" : "http://localhost:3001",
    },
  },
  css: [
    './assets/styles/main.scss', 
    'material-icons/iconfont/material-icons.css'
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
  ssr: false,
  app: {
    head: {
      charset: 'utf-8',
      viewport: 'width=device-width, initial-scale=1',
      title: 'Voteright',
      meta: [
        { name: 'google-site-verification', content: 'TYoJdcPBjFyZ50B0pA1Qpb9oO-eNZ3KhGRfsvbNs-xw' }
      ],
      link: [
        { rel: 'icon', type: 'image/png', href: '/favicon.ico' }, 
        { rel: 'apple-touch-icon', href: '/apple-touch-icon.png' } 
      ]
    }
  }
})
