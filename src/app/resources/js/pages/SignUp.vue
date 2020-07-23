<script>
export default {
  data () {
    return {
      name: null,
      email: null,
      password: null,
      passwordConfirmation: null
    }
  },

  methods: {
    async signUp () {
      await this.$store.dispatch('auth/signUp', {
        name: this.name,
        email: this.email,
        password: this.password,
        password_confirmation: this.passwordConfirmation
      })

      await this.$store.dispatch('auth/signIn', {
        username: this.email,
        password: this.password
      })

      this.$router.push('/')
    }
  }
}
</script>

<template>
  <div class="card border-0 shadow">
    <div class="card-body">
      <form @submit.prevent="signUp">
        <div class="form-group">
          <label for="name" class="sr-only">Имя</label>
          <input id="name" v-model="name" type="text" class="form-control" placeholder="Имя" required autofocus>
        </div>
        <div class="form-group">
          <label for="email" class="sr-only">E-Mail</label>
          <input id="email" v-model="email" type="email" class="form-control" placeholder="E-Mail" required autocomplete="email">
        </div>
        <div class="form-group">
          <label for="password" class="sr-only">Пароль</label>
          <input id="password" v-model="password" type="password" class="form-control" placeholder="Пароль" required autocomplete="new-password">
        </div>
        <div class="form-group">
          <label for="passwordConfirmation" class="sr-only">Подтвердите пароль</label>
          <input id="passwordConfirmation" v-model="passwordConfirmation" type="password" class="form-control" placeholder="Подтвердите пароль" required autocomplete="new-password" @paste.prevent>
        </div>
        <button type="submit" class="btn btn-lg btn-block btn-primary">Регистрация</button>
      </form>
    </div>
  </div>
</template>

<style scoped>
.card {
  width: 400px;
}
</style>
