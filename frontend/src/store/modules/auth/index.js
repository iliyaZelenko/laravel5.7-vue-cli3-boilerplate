import store from '@/store'
import router from '@/router'
import { vp } from '@/tools/helpers'
import { USER, USER_LOGGED_IN, USER_LOGGED_OUT, TOKEN, REFRESH_TOKEN_EXPIRED } from './mutation-types'
import forgotPassword from './modules/forgotPassword/'
import emailVerification from './modules/emailVerification/'

const localStorageKeys = {
  user: 'auth__user',
  token: 'auth__token',
  tokenExpiresIn: 'auth__tokenExpiresIn',
  refreshTokenExpiresIn: 'auth__refreshTokenExpiresIn'
}
const tokenSecondBeforeExpired = 50 // seconds before token expired, uses for refresh token request
const moduleNamespace = 'auth/'
let refreshTimeoutId = null

const module = {
  namespaced: true,
  modules: {
    forgotPassword,
    emailVerification
  },
  state: {
    user: JSON.parse(localStorage.getItem(localStorageKeys.user)),
    token: localStorage.getItem(localStorageKeys.token), // localStorage.getItem('auth-token')
    tokenExpiresIn: +localStorage.getItem(localStorageKeys.tokenExpiresIn), // timestamp
    refreshTokenExpiresIn: +localStorage.getItem(localStorageKeys.refreshTokenExpiresIn),
    refreshTokenAlreadyExpired: false
  },
  mutations: {
    [USER] (state, user) {
      state.user = user

      localStorage.setItem(localStorageKeys.user, JSON.stringify(user))

      return user
    },
    [TOKEN] (state, token) {
      console.log('token_global', token)
      state.token = (token && token.accessToken) || ''
      state.tokenExpiresIn = +(token && token.expiresIn) || ''
      state.refreshTokenExpiresIn = +(token && token.refreshTokenExpiresIn) || ''

      localStorage.setItem(localStorageKeys.token, state.token)
      localStorage.setItem(localStorageKeys.tokenExpiresIn, state.tokenExpiresIn)
      localStorage.setItem(localStorageKeys.refreshTokenExpiresIn, state.refreshTokenExpiresIn)
    },
    [REFRESH_TOKEN_EXPIRED] (state) {
      if (state.refreshTokenAlreadyExpired) return

      state.refreshTokenAlreadyExpired = true
      stopTokenRefresh()
      showRefreshTokenExpiredMessage()
    },
    [USER_LOGGED_IN] (state) {
      state.refreshTokenAlreadyExpired = false
      // console.log(store.state.route.path)
      if (store.state.route.meta.guest) {
        router.push({ name: 'profile' })
      }
      vp.$notify.success('logged in successfully!')
      setTimeoutTokenRefresh(state)
    },
    [USER_LOGGED_OUT] (state, manually) {
      if (manually) {
        vp.$notify.success('Logged out successfully.')
        stopTokenRefresh()
      }
      // else {
      // if route requires auth, then redirect
      if (store.state.route.meta.auth) {
        router.push({ name: 'signin' })
      }
      if (store.state.route.meta.guest) {
        router.push('/')
      }
      // }
    }
  },
  actions: {
    async signin ({ dispatch, commit }, form) {
      const loggedInData = await vp.$post('auth/signin', form)

      await dispatch('loggedIn', loggedInData)
    },
    async signup ({ dispatch, commit }, form) {
      const loggedInData = await vp.$post('auth/signup', form)

      await dispatch('loggedIn', loggedInData)
    },
    async loggedIn ({ dispatch, commit }, { user, tokenInfo }) {
      commit(TOKEN, tokenInfo)
      commit(USER, user)
      commit(USER_LOGGED_IN)
    },
    async getUser ({ commit }) {
      const { user } = await vp.$get('auth/user')
      return commit(USER, user)
    },
    async logout ({ dispatch, commit }) {
      await vp.$post('auth/logout')
      await dispatch('setNullTokenAndUser')
      commit(USER_LOGGED_OUT, true)
    },
    async refresh ({ dispatch, commit, state }) {
      const tokenInfo = await vp.$post('auth/refresh')

      // setTimeoutTokenRefresh()

      if (tokenInfo.status === 'tokenAlreadyRefreshed') return
      if (tokenInfo.status === 'refreshTokenExpired') {
        await dispatch('refreshTokenExpired')
        // refresh token expired
        // showRefreshTokenExpiredMessage()

        return
      }
      commit(TOKEN, tokenInfo)
      setTimeoutTokenRefresh(state)
    },
    async refreshTokenExpired ({ dispatch, commit }) {
      await dispatch('setNullTokenAndUser')
      // commit(TOKEN, null)
      // commit(USER, null)
      commit(REFRESH_TOKEN_EXPIRED)
      commit(USER_LOGGED_OUT, false)
    },
    async setNullUser ({ commit }) {
      commit(USER, null)
    },
    async setNullTokenAndUser ({ commit }) {
      commit(TOKEN, null)
      commit(USER, null)
    },
    async init ({ state }) {
      setTimeoutTokenRefresh(state)
    }
  },
  getters: {
    loggedIn (state) {
      return !!state.user
    },
    tokenNeedToRefresh (state) {
      const needToRefreshStart = state.tokenExpiresIn - tokenSecondBeforeExpired
      const now = Math.floor(Date.now() / 1000) // need to / 1000 because Date.now() return ms not seconds

      return state.token && needToRefreshStart < now
    },
    tokenExpired (state) { // true if token expired
      return state.tokenExpiresIn < Math.floor(Date.now() / 1000)
    }
  }
}

// export module
export default module

// async function checkTokenExpired () {
//   console.log('checkTokenExpired')
//   // console.log('needRefresh: ' + module.getters.tokenNeedToRefresh(module.state))
//   // if (!module.state.token && module.state.user) {
//   //   store.dispatch('auth/setNullUser')
//   // }
//
//   // if (module.getters.tokenNeedToRefresh(module.state)) {
//   try {
//     // clearInterval(intervalCheckTokenExpired)
//     await store.dispatch('auth/refresh')
//   } catch (e) {}
//
//   // intervalCheckTokenExpired = setInterval(checkTokenExpired, checkTokenEvery)
//   // }
// }

// function checkTokenExpired (state) {
//   const { token, refreshTokenExpiresIn } = state
//
//   if (!token) {
//     return
//   }

// let secondsFromNow = Date.now() / 1000
// let secondsToShowPlsSigninMessage = refreshTokenExpiresIn - secondsFromNow - refreshTokenSecondBeforeExpired
//
// let ms = secondsToShowPlsSigninMessage > 0 ? secondsToShowPlsSigninMessage * 1000 : 0
// clearTimeout(showRefreshTokenExpiredMessagereTimeoutId)
//
// console.log('ставлю смотритель рефреш токена')
// showRefreshTokenExpiredMessagereTimeoutId = setTimeout(async () => {
//   // showRefreshTokenExpiredMessage()
//   clearTimeout(refreshTimeoutId)
//
//   // await store.dispatch(moduleNamespace + 'setNullTokenAndUser')
//   store.commit(moduleNamespace + REFRESH_TOKEN_EXPIRED)
//   store.commit(moduleNamespace + TOKEN, null)
//   store.commit(moduleNamespace + USER, null)
// }, ms)

// setTimeoutTokenRefresh(state)
// only if refresh token not expired
// let ms2 = secondsToRefresh > 0 ? secondsToRefresh * 1000 : 0
// if (ms2 <= ms && ms) { // if token expired before refresh token expired and if refresh token not expired
//   refreshTimeoutId = setTimeout(async () => {
//     await store.dispatch(moduleNamespace + 'refresh')
//   }, ms2)
// }
// }

function setTimeoutTokenRefresh ({ token, tokenExpiresIn }) {
  if (!token) { // || refreshTimeoutId
    return
  }

  let secondsFromNow = Date.now() / 1000
  let secondsToRefresh = tokenExpiresIn - secondsFromNow - tokenSecondBeforeExpired
  let ms2 = secondsToRefresh > 0 ? secondsToRefresh * 1000 : 0

  clearTimeout(refreshTimeoutId)
  refreshTimeoutId = setTimeout(async () => {
    await store.dispatch(moduleNamespace + 'refresh')
  }, ms2)
}

function stopTokenRefresh () {
  clearTimeout(refreshTimeoutId) // because we dont need to refresh token
}

function showRefreshTokenExpiredMessage () {
  vp.$notify.info('Please, log in again')
}
