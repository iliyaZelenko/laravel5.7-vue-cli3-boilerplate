<template>
  <div style="max-width: 300px; width: 100%;">
    <h1 class="text-center">Sign up</h1>
    <router-link
      to="/"
      class="float-left"
    >
      🡄  Go back
    </router-link>
    <br>
    <form class="mt-4 w-100 clearfix">
      <div class="form-group">
        <label for="signupInputEmail">
          Email:
        </label>
        <input
          v-validate="'required|' + $formValidator.rules.email"
          id="signupInputEmail"
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
        <label for="signupInputName">
          Name:
        </label>
        <input
          v-validate="$formValidator.rules.name"
          id="signupInputName"
          v-model="form.name"
          :error-messages="errors.collect('name')"
          :class="{
            'form-control': true,
            'is-invalid': errors.has('name')
          }"
          data-vv-name="name"
          type="text"
          class="form-control"
          placeholder="Enter name"
        >
        <div :class="{ 'validation-feedback': true, 'invalid-feedback': errors.has('name') }">
          {{ errors.first('name') }}
        </div>
      </div>
      <div class="form-group">
        <label for="signupInputPassword">
          Password:
        </label>
        <input
          v-validate="$formValidator.rules.password"
          id="signupInputPassword"
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
          Sign up
        </span>
      </button>

      <router-link
        :to="{ name: 'signin' }"
        class="pt-2 d-block small"
      >
        Already have an account?
      </router-link>
    </form>
    <!--<hr>-->
    <!--<router-link-->
      <!--:to="{ name: 'signin' }"-->
      <!--class="d-block text-center"-->
    <!--&gt;-->
      <!--Already have an account?-->
    <!--</router-link>-->
  </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  name: 'Signup',
  data: () => ({
    form: {
      name: 'Vasya Pupkin',
      email: 'vasya@test.com',
      password: 'password'
    },
    loading: false
  }),
  methods: {
    async submit () {
      if (await this.$formValidator.validate(this.form)) {
        this.loading = true
        try {
          await this.signup(this.form)
        } finally {
          this.loading = false
        }
      }
    },
    ...mapActions('auth', ['signup'])
  },
  head () {
    return {
      title: 'Sign Up Page',
      meta: [
        { content: 'Sign Up Page Description', name: 'description', hid: 'description' }
      ]
    }
  }
}
</script>
