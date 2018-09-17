import Vue from 'vue'
import Vuex from 'vuex'
import createMutationsSharer from 'vuex-shared-mutations'
import authModule from './modules/auth/'

Vue.use(Vuex)

export default new Vuex.Store({
  plugins: [createMutationsSharer({ predicate: ['auth/USER', 'auth/TOKEN', 'auth/REFRESH_TOKEN_EXPIRED', 'auth/USER_LOGGED_IN', 'auth/USER_LOGGED_OUT'] })],
  modules: {
    auth: authModule
  },
  state: {

  },
  mutations: {

  },
  actions: {

  }
})
