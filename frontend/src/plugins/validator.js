import Vue from 'vue'
import VeeValidate from 'vee-validate'
import { rules, validator } from '@/tools/validator'

Vue.use(VeeValidate)

// vee-validate already have $validator
Vue.prototype.$formValidator = {
  rules: rules,
  async validate (data) {
    return this.validator.validateAll(data)
  },
  validator
}
