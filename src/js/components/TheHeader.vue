<template>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <router-link
                class="navbar-brand"
                to="/"
            >
                {{ appName }}
            </router-link>

            <button
                class="navbar-toggler"
                type="button"
                data-toggle="collapse"
                data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div
                id="navbarSupportedContent"
                class="collapse navbar-collapse"
            >
                <ul
                    v-if="isAuth"
                    class="navbar-nav mr-auto"
                >
                </ul>

                <ul
                    v-if="isAuth"
                    key="is-auth"
                    class="navbar-nav ml-auto"
                >
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="javascript:void(0)"
                            @click="onSignOut"
                        >
                            Выйти
                        </a>
                    </li>
                </ul>

                <ul
                    v-else
                    key="is-not-auth"
                    class="navbar-nav ml-auto"
                >
                    <li class="nav-item">
                        <router-link
                            class="nav-link"
                            to="/login"
                        >
                            Войти
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link
                            class="nav-link"
                            to="/register"
                        >
                            Регистрация
                        </router-link>
                    </li>
                </ul>
            </div>
        </nav>

        <AppAlert
            v-if="alert.show"
            class="rounded-0 mb-0"
            :type="alert.type"
            :message="alert.message"
            @close="onAlertClose"
        />
    </header>
</template>

<script>
    import { mapState, mapGetters, mapMutations, mapActions } from 'vuex'
    import AppAlert from './AppAlert'

    export default {
        name: 'TheHeader',

        components: {
            AppAlert
        },

        computed: {
            ...mapState({
                alert: state => state.alert
            }),

            ...mapGetters([
                'auth/isAuth'
            ]),

            isAuth () {
                return this['auth/isAuth']
            },

            appName () {
                return process.env.MIX_APP_NAME
            }
        },

        methods: {
            ...mapMutations([
                'alert/close'
            ]),

            ...mapActions([
                'auth/signOut'
            ]),

            onAlertClose () {
                this['alert/close']()
            },

            onSignOut () {
                this['auth/signOut']()
                    .then(() => this.$router.push('/login'))
            }
        }
    }
</script>
