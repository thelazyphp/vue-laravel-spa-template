<script>
import VGroupCard from '../../components/VGroupCard'

export default {
	components: {
		VGroupCard
	},

	computed: {
    loading () {
			return this.$store.state.groups.loading
    },

		posts () {
			return this.$store.state.groups.items
    }
	},

	async created () {
		if (!this.groups.length) {
			await this.$store.dispatch('groups/fetch')
		}
	}
}
</script>

<template>
  <div>
    <div class="jumbotron text-white jumbotron-fluid">
      <div class="container">
        <h1 class="display-4">Группы</h1>
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
