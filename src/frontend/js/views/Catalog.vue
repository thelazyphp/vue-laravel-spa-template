<template>
    <div class="page-wrapper">
        <component
            :is="filtersModalComponentName"
            v-if="$store.state.catalog.filter"
            :sources="$store.state.catalog.sources"
            :filter-value="$store.state.catalog.filter"
            :filter-props="$store.state.catalog.filterProps"
            :filter-options="$store.state.catalog.filterOptions" @update-filter-value="setFilter"></component>

        <div class="row no-gutters">
            <div class="col-md-4 col-lg-3 col-xl-2 sidebar">
                <ul class="nav nav-pills p-3 flex-column">
                    <li class="nav-item">
                        <a
                            href="javascript:void(0)"
                            :class="['nav-link d-flex align-items-center justify-content-between', { active: $store.state.catalog.table == 'db' }]"
                            @click="setTable('db')"
                        >
                            <span><i class="fas mr-2 fa-database"></i> База данных</span>
                            <span :class="['badge badge-pill', $store.state.catalog.table == 'db' ? 'badge-light' : 'badge-primary']">{{ $store.state.catalog.total }}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            href="javascript:void(0)"
                            :class="['nav-link d-flex align-items-center justify-content-between', { active: $store.state.catalog.table == 'favorited' }]"
                            @click="setTable('favorited')"
                        >
                            <span><i class="fas mr-2 fa-star"></i> Избранные</span>
                            <span :class="['badge badge-pill', $store.state.catalog.table == 'favorited' ? 'badge-light' : 'badge-primary']">{{ $store.state.catalog.favoritedTotal }}</span>
                        </a>
                    </li>
                </ul>
            </div>

            <main
                class="col-md-8 col-lg-9 col-xl-10"
                role="main"
            >
                <div class="py-3 container-fluid">
                    <div
                        class="btn-toolbar"
                        role="toolbar"
                    >
                        <form class="mr-2 mb-3 flex-grow-1 input-group">
                            <input
                                type="search"
                                placeholder="Поиск"
                                class="form-control">

                            <div class="input-group-append">
                                <button
                                    type="submit"
                                    title="Найти"
                                    class="btn btn-primary"
                                >
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>

                        <div
                            class="mr-2 btn-group"
                            role="group"
                        >
                            <button type="button" :class="['btn mb-3 btn-outline-primary', { active: sellType == 'rent' }]" @click="sellType = 'rent'">Аренда</button>
                            <button type="button" :class="['btn mb-3 btn-outline-primary', { active: sellType == 'sell' }]" @click="sellType = 'sell'">Продажа</button>
                        </div>

                        <div
                            class="mr-2 btn-group"
                            role="group"
                        >
                            <button
                                type="button"
                                class="btn mb-3 btn-primary"
                                data-toggle="modal"
                                data-target="#filtersModal"
                            >
                                <i class="fas mr-2 fa-sliders-h"></i> Фильтры</button>
                        </div>

                        <div
                            class="mr-2 btn-group"
                            role="group"
                        >
                            <button
                                type="button"
                                class="btn mb-3 btn-primary dropdown-toggle"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">С отмеченными</button>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a
                                    href="javascript:void(0)"
                                    class="dropdown-item"
                                >
                                    <i class="far mr-2 fa-star"></i> Удалить из избранных</a>

                                <a
                                    href="javascript:void(0)"
                                    class="dropdown-item"
                                >
                                    <i class="fas mr-2 fa-star"></i> Добавить в избранные</a>
                            </div>
                        </div>

                        <div
                            v-if="$store.state.catalog.table == 'db'"
                            class="mr-2 btn-group"
                            role="group"
                        >
                            <button
                                type="button"
                                class="btn mb-3 btn-primary"
                            >
                                <i class="fas fa-filter"></i><i class="fas mr-2 fa-xs fa-plus"></i> Сохранить заявку</button>
                        </div>
                    </div>
                </div>

                <component
                    :is="tableComponentName"
                    v-if="$store.state.catalog.data"
                    :sort-value="$store.state.catalog.sort"
                    :items="$store.state.catalog.data.data" @update-sort-value="setSort"></component>

                <div
                    v-if="$store.state.catalog.data"
                    class="container-fluid"
                >
                    <v-pagination
                        class="mt-3"
                        align="right"
                        :limit="1"
                        :show-disabled="true"
                        :data="$store.state.catalog.data" @pagination-change-page="setPage"></v-pagination>
                </div>
            </main>
        </div>
    </div>
</template>

<script>
    import store from '../store'

    import ApartmentsFiltersModal from '../components/catalog/apartments/ApartmentsFiltersModal'
    import ApartmentsTable from '../components/catalog/apartments/ApartmentsTable'

    export default {
        name: 'Catalog',

        components: {
            ApartmentsFiltersModal,
            ApartmentsTable,
        },

        data () {
            return {
                sellType: 'sell',
            }
        },

        computed: {
            filtersModalComponentName () {
                switch (this.$route.params.category) {
                    case 'apartments':
                        return 'ApartmentsFiltersModal'
                }
            },

            tableComponentName () {
                switch (this.$route.params.category) {
                    case 'apartments':
                        return 'ApartmentsTable'
                }
            },
        },

        created () {
            this.fetchData()
                .then(() => {
                    if (this.$store.state.catalog.sources === null) {
                        this.$store.commit(
                            'catalog/setSources',
                            JSON.parse(JSON.stringify(this.$store.state.catalog.data.included.sources))
                        )
                    }

                    if (this.$store.state.catalog.filter === null) {
                        this.$store.commit(
                            'catalog/setFilter',
                            JSON.parse(JSON.stringify(this.$store.state.catalog.data.meta.filter_props))
                        )
                    }

                    if (this.$store.state.catalog.total === null) {
                        this.$store.commit(
                            'catalog/setTotal',
                            this.$store.state.catalog.data.meta.total
                        )
                    }

                    if (this.$store.state.catalog.favoritedTotal === null) {
                        this.$store.commit(
                            'catalog/setFavoritedTotal',
                            this.$store.state.catalog.data.meta.favorited_total
                        )
                    }

                    if (this.$store.state.catalog.filterProps === null) {
                        this.$store.commit(
                            'catalog/setFilterProps',
                            JSON.parse(JSON.stringify(this.$store.state.catalog.data.meta.filter_props))
                        )
                    }

                    if (this.$store.state.catalog.filterOptions === null) {
                        this.$store.commit(
                            'catalog/setFilterOptions',
                            JSON.parse(JSON.stringify(this.$store.state.catalog.data.meta.filter_options))
                        )
                    }
                })
        },

        beforeRouteEnter (to, from, next) {
            if (!store.getters['auth/isAuth']) {
                next('/sign-in')
            } else {
                switch (to.params.category) {
                    case 'apartments':
                        document.title = `${process.env.MIX_APP_NAME} - Каталог. Квартиры`
                        break
                    case 'lands':
                        document.title = `${process.env.MIX_APP_NAME} - Каталог. Земельные участки`
                        break
                    case 'houses':
                        document.title = `${process.env.MIX_APP_NAME} - Каталог. Дома, дачи, коттеджи`
                        break
                    case 'commercial':
                        document.title = `${process.env.MIX_APP_NAME} - Каталог. Коммерческая недвижимость`
                        break
                    default:
                        next('*')
                }

                next()
            }
        },

        methods: {
            setTable (table) {
                this.$store.commit('catalog/setTable', table)

                this.$store.commit(
                    'catalog/setFilter',
                    this.$store.state.catalog.filterProps
                )

                this.$store.commit('catalog/setPage', 1)
                return this.fetchData()
            },

            setSearch (search) {
                this.$store.commit('catalog/setSearch', search)
                this.$store.commit('catalog/setPage', 1)
                return this.fetchData()
            },

            setFilter (filter) {
                this.$store.commit('catalog/setFilter', filter)
                this.$store.commit('catalog/setPage', 1)
                return this.fetchData()
            },

            setSort (sort) {
                this.$store.commit('catalog/setSort', sort)
                this.$store.commit('catalog/setPage', 1)
                return this.fetchData()
            },

            setPage (page) {
                this.$store.commit('catalog/setPage', page)
                return this.fetchData()
            },

            setPerPage (perPage) {
                this.$store.commit('catalog/setPerPage', perPage)
                this.$store.commit('catalog/setPage', 1)
                return this.fetchData()
            },

            fetchData () {
                return this.$store.dispatch('catalog/fetchData', {
                    category: this.$route.params.category,
                    fetchFavorited: this.$store.state.catalog.table == 'favorited',
                })
            },
        },
    }
</script>

<style scoped>
    .sidebar {
        border-bottom: 1px solid #dee2e6;
    }

    @media (min-width: 768px) {
        .sidebar {
            position: sticky;
            top: 55.22px;
            height: calc(100vh - 55.22px);
            border-bottom: none;
            border-right: 1px solid #dee2e6;
        }
    }
</style>
