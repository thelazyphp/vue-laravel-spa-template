<template>
    <div class="page-wrapper">
        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <h1 class="display-4">Войти</h1>
            </div>
        </div>

        <div class="container">
            <form @submit.prevent="onSubmit">
                <div class="form-group">
                    <label
                        class="sr-only"
                        for="email"
                    >
                        E-Mail
                    </label>

                    <input
                        id="email"
                        v-model="email"
                        class="form-control"
                        type="email"
                        placeholder="E-Mail"
                        required
                        autocomplete="email"
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label
                        class="sr-only"
                        for="password"
                    >
                        Пароль
                    </label>

                    <input
                        id="password"
                        v-model="password"
                        class="form-control"
                        type="password"
                        placeholder="Пароль"
                        required
                        autocomplete="cur-password"
                    >
                </div>

                <div class="text-right">
                    <button
                        class="btn btn-primary"
                        type="submit"
                    >
                        Войти
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapMutations } from 'vuex'

    export default {
        name: 'SignInPage',

        data () {
            return {
                email: null,
                password: null
            }
        },

        beforeRouteEnter (to, from, next) {
            document.title = `${process.env.MIX_APP_NAME} - Войти`
            next()
        },

        methods: {
            ...mapMutations([
                'alert/show'
            ]),

            ...mapActions([
                'auth/signIn'
            ]),

            onSubmit () {
                const formData = {
                    username: this.email,
                    password: this.password
                }

                this['auth/signIn'](formData)
                    .then(() => {
                        this.$router.push('/')

                        this['alert/show']({
                            type: 'primary',
                            message: 'Вы успешно вошли в аккаунт.'
                        })
                    })
                    .catch(error => {
                        if (error.response.data.error == 'invalid_grant') {
                            this['alert/show']({
                                type: 'danger',
                                message: 'Неверный E-Mail или пароль!'
                            })
                        }
                    })
            }
        }
    }
</script>
