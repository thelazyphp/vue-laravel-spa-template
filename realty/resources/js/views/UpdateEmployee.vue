<script>
import AppService from '../app.service'
import UserAvatarUpload from '../components/UserAvatarUpload'

export default {
  components: {
    UserAvatarUpload
  },

  data () {
    return {
      loading: false,

      image: {
        uploading: false,
        url: null
      },

      form: {
        image_id: null,
        role: null,
        f_name: null,
        m_name: null,
        l_name: null,
        email: null
      },

      password: null
    }
  },

  computed: {
    id () {
      return this.$route.params.id
    },

    defaultImage () {
      return '/realty/images/default_avatar.jpg'
    }
  },

  async created () {
    this.loading = true

    try {
      const res = await AppService.getUser(this.id)

      if (res.data.image) {
        this.image.url = res.data.image.url
        this.form.image_id = res.data.image.id
      }

      this.form.role = res.data.role
      this.form.f_name = res.data.f_name
      this.form.m_name = res.data.m_name
      this.form.l_name = res.data.l_name
      this.form.email = res.data.email
    } catch (error) {
      //

      console.log(error)
    } finally {
      this.loading = false
    }
  },

  methods: {
    async updateUser () {
      const user = this.form

      if (this.password) {
        user['password'] = this.password
      }

      this.loading = true

      try {
        await AppService.updateUser(this.id, this.form)
        this.$router.push('/employees')
      } catch (error) {
        //

        console.log(error)
      } finally {
        this.loading = false
      }
    },

    async uploadImage (event) {
      this.image.uploading = true

      try {
        const res = await AppService.uploadImage(event.target.files[0])
        this.image.url = res.data.url
        this.form.image_id = res.data.id
      } catch (error) {
        //

        console.log(error)
        this.image.url = null
        his.form.image_id = null
      } finally {
        this.image.uploading = false
      }
    }
  }
}
</script>

<template>
  <div class="container">
    <h1 class="my-4">Редактировать сотрудника</h1>
    <div class="mb-4 text-center">
      <UserAvatarUpload :size="200" :image="image.url || defaultImage" :uploading="image.uploading" @upload="uploadImage" />
    </div>
    <form class="mb-4" @submit.prevent="updateUser">
      <div class="form-group">
        <label for="lastName">Фамилия</label>
        <input id="lastName" v-model="form.l_name" type="text" class="form-control" autofocus>
      </div>
      <div class="form-group">
        <label for="firstName">Имя</label>
        <input id="firstName" v-model="form.f_name" type="text" class="form-control">
      </div>
      <div class="form-group">
        <label for="middleName">Отчество</label>
        <input id="middleName" v-model="form.m_name" type="text" class="form-control">
      </div>
      <div class="form-group">
        <label for="role">Роль</label>
        <select id="role" v-model="form.role" class="custom-select">
          <option value="employee">Сотрудник</option>
          <option value="manager">Менеджер</option>
        </select>
      </div>
      <div class="form-group">
        <label for="email">E-Mail</label>
        <input id="email" v-model="form.email" type="email" class="form-control">
      </div>
      <div class="form-group">
        <label for="password">Пароль</label>
        <input id="password" v-model="password" type="text" class="form-control">
      </div>
      <div class="text-right">
        <button type="submit" class="btn btn-primary">Сохранить</button>
      </div>
    </form>
  </div>
</template>
