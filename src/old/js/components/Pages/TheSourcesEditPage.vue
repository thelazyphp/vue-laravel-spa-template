<template>
    <div class="container">
        <div class="card shadow">
            <div class="card-header">{{ $t('sources_edit.title') }}</div>
            <form
                action="#"
                class="card-body"
                @submit.prevent="edit"
            >
                <div class="form-group">
                    <label for="id">{{ $t('sources_edit.labels.id') }}</label>
                    <input
                        id="id"
                        type="text"
                        class="form-control"
                        disabled
                        :value="source.id"
                    >
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input
                            id="agency-check"
                            v-model="isAgency"
                            type="checkbox"
                            class="custom-control-input"
                            :class="{ 'is-invalid': errors.isAgency }"
                        >
                        <label
                            for="agency-check"
                            class="custom-control-label"
                        >
                            {{ $t('sources_edit.labels.agency') }}
                        </label>
                        <div class="invalid-feedback">{{ errors.isAgency }}</div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="url">{{ $t('sources_edit.labels.url') }}</label>
                    <input
                        id="url"
                        v-model="url"
                        type="text"
                        class="form-control"
                        required
                        :class="{ 'is-invalid': errors.url }"
                    >
                    <div class="invalid-feedback">{{ errors.url }}</div>
                </div>
                <div class="form-group">
                    <label for="name">{{ $t('sources_edit.labels.name') }}</label>
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
                    <label for="description">{{ $t('sources_edit.labels.description') }}</label>
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
                        {{ $t('sources_edit.buttons.edit') }}
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
        name: 'TheSourcesEditPage',

        data () {
            return {
                isAgency: false,
                url: '',
                name: '',
                description: '',

                errors: {
                    isAgency: '',
                    url: '',
                    name: '',
                    description: ''
                }
            }
        },

        computed: {
            ...mapState({
                source: state => state.sources.item
            })
        },

        created () {
            this['sources/loadItem'](this.$route.params.id)
                .then(() => {
                    this.isAgency = this.source.is_agency
                    this.url = this.source.url
                    this.name = this.source.name
                    this.description = this.source.description
                })
        },

        methods: {
            ...mapActions([
                'sources/loadItem'
            ]),

            edit () {
                let data = {}

                if (this.isAgency != this.source.is_agency) {
                    data['is_agency'] = this.isAgency
                }

                if (this.url && this.url != this.source.url) {
                    data['url'] = this.url
                }

                if (this.name && this.name != this.source.name) {
                    data['name'] = this.name
                }

                if (this.description != this.source.description) {
                    data['description'] = this.description
                }

                new Http().patch(`/sources/${this.$route.params.id}`, data)
                    .then(() => {
                        this.clearErrors()

                        this['alert/SHOW']({
                            message: this.$t('messages.source_edited', {
                                id: this.$route.params.id
                            })
                        })

                        this.$router.push({
                            name: 'sources'
                        })
                    })
                    .catch(error => {
                        console.log(error)

                        if (error.response.status == 422) {
                            this.setErrors(error.response.data.errors)
                        } else {
                            this['alert/SHOW']({
                                type: 'danger',
                                message: this.$t('messages.edit_source_error')
                            })
                        }
                    })
            }
        }
    }
</script>
