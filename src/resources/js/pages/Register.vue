<template>
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header">{{ $t('pages.register.sign_up') }}</div>

                <form action="#" class="card-body" @submit.prevent="register">
                    <div class="text-center">
                        <p class="text-primary"><i class="fas fa-user fa-5x"></i></p>

                        <p>
                            {{ $t('pages.register.already_registered') }}<br>
                            <router-link :to="{ name: 'login' }">{{ $t('pages.register.sign_in_account') }}</router-link>
                        </p>
                    </div>

                    <div class="form-group">
                        <label for="name">{{ $t('pages.register.name') }}</label>
                        <input type="text" class="form-control" id="name" required autocomplete="name" autofocus v-model="name" :class="{ 'is-invalid': errors.name }">
                        <div class="invalid-feedback">{{ errors.name }}</div>
                    </div>

                    <div class="form-group">
                        <label for="email">{{ $t('pages.register.email') }}</label>
                        <input type="email" class="form-control" id="email" required autocomplete="email" v-model="email" :class="{ 'is-invalid': errors.email }">
                        <div class="invalid-feedback">{{ errors.email }}</div>
                    </div>

                    <div class="form-group">
                        <label for="password">{{ $t('pages.register.password') }}</label>
                        <input type="password" class="form-control" id="password" required autocomplete="new-password" v-model="password" :class="{ 'is-invalid': errors.password }">
                        <div class="invalid-feedback">{{ errors.password }}</div>
                    </div>

                    <div class="form-group">
                        <label for="password-confirmation">{{ $t('pages.register.confirm_password') }}</label>
                        <input type="password" class="form-control" id="password-confirmation" required autocomplete="new-password" @paste.prevent v-model="password_confirmation" :class="{ 'is-invalid': errors.password_confirmation }">
                        <div class="invalid-feedback">{{ errors.password_confirmation }}</div>
                    </div>

                    <div class="d-flex w-100 justify-content-end">
                        <button type="submit" class="btn btn-primary">{{ $t('buttons.sign_up') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import * as AuthService from '../services/auth.service'

    export default {
        name: 'register',

        data () {
            return {
                name: '',
                email: '',
                password: '',
                password_confirmation: '',

                errors: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                }
            }
        },

        methods: {
            register () {
                this.clearErrors()

                AuthService.makeRegister(this.name, this.email, this.password, this.password_confirmation)
                    .then(() => {
                        AuthService.makeLogin(this.email, this.password)
                            .then(() => {
                                this.$store.commit('alert/SHOW', {
                                    type: 'primary',
                                    message: this.$t('messages.signed_up')
                                })

                                this.$router.push({ name: 'home' })
                            })
                            .catch(error => {
                                console.log(error)
                                this.$store.commit('alert/SHOW', {
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
                            this.$store.commit('alert/SHOW', {
                                type: 'danger',
                                message: this.$t('errors.sign_up_error')
                            })
                        }
                    })
            }
        }
    }
</script>
