<template>
  <div v-if="$auth.user">
    <router-link
      to="/"
      class="float-left"
    >
      🡄  Go back
    </router-link>
    <br>

    <h1>{{ $auth.user.name }}</h1>

    <table class="table table-bordered">
      <tbody>
        <tr>
          <th scope="row">
            Created at:
          </th>
          <td>
            {{ $auth.user.createdAt }}
          </td>
        </tr>
        <tr>
          <th scope="row">
            Email:
          </th>
          <td>
            {{ $auth.user.email }}
          </td>
        </tr>
        <tr>
          <th scope="row">
            Has verified email:
          </th>
          <td>
            {{ $auth.user.hasVerifiedEmail }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: 'Profile'
}
</script>

<style lang="stylus">

</style>
