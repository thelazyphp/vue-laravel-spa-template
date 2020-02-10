<template>
    <th
        scope="col"
        class="clickable"
        :title="$t('table_header.sort')"
        @click="onClick"
    >
        <slot></slot>
        <i
            v-if="asc"
            class="fas fa-long-arrow-alt-up"
        >
        </i>
        <i
            v-if="desc"
            class="fas fa-long-arrow-alt-down"
        >
        </i>
    </th>
</template>

<script>
    export default {
        name: 'TableHeaderItem',

        props: {
            sort: {
                type: String,
                required: true
            },

            sortProp: {
                type: String,
                required: true
            }
        },

        computed: {
            asc () {
                return this.sort == this.sortProp
            },

            desc () {
                return this.sort == `-${this.sortProp}`
            }
        },

        methods: {
            onClick () {
                this.$emit(
                    'sort',
                    this.asc ? `-${this.sortProp}` : this.sortProp
                )
            }
        }
    }
</script>

<style scoped>
    .clickable {
        cursor: pointer;
    }
</style>
