import Vue from 'vue'
import axios from 'axios'
import VueAxios from 'vue-plugin-axios'
// import VueAxios from '@/../../../vue-plugin-axios/src/'
import store from '@/store'
import { vp } from '@/tools/helpers'
import { showServerError } from '@/tools/validator'

const baseApiURL = process.env.VUE_APP_BACKEND + '/api/' // 'http://localhost:8000/api/'

Vue.use(VueAxios, {
  axios,
  config: {
    baseURL: baseApiURL,
    headers: {
      // so laravel will understand that this is ajax $request->ajax()
      'X-Requested-With': 'XMLHttpRequest'
    }
  },
  interceptors: {
    beforeRequest (config, axiosInstance) {
      if (config.baseURL === baseApiURL) {
        const token = store.state.auth.token

        if (token) {
          config.headers.Authorization = `Bearer ${token}`
        }
      }

      return config
    },
    beforeResponseError (error) {
      const { response, message } = error

      if (response) { // backend error
        showServerError(response)

        // if 401
      } else if (message) { // network error
        vp.$notify.error(message)
      }

      // return Promise.reject(error)
    }
  }
})
