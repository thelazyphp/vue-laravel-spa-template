<template>
    <div id="app">
        <TheHeader/>

        <main>
            <RouterView/>
        </main>

        <TheFooter/>
    </div>
</template>

<script>
    import TheHeader from './TheHeader'
    import TheFooter from './TheFooter'

    export default {
        name: 'App',

        components: {
            TheHeader,
            TheFooter
        }
    }
</script>
