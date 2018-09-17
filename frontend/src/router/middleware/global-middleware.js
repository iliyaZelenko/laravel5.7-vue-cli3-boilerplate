import globalAuth from './global-auth'
import auth from './auth'
import guest from './guest'

export default (router) => {
  globalAuth(router)
  auth(router)
  guest(router)
}
