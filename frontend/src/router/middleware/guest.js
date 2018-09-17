import store from '@/store'
import { vp } from '@/tools/helpers'

// Похожая логика работает в REFRESH_TOKEN_EXPIRED мутации, после того как истечет токен.

export default function RedirectIfAuthenticated (router) {
  /**
   * If the user is already authenticated he shouldn't be able to visit
   * pages like login, register, etc...
   */
  router.beforeEach(async (to, from, next) => {
    let meta = to.matched.some(record => record.meta.guest)
    let user = store.state.auth.user

    if (meta && user) {
      if (from.fullPath) {
        next(false) // Если поставить false и есть такой случай что перекидывет на главную и она не рендерится почему-то(если зайти на страницу и нет from)
      } else {
        next({ name: 'home' })
      }

      // next(from.fullPath) Но прошлый путь может быть и тоже не для гостя!
      // router.push('profile')
      vp.$notify.error('This page is for guests only!')
      return
    }

    next()
  })
}
