<script>
export default {
  props: {
    post: {
      type: Object,
      required: true
    }
  },

  computed: {
    date () {
      const now = new Date(this.post.published_at)
      return `${now.toLocaleDateString()} в ${now.toLocaleTimeString()}`
    },

    message () {
      return this.post.message.length <= 200
        ? this.post.message
        : (this.post.message.substr(0, 200) + '...')
    }
  }
}
</script>

<template>
  <div class="card shadow border-0">
    <div class="card-body">
      <div class="media mb-3">
        <a class="mr-3" target="_blank" :href="post.user.url || post.url">
          <img class="user-avatar" alt="" :src="post.user.image">
        </a>
        <div class="media-body">
          <div class="d-flex flex-column">
            <a target="_blank" :href="post.user.url || post.url">{{ post.user.name }}</a>
            <small>{{ date }}</small>
          </div>
        </div>
      </div>
      <p v-if="post.message">{{ message }}</p>
    </div>
  </div>
</template>

<style scoped>
.user-avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
}
</style>
