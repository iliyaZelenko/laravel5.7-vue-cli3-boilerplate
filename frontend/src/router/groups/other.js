export default [
  {
    name: 'about',
    path: '/about',
    component: () => import(/* webpackChunkName: "about" */ '@/pages/About.vue')
  }
]
