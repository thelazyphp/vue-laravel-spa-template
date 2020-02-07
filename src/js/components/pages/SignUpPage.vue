<template>
    <div class="page-wrapper">
        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <h1 class="display-4">Регистрация</h1>
            </div>
        </div>

        <div class="container">
            <form @submit.prevent="onSubmit">
                <div class="form-group">
                    <label
                        class="sr-only"
                        for="firstName"
                    >
                        Имя
                    </label>

                    <input
                        id="firstName"
                        v-model="firstName"
                        class="form-control"
                        type="text"
                        placeholder="Имя"
                        required
                        autocomplete="given-name"
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label
                        class="sr-only"
                        for="lastName"
                    >
                        Фамилия
                    </label>

                    <input
                        id="lastName"
                        v-model="lastName"
                        class="form-control"
                        type="text"
                        placeholder="Фамилия"
                        required
                        autocomplete="family-name"
                    >
                </div>

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
                        autocomplete="new-password"
                    >
                </div>

                <div class="form-group">
                    <label
                        class="sr-only"
                        for="passwordConfirmation"
                    >
                        Подтвердите пароль
                    </label>

                    <input
                        id="passwordConfirmation"
                        v-model="passwordConfirmation"
                        class="form-control"
                        type="password"
                        placeholder="Подтвердите пароль"
                        required
                        autocomplete="new-password"
                    >
                </div>

                <div class="text-right">
                    <button
                        class="btn btn-primary"
                        type="submit"
                    >
                        Регистрация
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapMutations } from 'vuex'
    import request from '../../utils/request'

    export default {
        name: 'SignInPage',

        data () {
            return {
                firstName: null,
                lastName: null,
                email: null,
                password: null,
                passwordConfirmation: null
            }
        },

        beforeRouteEnter (to, from, next) {
            document.title = `${process.env.MIX_APP_NAME} - Регистрация`
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
                    first_name: this.firstName,
                    last_name: this.lastName,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.passwordConfirmation
                }

                request.post('/auth/register', formData)
                    .then(() => {
                        const formData = {
                            username: this.email,
                            password: this.password
                        }

                        this['auth/signIn'](formData)
                            .then(() => {
                                this.$router.push('/')

                                this['alert/show']({
                                    type: 'primary',
                                    message: 'Аккаунт успешно зарегистрирован.'
                                })
                            })
                    })
                    .catch(error => {
                        if (error.response.status == 422) {
                            Object.keys(error.response.data.errors).forEach(key => {
                                this['alert/show']({
                                    type: 'danger',
                                    message: `${error.response.data.errors[key][0]}`
                                })
                            })
                        }
                    })
            }
        }
    }
</script>
