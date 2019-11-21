<template>
    <footer>
        <div class="text-center text-muted">&copy; {{ year }}</div>
    </footer>
</template>

<script>
    export default {
        computed: {
            year () {
                return (new Date()).getFullYear()
            }
        }
    }
</script>

<style scoped>

</style>
