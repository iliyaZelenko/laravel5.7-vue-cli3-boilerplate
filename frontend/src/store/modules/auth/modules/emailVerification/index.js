import { vp } from '@/tools/helpers'
import router from '@/router'
// import { } from './mutation-types'

export default {
  namespaced: true,
  state: {},
  mutations: {},
  actions: {
    async resend ({ dispatch, commit }) {
      const responseMsg = await vp.$post('auth/email/resend')

      vp.$notify.success(responseMsg)
    },
    async verify ({ dispatch, commit }, queryURL) {
      try {
        const { message, user } = await vp.$post(queryURL)

        dispatch('auth/setUser', user, { root: true })

        vp.$notify.success(message)
        router.push({ name: 'profile' })
      } catch ({ response: { status, message } }) {
        if (status === 401) {
          router.push({ name: 'signin' })
        } else if (status === 429) {
          router.push({ name: 'profile' })
        }
      }
    }
  }
}
