<template>
    <div
        id="statistics-modal"
        class="modal fade"
        tabindex="-1"
        role="dialog"
        aria-labelledby="statistics-modal-label"
        aria-hidden="true"
    >
        <div
            class="modal-dialog modal-xl"
            role="document"
        >
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5
                        id="statistics-modal-label"
                        class="modal-title lead"
                    >
                        {{ $t('nca.statistics.title') }}
                    </h5>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        :aria-label="$t('nca.statistics.close')"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <NcaIsolatedsStatistics/>
                </div>
                <div class="modal-footer border-top-0">
                    <button
                        type="button"
                        class="btn btn-sm btn-secondary"
                        data-dismiss="modal"
                    >
                        {{ $t('nca.statistics.buttons.close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import NcaIsolatedsStatistics from './NcaIsolatedsStatistics'

    export default {
        name: 'NcaStatisticsModal',

        components: {
            NcaIsolatedsStatistics
        }
    }
</script>
