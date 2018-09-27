import Vue from 'vue'
import Vuex from 'vuex'
import createMutationsSharer from 'vuex-shared-mutations'

import authModule from './modules/auth/'
import currentUserProfile from './modules/profile/currentUser'

Vue.use(Vuex)

export default new Vuex.Store({
  plugins: [createMutationsSharer({
    predicate: [
      'auth/USER', 'auth/TOKEN', 'auth/REFRESH_TOKEN_EXPIRED', 'auth/USER_LOGGED_IN', 'auth/USER_LOGGED_OUT'
    ]
  })],
  modules: {
    auth: authModule,
    profile: currentUserProfile
  },
  state: {

  },
  mutations: {

  },
  actions: {

  }
})
