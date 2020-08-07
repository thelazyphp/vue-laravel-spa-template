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
    defaultAvatar () {
      return '/realty/images/default_avatar.jpg'
    },

    page () {
      return this.$store.state.users.page
    },

    users () {
      return this.$store.state.users.items
    },

    lastPage () {
      return this.$store.state.users.lastPage
    },

    currentUser () {
      return this.$store.state.users.current
    },

    currentUserIsManager () {
      return this.currentUser.role == 'manager'
    },

    currentUserIsEmployee () {
      return this.currentUser.role == 'employee'
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
    <h1 class="my-4">Сотрудники</h1>
    <template v-if="loading">
      <div class="mb-4 text-center">
        <div class="spinner-border text-primary" role="status">
          <span class="sr-only">Загрузка...</span>
        </div>
      </div>
    </template>
    <template v-else>
      <div class="card mb-4">
        <div v-if="currentUserIsManager" class="card-header bg-white d-flex justify-content-end" style="padding: 0.75rem">
          <router-link to="/employees/create" class="btn btn-primary"><i class="fas fa-plus"></i> Добавить</router-link>
        </div>
        <div v-if="users.length" class="card-body p-0 table-responsive">
          <table class="table">
            <thead class="bg-light">
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Аватар</th>
                <th scope="col">Роль</th>
                <th scope="col">Фамилия</th>
                <th scope="col">Имя</th>
                <th scope="col">Отчество</th>
                <th v-if="currentUserIsManager" scope="col">Действия</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in users" :key="user.id">
                <td>{{ user.id }}</td>
                <td>
                  <UserAvatar :size="50" :image="user.image ? user.image.url : defaultAvatar" />
                </td>
                <td>
                  <h5>
                    <span class="badge badge-pill badge-primary">{{ user.role == 'manager' ? 'менеджер' : 'сотрудник' }}</span>
                  </h5>
                </td>
                <td>{{ user.l_name }}</td>
                <td>{{ user.f_name }}</td>
                <td>{{ user.m_name }}</td>
                <td v-if="currentUserIsManager">
                  <template v-if="user.id != currentUser.id">
                    <a href="" class="btn btn-link" title="Удалить" @click.prevent="removeUser(user.id)">
                      <i class="far fa-trash-alt"></i>
                    </a>
                    <router-link :to="`/employees/${user.id}/update`" class="btn btn-link" title="Редактировать">
                      <i class="far fa-edit"></i>
                    </router-link>
                  </template>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </template>
  </div>
</template>

<style scoped>
.table th {
  border-top: none;
  border-bottom: none;
}

.table th, td {
  white-space: nowrap;
  text-align: center;
  vertical-align: middle;
}
</style>
