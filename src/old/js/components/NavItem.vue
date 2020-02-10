<template>
    <li class="nav-item">
        <RouterLink
            class="nav-link"
            :class="{ 'active': isActive }"
            :to="{ name: routeName }"
        >
            {{ text }}
        </RouterLink>
    </li>
</template>

<script>
    export default {
        name: 'NavItem',

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
