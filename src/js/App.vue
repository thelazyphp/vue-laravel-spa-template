<template>
    <div id="app">
        <TheHeader/>

        <main>
            <router-view></router-view>
        </main>

        <TheFooter/>
    </div>
</template>

<script>
    import TheHeader from './components/TheHeader'
    import TheFooter from './components/TheFooter'

    export default {
        components: {
            TheHeader,
            TheFooter
        }
    }
</script>
