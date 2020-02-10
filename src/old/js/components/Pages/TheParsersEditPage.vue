<template>
    <div class="container">
        <div class="card shadow">
            <div class="card-header">{{ $t('parsers_edit.title') }}</div>
            <form
                action="#"
                class="card-body"
                @submit.prevent="edit"
            >
                <div
                    v-if="sources.length"
                    class="form-group"
                >
                    <label for="source-id">{{ $t('parsers_edit.labels.source') }}</label>
                    <select
                        id="source-id"
                        v-model="source_id"
                        required
                        class="form-control"
                        :class="{ 'is-invalid': errors.source_id }"
                    >
                        <option
                            v-for="source in sources"
                            :key="source.id"
                            :value="source.id"
                            :title="source.description"
                        >
                            {{ source.name }}
                        </option>
                    </select>
                    <div class="invalid-feedback">{{ errors.source_id }}</div>
                </div>
                <div
                    v-else
                    class="form-group text-center"
                >
                    <p>{{ $t('parsers_edit.no_source_added') }}</p>
                    <RouterLink
                        class="btn btn-primary"
                        :to="{ name: 'sources.create' }"
                    >
                        {{ $t('parsers_edit.buttons.add_source') }}
                    </RouterLink>
                </div>
                <div class="form-group">
                    <label for="name">{{ $t('parsers_edit.labels.name') }}</label>
                    <input
                        id="name"
                        v-model="name"
                        type="text"
                        class="form-control"
                        required
                        :class="{ 'is-invalid': errors.name }"
                    >
                    <div class="invalid-feedback">{{ errors.name }}</div>
                </div>
                <div class="form-group">
                    <label for="model">{{ $t('parsers_edit.labels.model') }}</label>
                    <input
                        id="model"
                        v-model="model"
                        type="text"
                        class="form-control form-control-code"
                        required
                        :class="{ 'is-invalid': errors.model }"
                    >
                    <div class="invalid-feedback">{{ errors.model }}</div>
                </div>
                <div class="form-group">
                    <label for="links-parser">{{ $t('parsers_create.labels.links_parser') }}</label>
                    <input
                        id="links-parser"
                        v-model="links_parser"
                        type="text"
                        class="form-control form-control-code"
                        required
                        :class="{ 'is-invalid': errors.links_parser }"
                    >
                    <div class="invalid-feedback">{{ errors.links_parser }}</div>
                </div>
                <div class="form-group">
                    <label for="description">{{ $t('parsers_edit.labels.description') }}</label>
                    <textarea
                        id="description"
                        v-model="description"
                        rows="3"
                        class="form-control"
                        :class="{ 'is-invalid': errors.description }"
                    >
                    </textarea>
                    <div class="invalid-feedback">{{ errors.description }}</div>
                </div>
                <div class="d-flex w-100 justify-content-end">
                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        {{ $t('parsers_edit.buttons.edit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import Http from '../../utils/Http'

    export default {
        name: 'TheParsersEditPage',

        data () {
            return {
                source_id: '',
                name: '',
                model: '\\App\\Model::class',
                links_parser: '\\App\\Parsers\\LinksParsers\\LinksParser::class',
                description: '',

                errors: {
                    source_id: '',
                    name: '',
                    model: '',
                    links_parser: '',
                    description: ''
                }
            }
        },

        computed: {
            ...mapState({
                sources: state => state.sources.items,
                parser: state => state.parsers.item.data
            })
        },

        created () {
            this['sources/loadItems']()
                .then(() => {
                    this['parsers/loadItem'](this.$route.params.id)
                        .then(() => {
                            this.source_id = this.parser.source_id
                            this.name = this.parser.name
                            this.model = this.parser.model
                            this.links_parser = this.parser.links_parser
                            this.description = this.parser.description
                        })
                        .catch(error => {
                            console.log(error)
                        })
                })
                .catch(error => {
                    console.log(error)
                })
        },

        methods: {
            ...mapActions([
                'parsers/loadItem',
                'sources/loadItems'
            ]),

            edit () {
                if (this.source_id) {
                    const data = {}

                    if (this.source_id != this.parser.source_id) {
                        data['source_id'] = this.source_id
                    }

                    if (this.name && this.name != this.parser.name) {
                        data['name'] = this.name
                    }

                    if (this.model && this.model != this.parser.model) {
                        data['model'] = this.model
                    }

                    if (this.links_parser && this.links_parser != this.parser.links_parser) {
                        data['links_parser'] = this.links_parser
                    }

                    if (this.description != this.parser.description) {
                        data['description'] = this.description
                    }

                    new Http().patch(`/parsers/${this.$route.params.id}`, data)
                        .then(() => {
                            this.clearErrors()

                            this['alert/SHOW']({
                                message: this.$t('messages.parser_edited', {
                                    name: this.name
                                })
                            })

                            this.$router.push({
                                name: 'parsers'
                            })
                        })
                        .catch(error => {
                            console.log(error)

                            if (error.response.status == 422) {
                                this.setErrors(error.response.data.errors)
                            } else {
                                this['alert/SHOW']({
                                    type: 'danger',
                                    message: this.$t('errors.edit_parser_error')
                                })
                            }
                        })
                }
            }
        }
    }
</script>
