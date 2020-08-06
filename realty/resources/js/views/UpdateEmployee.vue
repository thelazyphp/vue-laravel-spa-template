<script>
import AppService from '../app.service'
import UserAvatarUpload from '../components/UserAvatarUpload'

export default {
  components: {
    UserAvatarUpload
  },

  data () {
    return {
      uploadingAvatar: false,
      loading: false,
      avatar: null,
      imageId: null,
      role: 'employee',
      lastName: null,
      firstName: null,
      middleName: null,
      email: null,
      password: null
    }
  },

  computed: {
    defaultAvatar () {
      return '/realty/images/default_avatar.jpg'
    }
  },

  async created () {
    this.loading = true

    const res = await AppService.getUser(this.$route.params.id)

    if (res.data.image) {
      this.avatar = res.data.image.url
      this.imageId = res.data.image.id
    }

    this.role = res.data.role
    this.lastName = res.data.l_name
    this.firstName = res.data.f_name
    this.middleName = res.data.m_name
    this.email = res.data.email

    this.loading = false
  },

  methods: {
    async uploadAvatar (event) {
      if (event.target.files.length) {
        this.uploadingAvatar = true

        try {
          const res = await AppService.uploadImage(event.target.files[0])
          this.avatar = res.data.url
          this.imageId = res.data.id
        } catch (error) {
          this.avatar = null
          this.imageId = null
        } finally {
          this.uploadingAvatar = false
        }
      }
    },

    async updateUser () {
      await AppService.updateUser(this.$route.params.id, {
        image_id: this.imageId,
        role: this.role,
        f_name: this.firstName,
        m_name: this.middleName,
        l_name: this.lastName,
        email: this.email,
        password: this.password
      })

      this.$router.push('/employees')
    }
  }
}
</script>

<template>
  <div class="container">
    <h1 class="my-5">Редактировать сотрудника</h1>
    <template v-if="loading">
      <div class="mb-3 text-center">
        <div class="spinner-border text-primary" role="status">
          <span class="sr-only">Загрузка...</span>
        </div>
      </div>
    </template>
    <template v-else>
      <div class="mb-5 text-center">
        <UserAvatarUpload :size="200" :image="avatar" :uploading="uploadingAvatar" @upload="uploadAvatar"/>
      </div>
      <form @submit.prevent="updateUser">
        <div class="row form-group">
          <label for="role" class="col-xl-2">Роль</label>
          <div class="col-xl-10">
            <select id="role" v-model="role" class="custom-select" autofocus>
              <option value="employee">Сотрудник</option>
              <option value="manager">Менеджер</option>
            </select>
          </div>
        </div>
        <div class="row form-group">
          <label for="lastName" class="col-xl-2">Фамилия <span class="text-danger">*</span></label>
          <div class="col-xl-10">
            <input id="lastName" v-model="lastName" type="text" class="form-control" required>
          </div>
        </div>
        <div class="row form-group">
          <label for="firstName" class="col-xl-2">Имя <span class="text-danger">*</span></label>
          <div class="col-xl-10">
            <input id="firstName" v-model="firstName" type="text" class="form-control" required>
          </div>
        </div>
        <div class="row form-group">
          <label for="middleName" class="col-xl-2">Отчество</label>
          <div class="col-xl-10">
            <input id="middleName" v-model="middleName" type="text" class="form-control">
          </div>
        </div>
        <div class="row form-group">
          <label for="email" class="col-xl-2">E-Mail <span class="text-danger">*</span></label>
          <div class="col-xl-10">
            <input id="email" v-model="email" type="email" class="form-control" required>
          </div>
        </div>
        <div class="row form-group">
          <label for="password" class="col-xl-2">Пароль <span class="text-danger">*</span></label>
          <div class="col-xl-10">
            <input id="password" v-model="password" type="text" class="form-control">
          </div>
        </div>
        <div class="mb-3 text-right">
          <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
      </form>
    </template>
  </div>
</template>
