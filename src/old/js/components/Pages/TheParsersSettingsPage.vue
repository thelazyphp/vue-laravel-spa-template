<template>
    <div class="container-fluid">
        <div class="card shadow">
            <div class="card-header">{{ $t('parsers_settings.title', { name: parser.name }) }}</div>
            <div class="card-body">
                <div class="d-flex w-100 justify-content-end mb-3">
                    <input
                        ref="file"
                        type="file"
                        class="d-none"
                        accept="application/json"
                        @change="importSettings"
                    >
                    <div>
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="$refs.file.click()"
                        >
                            <i class="fas fa-file-import mr-2"></i>{{ $t('parsers_settings.buttons.import') }}
                        </button>
                        <a
                            class="btn btn-primary"
                            role="button"
                            :href="exportUrl"
                            :download="exportName"
                        >
                            <i class="fas fa-file-export mr-2"></i>{{ $t('parsers_settings.buttons.export') }}
                        </a>
                        <RouterLink
                            class="btn btn-primary"
                            role="button"
                            target="_blank"
                            :to="{ name: 'parsers.debug' }"
                        >
                            {{ $t('parsers_settings.buttons.debug') }}
                        </RouterLink>
                    </div>
                </div>
                <div class="row">
                    <div class="col-2">
                        <div
                            class="nav nav-pills flex-column"
                            role="tablist"
                            aria-orientation="vertical"
                        >
                            <a
                                v-for="(setting, key, index) in settings"
                                :id="`v-pills-${key}-tab`"
                                :key="key"
                                class="nav-link"
                                data-toggle="pill"
                                role="tab"
                                aria-selected="true"
                                :href="`#v-pills-${key}`"
                                :aria-controls="`v-pills-${key}`"
                                :class="{ 'active': index == 0 }"
                            >
                                $model->{{ key }}
                            </a>
                        </div>
                    </div>
                    <div class="col-10">
                        <form
                            action="#"
                            class="tab-content"
                            @submit.prevent="saveSettings"
                        >
                            <div
                                v-for="(setting, key, index) in settings"
                                :id="`v-pills-${key}`"
                                :key="key"
                                class="tab-pane fade show"
                                role="tabpanel"
                                :aria-labelledby="`v-pills-${key}-tab`"
                                :class="{ 'active': index == 0 }"
                            >
                                <p>
                                    <a
                                        href="javascript:void(0)"
                                        @click="resetSetting(key)"
                                    >
                                        {{ $t('parsers_settings.reset') }}
                                    </a>
                                </p>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input
                                            :id="`${key}-skip-check`"
                                            v-model="settings[key].skip"
                                            type="checkbox"
                                            class="custom-control-input"
                                        >
                                        <label
                                            :for="`${key}-skip-check`"
                                            class="custom-control-label"
                                        >
                                            {{ $t('parsers_settings.labels.skip') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input
                                            :id="`${key}-use-const-check`"
                                            v-model="settings[key].use_const"
                                            type="checkbox"
                                            class="custom-control-input"
                                            :disabled="settings[key].skip"
                                        >
                                        <label
                                            :for="`${key}-use-const-check`"
                                            class="custom-control-label"
                                        >
                                            {{ $t('parsers_settings.labels.use_const') }}
                                        </label>
                                    </div>
                                </div>
                                <div
                                    v-if="settings[key].use_const"
                                    class="form-group"
                                >
                                    <label :for="`${key}-const`">{{ $t('parsers_settings.labels.const') }}</label>
                                    <input
                                        :id="`${key}-const`"
                                        v-model="settings[key].const"
                                        type="text"
                                        class="form-control"
                                        :disabled="settings[key].skip"
                                    >
                                </div>
                                <template v-else>
                                    <div class="form-group">
                                        <label :for="`${key}-default`">{{ $t('parsers_settings.labels.default_value') }}</label>
                                        <input
                                            :id="`${key}-default`"
                                            v-model="settings[key].default"
                                            type="text"
                                            class="form-control"
                                            :disabled="settings[key].skip"
                                        >
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input
                                                :id="`${key}-nullable-check`"
                                                v-model="settings[key].nullable"
                                                type="checkbox"
                                                class="custom-control-input"
                                            >
                                            <label
                                                :for="`${key}-nullable-check`"
                                                class="custom-control-label"
                                            >
                                                {{ $t('parsers_settings.labels.nullable') }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label :for="`${key}-selector`">{{ $t('parsers_settings.labels.selector') }}</label>
                                        <input
                                            :id="`${key}-selector`"
                                            v-model="settings[key].selector"
                                            type="text"
                                            class="form-control form-control-code"
                                            :disabled="settings[key].skip"
                                        >
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input
                                                :id="`${key}-as-array-check`"
                                                v-model="settings[key].as_array"
                                                type="checkbox"
                                                class="custom-control-input"
                                                :disabled="settings[key].skip"
                                            >
                                            <label
                                                :for="`${key}-as-array-check`"
                                                class="custom-control-label"
                                            >
                                                {{ $t('parsers_settings.labels.as_array') }}
                                            </label>
                                        </div>
                                    </div>
                                    <template v-if="settings[key].as_array">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input
                                                    :id="`${key}-sort-check`"
                                                    v-model="settings[key].array_options.sort"
                                                    type="checkbox"
                                                    class="custom-control-input"
                                                    :disabled="settings[key].skip"
                                                >
                                                <label
                                                    :for="`${key}-sort-check`"
                                                    class="custom-control-label"
                                                >
                                                    {{ $t('parsers_settings.labels.sort') }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label :for="`${key}-offset`">{{ $t('parsers_settings.labels.offset') }}</label>
                                            <input
                                                :id="`${key}-offset`"
                                                v-model="settings[key].array_options.offset"
                                                type="number"
                                                class="form-control"
                                                :disabled="settings[key].skip"
                                            >
                                        </div>
                                        <div class="form-group">
                                            <label :for="`${key}-length`">{{ $t('parsers_settings.labels.length') }}</label>
                                            <input
                                                :id="`${key}-length`"
                                                v-model="settings[key].array_options.length"
                                                type="number"
                                                class="form-control"
                                                :disabled="settings[key].skip"
                                            >
                                        </div>
                                    </template>
                                    <div
                                        v-if="! settings[key].as_array"
                                        class="form-group"
                                    >
                                        <label :for="`${key}-index`">{{ $t('parsers_settings.labels.element_index') }}</label>
                                        <input
                                            :id="`${key}-index`"
                                            v-model="settings[key].index"
                                            type="number"
                                            class="form-control"
                                            :disabled="settings[key].skip"
                                        >
                                    </div>
                                    <div class="form-group">
                                        <label :for="`${key}-property`">{{ $t('parsers_settings.labels.element_property') }}</label>
                                        <input
                                            :id="`${key}-property`"
                                            v-model="settings[key].property"
                                            type="text"
                                            class="form-control form-control-code"
                                            :disabled="settings[key].skip"
                                        >
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input
                                                :id="`${key}-use-pattern-check`"
                                                v-model="settings[key].use_pattern"
                                                type="checkbox"
                                                class="custom-control-input"
                                                :disabled="settings[key].skip"
                                            >
                                            <label
                                                :for="`${key}-use-pattern-check`"
                                                class="custom-control-label"
                                            >
                                                {{ $t('parsers_settings.labels.use_pattern') }}
                                            </label>
                                        </div>
                                    </div>
                                    <template v-if="settings[key].use_pattern">
                                        <div class="form-group">
                                            <label :for="`${key}-pattern`">{{ $t('parsers_settings.labels.pattern') }}</label>
                                            <input
                                                :id="`${key}-pattern`"
                                                v-model="settings[key].pattern"
                                                type="text"
                                                class="form-control form-control-code"
                                                :disabled="settings[key].skip"
                                            >
                                        </div>
                                        <div class="form-group">
                                            <label :for="`${key}-group`">{{ $t('parsers_settings.labels.capturing_group') }}</label>
                                            <input
                                                :id="`${key}-group`"
                                                v-model="settings[key].group"
                                                type="number"
                                                class="form-control"
                                                :disabled="settings[key].skip"
                                            >
                                        </div>
                                    </template>
                                    <div class="form-group">
                                        <label :for="`${key}-trim`">{{ $t('parsers_settings.labels.trim_spaces') }}</label>
                                        <select
                                            :id="`${key}-trim`"
                                            v-model="settings[key].trim"
                                            class="form-control"
                                            :disabled="settings[key].skip"
                                        >
                                            <option :value="false">{{ $t('parsers_settings.spaces_trims.no') }}</option>
                                            <option :value="true">{{ $t('parsers_settings.spaces_trims.yes') }}</option>
                                            <option value="left">{{ $t('parsers_settings.spaces_trims.left') }}</option>
                                            <option value="right">{{ $t('parsers_settings.spaces_trims.right') }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label :for="`${key}-prepend`">{{ $t('parsers_settings.labels.prepend_string') }}</label>
                                        <input
                                            :id="`${key}-prepend`"
                                            v-model="settings[key].prepend"
                                            type="text"
                                            class="form-control"
                                            :disabled="settings[key].skip"
                                        >
                                    </div>
                                    <div class="form-group">
                                        <label :for="`${key}-append`">{{ $t('parsers_settings.labels.append_string') }}</label>
                                        <input
                                            :id="`${key}-append`"
                                            v-model="settings[key].append"
                                            type="text"
                                            class="form-control"
                                            :disabled="settings[key].skip"
                                        >
                                    </div>
                                    <div class="form-group">
                                        <label :for="`${key}-cast`">{{ $t('parsers_settings.labels.cast') }}</label>
                                        <input
                                            :id="`${key}-cast`"
                                            v-model="settings[key].cast"
                                            type="text"
                                            class="form-control form-control-code"
                                            :disabled="settings[key].skip"
                                        >
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input
                                                :id="`${key}-use-script-check`"
                                                v-model="settings[key].use_script"
                                                type="checkbox"
                                                class="custom-control-input"
                                                :disabled="settings[key].skip"
                                            >
                                            <label
                                                :for="`${key}-use-script-check`"
                                                class="custom-control-label"
                                            >
                                                {{ $t('parsers_settings.labels.use_script') }}
                                            </label>
                                        </div>
                                    </div>
                                    <div
                                        v-if="settings[key].use_script"
                                        class="form-group"
                                    >
                                        <label :for="`${key}-script`">{{ $t('parsers_settings.labels.script') }}</label>
                                        <textarea
                                            :id="`${key}-script`"
                                            v-model="settings[key].script"
                                            rows="3"
                                            class="form-control form-control-code"
                                            :disabled="settings[key].skip"
                                        >
                                        </textarea>
                                    </div>
                                </template>
                                <div class="d-flex w-100 justify-content-end">
                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >
                                        {{ $t('parsers_settings.buttons.save') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import Http from '../../utils/Http'

    export default {
        name: 'TheParsersSettingsPage',

        data () {
            return {
                settings: {}
            }
        },

        computed: {
            ...mapState({
                meta: state => state.parsers.item.meta,
                parser: state => state.parsers.item.data
            }),

            exportUrl () {
                const json = encodeURIComponent(
                    JSON.stringify(this.settings)
                )

                return `data:application/json;charset=utf-8,${json}`
            },

            exportName () {
                return `${this.parser.name}.json`
            }
        },

        created () {
            this['parsers/loadItem'](this.$route.params.id)
                .then(() => {
                    this.settings = this.parser.settings
                })
        },

        methods: {
            ...mapActions([
                'parsers/loadItem'
            ]),

            importSettings (event) {
                const file = event.target.files[0]

                if (file.type != 'application/json') {
                    return
                }

                const reader = new FileReader()

                reader.onload = () => {
                    const settings = JSON.parse(reader.result)
                    
                    for (let attribute in settings) {
                        Object.keys(settings[attribute]).forEach(key => {
                            this.settings[attribute][key] = settings[attribute][key]
                        })
                    }
                }

                reader.readAsText(file)
            },

            resetSetting (attribute) {
                Object.keys(this.meta.default_setting).forEach(key => {
                    this.settings[attribute][key] = this.meta.default_setting[key]
                })
            },

            saveSettings () {
                const data = {
                    settings: this.settings
                }

                new Http().patch(`/parsers/${this.$route.params.id}`, data)
                    .then(() => {
                        this['alert/SHOW']({
                            message: this.$t('messages.parser_settings_saved', {
                                name: this.parser.name
                            })
                        })
                    })
                    .catch(error => {
                        console.log(error)

                        this['alert/SHOW']({
                            type: 'danger',
                            message: this.$t('errors.save_parser_settings_error')
                        })
                    })
            }
        }
    }
</script>
