<template>
    <div class="page-wrapper">
        <apartments-filters-modal
            v-if="$store.state.catalog.filter"
            :filter-value="$store.state.catalog.filter"
            :filter-props="$store.state.catalog.data.meta.filter_props"
            :filter-options="$store.state.catalog.data.meta.filter_options"
            @update-filter-value="setFilter"></apartments-filters-modal>

        <div class="row no-gutters">
            <div class="col-md-4 col-lg-3 col-xl-2 sidebar">
                <!-- -->
            </div>

            <main
                class="col-md-8 col-lg-9 col-xl-10"
                role="main"
            >
                <div class="py-3 container-fluid">
                    <button
                        type="button"
                        class="btn btn-primary"
                        data-toggle="modal"
                        data-target="#filtersModal"><i class="fas mr-2 fa-sliders-h"></i> Фильтры</button>
                </div>

                <apartments-table
                    v-if="$store.state.catalog.data"
                    :sort-value="$store.state.catalog.sort"
                    :items="$store.state.catalog.data.data"
                    @update-sort-value="setSort"></apartments-table>

                <div
                    v-if="$store.state.catalog.data"
                    class="container-fluid"
                >
                    <v-pagination
                        class="mt-3"
                        align="right"
                        :limit="1"
                        :show-disabled="true"
                        :data="$store.state.catalog.data"
                        @pagination-change-page="setPage"></v-pagination>
                </div>
            </main>
        </div>
    </div>
</template>

<script>
    import store from '../store'

    import ApartmentsTable from '../components/catalog/apartments/ApartmentsTable'
    import ApartmentsFiltersModal from '../components/catalog/apartments/ApartmentsFiltersModal'

    export default {
        name: 'Catalog',

        components: {
            ApartmentsTable,
            ApartmentsFiltersModal,
        },

        created () {
            this.fetchData()
                .then(response => {
                    if (!this.$store.state.catalog.filter) {
                        this.$store.commit(
                            'catalog/setFilter',

                            Object.assign(
                                {}, this.$store.state.catalog.data.meta.filter_props
                            )
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
                    default:
                        next('*')
                }

                next()
            }
        },

        methods: {
            fetchData () {
                return this.$store.dispatch(
                    'catalog/fetchData',
                    this.$route.params.category
                )
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
            }
        }
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
