<script>
export default {
  data () {
    return {
      name: null,
      email: null,
      password: null,
      newPassword: null,
      newPasswordConfirmation: null
    }
  },

  computed: {
    user () {
      return this.$store.state.users.current
    }
  },

  created () {
    this.name = this.user.name
    this.email = this.user.email
  },

  methods: {
    async update () {
      let data = {
        name: this.name,
        email: this.email
      }

      if (this.newPassword) {
        data['password'] = this.password
        data['new_password'] = this.newPassword
        data['new_password_confirmation'] = this.newPasswordConfirmation
      }

      await this.$store.dispatch('users/updateCurrent', data)
    }
  }
}
</script>

<template>
  <div>
    <div class="jumbotron text-white jumbotron-fluid">
      <div class="container">
        <h1 class="display-4">Профиль</h1>
      </div>
    </div>
    <section class="container">
      <form @submit.prevent="update">
        <div class="form-group">
          <label for="name">Имя</label>
          <input id="name" v-model="name" type="text" class="form-control">
        </div>
        <div class="form-group">
          <label for="email">E-Mail</label>
          <input id="email" v-model="email" type="email" class="form-control">
        </div>
        <div class="form-group">
          <label for="password">Пароль</label>
          <input id="password" v-model="password" type="password" class="form-control" :required="newPassword">
        </div>
        <div class="form-group">
          <label for="newPassword">Новый пароль</label>
          <input id="newPassword" v-model="newPassword" type="password" class="form-control">
        </div>
        <div class="form-group">
          <label for="newPasswordConfirmation">Подтвердите новый пароль</label>
          <input id="newPasswordConfirmation" v-model="newPasswordConfirmation" type="password" class="form-control" :required="newPassword" @paste.prevent>
        </div>
        <div class="mb-3 text-right">
          <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
      </form>
    </section>
  </div>
</template>

<style scoped>
.jumbotron {
  background-image: url("/facebook/images/background.jpg");
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center center;
}

section.container {
  margin: auto;
  max-width: 500px;
}
</style>
