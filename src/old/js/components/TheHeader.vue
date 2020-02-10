<template>
    <header>
        <TheHeaderNavbar/>
        <AppAlert
            v-if="alert.show"
            class="mb-0 rounded-0"
            :type="alert.type"
            :message="alert.message"
            @close="$store.commit('alert/CLOSE')"
        />
    </header>
</template>

<script>
    import { mapState } from 'vuex'

    import AppAlert from './AppAlert'
    import TheHeaderNavbar from './TheHeaderNavbar'

    export default {
        name: 'TheHeader',

        components: {
            AppAlert,
            TheHeaderNavbar
        },

        computed: {
            ...mapState({
                alert: state => state.alert
            })
        }
    }
</script>
