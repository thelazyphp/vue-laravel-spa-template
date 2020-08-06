<template>
  <span :style="style" class="user-avatar"></span>
</template>

<script>
export default {
  props: {
    size: {
      type: Number,
      required: true
    },

    image: {
      type: String,
      required: true
    }
  },

  computed: {
    style () {
      return {
        width: `${this.size}px`,
        height: `${this.size}px`,
        backgroundImage: `url(${this.image})`
      }
    }
  }
}
</script>

<style scoped>
.user-avatar {
  display: inline-block;
  border-radius: 50%;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center center;
}
</style>
