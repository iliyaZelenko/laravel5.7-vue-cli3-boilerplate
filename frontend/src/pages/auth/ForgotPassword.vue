<template>
  <div style="max-width: 300px; width: 100%;">
    <!--class="d-flex justify-content-center align-items-center"-->

    <h1 class="text-center">Forgot password</h1>

    <div
      v-show="sent"
      class="alert alert-primary mx-auto"
      role="alert"
    >
      This page is no longer needed, please follow the link in the e-mail message.
    </div>

    <div v-show="!sent">
      <router-link
        :to="{ name: 'signin' }"
        class="float-left"
      >
        🡄  Go back
      </router-link>
      <br>
      <form
        class="mt-4 w-100"
        autocomplete="on"
        @submit.prevent="submit"
        @keydown.enter="submit"
      >
        <div class="form-group">
          <label for="signinInputEmail">
            Email:
          </label>
          <input
            v-validate="'required|' + $formValidator.rules.email"
            id="signinInputEmail"
            v-model="form.email"
            :error-messages="errors.collect('email')"
            :class="{
              'form-control': true,
              'is-invalid': errors.has('email')
            }"
            data-vv-name="email"
            type="email"
            class="form-control"
            placeholder="Enter email"
          >
          <div :class="{ 'validation-feedback': true, 'invalid-feedback': errors.has('email') }">
            {{ errors.first('email') }}
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
  </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  data () {
    return {
      form: {
        email: null
      },
      btnLoading: false,
      sent: false
    }
  },
  computed: {
    btnDisabled () {
      return !!this.errors.items.length
    }
  },
  methods: {
    async submit () {
      if (await this.$formValidator.validate(this.form)) {
        await this.$actionWithLoading(this.sendEmail, 'btnLoading', this.form.email)
        this.sent = true
      }

      // if (await this.$formValidator.validate(this.form)) {
      //   this.btnLoading = true
      //   try {
      //     await this.sendEmail(this.form.email)
      //   } finally {
      //     this.btnLoading = false
      //   }
      // }
    },
    ...mapActions('auth/forgotPassword', [
      'sendEmail'
    ])
  }
}
</script>
