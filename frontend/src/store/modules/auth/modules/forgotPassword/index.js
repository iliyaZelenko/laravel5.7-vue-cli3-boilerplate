import { USER, USER_LOGGED_IN, USER_LOGGED_OUT, TOKEN, REFRESH_TOKEN_EXPIRED } from './mutation-types'

export default {
  namespaced: true,
  state: {

  },
  mutations: {
    [USER] (state, user) {
      // state.user = user
      //
      // localStorage.setItem(localStorageKeys.user, JSON.stringify(user))
      //
      // return user
    },
    [TOKEN] (state, token) {
      // console.log('token_global', token)
      // state.token = (token && token.accessToken) || ''
      // state.tokenExpiresIn = +(token && token.expiresIn) || ''
      // state.refreshTokenExpiresIn = +(token && token.refreshTokenExpiresIn) || ''
      //
      // localStorage.setItem(localStorageKeys.token, state.token)
      // localStorage.setItem(localStorageKeys.tokenExpiresIn, state.tokenExpiresIn)
      // localStorage.setItem(localStorageKeys.refreshTokenExpiresIn, state.refreshTokenExpiresIn)
    }
  }
}
