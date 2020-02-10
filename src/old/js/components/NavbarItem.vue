<template>
    <li
        class="nav-item"
        :class="{ 'active': isActive }"
    >
        <RouterLink
            class="nav-link"
            :to="{ name: routeName }"
        >
            {{ text }}
            <span
                v-if="isActive"
                class="sr-only"
            >
                ({{ $t('navbar_item.current') }})
            </span>
        </RouterLink>
    </li>
</template>

<script>
    export default {
        name: 'NavbarItem',

        props: {
            text: {
                type: String,
                required: true
            },

            routeName: {
                type: String,
                required: true
            }
        },

        computed: {
            isActive () {
                return this.$route.name && this.$route.name.startsWith(this.routeName)
            }
        }
    }
</script>
