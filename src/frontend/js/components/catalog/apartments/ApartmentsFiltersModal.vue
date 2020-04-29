<template>
    <div
        id="filtersModal"
        class="modal fade"
        role="dialog"
        tabindex="-1"
        aria-hidden="true"
        aria-labelledby="filtersModalLabel"
    >
        <div
            class="modal-dialog modal-dialog-scrollable"
            role="document"
        >
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5
                        id="filtersModalLabel"
                        class="modal-title">Фильтры</h5>

                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Закрыть"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form v-if="filter">
                        <div class="form-group">
                            <input ref="rooms1" v-model="filter.rooms" type="checkbox" class="d-none" :value="1">
                            <input ref="rooms2" v-model="filter.rooms" type="checkbox" class="d-none" :value="2">
                            <input ref="rooms3" v-model="filter.rooms" type="checkbox" class="d-none" :value="3">
                            <input ref="rooms4" v-model="filter.rooms" type="checkbox" class="d-none"  value="4+">

                            <p class="mb-2">Комнат</p>

                            <div class="d-flex">
                                <button type="button" :class="['flex-fill mx-1 btn btn-outline-primary', { active: filter.rooms.includes(1)    }]" @click="$refs.rooms1.click()">1</button>
                                <button type="button" :class="['flex-fill mx-1 btn btn-outline-primary', { active: filter.rooms.includes(2)    }]" @click="$refs.rooms2.click()">2</button>
                                <button type="button" :class="['flex-fill mx-1 btn btn-outline-primary', { active: filter.rooms.includes(3)    }]" @click="$refs.rooms3.click()">3</button>
                                <button type="button" :class="['flex-fill mx-1 btn btn-outline-primary', { active: filter.rooms.includes('4+') }]" @click="$refs.rooms4.click()">4 и более</button>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col form-group">
                                <label for="floorMin">Этаж, с</label>

                                <input
                                    id="floorMin"
                                    v-model.number="filter.floor['min']"
                                    type="number"
                                    class="form-control"
                                    :min="1"
                                    :step="1">
                            </div>

                            <div class="col form-group">
                                <label for="floorMax">Этаж, по</label>

                                <input
                                    id="floorMax"
                                    v-model.number="filter.floor['max']"
                                    type="number"
                                    class="form-control"
                                    :min="1"
                                    :step="1">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col form-group">
                                <label for="floorsMin">Этажность, от</label>

                                <input
                                    id="floorsMin"
                                    v-model.number="filter.floors['min']"
                                    type="number"
                                    class="form-control"
                                    :min="1"
                                    :step="1">
                            </div>

                            <div class="col form-group">
                                <label for="floorsMax">Этажность, до</label>

                                <input
                                    id="floorsMax"
                                    v-model.number="filter.floors['max']"
                                    type="number"
                                    class="form-control"
                                    :min="1"
                                    :step="1">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col form-group">
                                <label for="yearBuiltMin">Год постройки, с</label>

                                <input
                                    id="yearBuiltMin"
                                    v-model.number="filter.year_built['min']"
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
                                    v-model.number="filter.year_built['max']"
                                    type="number"
                                    class="form-control"
                                    :min="1901"
                                    :max="2038"
                                    :step="1">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col form-group">
                                <label for="sizeTotalMin">Площадь общая, от</label>

                                <input
                                    id="sizeTotalMin"
                                    v-model.number="filter.size_total['min']"
                                    type="number"
                                    class="form-control"
                                    :min="0.1"
                                    :step="0.1">
                            </div>

                            <div class="col form-group">
                                <label for="sizeTotalMax">Площадь общая, до</label>

                                <input
                                    id="sizeTotalMax"
                                    v-model.number="filter.size_total['max']"
                                    type="number"
                                    class="form-control"
                                    :min="0.1"
                                    :step="0.1">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col form-group">
                                <label for="sizeLivingMin">Площадь жилая, от</label>

                                <input
                                    id="sizeLivingMin"
                                    v-model.number="filter.size_living['min']"
                                    type="number"
                                    class="form-control"
                                    :min="0.1"
                                    :step="0.1">
                            </div>

                            <div class="col form-group">
                                <label for="sizeLivingMax">Площадь жилая, до</label>

                                <input
                                    id="sizeLivingMax"
                                    v-model.number="filter.size_living['max']"
                                    type="number"
                                    class="form-control"
                                    :min="0.1"
                                    :step="0.1">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col form-group">
                                <label for="sizeKitchenMin">Площадь кухни, от</label>

                                <input
                                    id="sizeKitchenMin"
                                    v-model.number="filter.size_kitchen['min']"
                                    type="number"
                                    class="form-control"
                                    :min="0.1"
                                    :step="0.1">
                            </div>

                            <div class="col form-group">
                                <label for="sizeKitchenMax">Площадь кухни, до</label>

                                <input
                                    id="sizeKitchenMax"
                                    v-model.number="filter.size_kitchen['max']"
                                    type="number"
                                    class="form-control"
                                    :min="0.1"
                                    :step="0.1">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col form-group">
                                <label for="priceAmountMin">Цена, от</label>

                                <input
                                    id="priceAmountMin"
                                    v-model.number="filter.price_amount['min']"
                                    type="number"
                                    class="form-control"
                                    :min="0"
                                    :step="1">
                            </div>

                            <div class="col form-group">
                                <label for="priceAmountMax">Цена, до</label>

                                <input
                                    id="priceAmountMax"
                                    v-model.number="filter.price_amount['max']"
                                    type="number"
                                    class="form-control"
                                    :min="0"
                                    :step="1">
                            </div>
                        </div>

                        <div
                            v-if="filterOptions"
                            class="form-group"
                        >
                            <p class="mb-2">Стены</p>

                            <div class="row no-gutters">
                                <div
                                    v-for="(option, index) in filterOptions.walls"
                                    :key="index"
                                    class="col-sm-6"
                                >
                                    <div class="custom-control custom-checkbox">
                                        <input
                                            :id="`walls${index}`"
                                            v-model="filter.walls"
                                            type="checkbox"
                                            class="custom-control-input"
                                            :value="option.value">

                                        <label
                                            class="custom-control-label"
                                            :for="`walls${index}`">{{ option.label }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="filterOptions"
                            class="form-group"
                        >
                            <p class="mb-2">Балкон</p>

                            <div class="row no-gutters">
                                <div
                                    v-for="(option, index) in filterOptions.balcony"
                                    :key="index"
                                    class="col-sm-6"
                                >
                                    <div class="custom-control custom-checkbox">
                                        <input
                                            :id="`balcony${index}`"
                                            v-model="filter.balcony"
                                            type="checkbox"
                                            class="custom-control-input"
                                            :value="option.value">

                                        <label
                                            class="custom-control-label"
                                            :for="`balcony${index}`">{{ option.label }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="filterOptions"
                            class="form-group"
                        >
                            <p class="mb-2">Санузел</p>

                            <div class="row no-gutters">
                                <div
                                    v-for="(option, index) in filterOptions.bathroom"
                                    :key="index"
                                    class="col-sm-6"
                                >
                                    <div class="custom-control custom-checkbox">
                                        <input
                                            :id="`bathroom${index}`"
                                            v-model="filter.bathroom"
                                            type="checkbox"
                                            class="custom-control-input"
                                            :value="option.value">

                                        <label
                                            class="custom-control-label"
                                            :for="`bathroom${index}`">{{ option.label }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="sources"
                            class="form-group"
                        >
                            <p class="mb-2">Источник</p>

                            <div class="row no-gutters">
                                <div
                                    v-for="(source, index) in sources"
                                    :key="index"
                                    class="col-sm-6"
                                >
                                    <div
                                        class="custom-control custom-checkbox"
                                        :title="source.description"
                                    >
                                        <input
                                            :id="`source${index}`"
                                            v-model="filter.source_id"
                                            type="checkbox"
                                            class="custom-control-input"
                                            :value="source.id">

                                        <label
                                            class="custom-control-label"
                                            :for="`source${index}`">{{ source.name }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="sellerIsPrivate">Продавец</label>

                            <select
                                id="sellerIsPrivate"
                                v-model="filter.seller_is_private"
                                class="custom-select"
                            >
                                <option :value="null">Любой</option>
                                <option :value="1">Частное лицо</option>
                                <option :value="0">Агентство/застройщик</option>
                            </select>
                        </div>

                        <div class="form-row">
                            <div class="col form-group">
                                <label for="publishedAtDateMin">Опубликовано, с</label>

                                <input
                                    id="publishedAtDateMin"
                                    v-model="filter.published_at['date_min']"
                                    type="date"
                                    class="form-control">
                            </div>

                            <div class="col form-group">
                                <label for="publishedAtDateMax">Опубликовано, по</label>

                                <input
                                    id="publishedAtDateMax"
                                    v-model="filter.published_at['date_max']"
                                    type="date"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col form-group">
                                <label for="publishedAtTimeMin">Время публикации, с</label>

                                <input
                                    id="publishedAtTimeMin"
                                    v-model="filter.published_at['time_min']"
                                    type="time"
                                    class="form-control">
                            </div>

                            <div class="col form-group">
                                <label for="publishedAtTimeMax">Время публикации, до</label>

                                <input
                                    id="publishedAtTimeMax"
                                    v-model="filter.published_at['time_max']"
                                    type="time"
                                    class="form-control">
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer border-top-0">
                    <button
                        v-show="!filterClear"
                        type="button"
                        class="btn btn-danger"
                        data-dismiss="modal"
                        @click="clearFilterValue"><i class="fas fa-times mr-2"></i> Отчистить</button>

                    <button
                        type="button"
                        class="btn btn-success"
                        data-dismiss="modal"
                        @click="updateFilterValue"><i class="fas fa-search mr-2"></i> Показать</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ApartmentsFiltersModal',

        props: {
            sources: {
                type: Array,
                required: true
            },

            filterValue: {
                type: Object,
                required: true
            },

            filterProps: {
                type: Object,
                required: true
            },

            filterOptions: {
                type: Object,
                required: true
            },
        },

        data () {
            return {
                filter: null,
            }
        },

        computed: {
            filterClear () {
                return JSON.stringify(this.filter) == JSON.stringify(this.filterProps)
            },
        },

        created () {
            this.filter = this.filterValue
        },

        methods: {
            clearFilterValue () {
                Object.keys(this.filterProps).forEach(key => {
                    this.filter[key] = this.filterProps[key]
                })

                this.updateFilterValue()
            },

            updateFilterValue () {
                this.$emit('update-filter-value', this.filter)
            },
        }
    }
</script>
