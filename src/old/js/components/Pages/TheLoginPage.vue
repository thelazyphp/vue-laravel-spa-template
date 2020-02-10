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
                    <div class="card-header">{{ $t('login.title') }}</div>
                    <form
                        action="#"
                        class="card-body"
                        @submit.prevent="login"
                    >
                        <p class="text-center text-primary">
                            <i class="fas fa-sign-in-alt fa-5x"></i>
                        </p>
                        <p class="text-center">
                            {{ $t('login.not_registered_yet') }}
                            <br>
                            <RouterLink :to="{ name: 'register' }">{{ $t('login.register_account') }}</RouterLink>
                        </p>
                        <div class="form-group">
                            <label for="email">{{ $t('login.labels.email') }}</label>
                            <input
                                id="email"
                                v-model="email"
                                type="email"
                                class="form-control"
                                required
                                autocomplete="email"
                                autofocus
                                :class="{ 'is-invalid': errors.email }"
                            >
                            <div class="invalid-feedback">{{ errors.email }}</div>
                        </div>
                        <div class="form-group">
                            <label for="password">{{ $t('login.labels.password') }}</label>
                            <input
                                id="password"
                                v-model="password"
                                type="password"
                                class="form-control"
                                required
                                autocomplete="cur-password"
                                :class="{ 'is-invalid': errors.password }"
                            >
                            <div class="invalid-feedback">{{ errors.password }}</div>
                        </div>
                        <div class="d-flex w-100 justify-content-end">
                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                {{ $t('login.buttons.sign_in') }}
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

    export default {
        name: 'TheLoginPage',

        data () {
            return {
                email: '',
                password: '',

                errors: {
                    email: '',
                    password: ''
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

            login () {
                const data = {
                    username: this.email,
                    password: this.password
                }

                this['auth/login'](data)
                    .then(() => {
                        this.clearErrors()

                        this['alert/SHOW']({
                            message: this.$t('messages.signed_in')
                        })

                        this.$router.push({
                            name: 'home'
                        })
                    })
                    .catch(error => {
                        console.log(error)

                        if (error.response.status == 400) {
                            this['alert/SHOW']({
                                type: 'danger',
                                message: this.$t('errors.invalid_credentials')
                            })
                        } else if (error.response.status == 422) {
                            this.setErrors(error.response.data.errors)
                        } else {
                            this['alert/SHOW']({
                                type: 'danger',
                                message: this.$t('errors.sign_in_error')
                            })
                        }
                    })
            }
        }
    }
</script>
