<script>
import AppService from '../app.service'

export default {
  data () {
    return {
      loading: false,

      form: {
        company_name: null,
        f_name: null,
        m_name: null,
        l_name: null,
        email: null,
        password: null,
        password_confirmation: null
      }
    }
  },

  methods: {
    async signUp () {
      this.loading = true

      try {
        await AppService.signUp(this.form)

        await this.$store.dispatch('auth/signIn', {
          username: this.form.email,
          password: this.form.password
        })

        this.$router.push('/')
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
  <div>
    <div class="jumbotron jumbotron-fluid">
      <div class="container">
        <h1 class="display-4"><i class="fas fa-spinner"></i> REALTY</h1>
      </div>
    </div>
    <div class="container">
      <h1 class="mb-4">Регистрация</h1>
      <form class="mb-4" @submit.prevent="signUp">
        <div class="form-group">
          <label for="lastName">Фамилия <span class="text-danger">*</span></label>
          <input id="lastName" v-model="form.l_name" type="text" class="form-control" required autofocus autocomplete="family-name">
        </div>
        <div class="form-group">
          <label for="firstName">Имя <span class="text-danger">*</span></label>
          <input id="firstName" v-model="form.f_name" type="text" class="form-control" required autocomplete="given-name">
        </div>
        <div class="form-group">
          <label for="middleName">Отчество</label>
          <input id="middleName" v-model="form.m_name" type="text" class="form-control" autocomplete="additional-name">
        </div>
        <div class="form-group">
          <label for="companyName">Название организации</label>
          <input id="companyName" v-model="form.company_name" type="text" class="form-control">
        </div>
        <div class="form-group">
          <label for="email">E-Mail <span class="text-danger">*</span></label>
          <input id="email" v-model="form.email" type="email" class="form-control" required autocomplete="email">
        </div>
        <div class="form-group">
          <label for="password">Пароль <span class="text-danger">*</span></label>
          <input id="password" v-model="form.password" type="password" class="form-control" required autocomplete="new-password">
        </div>
        <div class="form-group">
          <label for="passwordConfirmation">Подтвердите пароль <span class="text-danger">*</span></label>
          <input id="passwordConfirmation" v-model="form.password_confirmation" type="password" class="form-control" required autocomplete="new-password" @paste.prevent>
        </div>
        <div class="text-right">
          <button type="submit" class="btn btn-primary">Регистрация</button>
        </div>
      </form>
    </div>
  </div>
</template>
