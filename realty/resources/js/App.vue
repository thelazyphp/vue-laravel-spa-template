<template>
  <div id="app">
    <component :is="layout">
      <router-view />
    </component>
  </div>
</template>

<script>
import EmptyLayout from './layouts/Empty'
import DefaultLayout from './layouts/Default'

export default {
  components: {
    EmptyLayout,
    DefaultLayout
  },

  computed: {
    layout () {
      return `${this.$route.meta.layout || 'default'}-layout`
    }
  }
}
</script>
