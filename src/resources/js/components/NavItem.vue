<template>
    <li class="nav-item" :class="{ 'active': isActive }">
        <router-link class="nav-link" :to="{ name: routeName }">
            {{ text }} <span class="sr-only" v-if="isActive">({{ $t('components.nav_item.current') }})</span>
        </router-link>
    </li>
</template>

<script>
    export default {
        name: 'nav-item',

        props: {
            text: String,
            routeName: String
        },

        computed: {
            isActive () {
                return this.$route.name.startsWith(this.routeName)
            }
        }
    }
</script>
