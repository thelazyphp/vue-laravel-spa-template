<template>
    <div class="main-content">
        <div class="container-fluid">
            <div class="card card-body border-0 shadow-sm">
                <div class="btn-toolbar">
                    <div class="mb-3 mx-1 input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Категория</span>
                        </div>

                        <select class="custom-select">
                            <option value="apartments">Квартиры</option>
                            <option value="lands">Участки</option>
                            <option value="houses">Дачи, коттеджи</option>
                            <option value="commercial">Коммерческая</option>
                        </select>
                    </div>

                    <div class="mb-3 mx-1 input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Тип</span>
                        </div>

                        <select class="custom-select">
                            <option value="rent">Аренда</option>
                            <option value="sell">Продажа</option>
                        </select>
                    </div>

                    <div class="mb-3 mx-1 input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">На странице</span>
                        </div>

                        <select class="custom-select">
                            <option value="15">15</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>

                    <form
                        class="mb-3 mx-1 flex-grow-1 input-group"
                        @submit.prevent
                    >
                        <input
                            type="search"
                            placeholder="Поиск"
                            class="form-control">

                        <div class="input-group-append">
                            <button
                                type="submit"
                                class="btn btn-primary">Найти</button>
                        </div>
                    </form>
                </div>

                <component
                    class="mb-3"
                    :is="filtersTags"
                    :filter="$store.state.catalog.filter"
                    @reset="$store.commit('catalog/setFilter', null), $store.dispatch('catalog/filterItems')"></component>

                <div
                    v-if="!$store.getters['catalog/isFilterClear']"
                    class="btn-toolbar flex-col flex-sm-row"
                >
                    <div class="mx-1 mb-3 btn-group">
                        <button
                            type="button"
                            class="btn btn-outline-primary"
                            @click="createCompilation"><i class="mr-3 far fa-address-card"></i>Сохранить заявку</button>
                    </div>

                    <div class="mx-1 mb-3 btn-group">
                        <button
                            type="button"
                            class="btn btn-outline-primary"
                            @click="createCompilation"><i class="mr-3 fas fa-filter"></i>Сохранить подборку</button>
                    </div>
                </div>

                <template v-if="$store.getters['catalog/getItems'].length">
                    <component
                        :is="table"
                        :items="$store.getters['catalog/getItems']"
                        :sort-value="$store.state.catalog.sort"
                        @update-sort-value="$store.commit('catalog/setSort', $event), $store.dispatch('catalog/filterItems')"></component>

                    <v-pagination
                        class="mt-3"
                        align="right"
                        :limit="1"
                        :show-disabled="true"
                        :data="$store.state.catalog.data"
                        @pagination-change-page="$store.commit('catalog/setPage', $event), $store.dispatch('catalog/fetchData')"></v-pagination>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import ApartmentsFiltersTags from '../components/catalog/apartments/ApartmentsFiltersTags'
    import ApartmentsTable from '../components/catalog/apartments/ApartmentsTable'

    export default {
        name: 'Catalog',

        components: {
            ApartmentsFiltersTags,
            ApartmentsTable,
        },

        computed: {
            filtersTags () {
                return 'ApartmentsFiltersTags'
            },

            table () {
                return 'ApartmentsTable'
            },
        },

        created () {
            document.title = `${process.env.MIX_APP_NAME} - Каталог`
            this.$store.dispatch('catalog/fetchData')
        },

        methods: {
            createCompilation () {
                let name = null

                if (name = prompt('Введите название подборки')) {
                    //
                }
            },
        },
    }
</script>

<style scoped>
    .main-content {
        padding-top: 15px;
        padding-bottom: 15px;
    }
</style>
