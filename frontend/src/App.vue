<template>
  <div id="app">
    <!-- if need to refresh token before doing requests, if token expired then tokenNeedToRefresh === true -->
    <div
      v-if="loggedIn && tokenExpired"
      class="d-flex justify-content-center align-items-center h-100"
    >
      <h1>Loading...</h1>
    </div>
    <router-view v-else/> <!--v-else-if="(loggedIn && user) || !loggedIn"-->
  </div>
</template>

<style lang="stylus">
  #app
    font-family 'Avenir', Helvetica, Arial, sans-serif
</style>

<script>
import { mapState, mapGetters } from 'vuex'

export default {
  data: () => ({}),
  computed: {
    ...mapState('auth', ['user']),
    ...mapGetters('auth', ['loggedIn', 'tokenExpired'])
  }
  // async mounted () {
  //   await this.$store.dispatch('auth/init')
  // }
}
</script>
