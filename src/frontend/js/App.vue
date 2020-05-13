<template>
    <component :is="layout"></component>
</template>

<script>
    import Default from './layouts/Default'
    import Sidebar from './layouts/Sidebar'

    export default {
        components: { Default, Sidebar },

        computed: {
            layout () {
                return this.$route.meta.layout == 'default' ? 'Default' : 'Sidebar'
            },
        },
    }
</script>
