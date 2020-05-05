<template>
    <div class="page-wrap">
        <div class="row no-gutters">
            <div class="col-md-4 col-lg-3 col-xl-2 sidebar"></div>

            <main
                role="main"
                class="col-md-8 col-lg-9 col-xl-10"
            >
                <div class="container-fluid">
                    <component
                        class="py-3"
                        :is="filtersFormComponentName"
                        @update-filter-value="setFilter($event)"></component>

                    <template v-if="$store.state.catalog.data">
                        <component
                            :is="tableComponentName"
                            :sort-value="$store.state.catalog.sort"
                            :items="$store.state.catalog.data.data"
                            @update-sort-value="setSort($event)"></component>

                        <v-pagination
                            class="mt-3"
                            align="right"
                            :limit="1"
                            :show-disabled="true"
                            :data="$store.state.catalog.data"
                            @pagination-change-page="setPage($event)"></v-pagination>
                    </template>
                </div>
            </main>
        </div>
    </div>
</template>

<script>
    import store from '../store'

    import ApartmentsTable from '../components/catalog/apartments/ApartmentsTable'
    import ApartmentsFiltersForm from '../components/catalog/apartments/ApartmentsFiltersForm'

    export default {
        name: 'Catalog',
        components: { ApartmentsTable, ApartmentsFiltersForm },

        computed: {
            tableComponentName () {
                //

                return 'ApartmentsTable'
            },

            filtersFormComponentName () {
                //

                return 'ApartmentsFiltersForm'
            },
        },

        created () {
            this.fetchData()
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
                this.$store.commit('catalog/setFilter', null)
                this.$store.commit('catalog/setSort', null)
                this.$store.commit('catalog/setPage', 1)
                window.scrollTo({ top: 0 })
                return this.fetchData()
            },

            setFilter (filter) {
                this.$store.commit('catalog/setFilter', filter)
                this.$store.commit('catalog/setPage', 1)
                window.scrollTo({ top: 0 })
                return this.fetchData()
            },

            setSort (sort) {
                this.$store.commit('catalog/setSort', sort)
                this.$store.commit('catalog/setPage', 1)
                window.scrollTo({ top: 0 })
                return this.fetchData()
            },

            setPage (page) {
                this.$store.commit('catalog/setPage', page)
                window.scrollTo({ top: 0 })
                return this.fetchData()
            },

            setPerPage (perPage) {
                this.$store.commit('catalog/setPerPage', perPage)
                this.$store.commit('catalog/setPage', 1)
                window.scrollTo({ top: 0 })
                return this.fetchData()
            },

            fetchData () {
                return this.$store.dispatch('catalog/fetchData', { category: 'apartments', fetchFavorited: this.$store.state.catalog.table == 'favorited' })
            },
        },
    }
</script>

<style scoped>
    .sidebar {
        padding-top: 1rem;
        padding-bottom: 1rem;
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
