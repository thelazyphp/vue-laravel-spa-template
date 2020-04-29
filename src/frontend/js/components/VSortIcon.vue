<template>
    <i
        v-if="asc"
        key="sortAsc"
        class="fas fa-long-arrow-alt-up"></i>

    <i
        v-else-if="desc"
        key="sortDesc"
        class="fas fa-long-arrow-alt-down"></i>
</template>

<script>
    export default {
        name: 'VSortIcon',

        props: {
            sortProp: String,
            sortValue: String,
        },

        computed: {
            asc () {
                return this.sortValue == this.sortProp
            },

            desc () {
                return this.sortValue == `-${this.sortProp}`
            },
        },
    }
</script>
