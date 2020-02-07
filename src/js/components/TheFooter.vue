<template>
    <footer class="text-center mt-5">
        <div>&copy; {{ year }}</div>

        <ul class="list-inline">
            <router-link
                class="list-inline-item"
                to="/about"
            >
                О нас
            </router-link>

            <router-link
                class="list-inline-item"
                to="/contact"
            >
                Связаться с нами
            </router-link>
        </ul>
    </footer>
</template>

<script>
    export default {
        name: 'TheFooter',

        computed: {
            year () {
                return new Date().getFullYear()
            }
        }
    }
</script>
