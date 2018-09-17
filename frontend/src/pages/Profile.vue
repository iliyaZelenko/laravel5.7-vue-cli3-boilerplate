<template>
  <!--class="w-100 h-100"-->
  <div v-if="user">
    <router-link
      to="/"
      class="float-left"
    >
      🡄  Go back
    </router-link>
    <br>
    <div
      class="card"
      style="width: 18rem;"
    >
      <div class="card-header">
        {{ user.name }}
      </div>
      <ul class="list-group list-group-flush">
        <li class="list-group-item">
          <b>Created at:</b> {{ user.createdAt }}
        </li>
        <li class="list-group-item">
          <b>Email:</b> {{ user.email }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
  name: 'Profile',
  data: () => ({
  }),
  computed: {
    ...mapState('auth', ['user'])
  },
  async mounted () {
    // console.log(axios.setToken)
    // this.$http.setHeader('Y-Custom-Header', 'value123')
    // this.$http.setToken('token123')
    // axios.defaults.headers.common['X-Custom-Header'] = 'value1'

    // this.groups = (await axios('https://randomuser.me/api/')).data
    // try {
    // if (this.loggedIn) {
    //   this.groups = await this.$get('groups')
    // }
    // } catch (e) {
    //   console.log(e)
    // }
  }
}
</script>

<style lang="stylus">

</style>
