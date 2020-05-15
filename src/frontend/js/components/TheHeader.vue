<template>
    <nav class="navbar navbar-dark bg-primary">
        <router-link
            class="navbar-brand"
            :to="{ name: 'home' }">{{ appName }}</router-link>
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
    }
</script>
