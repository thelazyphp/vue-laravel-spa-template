<template>
    <div class="page-wrapper">
        <main
            class="container"
            role="main"
        >
            <h1 class="text-center py-5 font-weight-light">Регистрация</h1>

            <div class="row">
                <form
                    class="mx-auto col-lg-6"
                    @submit.prevent="signUp"
                >
                    <div class="form-row">
                        <div class="col-sm-6 form-group">
                            <label for="firstName">Имя <span class="text-danger">*</span></label>

                            <input
                                id="firstName"
                                v-model="form.firstName"
                                type="text"
                                class="form-control"
                                required
                                autofocus
                                autocomplete="given-name">
                        </div>

                        <div class="col-sm-6 form-group">
                            <label for="lastName">Фамилия <span class="text-danger">*</span></label>

                            <input
                                id="lastName"
                                v-model="form.lastName"
                                type="text"
                                class="form-control"
                                required
                                autocomplete="family-name">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="companyName">Название организации</label>

                        <input
                            id="companyName"
                            v-model="form.companyName"
                            type="text"
                            class="form-control"
                            required
                            aria-describedby="companyNameHelp">

                        <small
                            id="companyNameHelp"
                            class="form-text text-muted">Вы сможете заполнить это поле позже.</small>
                    </div>

                    <div class="form-group">
                        <label for="email">E-Mail <span class="text-danger">*</span></label>

                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="form-control"
                            required
                            autocomplete="email">
                    </div>

                    <div class="form-row">
                        <div class="col-sm-6 form-group">
                            <label for="password">Пароль <span class="text-danger">*</span></label>

                            <input
                                id="password"
                                v-model="form.password"
                                type="password"
                                class="form-control"
                                required
                                autocomplete="new-password">
                        </div>

                        <div class="col-sm-6 form-group">
                            <label for="passwordConfirmation">Подтвердите пароль <span class="text-danger">*</span></label>

                            <input
                                id="passwordConfirmation"
                                v-model="form.passwordConfirmation"
                                type="password"
                                class="form-control"
                                required
                                autocomplete="new-password"
                                @paste.prevent>
                        </div>
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
                                aria-hidden="true"></span> Регистрация</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</template>

<script>
    import store from '../store'

    export default {
        name: 'SignUp',

        data () {
            return {
                loading: false,

                form: {
                    firstName: null,
                    lastName: null,
                    companyName: null,
                    email: null,
                    password: null,
                    passwordConfirmation: null
                }
            }
        },

        beforeRouteEnter (to, from, next) {
            if (store.getters['auth/isAuth']) {
                next('/')
            } else {
                document.title = `${process.env.MIX_APP_NAME} - Регистрация`
                next()
            }
        },

        methods: {
            signUp () {
                const endpoint = '/auth/register'

                const formData = {
                    first_name: this.form.firstName,
                    last_name: this.form.lastName,
                    company_name: this.form.companyName,
                    email: this.form.email,
                    password: this.form.password,
                    password_confirmation: this.form.passwordConfirmation
                }

                this.loading = true

                return this.$http.post(endpoint, formData)
                    .then(() => {
                        const formData = {
                            username: this.form.email,
                            password: this.form.password
                        }

                        this.$store.dispatch('auth/signIn', formData)
                            .then(() => this.$router.push('/'))
                    })
                    .catch(error => console.log(error)).finally(() => this.loading = false)
            }
        }
    }
</script>
