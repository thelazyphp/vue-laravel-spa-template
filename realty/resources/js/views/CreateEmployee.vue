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
        role: 'employee',
        f_name: null,
        m_name: null,
        l_name: null,
        email: null,
        password: null
      }
    }
  },

  computed: {
    defaultImage () {
      return '/realty/images/default_avatar.jpg'
    }
  },

  methods: {
    async createUser () {
      this.loading = true

      try {
        await AppService.createUser(this.form)
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
    <h1 class="my-4">Добавить сотрудника</h1>
    <div class="mb-4 text-center">
      <UserAvatarUpload :size="200" :image="image.url || defaultImage" :uploading="image.uploading" @upload="uploadImage" />
    </div>
    <form class="mb-4" @submit.prevent="createUser">
      <div class="form-group">
        <label for="lastName">Фамилия <span class="text-danger">*</span></label>
        <input id="lastName" v-model="form.l_name" type="text" class="form-control" required autofocus>
      </div>
      <div class="form-group">
        <label for="firstName">Имя <span class="text-danger">*</span></label>
        <input id="firstName" v-model="form.f_name" type="text" class="form-control" required>
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
        <label for="email">E-Mail <span class="text-danger">*</span></label>
        <input id="email" v-model="form.email" type="email" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="password">Пароль <span class="text-danger">*</span></label>
        <input id="password" v-model="form.password" type="text" class="form-control" required>
      </div>
      <div class="text-right">
        <button type="submit" class="btn btn-primary">Добавить</button>
      </div>
    </form>
  </div>
</template>
