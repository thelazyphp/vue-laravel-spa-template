<template>
    <form
        class="form-row"
        @reset.prevent="resetFilter"
        @submit.prevent="updateFilterValue"
    >
        <div class="col-sm-6 col-lg-4 col-xl-3 form-group">
            <div class="d-flex btn-group btn-group-sm">
                <label
                    v-for="(option, index) in rooms"
                    :key="index"
                    :class="['btn btn-checkbox', { active: filter.rooms.includes(option.value) }]"
                    :title="option.title">{{ option.label }}<input
                        v-model="filter.rooms"
                        type="checkbox"
                        class="d-none"
                        :value="option.value"></label>
            </div>
        </div>

        <div
            v-if="moreFilters"
            class="col-sm-6 col-lg-4 col-xl-3 form-group"
        >
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text">Этаж</span>
                </div>

                <input
                    v-model.number="filter.floor.min"
                    type="number"
                    placeholder="с"
                    class="form-control"
                    :min="1"
                    :step="1">

                <input
                    v-model.number="filter.floor.max"
                    type="number"
                    placeholder="по"
                    class="form-control"
                    :min="1"
                    :step="1">

                <div
                    v-if="filter.floor.min !== null || filter.floor.max !== null"
                    class="input-group-append d-flex align-items-center"
                >
                    <a
                        href="javascript:void(0)"
                        title="Сбросить"
                        class="ml-2 text-danger"
                        @click="filter.floor.min = null, filter.floor.max = null, isFilterReset ? updateFilterValue() : false"><i class="fas fa-times"></i></a>
                </div>
            </div>
        </div>

        <div
            v-if="moreFilters"
            class="col-sm-6 col-lg-4 col-xl-3 form-group"
        >
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text">Этажность</span>
                </div>

                <input
                    v-model.number="filter.floors.min"
                    type="number"
                    placeholder="от"
                    class="form-control"
                    :min="1"
                    :step="1">

                <input
                    v-model.number="filter.floors.max"
                    type="number"
                    placeholder="до"
                    class="form-control"
                    :min="1"
                    :step="1">

                <div
                    v-if="filter.floors.min !== null || filter.floors.max !== null"
                    class="input-group-append d-flex align-items-center"
                >
                    <a
                        href="javascript:void(0)"
                        title="Сбросить"
                        class="ml-2 text-danger"
                        @click="filter.floors.min = null, filter.floors.max = null, isFilterReset ? updateFilterValue() : false"><i class="fas fa-times"></i></a>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4 col-xl-3 form-group">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text">Год постройки</span>
                </div>

                <select
                    v-model="filter.year_built.min"
                    class="custom-select"
                >
                    <option :value="null">-- с --</option>

                    <option
                        v-for="year in years"
                        :key="year"
                        :value="year">{{ year }}</option>
                </select>

                <select
                    v-model="filter.year_built.max"
                    class="custom-select"
                >
                    <option :value="null">-- по --</option>

                    <option
                        v-for="year in years"
                        :key="year"
                        :value="year">{{ year }}</option>
                </select>

                <div
                    v-if="filter.year_built.min !== null || filter.year_built.max !== null"
                    class="input-group-append d-flex align-items-center"
                >
                    <a
                        href="javascript:void(0)"
                        title="Сбросить"
                        class="ml-2 text-danger"
                        @click="filter.year_built.min = null, filter.year_built.max = null, isFilterReset ? updateFilterValue() : false"><i class="fas fa-times"></i></a>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4 col-xl-3 form-group">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text">Площадь общая</span>
                </div>

                <input
                    v-model.number="filter.size_total.min"
                    type="number"
                    placeholder="от"
                    class="form-control"
                    :min="0.01"
                    :step="0.01">

                <input
                    v-model.number="filter.size_total.max"
                    type="number"
                    placeholder="до"
                    class="form-control"
                    :min="0.01"
                    :step="0.01">

                <div class="input-group-append">
                    <span class="input-group-text">м<sup>2</sup></span>
                </div>

                <div
                    v-if="filter.size_total.min !== null || filter.size_total.max !== null"
                    class="input-group-append d-flex align-items-center"
                >
                    <a
                        href="javascript:void(0)"
                        title="Сбросить"
                        class="ml-2 text-danger"
                        @click="filter.size_total.min = null, filter.size_total.max = null, isFilterReset ? updateFilterValue() : false"><i class="fas fa-times"></i></a>
                </div>
            </div>
        </div>

        <div
            v-if="moreFilters"
            class="col-sm-6 col-lg-4 col-xl-3 form-group"
        >
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text">Площадь жилая</span>
                </div>

                <input
                    v-model.number="filter.size_living.min"
                    type="number"
                    placeholder="от"
                    class="form-control"
                    :min="0.01"
                    :step="0.01">

                <input
                    v-model.number="filter.size_living.max"
                    type="number"
                    placeholder="до"
                    class="form-control"
                    :min="0.01"
                    :step="0.01">

                <div class="input-group-append">
                    <span class="input-group-text">м<sup>2</sup></span>
                </div>

                <div
                    v-if="filter.size_living.min !== null || filter.size_living.max !== null"
                    class="input-group-append d-flex align-items-center"
                >
                    <a
                        href="javascript:void(0)"
                        title="Сбросить"
                        class="ml-2 text-danger"
                        @click="filter.size_living.min = null, filter.size_living.max = null, isFilterReset ? updateFilterValue() : false"><i class="fas fa-times"></i></a>
                </div>
            </div>
        </div>

        <div
            v-if="moreFilters"
            class="col-sm-6 col-lg-4 col-xl-3 form-group"
        >
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text">Площадь кухни</span>
                </div>

                <input
                    v-model.number="filter.size_kitchen.min"
                    type="number"
                    placeholder="от"
                    class="form-control"
                    :min="0.01"
                    :step="0.01">

                <input
                    v-model.number="filter.size_kitchen.max"
                    type="number"
                    placeholder="до"
                    class="form-control"
                    :min="0.01"
                    :step="0.01">

                <div class="input-group-append">
                    <span class="input-group-text">м<sup>2</sup></span>
                </div>

                <div
                    v-if="filter.size_kitchen.min !== null || filter.size_kitchen.max !== null"
                    class="input-group-append d-flex align-items-center"
                >
                    <a
                        href="javascript:void(0)"
                        title="Сбросить"
                        class="ml-2 text-danger"
                        @click="filter.size_kitchen.min = null, filter.size_kitchen.max = null, isFilterReset ? updateFilterValue() : false"><i class="fas fa-times"></i></a>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4 col-xl-3 form-group">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text">Цена</span>
                </div>

                <input
                    v-model.number="filter.price_amount.min"
                    type="number"
                    placeholder="от"
                    class="form-control"
                    :min="0"
                    :step="1">

                <input
                    v-model.number="filter.price_amount.max"
                    type="number"
                    placeholder="до"
                    class="form-control"
                    :min="0"
                    :step="1">

                <div class="input-group-append">
                    <span class="input-group-text">USD</span>
                </div>

                <div
                    v-if="filter.price_amount.min !== null || filter.price_amount.max !== null"
                    class="input-group-append d-flex align-items-center"
                >
                    <a
                        href="javascript:void(0)"
                        title="Сбросить"
                        class="ml-2 text-danger"
                        @click="filter.price_amount.min = null, filter.price_amount.max = null, isFilterReset ? updateFilterValue() : false"><i class="fas fa-times"></i></a>
                </div>
            </div>
        </div>

        <!-- <div
            v-if="moreFilters"
            class="col-sm-6 col-lg-4 col-xl-3 form-group"
        >
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text">Цена/м<sup>2</sup></span>
                </div>

                <input
                    v-model.number="filter.price_per_sqm.min"
                    type="number"
                    placeholder="от"
                    class="form-control"
                    :min="0.1"
                    :step="0.1">

                <input
                    v-model.number="filter.price_per_sqm.max"
                    type="number"
                    placeholder="до"
                    class="form-control"
                    :min="0.1"
                    :step="0.1">

                <div class="input-group-append">
                    <span class="input-group-text">USD/м<sup>2</sup></span>
                </div>

                <div
                    v-if="filter.price_per_sqm.min !== null || filter.price_per_sqm.max !== null"
                    class="input-group-append d-flex align-items-center"
                >
                    <a
                        href="javascript:void(0)"
                        title="Сбросить"
                        class="ml-2 text-danger"
                        @click="filter.price_per_sqm.min = null, filter.price_per_sqm.max = null, isFilterReset ? updateFilterValue() : false"><i class="fas fa-times"></i></a>
                </div>
            </div>
        </div> -->

        <div
            v-if="moreFilters"
            class="col-sm-6 col-lg-4 col-xl-3 form-group"
        >
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span
                        title="Дата размещения"
                        class="input-group-text">Дата размещ.</span>
                </div>

                <input
                    v-model="filter.published_at.date_min"
                    type="date"
                    placeholder="с"
                    class="form-control">

                <input
                    v-model="filter.published_at.date_max"
                    type="date"
                    placeholder="по"
                    class="form-control">

                <div
                    v-if="filter.published_at.date_min !== null || filter.published_at.date_max !== null"
                    class="input-group-append d-flex align-items-center"
                >
                    <a
                        href="javascript:void(0)"
                        title="Сбросить"
                        class="ml-2 text-danger"
                        @click="filter.published_at.date_min = null, filter.published_at.date_max = null, isFilterReset ? updateFilterValue() : false"><i class="fas fa-times"></i></a>
                </div>
            </div>
        </div>

        <div
            v-if="moreFilters"
            class="col-sm-6 col-lg-4 col-xl-3 form-group"
        >
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span
                        title="Время размещения"
                        class="input-group-text">Время размещ.</span>
                </div>

                <input
                    v-model="filter.published_at.time_min"
                    type="time"
                    placeholder="с"
                    class="form-control">

                <input
                    v-model="filter.published_at.time_max"
                    type="time"
                    placeholder="по"
                    class="form-control">

                <div
                    v-if="filter.published_at.time_min !== null || filter.published_at.time_max !== null"
                    class="input-group-append d-flex align-items-center"
                >
                    <a
                        href="javascript:void(0)"
                        title="Сбросить"
                        class="ml-2 text-danger"
                        @click="filter.published_at.time_min = null, filter.published_at.time_max = null, isFilterReset ? updateFilterValue() : false"><i class="fas fa-times"></i></a>
                </div>
            </div>
        </div>

        <div
            v-if="!isFilterReset"
            class="col-12"
        >
            <div class="d-flex justify-content-end">
                <div>
                    <button
                        type="reset"
                        class="btn btn-link text-decoration-none">Сбросить</button>

                    <button
                        type="submit"
                        class="btn btn-sm btn-primary"><i class="mr-2 fas fa-search"></i>Показать</button>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
    export default {
        name: 'ApartmentsFiltersForm',

        props: {
            filterValue: {
                type: Object,
                default: null
            },

            options: {
                type: Object,

                default () {
                    return {}
                }
            },
        },

        data () {
            return {
                moreFilters: false,

                filter: {
                    source_id: [],
                    rooms: [],
                    floor: { min: null, max: null },
                    floors: { min: null, max: null },
                    year_built: { min: null, max: null },
                    size_total: { min: null, max: null },
                    size_living: { min: null, max: null },
                    size_kitchen: { min: null, max: null },
                    walls: [],
                    balcony: [],
                    bathroom: [],
                    price_amount: { min: null, max: null },
                    // price_per_sqm: { min: null, max: null },

                    published_at: {
                        date_min: null,
                        date_max: null,
                        time_min: null,
                        time_max: null,
                    },
                },
            }
        },

        computed: {
            rooms () {
                return [
                    {
                        label: '1к',
                        value: 1,
                        title: '1-комнатные',
                    },
                    {
                        label: '2к',
                        value: 2,
                        title: '2-комнатные',
                    },
                    {
                        label: '3к',
                        value: 3,
                        title: '3-комнатные',
                    },
                    {
                        label: '4к+',
                        value: '4+',
                        title: '4-комнатные и больше',
                    },
                ]
            },

            years () {
                let arr = []

                for (let i = 1901; i <= 2038; i++) {
                    arr.push(i)
                }

                return arr
            },

            isFilterReset () {
                return !this.filter.source_id.length
                    && !this.filter.rooms.length
                    && this.filter.floor.min             == null
                    && this.filter.floor.max             == null
                    && this.filter.floors.min            == null
                    && this.filter.floors.max            == null
                    && this.filter.year_built.min        == null
                    && this.filter.year_built.max        == null
                    && this.filter.size_total.min        == null
                    && this.filter.size_total.max        == null
                    && this.filter.size_living.min       == null
                    && this.filter.size_living.max       == null
                    && this.filter.size_kitchen.min      == null
                    && this.filter.size_kitchen.max      == null
                    && !this.filter.walls.length
                    && !this.filter.balcony.length
                    && !this.filter.bathroom.length
                    && this.filter.price_amount.min      == null
                    && this.filter.price_amount.max      == null
                    // && this.filter.price_per_sqm.min  == null
                    // && this.filter.price_per_sqm.max  == null
                    && this.filter.published_at.date_min == null
                    && this.filter.published_at.date_max == null
                    && this.filter.published_at.time_min == null
                    && this.filter.published_at.time_max == null
            },
        },

        methods: {
            resetFilter () {
                this.filter.source_id             = []
                this.filter.rooms                 = []
                this.filter.floor.min             = null
                this.filter.floor.max             = null
                this.filter.floors.min            = null
                this.filter.floors.max            = null
                this.filter.year_built.min        = null
                this.filter.year_built.max        = null
                this.filter.size_total.min        = null
                this.filter.size_total.max        = null
                this.filter.size_living.min       = null
                this.filter.size_living.max       = null
                this.filter.size_kitchen.min      = null
                this.filter.size_kitchen.max      = null
                this.filter.walls                 = []
                this.filter.balcony               = []
                this.filter.bathroom              = []
                this.filter.price_amount.min      = null
                this.filter.price_amount.max      = null
                // this.filter.price_per_sqm.min  = null
                // this.filter.price_per_sqm.max  = null
                this.filter.published_at.date_min = null
                this.filter.published_at.date_max = null
                this.filter.published_at.time_min = null
                this.filter.published_at.time_max = null

                this.updateFilterValue()
            },

            updateFilterValue () {
                let value = null

                if (!this.isFilterReset) {
                    value = {}

                    Object.keys(this.filter).forEach(key => {
                        if (
                            Array.isArray(this.filter[key])
                            && this.filter[key].length
                        ) {
                            value[key] = this.filter[key]
                        } else if (
                            this.filter[key] !== undefined
                            && this.filter[key] !== null
                            && typeof this.filter[key] == 'object'
                        ) {
                            if (Object.keys(this.filter[key]).length) {
                                value[key] = {}

                                Object.keys(this.filter[key]).forEach(nestedKey => {
                                    if (this.filter[key][nestedKey] !== null) {
                                        value[key][nestedKey] = this.filter[key][nestedKey]
                                    }
                                })
                            }
                        } else if (this.filter[key] !== null) {
                            value[key] = this.filter[key]
                        }
                    })
                }

                this.$emit('update-filter-value', value)
            },
        },
    }
</script>

<style scoped>
    .btn-group .btn-checkbox {
        color: #495057;
        border-color: #ced4da;
    }

    .btn-group .btn-checkbox.active {
        background-color: #f7f6f0;
    }

    .input-group-text {
        background-color: #f7f6f0;
    }

    .input-group-prepend .input-group-text {
        width: 125px;
        min-width: 125px;
    }
</style>
