<template>
    <footer class="text-center my-5">
        &copy; {{ year }}

        <ul class="list-inline">
            <!-- Dev -->
            <li class="list-inline-item">
                <a href="/agency-parser/dev">Разработчикам</a>
            </li>

            <li class="list-inline-item">
                <a href="/agency-parser/docs">Документация</a>
            </li>

            <li class="list-inline-item">
                <a href="/agency-parser/contact">Связаться с нами</a>
            </li>
            <!-- End dev -->
        </ul>
    </footer>
</template>

<script>
    export default {
        name: 'TheFooter',

        computed: {
            year () {
                return (new Date()).getFullYear()
            }
        }
    }
</script>
