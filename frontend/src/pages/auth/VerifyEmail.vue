<template>
  <div>
    <div
      class="alert alert-primary"
      role="alert"
    >
      Processing the email confirmation request ...
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  async mounted () {
    const { queryURL } = this.$route.query

    await this.verify(queryURL)
  },
  methods: {
    ...mapActions('auth/emailVerification', ['verify'])
  }
}
</script>
