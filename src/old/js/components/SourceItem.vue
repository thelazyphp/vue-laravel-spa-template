<template>
    <li class="list-group-item">
        <p>{{ name }}</p>
        <div class="d-flex w-100 justify-content-end">
            <div>
                <button
                    type="button"
                    class="btn btn-sm btn-primary"
                    @click="edit"
                >
                    <i class="fas fa-edit mr-2"></i>{{ $t('parser_item.buttons.edit') }}
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

    export default {
        name: 'SourceItem',

        props: {
            id: {
                type: String,
                required: true
            },

            name: {
                type: String,
                required: true
            }
        },

        methods: {
            ...mapActions([
                'sources/removeItem'
            ]),

            edit () {
                this.$router.push({
                    name: 'sources.edit',

                    params: {
                        id: this.id
                    }
                })
            },

            remove () {
                this['sources/removeItem'](this.id)
                    .then(() => {
                        this['alert/SHOW']({
                            message: this.$t('messages.source_removed', {
                                id: this.id
                            })
                        })
                    })
                    .catch(error => {
                        console.log(error)

                        this['alert/SHOW']({
                            type: 'danger',
                            message: this.$t('errors.remove_source_error')
                        })
                    })
            }
        }
    }
</script>
