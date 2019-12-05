require('dotenv').config({path: '../core/.env'});

export default {
  //mode: 'universal',
  mode: 'spa',
  head: {
    title: process.env.APP_NAME,
    meta: [
      {charset: 'utf-8'},
      {name: 'viewport', content: 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0'},
      {name: 'apple-mobile-web-app-title', content: process.env.APP_NAME},
      {hid: 'title', name: 'title', content: process.env.APP_NAME},
    ],
  },
  css: [
    '~assets/scss/styles.scss',
  ],
  plugins: [
    '~/plugins/axios.js',
    '~/plugins/fontawesome.js',
    '~/plugins/filters.js',
    '~/plugins/modal.js',
    '~/plugins/table.js',
  ],
  modules: [
    'bootstrap-vue/nuxt',
    'nuxt-izitoast',
    '@nuxtjs/axios',
    '@nuxtjs/auth',
    '@nuxtjs/pwa',
    '@nuxtjs/dotenv',
  ],
  loading: {
    color: '#fff'
  },
  bootstrapVue: {
    css: false,
    bvCSS: false,
  },
  dotenv: {
    path: '../core/',
    filename: '.env',
    only: ['APP_NAME', 'SITE_URL'],
  },
  axios: {
    baseURL: process.env.SITE_URL + 'api/',
    progress: true,
    proxyHeaders: false,
    credentials: false
  },
  auth: {
    redirect: {
      home: '/',
      login: '/admin/',
      logout: '/',
    },
    watchLoggedIn: true,
    resetOnError: true,
    strategies: {
      local: {
        endpoints: {
          login: {url: 'security/login', method: 'post', propertyName: 'token'},
          logout: {url: 'security/logout', method: 'post'},
          user: {url: 'user/profile', method: 'get', propertyName: 'user'},
        },
      },
    },
    plugins: [
      '~/plugins/auth.js'
    ]
  },
  router: {
    linkActiveClass: 'active',
    middleware: [
      'auth',
    ],
  },
  izitoast: {
    position: 'bottomRight',
    transitionIn: 'bounceInLeft',
    transitionOut: 'fadeOutRight',
  },
  server: {
    host: '127.0.0.1',
    port: 5112
  }
}