<script>
export default {
  props: {
    text: {
      type: String,
      required: true
    }
  }
}
</script>

<template>
  <span class="badge badge-pill badge-primary">
    <slot>{{ text }}</slot>
    <a href="" role="button" title="Удалить" @click.prevent="$emit('remove')">
      <i class="fas fa-times"></i>
    </a>
  </span>
</template>

<style scoped>
.badge {
  font-size: 100%;
  font-weight: 400;
}

.badge [role="button"] {
  color: inherit;
}
</style>
