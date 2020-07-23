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
  <nav class="navbar navbar-light bg-white shadow-sm">
    <router-link to="/" class="navbar-brand">Facebook</router-link>
    <a href="" class="d-inline d-md-none" role="button" @click.prevent="$emit('toggle-sidebar')">
      <i :class="sidebarToggled ? 'fas fa-times' : 'fas fa-bars'"></i>
    </a>
  </nav>
</template>
