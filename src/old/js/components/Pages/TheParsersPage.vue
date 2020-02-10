<template>
    <div class="container">
        <div class="card shadow">
            <div class="card-header">{{ $t('parsers.title') }}</div>
            <div class="card-body text-center">
                <RouterLink
                    class="btn btn-primary"
                    :to="{ name: 'parsers.create' }"
                >
                    {{ $t('parsers.buttons.create') }}
                </RouterLink>
            </div>
            <ul class="list-group list-group-flush">
                <ParserItem
                    v-for="parser in parsers"
                    :key="parser.id"
                    :id="parser.id"
                    :name="parser.name"
                />
            </ul>
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import ParserItem from '../ParserItem'

    export default {
        name: 'TheParsersPage',

        components: {
            ParserItem
        },

        computed: {
            ...mapState({
                parsers: state => state.parsers.items
            })
        },

        created () {
            this['parsers/loadItems']()
        },

        methods: {
            ...mapActions([
                'parsers/loadItems'
            ])
        }
    }
</script>
