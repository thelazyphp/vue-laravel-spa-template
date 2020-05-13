<template>
    <div class="container">
        <div class="row">
            <form
                class="mx-auto col-lg-6"
                @submit.prevent="signUp"
            >
                <h1 class="mb-4 text-center font-weight-light">Регистрация</h1>

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
                            autocomplete="new-password" @paste.prevent>
                    </div>
                </div>

                <div class="pt-3">
                    <button
                        type="submit"
                        class="btn btn-lg btn-block btn-primary"
                        :disabled="isLoading"><span
                            v-if="isLoading"
                            role="status"
                            aria-hidden="true"
                            class="mr-3 spinner-border spinner-border-sm"></span>Регистрация</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'SignUp',

        data () {
            return {
                isLoading: false,

                form: {
                    firstName: null,
                    lastName: null,
                    companyName: null,
                    email: null,
                    password: null,
                    passwordConfirmation: null,
                },
            }
        },

        created () {
            document.title = `${process.env.MIX_APP_NAME} - Регистрация`
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
                    password_confirmation: this.form.passwordConfirmation,
                }

                this.isLoading = true

                return this.$http.post(endpoint, formData)
                    .then(() => {
                        const formData = {
                            username: this.form.email,
                            password: this.form.password,
                        }

                        return this.$store.dispatch('auth/signIn', formData)
                            .then(() => this.$router.push('/'))
                    })
                    .catch(error => console.log(error)).finally(() => this.isLoading = false)
            },
        },
    }
</script>
