<template>
    <div class="container-fluid">
        <div class="card shadow">
            <div class="card-header">{{ $t('parsers_debug.title', { name: parser.name }) }}</div>
            <div class="card-body">
                <form
                    action="#"
                    @submit.prevent="debug"
                >
                    <div class="form-group">
                        <label for="url">{{ $t('parsers_debug.labels.url') }}</label>
                        <input
                            id="url"
                            v-model="url"
                            type="url"
                            class="form-control"
                            required
                            :class="{ 'is-invalid': errors.url }"
                        >
                        <div class="invalid-feedback">{{ errors.url }}</div>
                    </div>
                    <div class="d-flex w-100 justify-content-end">
                        <div>
                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                {{ $t('parsers_debug.buttons.run') }}
                            </button>
                            <button
                                type="button"
                                class="btn btn-primary"
                                @click="debugLinksParser"
                            >
                                {{ $t('parsers_debug.buttons.run_links_parser') }}
                            </button>
                        </div>
                    </div>
                </form>
                <code
                    v-if="result"
                    class="mt-3"
                >
                    <pre>{{ result }}</pre>
                </code>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import Http from '../../utils/Http'

    export default {
        name: 'TheParsersDebugPage',

        data () {
            return {
                url: '',
                result: '',

                errors: {
                    url: ''
                }
            }
        },

        computed: {
            ...mapState({
                parser: state => state.parsers.item.data
            })
        },

        created () {
            this['parsers/loadItem'](this.$route.params.id)
        },

        methods: {
            ...mapActions([
                'parsers/loadItem'
            ]),

            debug () {
                const data = {
                    url: this.url
                }

                new Http().post(`/parsers/${this.$route.params.id}/debug`, data)
                    .then(({ data }) => {
                        this.clearErrors()
                        this.result = data
                    })
                    .catch(error => {
                        console.log(error)

                        if (error.response.status == 422) {
                            this.setErrors(
                                error.response.data.errors
                            )
                        } else {
                            this['alert/SHOW']({
                                type: 'danger',
                                message: this.$t('errors.debug_parser_error')
                            })
                        }
                    })
            },

            debugLinksParser () {
                new Http().post(`/parsers/${this.$route.params.id}/links-parser-debug`)
                    .then(({ data }) => {
                        this.result = data
                    })
                    .catch(error => {
                        console.log(error)

                        this['alert/SHOW']({
                            type: 'danger',
                            message: this.$t('errors.debug_links_parser_error')
                        })
                    })
            }
        }
    }
</script>
