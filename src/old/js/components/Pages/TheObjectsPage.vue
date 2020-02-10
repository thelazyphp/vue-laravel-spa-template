<template>
    <div class="container-fluid">

        <!-- Filters -->
        <div class="card shadow mb-3">
            <div class="card-body">
                <div class="form-row">
                    <div class="col-12 col-lg-4 form-group">
                        <label for="search">{{ $t('objects.labels.search') }}</label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                            <input
                                id="search"
                                v-model="search"
                                type="search"
                                class="form-control"
                                :placeholder="$t('objects.placeholders.search')"
                            >
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 form-group">
                        <label for="type">{{ $t('objects.labels.type') }}</label>
                        <select
                            id="type"
                            v-model="type"
                            class="form-control form-control-sm"
                        >
                            <option
                                v-for="type in types"
                                :key="type"
                                :value="type"
                            >
                                {{ $t(`objects.types.${type}`) }}
                            </option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-4 form-group">
                        <label for="source-id-filter">{{ $t('objects.filter.common.source_id.label') }}</label>
                        <select
                            id="source-id-filter"
                            v-model="filter.common.source_id"
                            class="form-control form-control-sm"
                        >
                            <option value="" selected>{{ $t('objects.filter.common.source_id.any') }}</option>
                            <option
                                v-for="source in sources"
                                :key="source.id"
                                :value="source.id"
                                :title="source.description"
                            >
                                {{ source.name }}
                            </option>
                        </select>
                    </div>
                </div>
                <hr>
                <div class="form-row">
                    <div class="col-12 col-lg-3 form-group">
                        <label for="country-filter">{{ $t('objects.filter.common.country.label') }}</label>
                        <select
                            id="country-filter"
                            v-model="filter.common.country"
                            class="form-control form-control-sm"
                        >
                            <option value="" selected>{{ $t('objects.filter.common.country.any') }}</option>
                            <option
                                v-for="country in locations.countries"
                                :key="country"
                                :value="country"
                            >
                                {{ country }}
                            </option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-3 form-group">
                        <label for="province-filter">{{ $t('objects.filter.common.province.label') }}</label>
                        <select
                            id="province-filter"
                            v-model="filter.common.province"
                            class="form-control form-control-sm"
                        >
                            <option value="" selected>{{ $t('objects.filter.common.province.any') }}</option>
                            <option
                                v-for="province in locations.provinces"
                                :key="province"
                                :value="province"
                            >
                                {{ province }}
                            </option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-3 form-group">
                        <label for="area-filter">{{ $t('objects.filter.common.area.label') }}</label>
                        <select
                            id="area-filter"
                            v-model="filter.common.area"
                            class="form-control form-control-sm"
                        >
                            <option value="" selected>{{ $t('objects.filter.common.area.any') }}</option>
                            <option
                                v-for="area in locations.areas"
                                :key="area"
                                :value="area"
                            >
                                {{ area }}
                            </option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-3 form-group">
                        <label for="locality-filter">{{ $t('objects.filter.common.locality.label') }}</label>
                        <select
                            id="locality-filter"
                            v-model="filter.common.locality"
                            class="form-control form-control-sm"
                        >
                            <option value="" selected>{{ $t('objects.filter.common.locality.any') }}</option>
                            <option
                                v-for="locality in locations.localities"
                                :key="locality"
                                :value="locality"
                            >
                                {{ locality }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-12 col-lg-4 form-group">
                        <label for="district-filter">{{ $t('objects.filter.common.district.label') }}</label>
                        <select
                            id="district-filter"
                            v-model="filter.common.district"
                            class="form-control form-control-sm"
                        >
                            <option value="" selected>{{ $t('objects.filter.common.district.any') }}</option>
                            <option
                                v-for="district in locations.districts"
                                :key="district"
                                :value="district"
                            >
                                {{ district }}
                            </option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-4 form-group">
                        <label for="street-filter">{{ $t('objects.filter.common.street.label') }}</label>
                        <select
                            id="street-filter"
                            v-model="filter.common.street"
                            class="form-control form-control-sm"
                        >
                            <option value="" selected>{{ $t('objects.filter.common.street.any') }}</option>
                            <option
                                v-for="street in locations.streets"
                                :key="street"
                                :value="street"
                            >
                                {{ street }}
                            </option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-4 form-group">
                        <label for="house-filter">{{ $t('objects.filter.common.house.label') }}</label>
                        <select
                            id="house-filter"
                            v-model="filter.common.house"
                            class="form-control form-control-sm"
                        >
                            <option value="" selected>{{ $t('objects.filter.common.house.any') }}</option>
                            <option
                                v-for="house in locations.houses"
                                :key="house"
                                :value="house"
                            >
                                {{ house }}
                            </option>
                        </select>
                    </div>
                </div>
                <hr>
                <template v-if="type == 'flats'">
                    <div class="form-row">
                        <div class="col-12 col-lg-4 form-group">
                            <label for="walls-type-filter">{{ $t('objects.filter.flats.walls_type.label') }}</label>
                            <select
                                id="walls-type-filter"
                                v-model="filter.flats.walls_type"
                                class="form-control form-control-sm"
                            >
                                <option value="" selected>{{ $t('objects.filter.flats.walls_type.any') }}</option>
                                <option
                                    v-for="type in options.flats.wallsTypes"
                                    :key="type"
                                    :value="type"
                                >
                                    {{ type }}
                                </option>
                            </select>
                        </div>
                        <div class="col-12 col-lg-4 form-group">
                            <label for="balcony-type-filter">{{ $t('objects.filter.flats.balcony_type.label') }}</label>
                            <select
                                id="balcony-type-filter"
                                v-model="filter.flats.balcony_type"
                                class="form-control form-control-sm"
                            >
                                <option value="" selected>{{ $t('objects.filter.flats.balcony_type.any') }}</option>
                                <option
                                    v-for="type in options.flats.balconyTypes"
                                    :key="type"
                                    :value="type"
                                >
                                    {{ type }}
                                </option>
                            </select>
                        </div>
                        <div class="col-12 col-lg-4 form-group">
                            <label for="bathroom-type-filter">{{ $t('objects.filter.flats.bathroom_type.label') }}</label>
                            <select
                                id="bathroom-type-filter"
                                v-model="filter.flats.bathroom_type"
                                class="form-control form-control-sm"
                            >
                                <option value="" selected>{{ $t('objects.filter.flats.bathroom_type.any') }}</option>
                                <option
                                    v-for="type in options.flats.bathroomTypes"
                                    :key="type"
                                    :value="type"
                                >
                                    {{ type }}
                                </option>
                            </select>
                        </div>
                    </div>
                </template>
                <div class="d-flex w-100 justify-content-end">
                    <a
                        href="javascript:void(0)"
                        @click="resetFilter(type)"
                    >
                        {{ $t('objects.links.reset_filter') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="card card-body shadow sticky-top mb-3">
            <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                <div
                    class="btn-group-toggle mr-2"
                    data-toggle="buttons"
                >
                    <label
                        class="btn btn-sm btn-outline-primary active"
                        @click="showMap = ! showMap"
                    >
                        <input
                            type="checkbox"
                            :checked="showMap"
                        >
                        <i class="fas fa-map-marked-alt"></i>
                    </label>
                </div>
                <div
                    class="btn-group-toggle mr-2"
                    data-toggle="buttons"
                >
                    <label
                        class="btn btn-sm btn-outline-primary active"
                        @click="showImages = ! showImages"
                    >
                        <input
                            type="checkbox"
                            :checked="showImages"
                        >
                        <i class="fas fa-image"></i>
                    </label>
                </div>
                <div
                    class="btn-group btn-group-sm btn-group-toggle mr-2"
                    data-toggle="buttons"
                >
                    <label
                        class="btn btn-outline-primary active"
                        @click="viewMode = 'list'"
                    >
                        <input
                            type="radio"
                            name="view-mode"
                            checked
                        >
                        <i class="fas fa-th-list"></i>
                    </label>
                    <label
                        class="btn btn-outline-primary"
                        @click="viewMode = 'table'"
                    >
                        <input
                            type="radio"
                            name="view-mode"
                        >
                        <i class="fas fa-table"></i>
                    </label>
                </div>
                <div
                    class="btn-group btn-group-sm btn-group-toggle"
                    data-toggle="buttons"
                >
                    <label class="btn btn-outline-primary active" @click="perPage = 10, loadItems()">
                        <input
                            type="radio"
                            name="per_page"
                            checked
                        >
                        10
                    </label>
                    <label class="btn btn-outline-primary" @click="perPage = 25, loadItems()">
                        <input
                            type="radio"
                            name="per_page"
                        >
                        25
                    </label>
                    <label class="btn btn-outline-primary" @click="perPage = 50, loadItems()">
                        <input
                            type="radio"
                            name="per_page"
                        >
                        50
                    </label>
                </div>
            </div>
        </div>

        <div
            v-show="showMap"
            id="map"
            class="rounded shadow mb-3"
        >
        </div>

        <div
            v-if="loading"
            class="text-center"
        >
            <div
                class="spinner-grow text-primary"
                role="status"
            >
                <span class="sr-only">{{ $t('common.loading') }}</span>
            </div>
        </div>

        <template v-if="viewMode == 'list'">
            <FlatsListItem
                v-for="item in items.data"
                :key="item.id"
                class="mb-3"
                :flat="item"
                :showImage="showImages"
                @findSimilar="findSimilar"
            />
        </template>

        <template v-if="viewMode == 'table'">
            <div class="card card-body shadow table-responsive text-nowrap mb-3">
                <table class="table table-sm table-borderless table-striped">
                    <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">ID</th>
                            <th scope="col">Published (updated)</th>
                            <th scope="col">Rooms</th>
                            <th scope="col">Floor / total</th>
                            <th scope="col">Construction year</th>
                            <th scope="col">Size / living / kitchen</th>
                            <th scope="col">Price</th>
                            <th scope="col">Walls</th>
                            <th scope="col">Balcony</th>
                            <th scope="col">Bathroom</th>
                            <th scope="col">Locality</th>
                            <th scope="col">Address</th>
                            <th
                                v-show="showImages"
                                scope="col"
                            >
                                Image
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="item in items.data"
                            :key="item.id"
                        >
                            <th scope="row">
                                <a
                                    href="javascript:void(0)"
                                    class="mr-2"
                                    :title="$t('flats_list_item.titles.open')"
                                >
                                    <i class="fas fa-external-link-square-alt fa-lg"></i>
                                </a>
                                <a
                                    href="javascript:void(0)"
                                    class="mr-2"
                                    :title="$t('flats_list_item.titles.add_to_bookmarks')"
                                >
                                    <i class="far fa-bookmark fa-lg"></i>
                                </a>
                                <a
                                    href="javascript:void(0)"
                                    :title="$t('flats_list_item.titles.bind')"
                                >
                                    <i class="fas fa-binoculars fa-lg"></i>
                                </a>
                            </th>
                            <th>{{ item.id }}</th>
                            <td v-if="item.published_at">{{ item.published_at }}</td>
                            <td v-else>{{ item.updated_at }}</td>
                            <td>{{ item.rooms_count || '-' }}</td>
                            <td v-if="item.floor">{{ item.floor }} / {{ item.floors_total || '-' }}</td>
                            <td v-else>-</td>
                            <td>{{ item.construction_year || '-' }}</td>
                            <td v-if="item.size">{{ item.size }} / {{ item.living_size || '-' }} / {{ item.kitchen_size || '-' }}</td>
                            <td v-else>-</td>
                            <td v-if="item.price">{{ item.price }} {{ item.price_currency || '' }}</td>
                            <td v-else>-</td>
                            <td>{{ item.walls_type || '-' }}</td>
                            <td>{{ item.balcony_type || '-' }}</td>
                            <td>{{ item.bathroom_type || '-' }}</td>
                            <td>{{ item.locality || '-' }}</td>
                            <td v-if="item.street">{{ item.street }} {{ item.house || '' }}</td>
                            <td v-else>-</td>
                            <td v-show="showImages">
                                <img
                                    class="rounded"
                                    width="150px"
                                    :src="item.images[0] || 'images/image_not_found.jpg'"
                                    :class="{ 'clickable': item.images[0] }"
                                    :title="$t('flats_list_item.titles.show_images')"
                                    @error="onImageError"
                                    @click="$emit('showImages')"
                                >
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </template>

        <pagination
            class="mb-0"
            align="center"
            :limit="1"
            :show-disabled="true"
            :data="items"
            @pagination-change-page="page = $event, loadItems()"
        >
            <span slot="prev-nav">&laquo; {{ $t('common.pagination.prev') }}</span>
	        <span slot="next-nav">{{ $t('common.pagination.next') }} &raquo;</span>
        </pagination>
    </div>
</template>

<script>
    import Http from '../../utils/Http'
    import FlatsListItem from '../FlatsListItem'

    export default {
        name: 'TheObjectsPage',

        components: {
            FlatsListItem
        },

        data () {
            return {
                type: '',
                search: '',

                viewMode: 'list',
                showMap: false,
                map: null,

                page: 1,
                perPage: 10,
                items: [],
                loading: false,
                showImages: true,

                filter: {
                    common: {
                        area: '',
                        house: '',
                        street: '',
                        country: '',
                        province: '',
                        locality: '',
                        district: '',
                        source_id: ''
                    },

                    flats: {
                        walls_type: '',
                        balcony_type: '',
                        bathroom_type: ''
                    }
                },

                sources: [],

                locations: {
                    areas: [],
                    houses: [],
                    streets: [],
                    countries: [],
                    districts: [],
                    provinces: [],
                    localities: []
                },

                options: {
                    flats: {
                        wallsTypes: [],
                        balconyTypes: [],
                        bathroomTypes: []
                    }
                }
            }
        },

        watch: {
            search () {
                this.loadItems()
            },

            filter: {
                deep: true,

                handler () {
                    this.page = 1
                    this.loadItems()
                }
            },

            ['filter.common.source_id'] () {
                this.loadOptions()
            },

            ['filter.common.country'] () {
                this.loadLocations()

                this.resetCommonFilterProps([
                    'province', 'area', 'locality', 'district', 'street', 'house'
                ])
            },

            ['filter.common.province'] () {
                this.loadLocations()

                this.resetCommonFilterProps([
                   'area', 'locality', 'district', 'street', 'house'
                ])
            },

            ['filter.common.area'] () {
                this.loadLocations()

                this.resetCommonFilterProps([
                   'locality', 'district', 'street', 'house'
                ])
            },

            ['filter.common.locality'] () {
                this.loadLocations()

                this.resetCommonFilterProps([
                   'district', 'street', 'house'
                ])
            },

            ['filter.common.district'] () {
                this.loadLocations()

                this.resetCommonFilterProps([
                   'street', 'house'
                ])
            },

            ['filter.common.street'] () {
                this.loadLocations()
                this.resetCommonFilterProps(['house'])
            }
        },

        computed: {
            types () {
                return [
                    'flats',
                    'lands',
                    'houses',
                    'commercials'
                ]
            }
        },

        created () {
            this.loadSources()
            this.type = this.types[0]

            this.loadPlacemarks()
            this.loadItems()
            this.loadOptions()
            this.loadLocations()
        },

        mounted () {
            ymaps.ready(() => {
                this.map = new ymaps.Map(
                    'map', {
                        center: [53.912291, 27.563383],
                        zoom: 12
                    }
                )
            })
        },

        methods: {
            onImageError (event) {
                event.target.classList.remove('clickable')
                event.target.src = 'images/image_not_found.jpg'
            },

            findSimilar (data) {
                console.log(123)
                if (data.prop == 'locality' || data.prop == 'street' || data.prop == 'house') {
                    this.filter.common[data.prop] = data.value
                } else {
                    this.filter[this.type][data.prop] = data.value
                }
            },

            loadPlacemarks () {
                new Http().get(`/map/placemarks?type=${this.type}`)
                    .then(({ data }) => {
                        const objectManager = new ymaps.ObjectManager()
                        objectManager.add(data)

                        this.map.geoObjects.removeAll()
                        this.map.geoObjects.add(objectManager)
                    })
            },

            loadItems () {
                this.items = {}
                this.loading = true

                let query = [`page=${this.page}`, `per_page=${this.perPage}`]

                if (this.search) {
                    query.push(`search=${this.search}`)
                }

                Object.keys(this.filter.common).forEach(key => {
                    if (this.filter.common[key]) {
                        query.push(
                            `filter[${key}]=${this.filter.common[key]}`
                        )
                    }
                })

                Object.keys(this.filter[this.type]).forEach(key => {
                    if (this.filter[this.type][key]) {
                        query.push(
                            `filter[${key}]=${this.filter[this.type][key]}`
                        )
                    }
                })

                new Http().get(`/${this.type}?${query.join('&')}`)
                    .then(({ data }) => {
                        this.items = data
                    })
                    .finally(() => {
                        this.loading = false
                    })
            },

            resetFilter () {
                this.search = ''

                Object.keys(this.filter.common).forEach(key => {
                    this.filter.common[key] = ''
                })

                Object.keys(this.filter[this.type]).forEach(key => {
                    this.filter[this.type][key] = ''
                })
            },

            resetCommonFilterProps (props) {
                for (let prop of props) {
                    this.filter.common[prop] = ''
                }
            },

            loadOptions () {
                let query = [`type=${this.type}`]

                if (this.filter.common.source_id) {
                    query.push(
                        `source_id=${this.filter.common.source_id}`
                    )
                }

                query = '?' + query.join('&')

                if (this.type == 'flats') {
                    new Http().get(`/options${query}&prop=walls_type`)
                        .then(({ data }) => {
                            this.options.flats.wallsTypes = data
                        })

                    new Http().get(`/options${query}&prop=balcony_type`)
                        .then(({ data }) => {
                            this.options.flats.balconyTypes = data
                        })

                    new Http().get(`/options${query}&prop=bathroom_type`)
                        .then(({ data }) => {
                            this.options.flats.bathroomTypes = data
                        })
                }
            },

            loadSources () {
                new Http().get('/sources').then(({ data }) => {
                    this.sources = data
                })
            },

            loadLocations () {
                this.loadAreas()
                this.loadHouses()
                this.loadStreets()
                this.loadCountries()
                this.loadDistricts()
                this.loadProvinces()
                this.loadLocalities()
            },

            loadAreas () {
                let query = [
                    `type=${this.type}`, `prop=area`
                ]

                for (let search of ['country', 'province']) {
                    if (this.filter.common[search]) {
                        query.push(
                            `search[${search}]=${this.filter.common[search]}`
                        )
                    }
                }

                new Http().get(`/locations?${query.join('&')}`)
                    .then(({ data }) => {
                        this.locations.areas = data
                    })
            },

            loadHouses () {
                let query = [
                    `type=${this.type}`, `prop=house`
                ]

                for (let search of [
                    'country', 'province', 'area', 'locality', 'district', 'street'
                ]) {
                    if (this.filter.common[search]) {
                        query.push(
                            `search[${search}]=${this.filter.common[search]}`
                        )
                    }
                }

                new Http().get(`/locations?${query.join('&')}`)
                    .then(({ data }) => {
                        this.locations.houses = data
                    })
            },

            loadStreets () {
                let query = [
                    `type=${this.type}`, `prop=street`
                ]

                for (let search of [
                    'country', 'province', 'area', 'locality', 'district'
                ]) {
                    if (this.filter.common[search]) {
                        query.push(
                            `search[${search}]=${this.filter.common[search]}`
                        )
                    }
                }

                new Http().get(`/locations?${query.join('&')}`)
                    .then(({ data }) => {
                        this.locations.streets = data
                    })
            },

            loadCountries () {
                let query = [
                    `type=${this.type}`, `prop=country`
                ]

                new Http().get(`/locations?${query.join('&')}`)
                    .then(({ data }) => {
                        this.locations.countries = data
                    })
            },

            loadDistricts () {
                let query = [
                    `type=${this.type}`, `prop=district`
                ]

                for (let search of [
                    'country', 'province', 'area', 'locality'
                ]) {
                    if (this.filter.common[search]) {
                        query.push(
                            `search[${search}]=${this.filter.common[search]}`
                        )
                    }
                }

                new Http().get(`/locations?${query.join('&')}`)
                    .then(({ data }) => {
                        this.locations.districts = data
                    })
            },

            loadProvinces () {
                let query = [
                    `type=${this.type}`, `prop=province`
                ]

                for (let search of ['country']) {
                    if (this.filter.common[search]) {
                        query.push(
                            `search[${search}]=${this.filter.common[search]}`
                        )
                    }
                }

                new Http().get(`/locations?${query.join('&')}`)
                    .then(({ data }) => {
                        this.locations.provinces = data
                    })
            },

            loadLocalities () {
                let query = [
                    `type=${this.type}`, `prop=locality`
                ]

                for (let search of [
                    'country', 'province', 'area'
                ]) {
                    if (this.filter.common[search]) {
                        query.push(
                            `search[${search}]=${this.filter.common[search]}`
                        )
                    }
                }

                new Http().get(`/locations?${query.join('&')}`)
                    .then(({ data }) => {
                        this.locations.localities = data
                    })
            }
        }
    }
</script>

<style scoped>
    #map {
        width: 100%;
        height: 600px;
    }

    .clickable {
        cursor: pointer !important;
    }
</style>
