<template>
    <div
        id="filter-modal"
        class="modal fade"
        tabindex="-1"
        role="dialog"
        aria-labelledby="filter-modal-label"
        aria-hidden="true"
    >
        <div
            class="modal-dialog modal-xl"
            role="document"
        >
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5
                        id="filter-modal-label"
                        class="modal-title lead"
                    >
                        {{ $t('nca.filter.title') }}
                    </h5>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        :aria-label="$t('nca.filter.close')"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <Component
                        :is="filterComponent"
                        ref="filter"
                        :options="options"
                    />
                </div>
                <div class="modal-footer border-top-0">
                    <button
                        type="button"
                        class="btn btn-sm btn-secondary"
                        @click="$refs.filter.resetFilter()"
                    >
                        {{ $t('nca.filter.buttons.reset') }}
                    </button>
                    <button
                        type="button"
                        class="btn btn-sm btn-primary"
                        data-dismiss="modal"
                        @click="$emit('setFilter', $refs.filter.filter)"
                    >
                        {{ $t('nca.filter.buttons.filter') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import NcaLandsFilter from './NcaLandsFilter'
    import NcaCapitalsFilter from './NcaCapitalsFilter'
    import NcaIsolatedsFilter from './NcaIsolatedsFilter'
    import NcaCommercialsFilter from './NcaCommercialsFilter'

    export default {
        name: 'NcaFilterModal',

        components: {
            NcaLandsFilter,
            NcaCapitalsFilter,
            NcaIsolatedsFilter,
            NcaCommercialsFilter
        },

        props: {
            category: {
                type: String,
                required: true,

                validator (value) {
                    return [
                        'lands',
                        'capitals',
                        'isolateds',
                        'commercials'
                    ].includes(value)
                }
            },

            options: {
                type: Object,
                required: []
            }
        },

        computed: {
            filterComponent () {
                switch (this.category) {
                    case 'lands':
                        return 'NcaLandsFilter'
                    case 'capitals':
                        return 'NcaCapitalsFilter'
                    case 'isolateds':
                        return 'NcaIsolatedsFilter'
                    case 'commercials':
                        return 'NcaCommercialsFilter'
                }
            }
        },

        methods: {
            resetFilter () {
                this.$refs.filter.resetFilter()
            }
        }
    }
</script>
