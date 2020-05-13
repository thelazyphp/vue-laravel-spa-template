<template>
    <form
        @reset.prevent="resetFilter"
        @submit.prevent="updateFilterValue"
    >
        <div class="form-group">
            <label for="priceAmount">Цена</label>

            <div class="input-group">
                <input
                    id="priceAmount"
                    v-model.number="filter.price_amount.min"
                    type="number"
                    min="0"
                    step="1"
                    placeholder="от"
                    class="form-control">

                <input
                    v-model.number="filter.price_amount.max"
                    type="number"
                    min="0"
                    step="1"
                    placeholder="до"
                    class="form-control">

                <div
                    v-if="filter.price_amount.min !== null || filter.price_amount.max !== null"
                    class="input-group-append"
                >
                    <button
                        type="button"
                        title="Сбросить"
                        class="btn btn-link text-danger"
                        @click="filter.price_amount.min = null, filter.price_amount.max = null"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="form-group">
            <p class="mb-2">Комнат<button
                v-if="filter.rooms.length"
                type="button"
                title="Сбросить"
                class="btn btn-link text-danger"
                @click="filter.rooms = []"
            >
                <i class="fas fa-times"></i>
            </button></p>

            <div
                v-for="(option, index) in rooms"
                :key="index"
                class="custom-control custom-checkbox"
            >
                <input
                    :id="`rooms${index}`"
                    v-model="filter.rooms"
                    type="checkbox"
                    class="custom-control-input"
                    :value="option.value">

                <label
                    class="custom-control-label"
                    :for="`rooms${index}`">{{ option.label }}</label>
            </div>
        </div>

        <div class="form-group">
            <label for="sizeTotal">Общая площадь</label>

            <div class="input-group">
                <input
                    id="sizeTotal"
                    v-model.number="filter.size_total.min"
                    type="number"
                    min="0"
                    step="0.01"
                    placeholder="от"
                    class="form-control">

                <input
                    v-model.number="filter.size_total.max"
                    type="number"
                    min="0"
                    step="0.01"
                    placeholder="до"
                    class="form-control">

                <div
                    v-if="filter.size_total.min !== null || filter.size_total.max !== null"
                    class="input-group-append"
                >
                    <button
                        type="button"
                        title="Сбросить"
                        class="btn btn-link text-danger"
                        @click="filter.size_total.min = null, filter.size_total.max = null"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <div
            v-if="filterOptions && filterOptions.bathroom"
            class="form-group"
        >
            <p class="mb-2">Санузел<button
                v-if="filter.bathroom.length"
                type="button"
                title="Сбросить"
                class="btn btn-link text-danger"
                @click="filter.bathroom = []"
            >
                <i class="fas fa-times"></i>
            </button></p>

            <div
                v-for="(option, index) in filterOptions.bathroom"
                :key="index"
                class="custom-control custom-checkbox"
            >
                <input
                    :id="`bathroom${index}`"
                    v-model="filter.bathroom"
                    type="checkbox"
                    class="custom-control-input"
                    :value="option.value">

                <label
                    class="custom-control-label"
                    :for="`bathroom${index}`"
                    :title="option.title">{{ option.label }}</label>
            </div>
        </div>

        <div class="form-group">
            <label for="yearBuilt">Год постройки</label>

            <div class="input-group">
                <select
                    id="yearBuilt"
                    v-model.number="filter.year_built.min"
                    class="custom-select"
                >
                    <option :value="null">-- от --</option>

                    <option
                        v-for="year in years"
                        :key="year"
                        :value="year">{{ year }}</option>
                </select>

                <select
                    v-model.number="filter.year_built.max"
                    class="custom-select"
                >
                    <option :value="null">-- до --</option>

                    <option
                        v-for="year in years"
                        :key="year"
                        :value="year">{{ year }}</option>
                </select>

                <div
                    v-if="filter.year_built.min !== null || filter.year_built.max !== null"
                    class="input-group-append"
                >
                    <button
                        type="button"
                        title="Сбросить"
                        class="btn btn-link text-danger"
                        @click="filter.year_built.min = null, filter.year_built.max = null"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="floor">Этаж</label>

            <div class="input-group">
                <input
                    id="floor"
                    v-model.number="filter.floor.min"
                    type="number"
                    min="1"
                    step="1"
                    placeholder="от"
                    class="form-control">

                <input
                    v-model.number="filter.floor.max"
                    type="number"
                    min="1"
                    step="1"
                    placeholder="до"
                    class="form-control">

                <div
                    v-if="filter.floor.min !== null || filter.floor.max !== null"
                    class="input-group-append"
                >
                    <button
                        type="button"
                        title="Сбросить"
                        class="btn btn-link text-danger"
                        @click="filter.floor.min = null, filter.floor.max = null"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="floors">Этажность</label>

            <div class="input-group">
                <input
                    id="floors"
                    v-model.number="filter.floors.min"
                    type="number"
                    min="1"
                    step="1"
                    placeholder="от"
                    class="form-control">

                <input
                    v-model.number="filter.floors.max"
                    type="number"
                    min="1"
                    step="1"
                    placeholder="до"
                    class="form-control">

                <div
                    v-if="filter.floors.min !== null || filter.floors.max !== null"
                    class="input-group-append"
                >
                    <button
                        type="button"
                        title="Сбросить"
                        class="btn btn-link text-danger"
                        @click="filter.floors.min = null, filter.floors.max = null"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="sizeLiving">Жилая площадь</label>

            <div class="input-group">
                <input
                    id="sizeLiving"
                    v-model.number="filter.size_living.min"
                    type="number"
                    min="0"
                    step="0.01"
                    placeholder="от"
                    class="form-control">

                <input
                    v-model.number="filter.size_living.max"
                    type="number"
                    min="0"
                    step="0.01"
                    placeholder="до"
                    class="form-control">

                <div
                    v-if="filter.size_living.min !== null || filter.size_living.max !== null"
                    class="input-group-append"
                >
                    <button
                        type="button"
                        title="Сбросить"
                        class="btn btn-link text-danger"
                        @click="filter.size_living.min = null, filter.size_living.max = null"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="sizeKitchen">Площадь кухни</label>

            <div class="input-group">
                <input
                    id="sizeKitchen"
                    v-model.number="filter.size_kitchen.min"
                    type="number"
                    min="0"
                    step="0.01"
                    placeholder="от"
                    class="form-control">

                <input
                    v-model.number="filter.size_kitchen.max"
                    type="number"
                    min="0"
                    step="0.01"
                    placeholder="до"
                    class="form-control">

                <div
                    v-if="filter.size_kitchen.min !== null || filter.size_kitchen.max !== null"
                    class="input-group-append"
                >
                    <button
                        type="button"
                        title="Сбросить"
                        class="btn btn-link text-danger"
                        @click="filter.size_kitchen.min = null, filter.size_kitchen.max = null"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <div
            v-if="filterOptions && filterOptions.walls"
            class="form-group"
        >
            <p class="mb-2">Стены<button
                v-if="filter.walls.length"
                type="button"
                title="Сбросить"
                class="btn btn-link text-danger"
                @click="filter.walls = []"
            >
                <i class="fas fa-times"></i>
            </button></p>

            <div
                v-for="(option, index) in filterOptions.walls"
                :key="index"
                class="custom-control custom-checkbox"
            >
                <input
                    :id="`walls${index}`"
                    v-model="filter.walls"
                    type="checkbox"
                    class="custom-control-input"
                    :value="option.value">

                <label
                    class="custom-control-label"
                    :for="`walls${index}`"
                    :title="option.title">{{ option.label }}</label>
            </div>
        </div>

        <div
            v-if="filterOptions && filterOptions.balcony"
            class="form-group"
        >
            <p class="mb-2">Балкон<button
                v-if="filter.balcony.length"
                type="button"
                title="Сбросить"
                class="btn btn-link text-danger"
                @click="filter.balcony = []"
            >
                <i class="fas fa-times"></i>
            </button></p>

            <div
                v-for="(option, index) in filterOptions.balcony"
                :key="index"
                class="custom-control custom-checkbox"
            >
                <input
                    :id="`balcony${index}`"
                    v-model="filter.balcony"
                    type="checkbox"
                    class="custom-control-input"
                    :value="option.value">

                <label
                    class="custom-control-label"
                    :for="`balcony${index}`"
                    :title="option.title">{{ option.label }}</label>
            </div>
        </div>

        <div
            v-if="isStandAloneComponent"
            class="form-group"
        >
            <label for="publishedAtDate">Дата размещения</label>

            <div class="input-group">
                <input
                    id="publishedAtDate"
                    v-model="filter.published_at.date_min"
                    type="date"
                    placeholder="от"
                    class="form-control">

                <input
                    v-model="filter.published_at.date_max"
                    type="date"
                    placeholder="до"
                    class="form-control">

                <div
                    v-if="filter.published_at.date_min !== null || filter.published_at.date_max !== null"
                    class="input-group-append"
                >
                    <button
                        type="button"
                        title="Сбросить"
                        class="btn btn-link text-danger"
                        @click="filter.published_at.date_min = null, filter.published_at.date_max = null"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <div
            v-if="isStandAloneComponent"
            class="form-group"
        >
            <label for="publishedAtTime">Время размещения</label>

            <div class="input-group">
                <input
                    id="publishedAtTime"
                    v-model="filter.published_at.time_min"
                    type="time"
                    placeholder="от"
                    class="form-control">

                <input
                    v-model="filter.published_at.time_max"
                    type="time"
                    placeholder="до"
                    class="form-control">

                <div
                    v-if="filter.published_at.time_min !== null || filter.published_at.time_max !== null"
                    class="input-group-append"
                >
                    <button
                        type="button"
                        title="Сбросить"
                        class="btn btn-link text-danger"
                        @click="filter.published_at.time_min = null, filter.published_at.time_max = null"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <div
            v-if="isStandAloneComponent"
            class="text-right"
        >
            <button
                type="reset"
                class="btn btn-danger">Сбросить</button>

            <button
                type="submit"
                class="btn btn-primary">Показать</button>
        </div>
    </form>
</template>

<script>
    export default {
        name: 'ApartmentsFiltersBar',

        props: {
            filterValue: {
                type: Object,
                default: null
            },

            filterOptions: {
                type: Object,

                default () {
                    return {}
                }
            },

            isStandAloneComponent: {
                type: Boolean,
                default: true
            },
        },

        data () {
            return {
                filter: {
                    price_amount: { min: null, max: null },
                    rooms: [],
                    size_total: { min: null, max: null },
                    bathroom: [],
                    year_built: { min: null, max: null },
                    floor: { min: null, max: null },
                    floors: { min: null, max: null },
                    size_living: { min: null, max: null },
                    size_kitchen: { min: null, max: null },
                    walls: [],
                    balcony: [],

                    published_at: {
                        date_min: null,
                        date_max: null,
                        time_min: null,
                        time_max: null,
                    },
                },
            }
        },

        watch: {
            filterValue: {
                deep: true,

                handler (value) {
                    this.setFilter(value)
                }
            },
        },

        computed: {
            rooms () {
                return [
                    { label: '1', value: 1 },
                    { label: '2', value: 2 },
                    { label: '3', value: 3 },
                    { label: '4 и более', value: '4+' },
                ]
            },

            years () {
                let years = []

                for (let year = 1901; year <= 2038; year++) {
                    years.push(year)
                }

                return years
            },

            isFilterReset () {
                return this.filter.price_amount.min      === null
                    && this.filter.price_amount.max      === null
                    && !this.filter.rooms.length
                    && this.filter.size_total.min        === null
                    && this.filter.size_total.max        === null
                    && !this.filter.bathroom.length
                    && this.filter.year_built.min        === null
                    && this.filter.year_built.max        === null
                    && this.filter.floor.min             === null
                    && this.filter.floor.max             === null
                    && this.filter.floors.min            === null
                    && this.filter.floors.max            === null
                    && this.filter.size_living.min       === null
                    && this.filter.size_living.max       === null
                    && this.filter.size_kitchen.min      === null
                    && this.filter.size_kitchen.max      === null
                    && !this.filter.walls.length
                    && !this.filter.balcony.length
                    && this.filter.published_at.date_min === null
                    && this.filter.published_at.date_max === null
                    && this.filter.published_at.time_min === null
                    && this.filter.published_at.time_max === null
            },
        },

        created () {
            this.setFilter(this.filterValue)
        },

        updated () {
            this.setFilter(this.filterValue)
        },

        methods: {
            resetFilter () {
                this.filter.price_amount.min      = null
                this.filter.price_amount.max      = null
                this.filter.rooms                 = []
                this.filter.size_total.min        = null
                this.filter.size_total.max        = null
                this.filter.bathroom              = []
                this.filter.year_built.min        = null
                this.filter.year_built.max        = null
                this.filter.floor.min             = null
                this.filter.floor.max             = null
                this.filter.floors.min            = null
                this.filter.floors.max            = null
                this.filter.size_living.min       = null
                this.filter.size_living.max       = null
                this.filter.size_kitchen.min      = null
                this.filter.size_kitchen.max      = null
                this.filter.walls                 = []
                this.filter.balcony               = []
                this.filter.published_at.date_min = null
                this.filter.published_at.date_max = null
                this.filter.published_at.time_min = null
                this.filter.published_at.time_max = null

                this.updateFilterValue()
            },

            setFilter (value) {
                if (value !== null) {
                    Object.keys(value).forEach(key => {
                        if (
                            value[key] !== undefined
                            && value[key] !== null
                            && typeof value[key] == 'object'
                        ) {
                            Object.assign(this.filter[key], value[key])
                        } else {
                            this.filter[key] = value[key]
                        }
                    })
                }
            },

            getFilterValue () {
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
                            Object.keys(this.filter[key]).forEach(nestedKey => {
                                if (this.filter[key][nestedKey] !== null) {
                                    if (typeof value[key] != 'object') {
                                        value[key] = {}
                                    }

                                    value[key][nestedKey] = this.filter[key][nestedKey]
                                }
                            })
                        } else if (this.filter[key] !== null) {
                            value[key] = this.filter[key]
                        }
                    })
                }

                return value
            },

            updateFilterValue () {
                if (this.isStandAloneComponent) {
                    this.$emit('update-filter-value', this.getFilterValue())
                }
            },
        },
    }
</script>
