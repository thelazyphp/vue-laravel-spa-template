<template>
    <div
        id="app"
        class="app-wrapper"
    >
        <the-header></the-header>
        <router-view></router-view>
    </div>
</template>

<script>
    import TheHeader from './components/TheHeader'

    export default {
        components: { TheHeader },
    }
</script>

<style scoped>
    .app-wrapper {
        position: relative;
    }
</style>
