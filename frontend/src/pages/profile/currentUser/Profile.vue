<template>
  <div
    v-if="$auth.user"
    class="w-100 h-100"
  >
    <div class="row">
      <div class="col-3">
        <h1>{{ $auth.user.name }}</h1>

        <router-link to="/">
          🡄  Go back
        </router-link>

        <div class="nav flex-column nav-pills mt-3">
          <router-link
            :to="{ name: 'profile' }"
            active-class="active"
            class="nav-link"
          >
            Main info
          </router-link>
          <router-link
            :to="{ name: 'profile-change-password' }"
            active-class="active"
            class="nav-link"
          >
            Change password
          </router-link>
          <!--<a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</a>-->
        </div>
      </div>
      <div class="col-9">
        <div class="tab-content">
          <div class="tab-pane fade show active">
            <div class="d-flex justify-content-center">
              <transition
                name="router"
                mode="out-in"
              >
                <router-view/>
              </transition>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Profile',
  data: () => ({})
}
</script>

<style lang="stylus">

</style>
