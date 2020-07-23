<script>
import PostCard from '../components/PostCard'
import TagsInput from '../components/TagsInput'

export default {
  components: {
    PostCard,
    TagsInput
  },

  data () {
    return {
      loading: false
    }
  },

  computed: {
    title () {
      return this.$route.meta.title || 'Посты'
    },

    tags () {
      return this.$store.getters['tags/text']
    },

    posts () {
      return this.$store.state.posts.items
    },

    sort: {
      get () {
        return this.$store.state.posts.sort
      },

      set (value) {
        this.$store.commit('posts/setSort', value)
      }
    },

    search: {
      get () {
        return this.$store.state.posts.search
      },

      set (value) {
        this.$store.commit('posts/setSearch', value)
      }
    }
	},

	async created () {
    await this.$store.dispatch('tags/fetch')
    await this.fetchPosts()
  },

  methods: {
    async addTag (text) {
      await this.$store.dispatch('tags/create', text)
      await this.fetchPosts(1)
    },

    async removeTag (index) {
      await this.$store.dispatch('tags/remove', index)
      await this.fetchPosts(1)
    },

    async fetchPosts (page = null) {
      if (!!page) {
        this.$store.commit('posts/setPage', page)
      }

      this.loading = true
      await this.$store.dispatch('posts/fetch')
      this.loading = false
    },

    async togglePostFavorite (id) {
      await this.$store.dispatch('posts/toggleFavorite', id)
    }
  }
}
</script>

<template>
  <div class="container">
    <div class="mx-auto" style="max-width: 500px">
      <h1 class="my-5 text-muted">{{ title }}</h1>
      <TagsInput :tags="tags" class="mb-3" @add="addTag($event)" @remove="removeTag($event)" />
      <form class="mb-3" @submit.prevent="fetchPosts(1)">
        <div class="row no-gutters">
          <div class="col">
            <input v-model="search" type="search" class="form-control" placeholder="Поиск">
          </div>
          <div class="col-auto">
            <button type="submit" class="btn ml-2 btn-primary">Найти</button>
          </div>
        </div>
      </form>
      <div class="dropdown mb-3 text-right">
        <a href="" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">параметры</a>
        <div class="dropdown-menu p-3 dropdown-menu-right">
          <form @submit.prevent>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">Сортировать</span>
              </div>
              <select v-model="sort" class="form-control" @change="fetchPosts(1)">
                <option value="-published_at">сначала новые</option>
                <option value="published_at">сначала старые</option>
              </select>
            </div>
          </form>
        </div>
      </div>
      <template v-if="loading">
        <div class="text-center">
          <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Загрузка...</span>
          </div>
        </div>
      </template>
      <template v-else>
        <PostCard v-for="post in posts" :key="post.id" :post="post" class="mb-3" @toggle-favorite="togglePostFavorite(post.id)" />
      </template>
    </div>
  </div>
</template>
