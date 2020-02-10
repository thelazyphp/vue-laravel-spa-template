<template>
    <div
        id="upload-modal"
        class="modal fade"
        tabindex="-1"
        role="dialog"
        aria-labelledby="upload-modal-label"
        aria-hidden="true"
    >
        <div
            class="modal-dialog"
            role="document"
        >
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5
                        id="upload-modal-label"
                        class="modal-title lead"
                    >
                        {{ $t('nca.upload.title') }}
                    </h5>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        :aria-label="$t('nca.upload.close')"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input
                        ref="files"
                        type="file"
                        class="d-none"
                        multiple
                        accept="application/vnd.ms-excel"
                        @change="addUpload"
                    >
                    <div class="form-group">
                        <div class="input-group input-group-sm">
                            <select
                                v-model="category"
                                class="custom-select"
                            >
                                <option
                                    v-for="category in categories"
                                    :key="category"
                                    :value="category"
                                >
                                    {{ $t(`nca.categories.${category}`) }}
                                </option>
                            </select>
                            <div class="input-group-append">
                                <button
                                    type="button"
                                    class="btn btn-outline-primary"
                                    :title="$t('nca.upload.buttons.browse')"
                                    @click="$refs.files.click()"
                                >
                                    <i class="fas fa-folder-open"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="! uploads.length"
                        class="text-muted text-center"
                    >
                        {{ $t('nca.upload.messages.no_files_chosen') }}
                    </div>
                    <div
                        v-for="(upload, index) in uploads"
                        :key="index"
                    >
                        <p class="mb-0">{{ upload.file.name }}</p>
                        <p
                            v-if="upload.isError"
                            class="mb-0 text-danger"
                        >
                            {{ $t('nca.upload.messages.error') }}
                        </p>
                        <p
                            v-if="upload.isSuccess"
                            class="mb-0 text-success"
                        >
                            {{ $t('nca.upload.messages.success') }}
                        </p>
                        <div
                            v-if="! upload.isUploading"
                            class="d-flex justify-content-between align-items-center"
                        >
                            <small class="text-muted">{{ upload.file.size }} {{ $t('nca.upload.messages.bytes') }}</small>
                            <a
                                href="javascript:void(0)"
                                :title="$t('nca.upload.buttons.remove')"
                                @click="uploads.splice(index, 1)"
                            >
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                        <template v-else>
                            <p class="mb-0">{{ $t('nca.upload.messages.uploading', { percent: upload.progress }) }}</p>
                            <div class="progress">
                                <div
                                    class="progress-bar progress-bar-striped progress-bar-animated"
                                    role="progressbar"
                                    aria-valuemin="0"
                                    aria-valuemax="100"
                                    :style="'width: ' + upload.progress + '%'"
                                    :aria-valuenow="upload.progress"
                                ></div>
                            </div>
                        </template>
                        <hr v-if="index < uploads.length - 1">
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button
                        type="button"
                        class="btn btn-sm btn-secondary"
                        data-dismiss="modal"
                    >
                        {{ $t('nca.upload.buttons.close') }}
                    </button>
                    <button
                        v-if="uploads.length"
                        type="button"
                        class="btn btn-sm btn-primary"
                        @click="upload"
                    >
                        {{ $t('nca.upload.buttons.upload') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Http from '../../utils/Http'

    export default {
        name: 'NcaUploadModal',

        data () {
            return {
                category: 'lands',
                uploads: []
            }
        },

        watch: {
            category () {
                this.uploads = []
            }
        },

        computed: {
            categories () {
                return [
                    'lands',
                    'capitals',
                    'isolateds',
                    'commercials'
                ]
            }
        },

        methods: {
            addUpload (event) {
                for (let file of event.target.files) {
                    this.uploads.push({
                        file,
                        category: this.category,
                        error: null,
                        progress: 0,
                        isError: false,
                        isSuccess: false,
                        isUploading: false
                    })
                }

                this.$refs.files.value = null
            },

            upload () {
                for (let upload of this.uploads) {
                    if (upload.isUploading) {
                        return
                    }

                    const data = new FormData()
                    data.append('file', upload.file)

                    const config = {
                        headers: {
                            'content-type': 'multipart/form-data'
                        },

                        onUploadProgress(event) {
                            if (event.lengthComputable) {
                                upload.progress = Math.round(
                                    (event.loaded / event.total) * 100
                                )
                            }
                        }
                    }

                    upload.isError = false
                    upload.isSuccess = false
                    upload.isUploading = true

                    new Http().post(`/nca/${upload.category}`, data, config)
                        .then(() => {
                            upload.isSuccess = true
                        })
                        .catch(error => {
                            upload.error = error
                            upload.isError = true
                        })
                        .finally(() => {
                            upload.progress = 0
                            upload.isUploading = false
                        })
                }
            }
        }
    }
</script>
