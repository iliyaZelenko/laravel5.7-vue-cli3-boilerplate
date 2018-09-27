import { vp } from '@/tools/helpers'
// import { } from './mutation-types'

export default {
  namespaced: true,
  state: {},
  mutations: {},
  actions: {
    async setPassword ({ dispatch, commit }, form) {
      const { message, user } = await vp.$post('profile/current/set-password', form)

      dispatch('auth/setUser', user, { root: true })

      vp.$notify.success(message)
    }
  }
}
