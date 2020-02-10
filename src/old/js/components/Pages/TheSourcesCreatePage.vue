<template>
    <div class="container">
        <div class="card shadow">
            <div class="card-header">{{ $t('sources_create.title') }}</div>
            <form
                action="#"
                class="card-body"
                @submit.prevent="create"
            >
                <div class="form-group">
                    <label for="id">{{ $t('sources_create.labels.id') }}</label>
                    <input
                        id="id"
                        v-model="id"
                        type="text"
                        class="form-control"
                        required
                        autofocus
                        :class="{ 'is-invalid': errors.id }"
                    >
                    <div class="invalid-feedback">{{ errors.id }}</div>
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
                            {{ $t('sources_create.labels.agency') }}
                        </label>
                        <div class="invalid-feedback">{{ errors.isAgency }}</div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="url">{{ $t('sources_create.labels.url') }}</label>
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
                    <label for="name">{{ $t('sources_create.labels.name') }}</label>
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
                    <label for="description">{{ $t('sources_create.labels.description') }}</label>
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
                        {{ $t('sources_create.buttons.create') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import Http from '../../utils/Http'

    export default {
        name: 'TheSourcesCreatePage',

        data () {
            return {
                id: '',
                isAgency: false,
                url: '',
                name: '',
                description: '',

                errors: {
                    id: '',
                    isAgency: '',
                    url: '',
                    name: '',
                    description: ''
                }
            }
        },

        methods: {
            create () {
                const data = {
                    id: this.id,
                    is_agency: this.isAgency,
                    url: this.url,
                    name: this.name,
                    description: this.description
                }

                new Http().post('/sources', data)
                    .then(() => {
                        this.clearErrors()

                        this['alert/SHOW']({
                            message: this.$t('messages.source_created', {
                                id: this.id
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
                                message: this.$t('errors.create_source_error')
                            })
                        }
                    })
            }
        }
    }
</script>
