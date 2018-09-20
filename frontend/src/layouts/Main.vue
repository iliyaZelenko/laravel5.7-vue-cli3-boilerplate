<template>
  <div
    class="d-flex flex-column"
    style="height: 100%;"
  >
    <nav
      class="navbar navbar-expand-lg navbar-light"
      style="background-color: #e3f2fd;"
    >
      <a
        class="navbar-brand"
        href="#"
      >
        {{ appName }}
      </a>
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarColor03"
        aria-controls="navbarColor03"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"/>
      </button>

      <div
        id="navbarColor03"
        class="collapse navbar-collapse"
      >
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <router-link
              to="/"
              class="nav-link"
            >
              Home
            </router-link>
          </li>
          <li class="nav-item">
            <router-link
              to="/about"
              class="nav-link"
            >
              About
            </router-link>
          </li>
        </ul>
        <form
          v-if="!loggedIn"
          class="form-inline"
        >
          <div class="form-group">
            <label for="inputEmail">
              Email:
            </label>
            <input
              v-validate="'required|' + $formValidator.rules.email"
              id="inputEmail"
              v-model="form.email"
              :error-messages="errors.collect('email')"
              :class="{
                'form-control': true,
                'is-invalid': errors.has('email')
              }"
              data-vv-name="email"
              type="email"
              class="form-control mx-2"
              placeholder="Enter email"
            >
          </div>
          <div class="form-group">
            <label for="inputPassword">
              Password:
            </label>
            <input
              v-validate="$formValidator.rules.password"
              id="inputPassword"
              v-model="form.password"
              :class="{
                'form-control': true,
                'is-invalid': errors.has('password')
              }"
              data-vv-name="password"
              type="password"
              class="form-control mx-2"
              placeholder="Password"
            >
          </div>
          <button
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

          <span class="ml-1">
            or
            <router-link to="/signup">Sign up</router-link>
          </span>
        </form>
        <template v-else>
          Hi, <b>{{ user.name }}</b>.

          <button
            class="btn btn-primary btn-sm float-right ml-2"
            @click="$router.push('/profile')"
          >
            Profile
          </button>

          <button
            class="btn btn-secondary btn-sm float-right ml-2"
            @click="$actionWithLoading(logout, 'loadingLogout')"
          >
            <span v-if="loadingLogout">
              Loading...
            </span>
            <span v-else>
              Logout
            </span>
          </button>
        </template>
      </div>
    </nav>

    <div
      v-show="$auth.user && !$auth.user.hasVerifiedEmail"
      class="alert alert-primary mx-auto mt-3"
      role="alert"
    >
      Please, verify your email. We sent a message there.
      <button
        class="btn btn-primary btn-sm ml-2"
        @click="resendEmailVerification"
      >
        <span v-if="resendLoading">
          Loading...
        </span>
        <span v-else>
          Resend
        </span>
      </button>
    </div>

    <div
      class="container d-flex align-items-center justify-content-center"
      style="height: 100%;"
    >
      <transition
        name="router"
        mode="out-in"
      >
        <router-view/>
      </transition>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState, mapGetters } from 'vuex'
// import { actionWithLoading } from '@/tools/helpers'

export default {
  name: 'MainLayout',
  data: () => ({
    appName: process.env.VUE_APP_SECRET,
    form: {
      email: 'test@test.com',
      password: 'password'
    },
    loading: false,
    loadingLogout: false,
    resendLoading: false
  }),
  computed: {
    ...mapState('auth', ['user']),
    ...mapGetters('auth', ['token', 'loggedIn'])
  },
  methods: {
    async submit () {
      await this.$actionWithLoading(this.signin, 'loading', this.form)

      // this.loading = true
      // try {
      //   await this.signin(this.form)
      // } finally {
      //   this.loading = false
      // }
    },
    async resendEmailVerification () {
      await this.$actionWithLoading(this.resend, 'resendLoading')

      // this.resendLoading = true
      //
      // try {
      //   await this.resend()
      // } finally {
      //   this.resendLoading = false
      // }
    },
    ...mapActions('auth', ['signin', 'logout']),
    ...mapActions('auth/emailVerification', ['resend'])
  }
}
</script>
