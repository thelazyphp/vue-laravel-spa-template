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

      form: {
        f_name: null,
        m_name: null,
        l_name: null,
        email: null
      },

      image: {
        uploading: false,
        url: null
      },

      companyName: null,
      curPassword: null,
      newPassword: null,
      newPasswordConfirmation: null,
      removeAccountConfirmation: null
    }
  },

  computed: {
    defaultImage () {
      return '/realty/images/default_avatar.jpg'
    },

    user () {
      return this.$store.state.users.current
    },

    isManager () {
      return this.user.role == 'manager'
    },

    isEmployee () {
      return this.user.role == 'employee'
    },

    removeAccountConfirmed () {
      return this.removeAccountConfirmation == 'удалить мой аккаунт'
    }
  },

  created () {
    this.form.f_name = this.user.f_name
    this.form.m_name = this.user.m_name
    this.form.l_name = this.user.l_name
    this.form.email = this.user.email

    if (this.user.image) {
      this.image.url = this.user.image.url
    }

    if (this.user.company) {
      this.companyName = this.user.company.name
    }
  },

  methods: {
    async uploadImage(event) {
      if (event.target.files.length) {
        this.image.uploading = true

        try {
          const res = await AppService.uploadImage(event.target.files[0])

          await this.$store.dispatch('users/updateCurrent', {
            image_id: res.data.id
          })

          this.image.url = res.data.url
        } catch (error) {
          //

          console.log(error)
          this.image.url = null
        } finally {
          this.image.uploading = false
        }
      }
    },

    async updateProfile () {
      let user = this.form

      if (this.companyName && this.isManager) {
        user['company_name'] = this.companyName
      }

      if (this.newPassword) {
        user['cur_password'] = this.curPassword
        user['new_password'] = this.newPassword
        user['new_password_confirmation'] = this.newPasswordConfirmation
      }

      this.loading = true

      try {
        await this.$store.dispatch('users/updateCurrent', user)
      } catch (error) {
        //

        console.log(error)
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

<template>
  <div class="container">
    <h1 class="my-4">Профиль</h1>
    <div class="mb-4 text-center">
      <UserAvatarUpload :size="200" :image="image.url || defaultImage" :uploading="image.uploading" @upload="uploadImage" />
    </div>
    <div class="mb-4 text-center">
      <h2>{{ user.l_name }} {{ user.f_name }} {{ user.m_name }}</h2>
      <h4>
        <span class="badge badge-pill badge-primary">{{ isManager ? 'менеджер' : 'сотрудник' }}</span>
      </h4>
    </div>
    <form class="mb-4" @submit.prevent="updateProfile">
      <div class="form-group">
        <label for="lastName">Фамилия</label>
        <input id="lastName" v-model="form.l_name" type="text" class="form-control" required autofocus>
      </div>
      <div class="form-group">
        <label for="firstName">Имя</label>
        <input id="firstName" v-model="form.f_name" type="text" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="middleName">Отчество</label>
        <input id="middleName" v-model="form.m_name" type="text" class="form-control">
      </div>
      <div class="form-group">
        <label for="companyName">Название организации</label>
        <input id="companyName" v-model="companyName" :disabled="isEmployee" type="text" class="form-control">
      </div>
      <div class="form-group">
        <label for="email">E-Mail</label>
        <input id="email" v-model="form.email" type="email" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="curPassword">Текущий пароль <span v-if="newPassword" class="text-danger">*</span></label>
        <input id="curPassword" v-model="curPassword" :required="newPassword ? true : false" type="password" class="form-control">
      </div>
      <div class="form-group">
        <label for="newPassword">Новый пароль</label>
        <input id="newPassword" v-model="newPassword" type="password" class="form-control">
      </div>
      <div class="form-group">
        <label for="newPasswordConfirmation">Подтвердите новый пароль <span v-if="newPassword" class="text-danger">*</span></label>
        <input id="newPasswordConfirmation" v-model="newPasswordConfirmation" :required="newPassword ? true : false" type="password" class="form-control" @paste.prevent>
      </div>
      <div class="text-right">
        <button type="submit" class="btn btn-primary">Сохранить</button>
      </div>
    </form>
  </div>
</template>
