<template>
    <table :class="['table', tableClass]">
        <caption v-if="caption">
            <slot name="caption">{{ caption }}</slot>
        </caption>

        <thead :class="theadClass">
            <tr>
                <th
                    v-for="(field, index) in fields"
                    :key="index"
                    scope="col"
                    :class="{ 'sortable': field.sortable }"
                    :title="field.sortable ? sortLabel : null"
                    :sort-order="getSortOrder(field)"
                    @click="field.sortable ? updateSortValue(field) : false"
                >
                    <slot
                        :name="`label(${field.prop})`"
                        :field="field">{{ field.label }}</slot>
                </th>

                <th
                    v-if="checkable"
                    class="text-right"
                    scope="col"
                >
                    <div
                        class="custom-control custom-checkbox"
                        :title="checkAllItemsLabel"
                    >
                        <input
                            id="checkAllItems"
                            v-model="checkAllItems"
                            class="custom-control-input"
                            type="checkbox">

                        <label
                            class="custom-control-label"
                            for="checkAllItems"></label>
                    </div>
                </th>
            </tr>
        </thead>

        <tbody>
            <template v-for="(item, itemIndex) in items">
                <tr
                    :key="itemIndex"
                    :class="{ 'no-hover': item.detailsShown }"
                >
                    <td
                        v-for="(field, fieldIndex) in fields"
                        :key="fieldIndex"
                    >
                        <slot
                            :name="`cell(${field.prop})`"
                            :field="field"
                            :item="item"
                            :index="itemIndex"
                            :data="item[field.prop]">{{ item[field.prop] }}</slot>
                    </td>

                    <td
                        v-if="checkable"
                        class="text-right"
                    >
                        <div
                            class="custom-control custom-checkbox"
                            :title="checkItemLabel"
                        >
                            <input
                                :id="`checkItem${itemIndex}`"
                                v-model="checkedItems"
                                class="custom-control-input"
                                type="checkbox"
                                :value="item">

                            <label
                                class="custom-control-label"
                                :for="`checkItem${itemIndex}`"></label>
                        </div>
                    </td>
                </tr>

                <tr
                    v-if="item.detailsShown"
                    :key="`details${itemIndex}`"
                    :class="{ 'no-hover': item.detailsShown }"
                >
                    <td
                        class="border-top-0"
                        :colspan="fields.length + (checkable ? 1 : 0)"
                    >
                        <slot
                            name="details"
                            :item="item"
                            :index="itemIndex"></slot>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
</template>

<script>
    const NAME = 'DataTable'

    export default {
        name: NAME,

        props: {
            theadDark: {
                type: Boolean,
                default: false
            },

            theadLight: {
                type: Boolean,
                default: false
            },

            sm: {
                type: Boolean,
                default: false
            },

            dark: {
                type: Boolean,
                default: false
            },

            hover: {
                type: Boolean,
                default: false
            },

            striped: {
                type: Boolean,
                default: false
            },

            bordered: {
                type: Boolean,
                default: false
            },

            borderless: {
                type: Boolean,
                default: false
            },

            caption: String,
            value: String,

            items: {
                type: Array,

                default () {
                    return []
                }
            },

            fields: {
                type: Array,
                required: true
            },

            checkable: {
                type: Boolean,
                default: false
            },

            sortLabel: {
                type: String,
                default: 'Сортировать'
            },

            checkItemLabel: {
                type: String,
                default: 'Выбрать'
            },

            checkAllItemsLabel: {
                type: String,
                default: 'Выбрать все'
            }
        },

        data () {
            return {
                checkedItems: []
            }
        },

        watch: {
            checkedItems (value) {
                this.$emit('check', value)

                this.items.forEach(item => {
                    item.checked = value.includes(item)
                })
            }
        },

        computed: {
            theadClass () {
                return {
                    'thead-dark': this.theadDark,
                    'thead-light': this.theadLight
                }
            },

            tableClass () {
                return {
                    'table-sm': this.sm,
                    'table-dark': this.dark,
                    'table-hover': this.hover,
                    'table-striped': this.striped,
                    'table-bordered': this.bordered,
                    'table-borderless': this.borderless
                    
                }
            },

            checkAllItems: {
                get () {
                    return this.checkedItems.length == this.items.length
                },

                set (value) {
                    this.checkedItems = []

                    if (value) {
                        this.items.forEach(item => {
                            this.checkedItems.push(item)
                        })
                    }
                }
            }
        },

        created () {
            this.items.forEach(item => {
                item.showDetails = () => {
                    item.detailsShown = true
                    this.$forceUpdate()
                }

                item.hideDetails = () => {
                    item.detailsShown = false
                    this.$forceUpdate()
                }

                item.toggleDetails = () => {
                    item.detailsShown = !item.detailsShown
                    this.$forceUpdate()
                }
            })
        },

        methods: {
            getSortOrder (field) {
                const sortProp = field.sortProp || field.prop

                switch (this.value) {
                    case sortProp:
                        return 'asc'
                    case `-${sortProp}`:
                        return 'desc'
                }

                return 'none'
            },

            updateSortValue (field) {
                const sortProp = field.sortProp || field.prop

                this.$emit(
                    'input',
                    this.value == sortProp ? `-${sortProp}` : sortProp
                )
            },
        }
    }
</script>

<style scoped>
    .table tr > th, td {
        vertical-align: middle;
    }

    .table-hover tbody tr.no-hover:hover {
        color: #212529;
        background-color: rgba(0, 0, 0, 0);
    }

    .table-dark.table-hover tbody tr.no-hover:hover {
        color: #fff;
        background-color: rgba(255, 255, 255, 0);
    }

    .table > thead > tr > th.sortable {
        padding-left: calc(.75rem + .65em);
        cursor: pointer;
        background-size: .65em 1em;
        background-repeat: no-repeat;
        background-position: left .375rem center;
    }

    .table > thead > tr > th.sortable[sort-order=none] {
        background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='101' height='101' preserveAspectRatio='none'%3E%3Cpath opacity='.3' d='M51 1l25 23 24 22H1l25-22zm0 100l25-23 24-22H1l25 22z'/%3E%3C/svg%3E");
    }

    .table > thead > tr > th.sortable[sort-order=asc] {
        background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='101' height='101' preserveAspectRatio='none'%3E%3Cpath d='M51 1l25 23 24 22H1l25-22z'/%3E%3Cpath opacity='.3' d='M51 101l25-23 24-22H1l25 22z'/%3E%3C/svg%3E");
    }

    .table > thead > tr > th.sortable[sort-order=desc] {
        background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='101' height='101' preserveAspectRatio='none'%3E%3Cpath opacity='.3' d='M51 1l25 23 24 22H1l25-22z'/%3E%3Cpath d='M51 101l25-23 24-22H1l25 22z'/%3E%3C/svg%3E");
    }
</style>
