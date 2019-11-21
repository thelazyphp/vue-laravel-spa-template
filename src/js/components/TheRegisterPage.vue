<template>
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header">{{ $t('register.title') }}</div>

                <form
                    action="#"
                    class="card-body"
                    @submit.prevent="register"
                >
                    <p class="text-center text-primary">
                        <i class="fas fa-user-plus fa-5x"></i>
                    </p>

                    <p class="text-center">
                        {{ $t('register.already_registered') }}
                        <br>
                        <RouterLink :to="{ name: 'login' }">{{ $t('register.sign_in_account') }}</RouterLink>
                    </p>

                    <div class="form-group">
                        <label for="name">{{ $t('register.labels.name') }}</label>
                        <input
                            id="name"
                            v-model="name"
                            type="text"
                            class="form-control"
                            required
                            autocomplete="name"
                            autofocus
                            :class="{ 'is-invalid': errors.name }"
                        >
                        <div class="invalid-feedback">{{ errors.name }}</div>
                    </div>

                    <div class="form-group">
                        <label for="email">{{ $t('register.labels.email') }}</label>
                        <input
                            id="email"
                            v-model="email"
                            type="email"
                            class="form-control"
                            required
                            autocomplete="email"
                            :class="{ 'is-invalid': errors.email }"
                        >
                        <div class="invalid-feedback">{{ errors.email }}</div>
                    </div>

                    <div class="form-group">
                        <label for="password">{{ $t('register.labels.password') }}</label>
                        <input
                            id="password"
                            v-model="password"
                            type="password"
                            class="form-control"
                            required
                            autocomplete="new-password"
                            :class="{ 'is-invalid': errors.password }"
                        >
                        <div class="invalid-feedback">{{ errors.password }}</div>
                    </div>

                    <div class="form-group">
                        <label for="password-confirmation">{{ $t('register.labels.confirm_password') }}</label>
                        <input
                            id="password-confirmation"
                            v-model="passwordConfirmation"
                            type="password"
                            class="form-control"
                            required
                            autocomplete="new-password"
                            :class="{ 'is-invalid': errors.passwordConfirmation }"
                            @paste.prevent
                        >
                        <div class="invalid-feedback">{{ errors.passwordConfirmation }}</div>
                    </div>

                    <div class="d-flex w-100 justify-content-end">
                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            {{ $t('register.buttons.sign_up') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions } from 'vuex'
    import Http from '../utils/Http'

    export default {
        data () {
            return {
                name: '',
                email: '',
                password: '',
                passwordConfirmation: '',
                errors: {
                    name: '',
                    email: '',
                    password: '',
                    passwordConfirmation: ''
                }
            }
        },

        methods: {
            ...mapActions([
                'auth/login'
            ]),

            register () {
                const data = {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.passwordConfirmation
                }

                new Http().post('/auth/register', data)
                    .then(() => {
                        this.clearErrors()

                        const data = {
                            username: this.email,
                            password: this.password
                        }

                        this['auth/login'](data)
                            .then(() => {
                                this['alert/SHOW']({
                                    message: this.$t('messages.signed_up')
                                })

                                this.$router.push({
                                    name: 'home'
                                })
                            })
                            .catch(error => {
                                console.log(error)
                                this['alert/SHOW']({
                                    type: 'danger',
                                    message: this.$t('errors.sign_in_error')
                                })
                            })
                    })
                    .catch(error => {
                        console.log(error)
                        if (error.response.status == 422) {
                            this.setErrors(error.response.data.errors)
                        } else {
                            this['alert/SHOW']({
                                type: 'danger',
                                message: this.$t('errors.sign_up_error')
                            })
                        }
                    })
            }
        }
    }
</script>

<style scoped>

</style>
