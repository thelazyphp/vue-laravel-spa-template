<template>
    <th
        scope="col"
        :title="title"
        :class="{ sortable: isSortable }"
    >
        <v-sort-icon
            v-if="isSortable"
            class="mr-2"
            :sort-prop="sortProp"
            :sort-value="sortValue"></v-sort-icon>

        <slot></slot>
    </th>
</template>

<script>
    import VSortIcon from '../components/VSortIcon'

    export default {
        name: 'VTableHeader',
        components: { VSortIcon },

        props: {
            title: String,
            sortProp: String,
            sortValue: String,

            isSortable: {
                type: Boolean,
                default: false
            },
        },
    }
</script>

<style scoped>
    .sortable {
        cursor: pointer;
    }
</style>
