<template>
    <div class="page-wrapper">
        <div class="jumbotron jumbotron-fluid mb-0">
            <div class="container">
                <div class="form-group">
                    <label
                        for="category"
                        class="sr-only"
                    >
                        Категория
                    </label>

                    <select
                        id="category"
                        v-model="category"
                        class="custom-select"
                    >
                        <option
                            v-for="category in categories"
                            :key="category.value"
                            :value="category.value"
                        >
                            {{ category.name }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label
                        for="search"
                        class="sr-only"
                    >
                        Найти
                    </label>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-search-location"></i>
                            </span>
                        </div>

                        <input
                            id="search"
                            v-model="search"
                            type="search"
                            class="form-control"
                            placeholder="Начните вводить местоположение..."
                        >
                    </div>
                </div>

                <div class="card card-body">
                    <AppFilter
                        input-size='sm'
                        :show-labels="true"
                        :filter-data="filter.data[category]"
                        :filter-options="filter.options[category]"
                        :input-groups="filter.inputGroups[category]"
                    />

                    <div class="d-flex justify-content-end">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item">
                                <a
                                    href="javascript:void(0)"
                                    role="button"
                                    @click="clearFilter"
                                >
                                    Сбросить
                                </a>
                            </li>

                            <li class="list-inline-item">
                                <button
                                    type="button"
                                    class="btn btn-primary"
                                    @click="page = 1, fetchItems()"
                                >
                                    Показать
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="loading"
            class="text-center mt-3"
        >
            <div
                class="spinner-border text-primary"
                role="status"
            >
                <span class="sr-only">
                    Загрузка...
                </span>
            </div>
        </div>

        <div
            v-else
            class="table-responsive"
        >
            <AppTable
                class="table-hover mb-0"
                style="font-size: 13px"
                text-align="center"
                vertical-align="middle"
                sort-title="Сортировать"
                check-row-title="Выбрать строку"
                check-all-rows-title="Выбрать все строки"
                :check-rows="true"
                :sort-value="sort"
                :cols="table.cols[this.category]"
                :rows="table.rows"
                @sort="sort = $event"
            />
        </div>

        <pagination
            class="mt-3"
            align="center"
            :limit="1"
            :data="items"
            @pagination-change-page="page = $event"
        >
            <span slot="prev-nav">
                <i class="fas fa-angle-left"></i> Назад
            </span>

	        <span slot="next-nav">
                Вперед <i class="fas fa-angle-right"></i>
            </span>
        </pagination>
    </div>
</template>

<script>
    import Http from '../../utils/Http'

    import AppTable from '../AppTable'
    import AppFilter from '../AppFilter'

    import * as landsFilter from './nca-lands-filter'
    import * as capitalsFilter from './nca-capitals-filter'
    import * as isolatedsFilter from './nca-isolateds-filter'
    import * as commercialsFilter from './nca-commercials-filter'

    import landsCols from './nca-lands-table-columns'
    import capitalsCols from './nca-capitals-table-columns'
    import isolatedsCols from './nca-isolateds-table-columns'
    import commercialsCols from './nca-commercials-table-columns'

    export default {
        name: 'TheNcaPage',

        components: {
            AppTable,
            AppFilter
        },

        data () {
            return {
                category: 'lands',
                search: null,
                page: 1,
                perPage: 15,
                sort: '-contract_date',
                loading: false,
                items: {},

                table: {
                    rows: [],

                    cols: {
                        lands: landsCols,
                        capitals: capitalsCols,
                        isolateds: isolatedsCols,
                        commercials: commercialsCols
                    }
                },

                filter: {
                    options: {
                        lands: {},
                        capitals: {},
                        isolateds: {},
                        commercials: {}
                    },

                    data: {
                        lands: landsFilter.filter,
                        capitals: capitalsFilter.filter,
                        isolateds: isolatedsFilter.filter,
                        commercials: commercialsFilter.filter
                    },

                    inputGroups: {
                        lands: landsFilter.inputGroups,
                        capitals: capitalsFilter.inputGroups,
                        isolateds: isolatedsFilter.inputGroups,
                        commercials: commercialsFilter.inputGroups
                    }
                }
            }
        },

        computed: {
            categories () {
                return [
                    {
                        name: 'Земельные участки',
                        value: 'lands'
                    },
                    {
                        name: 'Капитальные строения',
                        value: 'capitals'
                    },
                    {
                        name: 'Изолированные помещения',
                        value: 'isolateds'
                    },
                    {
                        name: 'Коммерческая недвижимость',
                        value: 'commercials'
                    }
                ]
            },

            query () {
                let query = []

                if (this.search) {
                    query.push(
                        `search=${this.search}`
                    )
                }

                Object.keys(this.filter.data[this.category]).forEach(key => {
                    if (this.filter.data[this.category][key]) {
                        query.push(
                            `filter[${key}]=${this.filter.data[this.category][key]}`
                        )
                    }
                })

                if (this.sort) {
                    query.push(
                        `sort=${this.sort}`
                    )
                }

                if (this.page) {
                    query.push(
                        `page=${this.page}`
                    )
                }

                if (this.perPage) {
                    query.push(
                        `per_page=${this.perPage}`
                    )
                }

                return query.length ? query.join('&') : ''
            }
        },

        watch: {
            category (value) {
                this.page = 1

                this.clearFilter()
                this.fetchItems()
            },

            search () {
                this.page = 1
                this.fetchItems()
            },

            sort () {
                this.page = 1
                this.fetchItems()
            },

            page () {
                this.fetchItems()
            },

            perPage () {
                this.page = 1
                this.fetchItems()
            }
        },

        created () {
            this.fetchItems()
        },

        methods: {
            clearFilter () {
                Object.keys(this.filter.data[this.category]).forEach(key => {
                    this.filter.data[this.category][key] = null
                })
            },

            fetchItems () {
                this.loading = true

                new Http().get(`/nca/${this.category}?${this.query}`)
                    .then(({ data }) => {
                        this.fetchOptions()

                        this.loading = false
                        this.items = data
                        this.table.rows = data.data
                    })
            },

            fetchOptions () {
                new Http().get(`/nca/options?category=${this.category}`)
                    .then(({ data }) => {
                        this.filter.options[this.category] = {}

                        Object.keys(data).forEach(dataKey => {
                            this.filter.options[this.category][dataKey] = []

                            Object.keys(data[dataKey]).forEach(optionKey => {
                                this.filter.options[this.category][dataKey].push({
                                    name: data[dataKey][optionKey],
                                    value: data[dataKey][optionKey]
                                })
                            })
                        })
                    })
            }
        }
    }
</script>
