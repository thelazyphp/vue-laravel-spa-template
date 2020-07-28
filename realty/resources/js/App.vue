<script>
import DefaultLayout from './layouts/Default'

export default {
  components: {
    DefaultLayout
  },

  computed: {
    layout () {
      return `${this.$route.meta.layout || 'default'}-layout`
    }
  }
}
</script>

<template>
  <div id="app">
    <component :is="layout"></component>
  </div>
</template>
