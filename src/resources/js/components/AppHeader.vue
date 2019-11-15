<template>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
            <router-link class="navbar-brand" :to="{ name: 'home' }">{{ appName }}</router-link>

            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" :aria-label="$t('components.header.toggle_navigation')">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <template v-if="currentUser.id">

                    </template>
                </ul>

                <ul class="navbar-nav ml-auto">
                    <router-link class="btn btn-outline-light my-2 my-lg-0" v-if="! currentUser.id" :to="{ name: 'login' }">{{ $t('components.header.sign_in') }}</router-link>

                    <template v-else>
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ currentUser.name }}</a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <router-link class="dropdown-item" :to="{ name: 'profile' }">{{ $t('components.header.profile') }}</router-link>
                                <a href="#" class="dropdown-item" @click="logout">{{ $t('components.header.sign_out') }}</a>
                            </div>
                        </li>
                    </template>
                </ul>
            </div>
        </nav>
    </header>
</template>

<script>
    import NavItem from './NavItem'
    import * as AuthService from '../services/auth.service'

    export default {
        name: 'app-header',
        components: { NavItem },

        methods: {
            logout () {
                AuthService.makeLogout()
                    .then(() => this.$router.push({ name: 'login' }))
                    .catch(error => {
                        console.log(error)
                        this.$store.commit('alert/SHOW', {
                            type: 'danger',
                            message: this.$t('errors.sign_out_error')
                        })
                    })
            }
        },

        computed: {
            appName () {
                return process.env.MIX_APP_NAME || 'Laravel'
            }
        }
    }
</script>

<style scoped>
    .navbar-toggler {
        outline: none !important;
    }
</style>
