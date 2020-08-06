<script>
export default {
  data () {
    return {
      loading: false,

      form: {
        username: null,
        password: null
      }
    }
  },

  methods: {
    async signIn () {
      this.loading = true

      try {
        await this.$store.dispatch('auth/signIn', this.form)
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
      <h1 class="mb-4">Вход</h1>
      <form class="mb-4" @submit.prevent="signIn">
        <div class="form-group">
          <label for="email">E-Mail</label>
          <input id="email" v-model="form.username" type="email" class="form-control" required autofocus autocomplete="email">
        </div>
        <div class="form-group">
          <label for="password">Пароль</label>
          <input id="password" v-model="form.password" type="password" class="form-control" required autocomplete="cur-password">
        </div>
        <div class="text-right">
          <button type="submit" class="btn btn-primary">Войти</button>
        </div>
      </form>
    </div>
  </div>
</template>
