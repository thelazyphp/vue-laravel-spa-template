<script>
import VPostCard from '../../components/VPostCard'

export default {
	components: {
		VPostCard
	},

	computed: {
    loading () {
			return this.$store.state.posts.loading
    },

		posts () {
			return this.$store.state.posts.items
    }
	},

	async created () {
		if (!this.posts.length) {
			await this.$store.dispatch('posts/fetch')
		}
	}
}
</script>

<template>
  <div>
    <div class="jumbotron text-white jumbotron-fluid">
      <div class="container">
        <h1 class="display-4">Посты</h1>
      </div>
    </div>
    <section class="container">
      <!-- -->
    </section>
  </div>
</template>

<style scoped>
.jumbotron {
  background-image: url("/facebook/images/background.jpg");
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center center;
}

section.container {
  margin: auto;
  max-width: 500px;
}
</style>
