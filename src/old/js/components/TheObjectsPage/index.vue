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
                
                <div class="form-group">
                    <div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-outline-primary active" @click="viewMode = 'table'">
                            <input type="radio" name="options" id="option1" checked> <i class="fas fa-table"></i>
                        </label>
                        <label class="btn btn-outline-primary" @click="viewMode = 'cards'">
                            <input type="radio" name="options" id="option2"> <i class="fas fa-th"></i>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input
                            id="show-images"
                            v-model="showImages"
                            type="checkbox"
                            class="custom-control-input"
                        >

                        <label
                            for="show-images"
                            class="custom-control-label"
                        >
                            Показывать фото
                        </label>
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
            v-else-if="viewMode == 'table'"
            key="table-view-mode"
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
                :cols="table.cols[category]"
                :rows="table.rows"
                @sort="sort = $event"
            />
        </div>

        <div
            v-else-if="viewMode == 'cards'"
            key="cards-view-mode"
            class="container mt-3"
        >
            <div class="row">
                <div
                    v-for="item in items.data"
                    :key="item.id"
                    class="col-lg-4 mb-3"
                >
                    <FlatCard
                        :item="item"
                        :show-image="showImages"
                    />
                </div>
            </div>
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
    import FlatCard from './components/FlatCard'

    import flatsCols from './flats-table-columns'

    export default {
        name: 'TheObjectsPage',

        components: {
            AppTable,
            FlatCard
        },

        data () {
            return {
                category: 'flats',
                search: null,
                sort: '-updated_at',
                page: 1,
                perPage: 15,
                showImages: true,
                viewMode: 'table',
                loading: false,
                items: {},

                table: {
                    rows: [],

                    cols: {
                        flats: flatsCols
                    }
                }
            }
        },

        watch: {
            sort () {
                this.page = 1
                this.fetchItems()
            },

            page () {
                this.fetchItems()
            },

            showImages (value) {
                this.table.cols[this.category][0].display = value
            }
        },

        computed: {
            categories () {
                return [
                    {
                        name: 'Квартиры',
                        value: 'flats'
                    },
                    {
                        name: 'Земельные участки',
                        value: 'lands'
                    },
                    {
                        name: 'Дома, дачи, коттеджи',
                        value: 'houses'
                    },
                    {
                        name: 'Коммерческая недвижимость',
                        value: 'commercials'
                    }
                ]
            },

            query () {
                let query = []

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

        created () {
            this.fetchItems()
        },

        methods: {
            fetchItems () {
                this.loading = true

                new Http().get(`/${this.category}?${this.query}`)
                    .then(({ data }) => {
                        this.loading = false
                        this.items = data
                        this.table.rows = data.data
                    })
            }
        }
    }
</script>
