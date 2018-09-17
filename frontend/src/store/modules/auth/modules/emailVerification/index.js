import { vp } from '@/tools/helpers'
// import { } from './mutation-types'

export default {
  namespaced: true,
  state: {

  },
  mutations: {
    // [USER] (state, user) {
    //   state.user = user
    //
    //   localStorage.setItem(localStorageKeys.user, JSON.stringify(user))
    //
    //   return user
    // }
  },
  actions: {
    async resend ({ dispatch, commit }) {
      return vp.$post('auth/email/resend')
    }
  }
}
