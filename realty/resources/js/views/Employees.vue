<script>
import UserAvatar from '../components/UserAvatar'

export default {
  components: {
    UserAvatar
  },

  data () {
    return {
      loading: false
    }
  },

  computed: {
    users () {
      return this.$store.state.users.items
    },

    defaultAvatar () {
      return '/realty/images/default_avatar.jpg'
    }
  },

  async created () {
    await this.fetchUsers()
  },

  methods: {
    async fetchUsers (page = null) {
      if (!!page) {
        this.$store.commit(
          'users/setPage', page
        )
      }

      this.loading = true

      try {
        await this.$store.dispatch('users/fetch')
      } catch (error) {
        //

        console.log(error)
      } finally {
        this.loading = false
      }
    },

    async removeUser (id) {
      if (confirm('Удалить сотрудника?')) {
        try {
          await this.$store.dispatch('users/remove', id)
        } catch (error) {
          //

          console.log(error)
        }
      }
    }
  }
}
</script>

<template>
  <div class="container">
    <h1 class="my-5">Сотрудники</h1>
    <template v-if="loading">
      <div class="mb-3 text-center">
        <div class="spinner-border text-primary" role="status">
          <span class="sr-only">Загрузка...</span>
        </div>
      </div>
    </template>
    <template v-else>
      <div class="card mb-3">
        <div class="card-body p-0 table-responsive">
          <div class="card-header d-flex bg-white" style="padding: 0.75rem">
            <form class="row flex-fill no-gutters" @submit.prevent>
              <div class="col-auto">
                <button type="submit" class="btn mr-2 btn-sm btn-primary">Найти</button>
              </div>
              <div class="col">
                <input type="search" class="form-control form-control-sm" placeholder="Поиск">
              </div>
            </form>
            <router-link to="/employees/create" class="btn ml-2 btn-sm btn-primary">Добавить</router-link>
          </div>
          <table class="table">
            <thead class="bg-light">
              <tr>
                <th scope="row" class="text-nowrap border-top-0 border-bottom-0">ID</th>
                <th scope="row" class="text-nowrap border-top-0 border-bottom-0"></th>
                <th scope="row" class="text-nowrap border-top-0 border-bottom-0">Роль</th>
                <th scope="row" class="text-nowrap border-top-0 border-bottom-0">Фамилия</th>
                <th scope="row" class="text-nowrap border-top-0 border-bottom-0">Имя</th>
                <th scope="row" class="text-nowrap border-top-0 border-bottom-0">Отчество</th>
                <th scope="row" class="text-nowrap border-top-0 border-bottom-0">E-Mail</th>
                <th scope="row" class="text-nowrap border-top-0 border-bottom-0"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in users" :key="user.id">
                <td class="text-nowrap align-middle">{{ user.id }}</td>
                <td>
                  <UserAvatar :size="50" :image="user.image ? user.image.url : defaultAvatar" />
                </td>
                <td class="text-nowrap align-middle">
                  <h5>
                    <span class="badge badge-pill badge-primary">{{ user.role == 'manager' ? 'менеджер' : 'сотрудник' }}</span>
                  </h5>
                </td>
                <td class="text-nowrap align-middle">{{ user.l_name }}</td>
                <td class="text-nowrap align-middle">{{ user.f_name }}</td>
                <td class="text-nowrap align-middle">{{ user.m_name }}</td>
                <td class="text-nowrap align-middle">
                  <a :href="`mailto:${user.email}`">{{ user.email }}</a>
                </td>
                <td class="text-nowrap align-middle">
                  <a href="" title="Удалить" @click.prevent="removeUser(user.id)">
                    <i class="far fa-trash-alt"></i>
                  </a>
                  <router-link :to="`/employees/${user.id}/update`" class="ml-2" title="Редактировать">
                    <i class="far fa-edit"></i>
                  </router-link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </template>
  </div>
</template>
