import Vue from 'vue'
import Router from 'vue-router'
import { sync } from 'vuex-router-sync'
import routes from './routes'
import store from '@/store'
import globalMiddleware from './middleware/global-middleware'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

sync(store, router)
globalMiddleware(router)

export default router
