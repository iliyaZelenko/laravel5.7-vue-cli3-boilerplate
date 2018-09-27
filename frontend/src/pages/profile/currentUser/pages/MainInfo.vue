<template>
  <div class="w-100">
    <!--<h1>{{ $auth.user.name }}</h1>-->

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
        <tr>
          <th scope="row">
            Location:
          </th>
          <td>
            <div id="map"/>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data: () => ({
    map: null
  }),
  mounted () {
    // load only once
    if (window.google && window.google.maps) {
      this.initMap()
      return
    }

    const script = document.createElement('script')
    script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyAqEnLDFIYA4FBcReiN2Z8eqkLidAYcpdo&callback=initMap'
    script.async = script.defer = true

    document.body.appendChild(script)

    window.initMap = this.initMap
  },
  methods: {
    initMap () {
      this.map = new window.google.maps.Map(document.getElementById('map'), {
        center: { lat: -34.397, lng: 150.644 },
        zoom: 8
      })
    }
  }
}
</script>

<style lang="stylus">
  #map
    height: 300px;
    width: 100%;
</style>
