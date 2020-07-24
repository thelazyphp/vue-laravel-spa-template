<script>
import GroupCard from '../components/GroupCard'

export default {
  components: {
    GroupCard
  },

  data () {
    return {
      addGroup: false,
      searching: false,
      url: null,
      group: null,
      notFound: false,
      loading: false,
      loadingNext: false
    }
  },

  computed: {
    title () {
      return this.$route.meta.title
    },

    groups () {
      return this.$store.state.groups.items
    },

    sort: {
      get () {
        return this.$store.state.groups.sort
      },

      set (value) {
        this.$store.commit('groups/setSort', value)
      }
    },

    search: {
      get () {
        return this.$store.state.groups.search
      },

      set (value) {
        this.$store.commit('groups/setSearch', value)
      }
    }
	},

	async created () {
    await this.fetchGroups()
  },

  methods: {
    async searchGroup () {
      this.group = null
      this.notFound = false
      this.searching = true

      try {
        const res = await this.$http.get(`/search-group?url=${this.url}`)
        this.group = res.data
      } catch (error) {
        this.notFound = true
      } finally {
        this.searching = false
      }
    },

    async createGroup () {
      await this.$store.dispatch('groups/create', this.group)
      this.addGroup = false
      this.url = false
      this.groups = false
    },

    async fetchGroups (page = null) {
      if (!!page) {
        this.$store.commit('groups/setPage', page)
      }

      this.loading = true
      await this.$store.dispatch('groups/fetch')
      this.loading = false
    },

    async fetchNextGroups () {
      this.loadingNext = true
      await this.$store.dispatch('groups/fetchNext')
      this.loadingNext = false
    },

    async removeGroup (id) {
      if (confirm('Удалить группу?')) {
        await this.$store.dispatch('groups/remove', id)
      }
    },

    async updateGroup (id, data) {
      await this.$store.dispatch('groups/update', { id, data })
    }
  }
}
</script>

<template>
  <div class="container">
    <div class="mx-auto" style="max-width: 500px">
      <h1 class="my-5 text-muted">{{ title }}</h1>
      <div class="my-5">
        <button v-if="!addGroup" type="button" class="btn btn-primary" @click="addGroup = true">Добавить группу</button>
        <div v-if="addGroup" class="card border-0 shadow">
          <div class="card-body">
            <p class="lead">Добавить группу</p>
            <div v-if="searching" class="mb-3 text-center">
              <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Поиск...</span>
              </div>
            </div>
            <form v-else class="mb-3" @submit.prevent="searchGroup">
              <div class="row no-gutters">
                <div class="col">
                  <input v-model="url" type="text" class="form-control" placeholder="Ссылка на группу или ID" required>
                </div>
                <div class="col-auto">
                  <button type="submit" class="btn ml-2 btn-primary">Найти</button>
                </div>
              </div>
            </form>
            <p v-if="notFound">Группа не найдена</p>
            <div v-else-if="group" class="d-flex mb-3">
              <a :href="group.url" class="mr-3" target="_blank">
                <span :style="{
                  display: 'inline-block',
                  width: '50px',
                  height: '50px',
                  borderRadius: '50%',
                  backgroundImage: `url(${group.image})`,
                  backgroundSize: 'cover',
                  backgroundRepeat: 'no-repeat',
                  backgroundPosition: 'center center' }"></span>
              </a>
              <a :href="group.url" target="_blank">{{ group.name }}</a>
            </div>
            <div class="d-flex">
              <button type="button" class="btn btn-primary" @click="addGroup = false">Закрыть</button>
              <button v-if="group" type="button" class="btn ml-2 btn-primary" @click="createGroup">Сохранить</button>
            </div>
          </div>
        </div>
      </div>
      <form class="mb-3" @submit.prevent="fetchGroups(1)">
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
              <select v-model="sort" class="form-control" @change="fetchGroups(1)">
                <option value="-updated_at">сначала новые</option>
                <option value="updated_at">сначала старые</option>
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
        <GroupCard v-for="group in groups" :key="group.id" :group="group" class="mb-3" @remove="removeGroup(group.id)" @update="updateGroup(group.id, $event)" />
      </template>
    </div>
  </div>
</template>
