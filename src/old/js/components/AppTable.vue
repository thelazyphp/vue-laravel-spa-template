<template>
    <table class="table">
        <caption
            v-if="caption"
            v-html="caption"
        >
        </caption>
        <thead :class="headColor ? `thead-${headColor}` : null">
            <tr>
                <th
                    v-for="(col, index) in cols"
                    :key="index"
                    scope="col"
                    :class="[{ 'sort-btn': col.sort, 'text-nowrap': col.sort, 'd-none': col.display === false }, `text-${textAlign}`, `align-${verticalAlign}`]"
                    :title="sortTitle"
                    @click="onSortBtnClick(col)"
                >
                    <span v-html="col.name"></span>
                    <i
                        v-if="col.sort && isSortOrderAsc(col.sortProp || col.prop)"
                        class="fas fa-long-arrow-alt-up"
                    >
                    </i>
                    <i
                        v-if="col.sort && isSortOrderDesc(col.sortProp || col.prop)"
                        class="fas fa-long-arrow-alt-down"
                    >
                    </i>
                </th>
                <th
                    v-if="checkRows"
                    :class="[`text-${textAlign}`, `align-${verticalAlign}`]"
                >
                    <div
                        class="custom-control custom-checkbox"
                        :title="checkAllRowsTitle"
                    >
                        <input
                            id="check-all-rows"
                            v-model="checkAllRows"
                            type="checkbox"
                            class="custom-control-input"
                        >
                        <label
                            for="check-all-rows"
                            class="custom-control-label"
                        >
                        </label>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr
                v-for="(row, rowIndex) in rows"
                :key="rowIndex"
                :class="getRowClass(row, rowIndex)"
                :style="getRowStyle(row, rowIndex)"
            >
                <td
                    v-for="(col, colIndex) in cols"
                    :key="colIndex"
                    :class="[getCellClass(row, col, rowIndex), { 'd-none': col.display === false, 'text-nowrap': title = (typeof col.tooltip == 'function' ? col.tooltip(row, col, rowIndex) : col.tooltip) }, `text-${textAlign}`, `align-${verticalAlign}`]"
                    :style="getCellStyle(row, col, rowIndex)"
                >
                    <Component
                        :is="col.component"
                        v-if="col.component"
                        key="cell-component"
                        :row="row"
                        :col="col"
                        :row-index="rowIndex"
                        :data="col.componentData"
                        v-on="$listeners"
                    />
                    <span
                        v-else
                        key="cell-html"
                        v-html="getCellValue(row, col, rowIndex)"
                    >
                    </span>
                    <i
                        v-if="title"
                        data-toggle="tooltip"
                        class="fas fa-info-circle text-info"
                        :title="title"
                    ></i>
                </td>
                <td
                    v-if="checkRows"
                    :class="[`text-${textAlign}`, `align-${verticalAlign}`]"
                >
                    <div
                        class="custom-control custom-checkbox"
                        :title="checkRowTitle"
                    >
                        <input
                            :id="`check-row-${rowIndex}`"
                            v-model="checkedRows"
                            type="checkbox"
                            class="custom-control-input"
                            :value="row"
                        >
                        <label
                            :for="`check-row-${rowIndex}`"
                            class="custom-control-label"
                        >
                        </label>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        name: 'AppTable',

        props: {
            cols: {
                type: Array,
                required: true
            },

            rows: {
                type: Array,

                default () {
                    return []
                }
            },

            checkRows: {
                type: Boolean,
                default: false
            },

            textAlign: {
                type: String,
                default: 'left',

                validator (value) {
                    return ['left', 'center', 'right'].includes(value)
                }
            },

            verticalAlign: {
                type: String,
                default: 'baseline',

                validator (value) {
                    return ['baseline', 'top', 'middle', 'botton'].includes(value)
                }
            },

            caption: String,
            sortValue: String,
            sortTitle: String,
            checkRowTitle: String,
            checkAllRowsTitle: String,
            rowClass: [String, Array, Object, Function],
            rowStyle: [String, Array, Object, Function],

            headColor: {
                type: String,

                validator (value) {
                    return ['dark', 'light'].includes(value)
                }
            }
        },

        data () {
            return {
                checkedRows: []
            }
        },

        computed: {
            checkAllRows: {
                get () {
                    return this.rows.length && this.checkedRows.length == this.rows.length
                },

                set (value) {
                    this.checkedRows = []

                    if (value) {
                        for (let row of this.rows) {
                            this.checkedRows.push(row)
                        }
                    }
                }
            }
        },

        mounted () {
            $('[data-toggle="tooltip"]').tooltip()
        },

        methods: {
            onSortBtnClick (col) {
                if (col.sort) {
                    this.$emit(
                        'sort',
                        this.getInvertedSortValue(col.sortProp || col.prop)
                    )
                } else {
                    return false
                }
            },

            isSortOrderAsc (sortProp) {
                return this.sortValue == sortProp
            },

            isSortOrderDesc (sortProp) {
                return this.sortValue == `-${sortProp}`
            },

            getRowClass (row, rowIndex) {
                return this.rowClass
                    ? (typeof this.rowClass == 'function'
                        ? this.rowClass(row, rowIndex) : this.rowClass) : null
            },

            getRowStyle (row, rowIndex) {
                return this.rowStyle
                    ? (typeof this.rowStyle == 'function'
                        ? this.rowStyle(row, rowIndex) : this.rowStyle) : null
            },

            getCellClass (row, col, rowIndex) {
                return col.cellClass
                    ? (typeof col.cellClass == 'function'
                        ? col.cellClass(row, col, rowIndex) : col.cellClass) : null
            },

            getCellStyle (row, col, rowIndex) {
                return col.cellStyle
                    ? (typeof col.cellStyle == 'function'
                        ? col.cellStyle(row, col, rowIndex) : col.cellStyle) : null
            },

            getInvertedSortValue (sortProp) {
                return this.isSortOrderAsc(sortProp) ? `-${sortProp}` : sortProp
            },

            getCheckedRows () {
                return this.checkedRows
            },

            getCellValue (row, col, rowIndex) {
                return typeof col.filter == 'function' ? col.filter(row, col, rowIndex) : row[col.prop]
            }
        }
    }
</script>

<style scoped>
    .sort-btn {
        cursor: pointer;
    }
</style>
