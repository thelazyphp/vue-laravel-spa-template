<template>
    <nav class="navbar sticky-top navbar-light bg-white border-bottom">
        <router-link
            to="/"
            class="navbar-brand">{{ appName }} <sup class="badge badge-primary">beta</sup></router-link>

        <form
            v-if="$store.getters['auth/isAuth']"
            class="form-inline"
            @submit.prevent="signOut"
        >
            <button
                type="submit"
                class="btn btn-outline-primary">Выйти</button>
        </form>

        <form
            v-else
            class="form-inline"
        >
            <router-link
                to="/sign-in"
                class="btn btn-outline-primary">Войти</router-link>
        </form>
    </nav>
</template>

<script>
    export default {
        name: 'TheHeader',

        computed: {
            appName () {
                return process.env.MIX_APP_NAME
            },
        },

        methods: {
            signOut () {
                this.$store.dispatch('auth/signOut')
                    .then(() => this.$router.push('/sign-in')).catch(error => console.log(error))
            },
        },
    }
</script>
