<template>
    <div class="container">
        <div class="row">
            <form
                class="mx-auto col-md-6 col-lg-4"
                @submit.prevent="signIn"
            >
                <h1 class="mb-4 text-center font-weight-light">Войти</h1>

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

                <div class="pt-3">
                    <button
                        type="submit"
                        class="btn btn-lg btn-block btn-primary"
                        :disabled="$store.state.auth.isLoading"><span
                            v-if="$store.state.auth.isLoading"
                            role="status"
                            aria-hidden="true"
                            class="mr-3 spinner-border spinner-border-sm"></span>Войти</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'SignIn',

        data () {
            return {
                form: {
                    email: null,
                    password: null,
                },
            }
        },

       created () {
           document.title = `${process.env.MIX_APP_NAME} - Войти`
       },

        methods: {
            signIn () {
                const formData = {
                    username: this.form.email,
                    password: this.form.password,
                }

                return this.$store.dispatch('auth/signIn', formData)
                    .then(() => this.$router.push('/')).catch(error => console.log(error))
            },
        },
    }
</script>
