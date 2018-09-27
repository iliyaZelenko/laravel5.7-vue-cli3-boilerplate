<template>
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
      <span v-if="loading">
        Loading...
      </span>
      <span v-else>
        Resend
      </span>
    </button>
  </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  name: 'MainLayoutVerifyEmail',
  data: () => ({
    loading: false
  }),
  methods: {
    async resendEmailVerification () {
      await this.$actionWithLoading(this.resend)
    },
    ...mapActions('auth/emailVerification', ['resend'])
  }
}
</script>

<style scoped>

</style>
