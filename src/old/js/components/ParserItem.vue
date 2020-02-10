<template>
    <li class="list-group-item">
        <p>{{ name }}</p>
        <div class="d-flex w-100 justify-content-end">
            <div>
                <button
                    type="button"
                    class="btn btn-sm btn-primary"
                    @click="run"
                >
                    <i class="fas fa-play mr-2"></i>{{ $t('parser_item.buttons.run') }}
                </button>
                <button
                    type="button"
                    class="btn btn-sm btn-primary"
                    @click="edit"
                >
                    <i class="fas fa-edit mr-2"></i>{{ $t('parser_item.buttons.edit') }}
                </button>
                <button
                    type="button"
                    class="btn btn-sm btn-primary"
                    @click="configure"
                >
                    <i class="fas fa-sliders-h mr-2"></i>{{ $t('parser_item.buttons.configure') }}
                </button>
                <button
                    type="button"
                    class="btn btn-sm btn-danger"
                    @click="remove"
                >
                    <i class="fas fa-trash mr-2"></i>{{ $t('parser_item.buttons.remove') }}
                </button>
            </div>
        </div>
    </li>
</template>

<script>
    import { mapActions } from 'vuex'
    import Http from '../utils/Http'

    export default {
        name: 'ParserItem',

        props: {
            id: {
                type: Number,
                required: true
            },

            name: {
                type: String,
                required: true
            }
        },

        methods: {
            ...mapActions([
                'parsers/removeItem'
            ]),

            run () {
                new Http().post(`/parsers/${this.id}/run`)
            },

            edit () {
                this.$router.push({
                    name: 'parsers.edit',

                    params: {
                        id: this.id
                    }
                })
            },

            configure () {
                this.$router.push({
                    name: 'parsers.settings',

                    params: {
                        id: this.id
                    }
                })
            },

            remove () {
                this['parsers/removeItem'](this.id)
                    .then(() => {
                        this['alert/SHOW']({
                            message: this.$t('messages.parser_removed', {
                                name: this.name
                            })
                        })
                    })
                    .catch(error => {
                        console.log(error)

                        this['alert/SHOW']({
                            type: 'danger',
                            message: this.$t('errors.remove_parser_error')
                        })
                    })
            }
        }
    }
</script>
