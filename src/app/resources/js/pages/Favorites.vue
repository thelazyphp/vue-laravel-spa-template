<script>
import Posts from './Posts'

export default {
  extends: Posts,

  methods: {
    async fetchPosts (page = null) {
      if (!!page) {
        this.$store.commit('posts/setPage', page)
      }

      this.loading = true
      await this.$store.dispatch('posts/fetch', true)
      this.loading = false
    }
  }
}
</script>
