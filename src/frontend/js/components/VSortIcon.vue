<template>
    <i
        v-if="isSortAsc"
        key="sortAsc"
        class="fas fa-long-arrow-alt-up"></i>

    <i
        v-else-if="isSortDesc"
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
            isSortAsc () {
                return this.sortValue == this.sortProp
            },

            isSortDesc () {
                return this.sortValue == `-${this.sortProp}`
            },
        },
    }
</script>
