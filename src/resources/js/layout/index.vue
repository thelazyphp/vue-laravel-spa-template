<template>
    <div id="app">
        <app-header></app-header>

        <main class="container py-5">
            <router-view></router-view>
        </main>

        <app-footer></app-footer>
    </div>
</template>

<script>
    import AppHeader from '../components/AppHeader'
    import AppFooter from '../components/AppFooter'

    export default {
        name: 'layout',

        components: {
            AppHeader,
            AppFooter
        }
    }
</script>
