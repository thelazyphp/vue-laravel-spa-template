<template>
    <div class="container-fluid">
        <NcaMapModal
            ref="mapModal"
            :search="search"
            :filter="filter"
            :category="category"
        />

        <NcaFilterModal
            ref="filterModal"
            :options="options"
            :category="category"
            @setFilter="setFilter"
        />

        <NcaUploadModal ref="uploadModal"/>

        <NcaStatisticsModal ref="statisticsModal"/>

        <div class="card shadow">
            <div class="card-header border-bottom-0">

                <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                    <input
                        v-model="search"
                        type="search"
                        class="form-control"
                        placeholder="Начните вводить ключевые слова"
                    >
                    <select
                        v-model="category"
                        class="custom-select"
                    >
                        <option value="lands">Земельные участки</option>
                        <option value="capitals">Капитальные строения</option>
                        <option value="isolateds">Изолированные помещения</option>
                        <option value="commercials">Коммерческая недвижимость</option>
                    </select>
                    <div class="input-group-append">
                        <button
                            type="button"
                            title="Фильтр"
                            data-toggle="modal"
                            data-target="#filter-modal"
                            class="btn btn-outline-primary"
                        >
                            <i class="fas fa-filter"></i>
                        </button>
                        <button
                            type="button"
                            data-toggle="modal"
                            title="Открыть карту"
                            data-target="#map-modal"
                            class="btn btn-outline-primary"
                        >
                            <i class="fas fa-map-marked-alt"></i>
                        </button>
                        <button
                            type="button"
                            data-toggle="modal"
                            title="Статистика"
                            class="btn btn-outline-primary"
                            data-target="#statistics-modal"
                        >
                            <i class="fas fa-chart-line"></i>
                        </button>
                        <button
                            type="button"
                            data-toggle="modal"
                            title="Загрузить элементы"
                            data-target="#upload-modal"
                            class="btn btn-outline-primary"
                        >
                            <i class="fas fa-cloud-upload-alt"></i>
                        </button>
                    </div>
                </div>

                <div class="text-center text-lg-left">
                    Найдено элементов: {{ items.total || 0 }}
                </div>

            </div>

            <div class="card-body border-top p-0">

                <div
                    v-if="loading"
                    class="text-center p-3"
                >
                    <div
                        class="spinner-border text-primary"
                        role="status"
                    >
                        <span class="sr-only">Загрузка...</span>
                    </div>
                </div>
                <div
                    v-else
                    class="table-responsive"
                >
                    <AppTable
                        ref="table"
                        text-align="center"
                        vertical-align="middle"
                        style="font-size: 13px"
                        class="table-hover mb-0"
                        sort-title="Сортировать"
                        check-row-title="Выбрать"
                        check-all-rows-title="Выбрать все"
                        :check-rows="true"
                        :sort-value="sort"
                        :rows="items.data || []"
                        :row-class="tableRowClass"
                        :cols="tableCols[category]"
                        @sort="sort = $event"
                    />
                </div>

            </div>
            <div class="card-footer">
                <div class="row">

                    <div class="col-lg-6 d-flex justify-content-center justify-content-lg-start">
                        <div class="form-inline">
                            <select
                                v-model="perPage"
                                class="custom-select custom-select-sm"
                            >
                                <option value="15">15 на странице</option>
                                <option value="25">25 на странице</option>
                                <option value="50">50 на странице</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6 d-flex justify-content-center justify-content-lg-end">
                        <pagination
                            class="mt-3 mt-lg-0 mb-0"
                            size="small"
                            :limit="1"
                            :data="items"
                            @pagination-change-page="page = $event"
                        >
                            <span slot="prev-nav">&laquo; Назад</span>
	                        <span slot="next-nav">Вперед &raquo;</span>
                        </pagination>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Http from '../../utils/Http'

    import landsColumns from '../Nca/nca-lands-table-columns'
    import capitalsColumns from '../Nca/nca-capitals-table-columns'
    import isolatedsColumns from '../Nca/nca-isolateds-table-columns'
    import commercialsColumns from '../Nca/nca-commercials-table-columns'

    import AppTable from '../AppTable'
    import NcaMapModal from '../Nca/NcaMapModal'
    import NcaFilterModal from '../Nca/NcaFilterModal'
    import NcaUploadModal from '../Nca/NcaUploadModal'
    import NcaStatisticsModal from '../Nca/NcaStatisticsModal'

    export default {
        name: 'TheNcaPage',

        components: {
            AppTable,
            NcaMapModal,
            NcaFilterModal,
            NcaUploadModal,
            NcaStatisticsModal
        },

        data() {
            return {
                category: 'lands',
                search: '',
                sort: 'contract_date',
                filter: {},
                page: 1,
                perPage: 15,
                loading: false,
                items: {},
                options: {},

                tableCols: {
                    lands: landsColumns,
                    capitals: capitalsColumns,
                    isolateds: isolatedsColumns,
                    commercials: commercialsColumns
                }
            }
        },

        watch: {
            category () {
                this.page = 1
                this.filter = {}
                this.retriveItems()
                this.retriveOptions()
            },

            search () {
                this.page = 1
                this.retriveItems()
            },

            sort () {
                this.page = 1
                this.retriveItems()
            },

            page () {
                this.retriveItems()
            },

            perPage () {
                this.page = 1
                this.retriveItems()
            }
        },

        computed: {
            tableRowClass () {
                return (row) => {
                    let value = null

                    if (row.LP || row.DEL) {
                        value = 'table-secondary'
                    } else if (row.P) {
                        value = 'table-primary'
                    } else if (row.S) {
                        value = 'table-warning'
                    } else if (row.NB) {
                        value = 'table-success'
                    }

                    return value
                }
            }
        },

        created () {
            this.retriveItems()
            this.retriveOptions()
        },

        methods: {
            setFilter (data) {
                this.page = 1
                this.filter = data
                this.retriveItems()
            },

            retriveItems () {
                this.loading = true

                let query = [
                    `page=${this.page}`,
                    `per_page=${this.perPage}`
                ]

                if (this.search) {
                    query.push(`search=${this.search}`)
                }

                Object.keys(this.filter).forEach(key => {
                    if (this.filter[key]) {
                        query.push(`filter[${key}]=${this.filter[key]}`)
                    }
                })

                query.push(`sort=${this.sort}`)

                new Http().get(`/nca/${this.category}?${query.join('&')}`)
                    .then(({ data }) => this.items = data)
                    .finally(() => this.loading = false)
            },

            retriveOptions () {
                let query = [`category=${this.category}`]

                new Http().get(`/nca/options?${query.join('&')}`)
                    .then(({ data }) => this.options = data)
            }
        }
    }
</script>
