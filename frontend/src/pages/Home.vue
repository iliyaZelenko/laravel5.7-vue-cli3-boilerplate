<template>
  <div class="w-100 h-100" >
    <auth/>

    <hr>

    <h2 class="mb-4 mt-5 text-center">
      Users
    </h2>

    <div
      v-show="!user"
      class="alert alert-primary mx-auto w-50"
      role="alert"
    >
      You need to be authorized to view.
    </div>

    <table
      v-show="users && loggedIn"
      class="table"
    >
      <thead class="thead-light">
        <tr>
          <th scope="col">id</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Created at</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="user in users"
          :key="user.id"
        >
          <th scope="row">{{ user.id }}</th>
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
          <td>{{ user.created_at }}</td>
        </tr>
      </tbody>
    </table>

    <div
      v-show="loading"
      class="text-muted text-center"
    >
      Loading...
    </div>
  </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import Auth from '@/components/Auth.vue'

export default {
  name: 'Home',
  components: { Auth },
  data: () => ({
    users: [],
    loading: false
  }),
  computed: {
    ...mapState('auth', ['user']),
    ...mapGetters('auth', ['loggedIn'])
  },
  watch: {
    loggedIn (val) {
      if (val) this.fetchUsers()
    }
  },
  mounted () {
    if (this.loggedIn) this.fetchUsers()
  },
  methods: {
    async fetchUsers () {
      this.loading = true
      this.users = await this.$get('users')
      this.loading = false
    }
  }
}
</script>
