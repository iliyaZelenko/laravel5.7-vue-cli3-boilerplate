export default [
  {
    name: 'profile',
    path: '/profile',
    // route level code-splitting
    // this generates a separate chunk (profile.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "profile" */ '@/pages/Profile.vue'),
    meta: { auth: true }
  }
]
