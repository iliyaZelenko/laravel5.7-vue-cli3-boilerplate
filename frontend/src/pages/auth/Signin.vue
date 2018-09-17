<template>
  <div style="max-width: 300px; width: 100%;">
    <h1 class="text-center">Sign in</h1>
    <router-link
      to="/"
      class="float-left"
    >
      🡄  Go back
    </router-link>
    <br>
    <form class="mt-4 w-100">
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
      <div class="form-group">
        <label for="signinInputPassword">
          Password:
        </label>
        <input
          v-validate="$formValidator.rules.password"
          id="signinInputPassword"
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
      <button
        :disabled="!!errors.items.length"
        type="submit"
        class="btn btn-primary float-right"
        @click.prevent="submit"
      >
        <span v-if="loading">
          Loading...
        </span>
        <span v-else>
          Sign in
        </span>
      </button>

      <span>
        <router-link
          :to="{ name: 'forgot-password' }"
          class="small"
        >
          Forgot password?
        </router-link>
        <br>
        <router-link
          :to="{ name: 'signup' }"
          class="small"
        >
          Sign up
        </router-link>
      </span>
    </form>
  </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  name: 'Signin',
  data: () => ({
    form: {
      email: 'test@test.com',
      password: 'password'
    },
    loading: false
  }),
  computed: {
    // ...mapState('auth', ['user']),
    // ...mapGetters('auth', ['loggedIn'])
  },
  methods: {
    async submit () {
      this.loading = true
      try {
        await this.signin(this.form)
      } catch (e) {
        // console.log(e)
      }
      this.loading = false
    },
    ...mapActions('auth', ['signin'])
  }
}
</script>
