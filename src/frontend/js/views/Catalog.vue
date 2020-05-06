<template>
    <div class="page-wrap">
        <div class="row no-gutters">
            <div class="col-md-4 col-lg-3 col-xl-2 sidebar">
                <div class="container-fluid">
                    <div class="mb-3 text-center">
                        <a href="javascript:void(0)">
                            <img
                                src="/realty/images/avatars/default/avatar_default_1.png"
                                alt=""
                                class="avatar"
                                :title="$store.state.users.current.first_name + ' ' + $store.state.users.current.last_name">
                        </a>
                    </div>
                </div>

                <div class="nav flex-column">
                    <div class="nav-item">
                        <a
                            href="javascript:void(0)"
                            :class="['nav-link text-muted d-flex align-items-center justify-content-between', { active: $store.state.catalog.table == 'db' }]"
                            @click="setTable('db')"
                        >
                            <span><i class="mr-2 fas fa-database"></i>В базе</span>
                            <span class="badge badge-pill badge-primary">{{ $store.state.catalog.total || 0 }}</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a
                            href="javascript:void(0)"
                            :class="['nav-link text-muted d-flex align-items-center justify-content-between', { active: $store.state.catalog.table == 'favorited' }]"
                            @click="setTable('favorited')"
                        >
                            <span><i class="mr-2 fas fa-heart"></i>В избранных</span>
                            <span class="badge badge-pill badge-primary">{{ $store.state.catalog.favoritedTotal || 0 }}</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a
                            href="javascript:void(0)"
                            :class="['nav-link text-muted d-flex align-items-center justify-content-between', { active: $store.state.catalog.table == 'nop1' }]"
                            @click="setTable('nop1')"
                        >
                            <span><i class="mr-2 fas fa-filter"></i>Заявки</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a
                            href="javascript:void(0)"
                            :class="['nav-link text-muted d-flex align-items-center justify-content-between', { active: $store.state.catalog.table == 'nop2' }]"
                            @click="setTable('nop2')"
                        >
                            <span><i class="mr-2 fas fa-address-card"></i>Клиенты</span>
                        </a>
                    </div>
                </div>
            </div>

            <main
                role="main"
                class="col-md-8 col-lg-9 col-xl-10"
            >
                <div class="container-fluid">
                    <component
                        class="py-3"
                        :is="filtersFormComponentName"
                        :filter-value="$store.state.catalog.filter"
                        @update-filter-value="setFilter($event)"></component>

                    <div class="btn-toolbar mb-3 justify-content-end">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">На странице</span>
                            </div>

                            <select
                                class="custom-select"
                                :value="$store.state.catalog.perPage"
                                @change="setPerPage($event.target.value)"
                            >
                                <option
                                    v-for="number in [15, 25, 50, 100]"
                                    :key="number"
                                    :value="number">{{ number }}</option>
                            </select>
                        </div>
                    </div>

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
                .then(() => {
                    if (this.$store.state.catalog.total === null) {
                        this.$store.commit(
                            'catalog/setTotal', this.$store.state.catalog.data.meta.total
                        )
                    }

                    if (this.$store.state.catalog.favoritedTotal === null) {
                        this.$store.commit(
                            'catalog/setFavoritedTotal', this.$store.state.catalog.data.meta.favorited_total
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
                this.$store.commit('catalog/setFilter', null)
                this.$store.commit('catalog/setSort', null)
                this.$store.commit('catalog/setPage', 1)
                return this.fetchData()
            },

            setSearch (search) {
                search = search === '' ? null : search
                this.$store.commit('catalog/setSearch', search)
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

            search () {
                this.$store.commit('catalog/setPage', 1)
                return this.fetchData()
            },

            fetchData () {
                window.scrollTo({ top: 0 })
                return this.$store.dispatch('catalog/fetchData', { category: 'apartments', fetchFavorited: this.$store.state.catalog.table == 'favorited' })
            },
        },
    }
</script>

<style scoped>
    .avatar {
        display: inline-block;
        width: 64px;
        height: 64px;
        border-radius: 50%;
    }

    .sidebar {
        padding-top: 1rem;
        padding-bottom: 1rem;
        background-color: #f7f6f0;
        border-bottom: 1px solid #dee2e6;
    }

    .sidebar .nav-link:hover,
             .nav-link.active {
        /* primary */
        color: #3490dc !important;
    }

    @media (min-width: 768px) {
        .sidebar {
            position: sticky;
            top: 55.22px;
            height: calc(100vh - 55.22px);
            overflow-x: hidden;
            overflow-y: auto;
            border-bottom: none;
            border-right: 1px solid #dee2e6;
        }
    }

    .input-group-text {
        background-color: #f7f6f0;
    }

    .input-group-prepend .input-group-text {
        width: 125px;
        min-width: 125px;
    }
</style>
