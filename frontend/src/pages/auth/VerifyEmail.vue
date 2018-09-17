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
export default {
  async mounted () {
    const { queryURL } = this.$route.query

    try {
      const result = await this.$post(queryURL)

      console.log(result)
    } catch ({ response: { status } }) {
      if (status === 401) {
        this.$router.push({ name: 'signin' })
      }
    }
  }
}
</script>
