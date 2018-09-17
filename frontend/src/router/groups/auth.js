export default [
  {
    name: 'signup',
    path: '/signup',
    component: () => import(/* webpackChunkName: "signup" */ '@/pages/auth/Signup.vue'),
    meta: { guest: true }
  },
  {
    name: 'signin',
    path: '/signin',
    component: () => import(/* webpackChunkName: "signin" */ '@/pages/auth/Signin.vue'),
    meta: { guest: true }
  },
  {
    name: 'forgot-password',
    path: '/forgot-password',
    component: () => import(/* webpackChunkName: "signin" */ '@/pages/auth/ForgotPassword.vue'),
    meta: { guest: true }
  },
  {
    name: 'reset-password',
    path: '/reset-password/:token/:email',
    component: () => import(/* webpackChunkName: "signin" */ '@/pages/auth/ResetPassword.vue'),
    meta: { guest: true }
  },
  {
    name: 'verify-email',
    path: '/verify-email',
    component: () => import(/* webpackChunkName: "signin" */ '@/pages/auth/VerifyEmail.vue'),
    // meta: { guest: true }
  }
]
