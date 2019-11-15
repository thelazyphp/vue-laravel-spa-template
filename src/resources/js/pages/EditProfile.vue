<template>
    <div class="card shadow">
        <div class="card-header">{{ $t('pages.edit_profile.edit_profile') }}</div>

        <form action="#" class="card-body" @submit.prevent="editProfile">
            <p class="text-center text-primary">
                <i class="fas fa-user-edit fa-5x"></i>
            </p>

            <div class="form-group">
                <label for="name">{{ $t('pages.edit_profile.name') }}</label>
                <input type="text" class="form-control" id="name" autocomplete="name" autofocus v-model="name" :class="{ 'is-invalid': errors.name }">
                <div class="invalid-feedback">{{ errors.name }}</div>
            </div>

            <div class="form-group">
                <label for="email">{{ $t('pages.edit_profile.email') }}</label>
                <input type="email" class="form-control" id="email" autocomplete="email" v-model="email" :class="{ 'is-invalid': errors.email }">
                <div class="invalid-feedback">{{ errors.email }}</div>
            </div>

            <div class="form-group">
                <label for="password">{{ $t('pages.edit_profile.password') }}</label>
                <input type="password" class="form-control" id="password" autocomplete="cur-password" v-model="password" :class="{ 'is-invalid': errors.password }">
                <div class="invalid-feedback">{{ errors.password }}</div>
            </div>

            <div class="form-group">
                <label for="new-password">{{ $t('pages.edit_profile.new_password') }}</label>
                <input type="password" class="form-control" id="new-password" autocomplete="new-password" v-model="new_password" :class="{ 'is-invalid': errors.new_password }">
                <div class="invalid-feedback">{{ errors.new_password }}</div>
            </div>

            <div class="form-group">
                <label for="new-password-confirmation">{{ $t('pages.edit_profile.confirm_password') }}</label>
                <input type="password" class="form-control" id="new-password-confirmation" autocomplete="new-password" @paste.prevent v-model="new_password_confirmation" :class="{ 'is-invalid': errors.new_password_confirmation }">
                <div class="invalid-feedback">{{ errors.new_password_confirmation }}</div>
            </div>

            <div class="d-flex w-100 justify-content-end">
                <div>
                    <button type="submit" class="btn btn-primary">{{ $t('buttons.edit_profile') }}</button>
                    <router-link class="btn btn-danger" :to="{ name: 'account.delete' }">{{ $t('buttons.delete_account') }}</router-link>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import * as UsersService from '../services/users.service'

    export default {
        name: 'edit-profile',

        data () {
            return {
                name: this.$store.state.users.current.name,
                email: this.$store.state.users.current.email,
                password: '',
                new_password: '',
                new_password_confirmation: '',

                errors: {
                    name: '',
                    email: '',
                    password: '',
                    new_password: '',
                    new_password_confirmation: ''
                }
            }
        },

        methods: {
            editProfile () {
                this.clearErrors()

                UsersService.editProfile(this)
                    .then(() => {
                        this.$store.commit('alert/SHOW', {
                            type: 'primary',
                            message: this.$t('messages.profile_edited')
                        })
                    })
                    .catch(error => {
                        console.log(error)
                        if (error.response.status == 422) {
                            this.setErrors(error.response.data.errors)
                        } else {
                            this.$store.commit('alert/SHOW', {
                                type: 'danger',
                                message: this.$t('errors.edit_profile_error')
                            })
                        }
                    })
            }
        }
    }
</script>
