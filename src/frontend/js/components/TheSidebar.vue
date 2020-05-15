<template>
    <div class="sidebar-content">
        <ul class="nav flex-column">
            <li class="nav-item">
                <router-link
                    :to="{ name: 'home' }"
                    :class="['nav-link', { active: isLinkActive('home') }]"><i class="mr-3 fas fa-home"></i>Главная</router-link>
            </li>

            <li class="nav-item">
                <router-link
                    :to="{ name: 'catalog' }"
                    :class="['nav-link', { active: isLinkActive('catalog') }]"><i class="mr-3 fas fa-shopping-bag"></i>Каталог</router-link>
            </li>

            <li class="nav-item">
                <router-link
                    :to="{ name: 'createClientRequest' }"
                    :class="['nav-link', { active: isLinkActive('createClientRequest') }]"><i class="mr-3 far fa-address-card"></i>Заявки</router-link>
            </li>

            <li class="nav-item">
                <router-link
                    :to="{ name: 'clients' }"
                    :class="['nav-link', { active: isLinkActive('clients') }]"><i class="mr-3 fas fa-phone-alt"></i>Клиенты</router-link>
            </li>

            <li class="nav-item">
                <router-link
                    :to="{ name: 'compilations' }"
                    :class="['nav-link', { active: isLinkActive('compilations') }]"><i class="mr-3 fas fa-filter"></i>Подборки</router-link>
            </li>

            <li class="nav-item">
                <router-link
                    :to="{ name: 'favorites' }"
                    :class="['nav-link', { active: isLinkActive('favorites') }]"><i class="mr-3 fas fa-heart"></i>Избранные</router-link>
            </li>
        </ul>

        <template v-if="$route.name == 'catalog'">
            <hr>

            <component
                :is="catalogFiltersForm"
                :value="$store.state.catalog.filter"
                :options="$store.getters['catalog/getFilterOptions']"
                @reset="$store.dispatch('catalog/filterItems')"
                @submit="$store.dispatch('catalog/filterItems')"
                @input="$store.commit('catalog/setFilter', $event)"></component>
        </template>
    </div>
</template>

<script>
    import CatalogApartmentsFiltersForm from '../components/catalog/apartments/ApartmentsFiltersForm'

    export default {
        name: 'TheSidebar',
        components: { CatalogApartmentsFiltersForm },

        computed: {
            catalogFiltersForm () {
                return 'CatalogApartmentsFiltersForm'
            },
        },

        methods: {
            isLinkActive (routeName) {
                return this.$route.name == routeName
            },
        },
    }
</script>

<style scoped>
    .sidebar-content {
        padding: 15px;
    }

    .nav-link {
        /* theme: muted */
        color: #6c757d !important;
    }

    .nav-link:hover,
    .nav-link.active {
        /* theme: primary */
        color: #536de6 !important;
    }
</style>
