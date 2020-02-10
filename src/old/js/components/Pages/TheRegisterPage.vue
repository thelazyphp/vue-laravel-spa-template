<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div
                    v-if="loading"
                    class="text-center mb-3"
                >
                    <div
                        class="spinner-grow text-primary"
                        role="status"
                    >
                        <span class="sr-only">{{ $t('common.loading') }}...</span>
                    </div>
                </div>
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
                            <label for="first-name">{{ $t('register.labels.first_name') }}</label>
                            <input
                                id="first-name"
                                v-model="first_name"
                                type="text"
                                class="form-control"
                                required
                                autocomplete="given-name"
                                autofocus
                                :class="{ 'is-invalid': errors.first_name }"
                            >
                            <div class="invalid-feedback">{{ errors.first_name }}</div>
                        </div>
                        <div class="form-group">
                            <label for="last-name">{{ $t('register.labels.last_name') }}</label>
                            <input
                                id="last-name"
                                v-model="last_name"
                                type="text"
                                class="form-control"
                                required
                                autocomplete="family-name"
                                autofocus
                                :class="{ 'is-invalid': errors.last_name }"
                            >
                            <div class="invalid-feedback">{{ errors.last_name }}</div>
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
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import Http from '../../utils/Http'

    export default {
        name: 'TheRegisterPage',

        data () {
            return {
                first_name: '',
                last_name: '',
                email: '',
                password: '',
                passwordConfirmation: '',

                errors: {
                    first_name: '',
                    last_name: '',
                    email: '',
                    password: '',
                    passwordConfirmation: ''
                }
            }
        },

        computed: {
            ...mapState({
                loading: state => state.auth.loading
            })
        },

        methods: {
            ...mapActions([
                'auth/login'
            ]),

            register () {
                const data = {
                    first_name: this.first_name,
                    last_name: this.last_name,
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
