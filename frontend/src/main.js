import Vue from 'vue'
import App from '@/App.vue'
import router from '@/router'
import store from '@/store'

import '@/styles/main.styl'
// Plugins
import './plugins/vue-plugin-axios'
import './plugins/bootstrap'
import './plugins/notifications'
import './plugins/validator'

Vue.config.productionTip = false

Vue.prototype.$auth = {
  get user () {
    return store.state.auth.user
  },
  get loggedIn () {
    return store.getters['auth/loggedIn']
  }
}

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')

// init auth module
store.dispatch('auth/init')
