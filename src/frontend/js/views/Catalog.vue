<template>
    <div class="main-content">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light">
                    <li class="breadcrumb-item">
                        <router-link :to="{ name: 'home' }">Главная</router-link>
                    </li>

                    <li
                        class="breadcrumb-item active"
                        aria-current="page">Каталог</li>
                </ol>
            </nav>

            <div class="card card-body border-0 shadow-sm">
                <div
                    v-if="!$store.getters['catalog/isFilterClear']"
                    class="mb-3 btn-toolbar"
                >
                    <div class="btn-group">
                        <button
                            type="button"
                            class="btn btn-danger"
                            @click="createCompilation"><i class="mr-3 fas fa-filter"></i>Сохранить подборку</button>
                    </div>
                </div>

                <template v-if="$store.getters['catalog/getItems'].length">
                    <component
                        :is="table"
                        :items="$store.getters['catalog/getItems']"
                        :sort-value="$store.state.catalog.sort"
                        @update-sort-value="$store.dispatch('catalog/sortItems', $event)"></component>

                    <v-pagination
                        class="mt-3"
                        align="right"
                        :limit="1"
                        :show-disabled="true"
                        :data="$store.state.catalog.data"
                        @pagination-change-page="$store.dispatch('catalog/changeItemsPage', $event)"></v-pagination>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import ApartmentsTable from '../components/catalog/apartments/ApartmentsTable'

    export default {
        name: 'Catalog',

        components: {
            ApartmentsTable,
        },

        computed: {
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
