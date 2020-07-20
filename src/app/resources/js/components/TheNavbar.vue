<script>
export default {
  props: {
    sidebarToggled: {
      type: Boolean,
      required: true
    }
  }
}
</script>

<template>
  <nav class="navbar fixed-top navbar-light bg-white shadow-sm">
    <router-link to="/" class="navbar-brand" active-class="" exact-active-class="">Facebook</router-link>
    <a href="" class="text-primary d-inline-block d-md-none" role="button" @click.prevent="$emit('toggle-sidebar')">
      <i v-if="sidebarToggled" class="fas fa-times"></i>
      <i v-else class="fas fa-bars"></i>
    </a>
  </nav>
</template>
