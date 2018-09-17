<template>
  <div style="max-width: 300px; width: 100%;">
    <div>
      <!--class="d-flex justify-content-center align-items-center"-->

      <h1 class="text-center">Forgot password</h1>

      <div
        v-show="sent"
        class="alert alert-primary mx-auto w-50"
        role="alert"
      >
        This page is no longer needed, please follow the link in the message.
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
            Continue
          </button>
        </form>
      </div>
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
        this.btnLoading = true
        if (await this.resetPasswordSendEmail(this.form)) {
          this.sent = true
        }
        this.btnLoading = false
      }
    },
    ...mapActions('auth', [
      'resetPasswordSendEmail'
    ])
  }
}
</script>
