<script>
import VTag from './VTag'

export default {
  components: {
    VTag
  },

  props: {
    tags: {
      type: Array,

      default () {
        return []
      }
    }
  }
}
</script>

<template>
  <div>
    <VTag v-for="(tag, index) in tags" :key="index" class="mr-2 mt-2" :text="tag" @remove="$emit('remove-tag', index)" />
    <slot></slot>
  </div>
</template>
