<template>
    <div id="app">
        <app-header></app-header>
        <alert class="mb-0 rounded-0" v-if="alert.show" @close="$store.commit('alert/CLOSE')" :type="alert.type" :message="alert.message"></alert>

        <main class="container py-5">
            <router-view></router-view>
        </main>

        <app-footer></app-footer>
    </div>
</template>

<script>
    import AppHeader from '../components/AppHeader'
    import Alert from '../components/Alert'
    import AppFooter from '../components/AppFooter'

    export default {
        name: 'layout',

        components: {
            AppHeader,
            Alert,
            AppFooter
        },

        computed: {
            alert () {
                return this.$store.state.alert
            }
        }
    }
</script>
