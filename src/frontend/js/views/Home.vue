<template></template>

<script>
    export default {
        name: 'Home',

        created () {
            document.title = `${process.env.MIX_APP_NAME} - Главная`
        },
    }
</script>
