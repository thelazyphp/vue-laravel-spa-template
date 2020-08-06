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

  created () {
    this.avatar = this.defaultAvatar
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

    async createUser () {
      await AppService.createUser({
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
  <div class="py-5 container">
    <h1 class="mb-5">Добавить сотрудника</h1>
    <div class="mb-5 text-center">
      <UserAvatarUpload :size="200" :image="avatar" :uploading="uploadingAvatar" @upload="uploadAvatar"/>
    </div>
    <form @submit.prevent="createUser">
      <div class="row form-group">
        <label for="role" class="col-xl-2">Роль</label>
        <div class="col-xl-10">
          <select id="role" v-model="role" class="custom-select" autofocus>
            <option value="manager">Менеджер</option>
            <option value="employee">Сотрудник</option>
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
          <input id="password" v-model="password" type="text" class="form-control" required>
        </div>
      </div>
      <div class="text-right">
        <button type="submit" class="btn btn-primary">Добавить</button>
      </div>
    </form>
  </div>
</template>
