<template>
    <div
        id="map-modal"
        class="modal fade"
        tabindex="-1"
        role="dialog"
        aria-labelledby="stats-modal-label"
        aria-hidden="true"
    >
        <div
            class="modal-dialog modal-xl"
            role="document"
        >
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5
                        id="map-modal-label"
                        class="modal-title lead"
                    >
                        {{ $t('nca.map.title') }}
                    </h5>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        :aria-label="$t('nca.map.close')"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="map"></div>
                </div>
                <div class="modal-footer border-top-0">
                    <button
                        type="button"
                        class="btn btn-sm btn-secondary"
                        data-dismiss="modal"
                    >
                        {{ $t('nca.map.buttons.close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Http from '../../utils/Http'

    export default {
        name: 'NcaMapModal',

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

            search: String,
            filter: Object
        },

        data () {
            return {
                map: null
            }
        },

        watch: {
            category () {
                this.retrivePlacemarks()
            },

            search () {
                this.retrivePlacemarks()
            },

            filter () {
                this.retrivePlacemarks()
            }
        },

        mounted () {
            ymaps.ready(() => {
                this.map = new ymaps.Map(
                    'map', {
                        center: [53.912291, 27.563383],
                        zoom: 12
                    }
                )

                this.retrivePlacemarks()
            })
        },

        methods: {
            retrivePlacemarks () {
                let query = [`category=${this.category}`]

                if (this.search) {
                    query.push(`search=${this.search}`)
                }

                Object.keys(this.filter).forEach(key => {
                    if (this.filter[key]) {
                        query.push(`filter[${key}]=${this.filter[key]}`)
                    }
                })

                new Http().get(`/nca/map/placemarks?${query.join('&')}`)
                    .then(({ data }) => {
                        const objectManager = new ymaps.ObjectManager()
                        objectManager.add(data)

                        this.map.geoObjects.removeAll()
                        this.map.geoObjects.add(objectManager)

                        objectManager.objects.events.add(['balloonopen'], event => {
                            const id = event.get('objectId')
                        })

                        objectManager.objects.events.add(['balloonclose'], event => {
                            const id = event.get('objectId')
                        })
                    })
            }
        }
    }
</script>

<style scoped>
    #map {
        width: 100%;
        height: 600px;
    }
</style>
