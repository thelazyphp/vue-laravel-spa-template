<template>
    <div class="filter-stack-wrap">
        <div class="dropdown">
            <button
                type="button"
                class="mb-3 btn btn-secondary dropdown-toggle"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
            >
                <i class="mr-2 fas fa-filter"></i>Добавить фильтры</button>

            <div class="dropdown-menu">
                <a
                    v-for="(filter, index) in filters"
                    :key="index"
                    href="javascript:void(0)"
                    class="dropdown-item" @click="addFilter(filter)">{{ filter.label }}</a>
            </div>
        </div>

        <div class="row">
            <div
                v-for="(filter, index) in stack"
                :key="index"
                class="col-sm-6 col-md-6 col-lg-4 col-xl-3"
            >
                <div
                    v-if="filter.prop == 'rooms'"
                    :key="index"
                    class="form-group"
                >
                    <p class="mb-2">Комнат</p>

                    <input
                        v-for="(option, optionIndex) in rooms"
                        :key="optionIndex"
                        :ref="`rooms${optionIndex}`"
                        v-model="filter.value"
                        type="checkbox"
                        class="d-none"
                        :value="option.value">

                    <div class="d-flex">
                        <button
                            v-for="(option, optionIndex) in rooms"
                            :key="optionIndex"
                            type="button"
                            :class="['mx-1 flex-fill btn btn-outline-primary', { active: filter.value.includes(option.value) }]"
                            @click="$refs[`rooms${optionIndex}`][0].click()">{{ option.label }}</button>
                    </div>
                </div>

                <div
                    v-if="filter.prop == 'floor'"
                    :key="index"
                    class="form-row"
                >
                    <div class="col form-group">
                        <label for="floorMin">Этаж, с</label>

                        <input
                            id="floorMin"
                            v-model.number="filter.value.min"
                            type="number"
                            class="form-control"
                            :min="1"
                            :step="1">
                    </div>

                    <div class="col form-group">
                        <label for="floorMax">Этаж, по</label>

                        <input
                            id="floorMax"
                            v-model.number="filter.value.max"
                            type="number"
                            class="form-control"
                            :min="1"
                            :step="1">
                    </div>
                </div>

                <div
                    v-if="filter.prop == 'floors'"
                    :key="index"
                    class="form-row"
                >
                    <div class="col form-group">
                        <label for="floorsMin">Этажность, от</label>

                        <input
                            id="floorsMin"
                            v-model.number="filter.value.min"
                            type="number"
                            class="form-control"
                            :min="1"
                            :step="1">
                    </div>

                    <div class="col form-group">
                        <label for="floorsMax">Этажность, до</label>

                        <input
                            id="floorsMax"
                            v-model.number="filter.value.max"
                            type="number"
                            class="form-control"
                            :min="1"
                            :step="1">
                    </div>
                </div>

                <div
                    v-if="filter.prop == 'year_built'"
                    :key="index"
                    class="form-row"
                >
                    <div class="col form-group">
                        <label for="yearBuiltMin">Год постройки, с</label>

                        <input
                            id="yearBuiltMin"
                            v-model.number="filter.value.min"
                            type="number"
                            class="form-control"
                            :min="1901"
                            :max="2038"
                            :step="1">
                    </div>

                    <div class="col form-group">
                        <label for="yearBuiltMax">Год постройки, по</label>

                        <input
                            id="yearBuiltMax"
                            v-model.number="filter.value.max"
                            type="number"
                            class="form-control"
                            :min="1901"
                            :max="2038"
                            :step="1">
                    </div>
                </div>

                <div
                    v-if="filter.prop == 'size_total'"
                    :key="index"
                    class="form-row"
                >
                    <div class="col form-group">
                        <label for="sizeTotalMin">Площадь общая, от</label>

                        <div class="input-group">
                            <input
                                id="sizeTotalMin"
                                v-model.number="filter.value.min"
                                type="number"
                                class="form-control"
                                :min="0.1"
                                :step="0.1">

                            <div class="input-group-append">
                                <span class="input-group-text">м<sup>2</sup></span>
                            </div>
                        </div>
                    </div>

                    <div class="col form-group">
                        <label for="sizeTotalMax">Площадь общая, до</label>

                        <div class="input-group">
                            <input
                                id="sizeTotalMax"
                                v-model.number="filter.value.max"
                                type="number"
                                class="form-control"
                                :min="0.1"
                                :step="0.1">

                            <div class="input-group-append">
                                <span class="input-group-text">м<sup>2</sup></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    v-if="filter.prop == 'size_living'"
                    :key="index"
                    class="form-row"
                >
                    <div class="col form-group">
                        <label for="sizeLivingMin">Площадь жилая, от</label>

                        <div class="input-group">
                            <input
                                id="sizeLivingMin"
                                v-model.number="filter.value.min"
                                type="number"
                                class="form-control"
                                :min="0.1"
                                :step="0.1">

                            <div class="input-group-append">
                                <span class="input-group-text">м<sup>2</sup></span>
                            </div>
                        </div>
                    </div>

                    <div class="col form-group">
                        <label for="sizeLivingMax">Площадь жилая, до</label>

                        <div class="input-group">
                            <input
                                id="sizeLivingMax"
                                v-model.number="filter.value.max"
                                type="number"
                                class="form-control"
                                :min="0.1"
                                :step="0.1">

                            <div class="input-group-append">
                                <span class="input-group-text">м<sup>2</sup></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    v-if="filter.prop == 'size_kitchen'"
                    :key="index"
                    class="form-row"
                >
                    <div class="col form-group">
                        <label for="sizeKitchenMin">Площадь кухни, от</label>

                        <div class="input-group">
                            <input
                                id="sizeKitchenMin"
                                v-model.number="filter.value.min"
                                type="number"
                                class="form-control"
                                :min="0.1"
                                :step="0.1">

                            <div class="input-group-append">
                                <span class="input-group-text">м<sup>2</sup></span>
                            </div>
                        </div>
                    </div>

                    <div class="col form-group">
                        <label for="sizeKitchenMax">Площадь кухни, до</label>

                        <div class="input-group">
                            <input
                                id="sizeKitchenMax"
                                v-model.number="filter.value.max"
                                type="number"
                                class="form-control"
                                :min="0.1"
                                :step="0.1">

                            <div class="input-group-append">
                                <span class="input-group-text">м<sup>2</sup></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    v-if="filter.prop == 'price_amount'"
                    :key="index"
                    class="form-row"
                >
                    <div class="col form-group">
                        <label for="priceAmountMin">Цена, от</label>

                        <input
                            id="priceAmountMin"
                            v-model.number="filter.value.min"
                            type="number"
                            class="form-control"
                            :min="0"
                            :step="1">
                    </div>

                    <div class="col form-group">
                        <label for="priceAmountMax">Цена, до</label>

                        <input
                            id="priceAmountMax"
                            v-model.number="filter.value.max"
                            type="number"
                            class="form-control"
                            :min="0"
                            :step="1">
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="stack.length"
            class="d-flex justify-content-end"
        >
            <button
                type="button"
                class="ml-2 btn btn-danger"
                @click="clearFilterValue"
            >
                <i class="mr-2 fas fa-times"></i>Отчистить</button>

            <button
                v-if="stack.length"
                type="button"
                class="ml-2 btn btn-success"
                @click="updateFilterValue"
            >
                <i class="mr-2 fas fa-search"></i>Показать</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'FilterStack',

        data () {
            return {
                stack: [],
                stacked: [],
            }
        },

        computed: {
            filters () {
                return [
                    {
                        label: 'Комнат',
                        prop: 'rooms',
                        value: [],
                    },
                    {
                        label: 'Этаж',
                        prop: 'floor',
                        value: { min: null, max: null },
                    },
                    {
                        label: 'Этажность',
                        prop: 'floors',
                        value: { min: null, max: null },
                    },
                    {
                        label: 'Год постройки',
                        prop: 'year_built',
                        value: { min: null, max: null },
                    },
                    {
                        label: 'Площадь общая',
                        prop: 'size_total',
                        value: { min: null, max: null },
                    },
                    {
                        label: 'Площадь жилая',
                        prop: 'size_living',
                        value: { min: null, max: null },
                    },
                    {
                        label: 'Площадь кухни',
                        prop: 'size_kitchen',
                        value: { min: null, max: null },
                    },
                    {
                        label: 'Цена',
                        prop: 'price_amount',
                        value: { min: null, max: null },
                    },
                    {
                        label: 'Дата публикации',
                        prop: 'published_at',
                        value: { date_min: null, date_max: null },
                    },
                    {
                        label: 'Время публикации',
                        prop: 'published_at',
                        value: { time_min: null, time_max: null },
                    },
                ]
            },

            rooms () {
                return [
                    { label: '1', value: 1 },
                    { label: '2', value: 2 },
                    { label: '3', value: 3 },
                    { label: '4 и более', value: '4+' },
                ]
            },

            filterValue () {
                let value = null

                if (this.stack.length) {
                    value = {}
                    this.stack.forEach(filter => value[filter.prop] = filter.value)
                }

                return value
            },
        },

        methods: {
            clearFilterValue () {
                this.stack = []
                this.stacked = []
                this.updateFilterValue()
            },

            updateFilterValue () {
                this.$emit('update-filter-value', this.filterValue)
            },

            addFilter (filter) {
                if (this.stacked.includes(filter.prop)) {
                    if (typeof filter.value == 'object') {
                        Object.assign(
                            this.stack[
                                this.stack.findIndex(item => item.prop == filter.prop)
                            ].value, filter.value
                        )
                    }
                } else {
                    this.stack.push(filter)
                    this.stacked.push(filter.prop)
                }
            },
        },
    }
</script>
