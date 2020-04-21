<template>
    <div class="page-wrapper">
        <div class="row no-gutters">
            <div class="col-md-4 col-lg-3 col-xl-2 sidebar">
                <!-- -->
            </div>

            <main
                class="col-md-8 col-lg-9 col-xl-10"
                role="main"
            >
                <apartments-table
                    v-if="$store.state.catalog.data"
                    :items="$store.state.catalog.data.data"></apartments-table>
            </main>
        </div>
    </div>
</template>

<script>
    import store from '../store'
    import ApartmentsTable from '../components/catalog/apartments/ApartmentsTable'

    export default {
        name: 'Catalog',
        components: { ApartmentsTable },

        created () {
            this.$store.dispatch(
                'catalog/fetchData', this.$route.params.category
            )
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
