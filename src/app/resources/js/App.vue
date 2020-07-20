<script>
import Empty from './layouts/Empty'
import Default from './layouts/Default'

export default {
  components: {
    Empty,
    Default
  },

  computed: {
    layout () {
      return this.$route.meta.layout || 'default'
    }
  }
}
</script>

<template>
  <div id="app">
    <component :is="layout"></component>
  </div>
</template>

<style scoped>
  #app {
    width: 100%;
    height: 100vh;
  }
</style>
