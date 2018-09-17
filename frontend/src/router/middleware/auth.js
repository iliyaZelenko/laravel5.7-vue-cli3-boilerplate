import store from '@/store'
import { vp } from '@/tools/helpers'

export default function RedirectIfAuthenticated (router) {
  router.beforeEach(async (to, from, next) => {
    let meta = to.matched.some(record => record.meta.auth)
    let user = store.state.auth.user

    if (meta && !user) {
      // next(from.fullPath)
      next({ name: 'signin' })
      // Выводится "Please, log in again"
      vp.$notify.error('This page requires authentication!')
      return
    }

    next()
  })
}
