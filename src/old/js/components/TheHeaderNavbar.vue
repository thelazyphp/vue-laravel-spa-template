<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
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
            data-target="#navbar-collapse"
            aria-controls="navbar-collapse"
            aria-expanded="false"
            :aria-label="$t('header_navbar.buttons.toggle_navigation')"
        >
            <span class="navbar-toggler-icon"></span>
            <!-- <span class="badge badge-pill badge-danger">4</span> -->
        </button>
        <div
            id="navbar-collapse"
            class="collapse navbar-collapse"
        >
            <ul class="navbar-nav mr-auto">
                <template v-if="isAuth">
                    <NavbarItem
                        route-name="nca"
                        text="НКА"
                    />
                    <NavbarItem
                        route-name="objects"
                        text="Объекты"
                    />
                </template>
                <template v-if="isAuth && currentUser.role == 'developer'">
                    <NavbarItem
                        route-name="sources"
                        :text="$t('header_navbar.links.sources')"
                    />
                    <NavbarItem
                        route-name="parsers"
                        :text="$t('header_navbar.links.parsers')"
                    />
                </template>
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
                            id="notifications-dropdown"
                            href="javascript:void(0)"
                            class="nav-link"
                            role="button"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                        >
                            <i class="fas fa-bell"></i>
                            <!-- <span class="badge badge-pill badge-danger">1</span> -->
                        </a>
                        <div
                            class="dropdown-menu dropdown-menu-right p-3"
                            aria-labelledby="notifications-dropdown"
                        >
                            <div class="text-center text-muted text-nowrap">Нет новых уведомлений</div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a
                            id="messages-dropdown"
                            href="javascript:void(0)"
                            class="nav-link"
                            role="button"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                        >
                            <i class="far fa-envelope"></i>
                            <!-- <span class="badge badge-pill badge-danger">1</span> -->
                        </a>
                        <div
                            class="dropdown-menu dropdown-menu-right p-3"
                            aria-labelledby="messages-dropdown"
                        >
                            <div class="text-center text-muted text-nowrap">Нет новых сообщений</div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a
                            id="current-user-dropdown"
                            href="javascript:void(0)"
                            class="nav-link"
                            role="button"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                        >
                            <i class="fas fa-user"></i>
                            <!-- {{ currentUser.first_name }} {{ currentUser.last_name }} -->
                        </a>
                        <div
                            class="dropdown-menu dropdown-menu-right"
                            aria-labelledby="current-user-dropdown"
                        >
                            <RouterLink
                                class="dropdown-item"
                                :to="{ name: 'profile' }"
                            >
                                {{ $t('header_navbar.links.profile') }}
                            </RouterLink>
                            <a
                                href="javascript:void(0)"
                                class="dropdown-item"
                                @click="logout"
                            >
                                {{ $t('header_navbar.links.sign_out') }}
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
    import NavbarItem from './NavbarItem'

    export default {
        name: 'TheHeaderNavbar',

        components: {
            NavbarItem
        },

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
