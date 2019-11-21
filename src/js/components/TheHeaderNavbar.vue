<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
        <RouterLink
            class="navbar-brand"
            :to="{ name: 'home' }"
        >
            {{ appName }}
        </RouterLink>

        <button
            type="button"
            class="navbar-toggler"
            data-toggle="collapse"
            data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            :aria-label="$t('header_navbar.buttons.toggle_navigation')"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div
            id="navbarSupportedContent"
            class="collapse navbar-collapse"
        >
            <ul class="navbar-nav mr-auto">
                <!-- -->
            </ul>

            <ul class="navbar-nav ml-auto">
                <RouterLink
                    v-if="! isAuth"
                    class="btn btn-outline-light my-2 my-lg-0"
                    :to="{ name: 'login' }"
                >
                    {{ $t('header_navbar.buttons.sign_in') }}
                </RouterLink>

                <template v-else>
                    <li class="nav-item dropdown">
                        <a
                            id="navbarDropdown"
                            href="#"
                            class="nav-link dropdown-toggle"
                            role="button"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                        >
                            {{ currentUser.name }}
                        </a>

                        <div
                            class="dropdown-menu dropdown-menu-right"
                            aria-labelledby="navbarDropdown"
                        >
                            <RouterLink
                                class="dropdown-item"
                                :to="{ name: 'profile' }"
                            >
                                {{ $t('header_navbar.buttons.profile') }}
                            </RouterLink>

                            <a
                                href="#"
                                class="dropdown-item"
                                @click="logout"
                            >
                                {{ $t('header_navbar.buttons.sign_out') }}
                            </a>
                        </div>
                    </li>
                </template>
            </ul>
        </div>
    </nav>
</template>

<script>
    import { mapActions } from 'vuex'

    export default {
        computed: {
            isAuth () {
                return this.$store.getters['auth/check']
            },

            appName () {
                return process.env.MIX_APP_NAME || 'Laravel'
            }
        },

        methods: {
            ...mapActions([
                'auth/logout'
            ]),

            logout () {
                this['auth/logout']()
                    .then(() => {
                        this.$router.push({
                            name: 'login'
                        })
                    })
                    .catch(error => {
                        console.log(error)
                        this['alert/SHOW']({
                            type: 'danger',
                            message: this.$t('errors.sign_out.error')
                        })
                    })
            }
        }
    }
</script>

<style scoped>
    .navbar-toggler {
        outline: none !important;
    }
</style>
