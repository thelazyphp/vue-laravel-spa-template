<script>
export default {
  data () {
    return {
      email: null,
      password: null
    }
  },

  computed: {
    loading () {
      return this.$store.state.auth.loading
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
  <div class="container">
    <div class="content">
      <div class="card shadow border-0">
        <div class="card-body">
          <div v-if="loading" class="text-center">
            <div class="spinner-border text-primary" role="status">
              <span class="sr-only">Загрузка...</span>
            </div>
          </div>
          <form v-else @submit.prevent="signIn">
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
    </div>
  </div>
</template>

<style scoped>
.content {
  padding: 125px 0 125px 0;
}

.card {
  margin: auto;
  max-width: 400px;
}
</style>
