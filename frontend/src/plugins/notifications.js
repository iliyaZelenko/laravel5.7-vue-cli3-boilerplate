import Vue from 'vue'
import VueNotifications from 'vue-notifications'
import iziToast from 'izitoast' // https://github.com/dolce/iziToast
import 'izitoast/dist/css/iziToast.min.css'

function toast ({title, message, type, timeout, cb}) {
  if (type === VueNotifications.types.warn) type = 'warning'
  return iziToast[type]({title, message, timeout})
}

const options = {
  success: toast,
  error: toast,
  info: toast,
  warn: toast
}

const $notify = {}

for (let type in options) {
  $notify[type] = (title, message = '', { timeout = 3000 } = {} /* <-- this arg is like options */) => {
    toast({ type, title, message, timeout })
  }
}

Vue.use(VueNotifications, options)
Vue.prototype.$notify = $notify
