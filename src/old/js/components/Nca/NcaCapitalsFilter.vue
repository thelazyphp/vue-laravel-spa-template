<template>
    <div class="nca-capitals-filter-wrapper">
        <div class="form-row">
            <div class="col-lg-3 form-group">
                <label for="country-filter">{{ $t('nca.filter.common.country.label') }}</label>
                <select
                    id="country-filter"
                    v-model="filter.country"
                    class="form-control form-control-sm"
                >
                    <option
                        value=""
                        selected
                    >
                        {{ $t('nca.filter.common.country.any') }}
                    </option>
                    <option
                        v-for="country in (options['country'] || [])"
                        :key="country"
                        :value="country"
                    >
                        {{ country }}
                    </option>
                </select>
            </div>
            <div class="col-lg-3 form-group">
                <label for="province-filter">{{ $t('nca.filter.common.province.label') }}</label>
                <select
                    id="province-filter"
                    v-model="filter.province"
                    class="form-control form-control-sm"
                >
                    <option
                        value=""
                        selected
                    >
                        {{ $t('nca.filter.common.province.any') }}
                    </option>
                    <option
                        v-for="province in (options['province'] || [])"
                        :key="province"
                        :value="province"
                    >
                        {{ province }}
                    </option>
                </select>
            </div>
            <div class="col-lg-3 form-group">
                <label for="area-filter">{{ $t('nca.filter.common.area.label') }}</label>
                <select
                    id="area-filter"
                    v-model="filter.area"
                    class="form-control form-control-sm"
                >
                    <option
                        value=""
                        selected
                    >
                        {{ $t('nca.filter.common.area.any') }}
                    </option>
                    <option
                        v-for="area in (options['area'] || [])"
                        :key="area"
                        :value="area"
                    >
                        {{ area }}
                    </option>
                </select>
            </div>
            <div class="col-lg-3 form-group">
                <label for="locality-filter">{{ $t('nca.filter.common.locality.label') }}</label>
                <select
                    id="locality-filter"
                    v-model="filter.locality"
                    class="form-control form-control-sm"
                >
                    <option
                        value=""
                        selected
                    >
                        {{ $t('nca.filter.common.locality.any') }}
                    </option>
                    <option
                        v-for="locality in (options['locality'] || [])"
                        :key="locality"
                        :value="locality"
                    >
                        {{ locality }}
                    </option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="col-lg-4 form-group">
                <label for="district-filter">{{ $t('nca.filter.common.district.label') }}</label>
                <select
                    id="district-filter"
                    v-model="filter.district"
                    class="form-control form-control-sm"
                >
                    <option
                        value=""
                        selected
                    >
                        {{ $t('nca.filter.common.district.any') }}
                    </option>
                    <option
                        v-for="district in (options['district'] || [])"
                        :key="district"
                        :value="district"
                    >
                        {{ district }}
                    </option>
                </select>
            </div>
            <div class="col-lg-4 form-group">
                <label for="street-filter">{{ $t('nca.filter.common.street.label') }}</label>
                <select
                    id="street-filter"
                    v-model="filter.street"
                    class="form-control form-control-sm"
                >
                    <option
                        value=""
                        selected
                    >
                        {{ $t('nca.filter.common.street.any') }}
                    </option>
                    <option
                        v-for="street in (options['street'] || [])"
                        :key="street"
                        :value="street"
                    >
                        {{ street }}
                    </option>
                </select>
            </div>
            <div class="col-lg-4 form-group">
                <label for="house-filter">{{ $t('nca.filter.common.house.label') }}</label>
                <select
                    id="house-filter"
                    v-model="filter.house"
                    class="form-control form-control-sm"
                >
                    <option
                        value=""
                        selected
                    >
                        {{ $t('nca.filter.common.house.any') }}
                    </option>
                    <option
                        v-for="house in (options['house'] || [])"
                        :key="house"
                        :value="house"
                    >
                        {{ house }}
                    </option>
                </select>
            </div>
        </div>
        <hr>
        <div class="form-row">
            <div class="col-lg-4 form-group">
                <label for="type-filter">{{ $t('nca.filter.common.type.label') }}</label>
                <select
                    id="type-filter"
                    v-model="filter.type"
                    class="form-control form-control-sm"
                >
                    <option
                        value=""
                        selected
                    >
                        {{ $t('nca.filter.common.type.any') }}
                    </option>
                    <option
                        v-for="type in (options['type'] || [])"
                        :key="type"
                        :value="type"
                    >
                        {{ type }}
                    </option>
                </select>
            </div>
            <div class="col-lg-4 form-group">
                <label for="role-filter">{{ $t('nca.filter.common.role.label') }}</label>
                <select
                    id="role-filter"
                    v-model="filter.role"
                    class="form-control form-control-sm"
                >
                    <option
                        value=""
                        selected
                    >
                        {{ $t('nca.filter.common.role.any') }}
                    </option>
                    <option
                        v-for="role in (options['role'] || [])"
                        :key="role"
                        :value="role"
                    >
                        {{ role }}
                    </option>
                </select>
            </div>
            <div class="col-lg-4 form-group">
                <label for="walls-type-filter">{{ $t('nca.filter.capitals.walls_type.label') }}</label>
                <select
                    id="walls-type-filter"
                    v-model="filter.walls_type"
                    class="form-control form-control-sm"
                >
                    <option
                        value=""
                        selected
                    >
                        {{ $t('nca.filter.capitals.walls_type.any') }}
                    </option>
                    <option
                        v-for="wallsType in (options['walls_type'] || [])"
                        :key="wallsType"
                        :value="wallsType"
                    >
                        {{ wallsType }}
                    </option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="col-lg-4 form-group">
                <label for="size-filter">{{ $t('nca.filter.capitals.size.label') }}</label>
                <div class="input-group input-group-sm">
                    <input
                        id="size-filter"
                        v-model="filter.size_min"
                        type="number"
                        min="0"
                        step="0.1"
                        class="form-control"
                        aria-describedby="size-filter-addon"
                        :placeholder="$t('nca.filter.capitals.size.min')"
                        :aria-label="$t('nca.filter.capitals.size.aria-label')"
                    >
                    <input
                        v-model="filter.size_max"
                        type="number"
                        min="0"
                        step="0.1"
                        class="form-control"
                        aria-describedby="land-size-filter-addon"
                        :placeholder="$t('nca.filter.capitals.size.max')"
                        :aria-label="$t('nca.filter.capitals.size.aria-label')"
                    >
                    <div class="input-group-append">
                        <span
                            id="size-filter-addon"
                            class="input-group-text"
                        >
                            {{ $t('nca.filter.capitals.size.addon') }}<sup>2</sup>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 form-group">
                <label for="land-size-filter">{{ $t('nca.filter.capitals.land_size.label') }}</label>
                <div class="input-group input-group-sm">
                    <input
                        id="land-size-filter"
                        v-model="filter.land_size_min"
                        type="number"
                        min="0"
                        step="0.1"
                        class="form-control"
                        aria-describedby="land-size-filter-addon"
                        :placeholder="$t('nca.filter.capitals.land_size.min')"
                        :aria-label="$t('nca.filter.capitals.land_size.aria-label')"
                    >
                    <input
                        v-model="filter.land_size_max"
                        type="number"
                        min="0"
                        step="0.1"
                        class="form-control"
                        aria-describedby="land-size-filter-addon"
                        :placeholder="$t('nca.filter.capitals.land_size.max')"
                        :aria-label="$t('nca.filter.capitals.land_size.aria-label')"
                    >
                    <div class="input-group-append">
                        <span
                            id="land-size-filter-addon"
                            class="input-group-text"
                        >
                            {{ $t('nca.filter.capitals.land_size.addon') }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 form-group">
                <label for="construction-year-filter">{{ $t('nca.filter.capitals.construction_year.label') }}</label>
                <div class="input-group input-group-sm">
                    <input
                        id="construction-year-filter"
                        v-model="filter.construction_year_min"
                        type="number"
                        min="1970"
                        step="1"
                        class="form-control"
                        :placeholder="$t('nca.filter.capitals.construction_year.min')"
                    >
                    <input
                        v-model="filter.construction_year_max"
                        type="number"
                        min="1970"
                        step="1"
                        class="form-control"
                        :placeholder="$t('nca.filter.capitals.construction_year.max')"
                    >
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-lg-4 form-group">
                <label for="floors-total-filter">{{ $t('nca.filter.capitals.floors_total.label') }}</label>
                <div class="input-group input-group-sm">
                    <input
                        id="floors-total-filter"
                        v-model="filter.floors_total_min"
                        type="number"
                        min="1"
                        step="1"
                        class="form-control"
                        :placeholder="$t('nca.filter.capitals.floors_total.min')"
                    >
                    <input
                        v-model="filter.floors_total_max"
                        type="number"
                        min="1"
                        step="1"
                        class="form-control"
                        :placeholder="$t('nca.filter.capitals.floors_total.max')"
                    >
                </div>
            </div>
            <div class="col-lg-4 form-group">
                <label for="undeground-floors-total-filter">{{ $t('nca.filter.capitals.undeground_floors_total.label') }}</label>
                <div class="input-group input-group-sm">
                    <input
                        id="undeground-floors-total-filter"
                        v-model="filter.undeground_floors_total_min"
                        type="number"
                        min="1"
                        step="1"
                        class="form-control"
                        :placeholder="$t('nca.filter.capitals.undeground_floors_total.min')"
                    >
                    <input
                        v-model="filter.undeground_floors_total_max"
                        type="number"
                        min="1"
                        step="1"
                        class="form-control"
                        :placeholder="$t('nca.filter.capitals.undeground_floors_total.max')"
                    >
                </div>
            </div>
            <div class="col-lg-4 form-group">
                <label for="price-filter">{{ $t('nca.filter.common.price.label') }}</label>
                <div class="input-group input-group-sm">
                    <input
                        id="price-filter"
                        v-model="filter.price_min"
                        type="number"
                        min="0"
                        step="1"
                        class="form-control"
                        :placeholder="$t('nca.filter.common.price.min')"
                    >
                    <input
                        v-model="filter.price_max"
                        type="number"
                        min="0"
                        step="1"
                        class="form-control"
                        :placeholder="$t('nca.filter.common.price.max')"
                    >
                    <select
                        v-model="filter.price_currency"
                        class="form-control"
                    >
                        <option
                            value=""
                            selected
                        >
                            {{ $t('nca.filter.common.price.any') }}
                        </option>
                        <option
                            v-for="priceCurrency in (options['price_currency'] || [])"
                            :key="priceCurrency"
                            :value="priceCurrency"
                        >
                            {{ priceCurrency }}
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'NcaCapitalsFilter',

        props: {
            options: {
                type: Object,
                required: []
            }
        },

        data () {
            return {
                filter: {
                    country: '',
                    province: '',
                    area: '',
                    locality: '',
                    district: '',
                    street: '',
                    house: '',
                    type: '',
                    role: '',
                    walls_type: '',
                    size_min: null,
                    size_max: null,
                    land_size_min: null,
                    land_size_max: null,
                    construction_year_min: null,
                    construction_year_max: null,
                    floors_total_min: null,
                    floors_total_max: null,
                    undeground_floors_total_min: null,
                    undeground_floors_total_max: null,
                    price_min: null,
                    price_max: null,
                    price_currency: ''
                }
            }
        },

        watch: {
            ['filter.size_min'] (value) {
                this.filter.size_max = value
            },

            ['filter.land_size_min'] (value) {
                this.filter.land_size_max = value
            },

            ['filter.construction_year_min'] (value) {
                this.filter.construction_year_max = value
            },

            ['filter.floors_total_min'] (value) {
                this.filter.floors_total_max = value
            },

            ['filter.undeground_floors_total_min'] (value) {
                this.filter.undeground_floors_total_max = value
            },

            ['filter.price_min'] (value) {
                this.filter.price_max = value
            }
        },

        methods: {
            resetFilter () {
                this.filter.country = ''
                this.filter.province = ''
                this.filter.area = ''
                this.filter.locality = ''
                this.filter.district = ''
                this.filter.street = ''
                this.filter.house = ''
                this.filter.type = ''
                this.filter.role = ''
                this.filter.walls_type = ''
                this.filter.size_min = null
                this.filter.size_max = null
                this.filter.land_size_min = null
                this.filter.land_size_max = null
                this.filter.construction_year_min = null
                this.filter.construction_year_max = null
                this.filter.floors_total_min = null
                this.filter.floors_total_max = null
                this.filter.undeground_floors_total_min = null
                this.filter.undeground_floors_total_max = null
                this.filter.price_min = null
                this.filter.price_max = null
                this.filter.price_currency = ''
            }
        }
    }
</script>
