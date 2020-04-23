<template>
    <div class="page-wrapper">
        <main
            class="container"
            role="main"
        >
            <h1 class="text-center py-5 font-weight-light">Войти</h1>

            <div class="row">
                <form
                    class="mx-auto col-lg-4"
                    @submit.prevent="signIn"
                >
                    <div class="form-group">
                        <label for="email">E-Mail</label>

                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="form-control"
                            required
                            autofocus
                            autocomplete="email">
                    </div>

                    <div class="form-group">
                        <label for="password">Пароль</label>

                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="form-control"
                            required
                            autocomplete="cur-password">
                    </div>

                    <div class="py-3">
                        <button
                            type="submit"
                            class="btn btn-lg btn-block btn-primary"
                            :disabled="loading"
                        >
                            <span
                                v-if="loading"
                                class="spinner-border spinner-border-sm"
                                role="status"
                                aria-hidden="true"></span> Войти</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</template>

<script>
    import store from '../store'

    export default {
        name: 'SignIn',

        data () {
            return {
                loading: false,

                form: {
                    email: null,
                    password: null
                }
            }
        },

        beforeRouteEnter (to, from, next) {
            if (store.getters['auth/isAuth']) {
                next('/')
            } else {
                document.title = `${process.env.MIX_APP_NAME} - Войти`
                next()
            }
        },

        methods: {
            signIn () {
                const formData = {
                    username: this.form.email,
                    password: this.form.password
                }

                this.loading = true

                return this.$store.dispatch('auth/signIn', formData)
                    .then(() => this.$router.push('/'))
                    .catch(error => console.log(error)).finally(() => this.loading = false)
            }
        }
    }
</script>
