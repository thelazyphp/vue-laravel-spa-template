<template>
    <div class="container">
        <div class="card shadow mb-3">
            <div class="card-header">{{ $t('sources.title') }}</div>
            <div class="card-body text-center">
                <RouterLink
                    class="btn btn-primary"
                    :to="{ name: 'sources.create' }"
                >
                    {{ $t('sources.buttons.create') }}
                </RouterLink>
            </div>
            <ul class="list-group list-group-flush">
                <SourceItem
                    v-for="source in sources"
                    :key="source.id"
                    :id="source.id"
                    :name="source.name"
                />
            </ul>
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import SourceItem from '../SourceItem'

    export default {
        name: 'TheSourcesPage',

        components: {
            SourceItem
        },

        computed: {
            ...mapState({
                sources: state => state.sources.items
            })
        },

        created () {
            this['sources/loadItems']()
        },

        methods: {
            ...mapActions([
                'sources/loadItems'
            ])
        }
    }
</script>
