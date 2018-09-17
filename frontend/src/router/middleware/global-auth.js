import store from '@/store'

export default function RedirectIfAuthenticated (router) {
  router.beforeEach(async (to, from, next) => {
    let token = store.state.auth.token
    let user = store.state.auth.user
    let needToRefresh = store.getters['auth/tokenNeedToRefresh']

    try {
      // if site have token but dont have user – need to get user
      !user && token && !needToRefresh && await store.dispatch('auth/getUser')
    } catch (e) {
      console.error(e)
    }

    // if token removed
    if (!token && user) {
      store.dispatch('auth/setNullUser')
    }

    next()
  })
}
