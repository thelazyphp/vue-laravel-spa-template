<template>
    <div
        id="app"
        class="app-wrapper"
    >
        <the-header></the-header>

        <main role="main">
            <router-view></router-view>
        </main>
    </div>
</template>

<script>
    import TheHeader from '../components/TheHeader'

    export default {
        name: 'Default',
        components: { TheHeader },
    }
</script>

<style scoped>
    [role="main"] {
        padding-top: 125px;
        padding-bottom: 125px;
    }
</style>
