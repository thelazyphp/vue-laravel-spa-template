<script>
export default {
  data () {
    return {
      email: null,
      password: null
    }
  },

  methods: {
    async signIn() {
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
      <form @submit.prevent="signIn">
        <div class="form-group">
          <label for="email" class="sr-only">E-Mail</label>
          <input id="email" v-model="email" type="email" class="form-control" placeholder="E-Mail" required autofocus autocomplete="email">
        </div>
        <div class="form-group">
          <label for="password" class="sr-only">Пароль</label>
          <input id="password" v-model="password" type="password" class="form-control" placeholder="Пароль" required autocomplete="cur-password">
        </div>
        <button type="submit" class="btn btn-lg btn-block btn-primary">Войти</button>
      </form>
    </div>
  </div>
</template>

<style scoped>
.card {
  width: 400px;
}
</style>
