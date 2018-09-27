<template>
  <div style="max-width: 300px; width: 100%;">
    <!--class="d-flex justify-content-center align-items-center"-->

    <h1 class="text-center">Enter new password</h1>

    <form
      class="mt-4 w-100"
      autocomplete="on"
      @submit.prevent="submit"
      @keydown.enter="submit"
    >
      <div class="form-group">
        <input
          v-validate="$formValidator.rules.password"
          v-model="form.password"
          :class="{
            'form-control': true,
            'is-invalid': errors.has('password')
          }"
          data-vv-name="password"
          type="password"
          class="form-control"
          placeholder="Password"
        >
        <div :class="{ 'validation-feedback': true, 'invalid-feedback': errors.has('password') }">
          {{ errors.first('password') }}
        </div>
      </div>

      <!--:loading="btnLoading"-->
      <button
        :disabled="btnDisabled"
        type="submit"
        class="btn btn-primary float-right"
      >
        <span v-if="btnLoading">
          Loading...
        </span>
        <span v-else>
          Continue
        </span>
      </button>
    </form>
  </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  data: () => ({
    form: {
      password: null
    },
    btnLoading: false,
    passwordShow: true
  }),
  computed: {
    btnDisabled () {
      return !!this.errors.items.length
    }
  },
  methods: {
    async submit () {
      if (await this.$formValidator.validate(this.form)) {
        const { email, token } = this.$route.params

        await this.$actionWithLoading(this.resetPassword, 'btnLoading', {
          password: this.form.password,
          email,
          token
        })
      }
    },
    ...mapActions('auth/forgotPassword', [
      'resetPassword'
    ])
  }
}
</script>
