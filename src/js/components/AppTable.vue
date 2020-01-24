<template>
    <table class="table">
        <caption
            v-if="caption"
            v-html="caption"
        >
        </caption>
        <thead :class="{ [`thead-${headColor}`]: headColor }">
            <tr>
                <th
                    v-for="(col, index) in cols"
                    :key="index"
                    scope="col"
                    :class="{ 'sort-btn': col.sort, 'd-none': col.display === false }"
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
                <th v-if="checkRows">
                    <div class="custom-control custom-checkbox">
                        <input
                            id="check-all-rows"
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
                    :class="[getCellClass(row, col, rowIndex), { 'd-none': col.display === false }]"
                    :style="getCellStyle(row, col, rowIndex)"
                >
                    <Component
                        :is="col.component"
                        v-if="col.component"
                        key="cell-component"
                        :row="row"
                        :col="col"
                        :row-index="rowIndex"
                        v-on="$listeners"
                    />
                    <span
                        v-else
                        key="cell-html"
                        v-html="getCellValue(row, col, rowIndex)"
                    >
                    </span>
                </td>
                <td v-if="checkRows">
                    <div class="custom-control custom-checkbox">
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

            caption: String,
            sortValue: String,
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
