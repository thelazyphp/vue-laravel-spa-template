<template>
    <div class="page-wrap">
        <div class="jumbotron bg-white text-center">
            <h1 class="display-4">Настроить парсер</h1>

            <p
                v-if="parser"
                class="lead"
            >
                {{ parser.name }}
            </p>

            <ul class="list-inline">
                <input
                    ref="file"
                    class="d-none"
                    type="file"
                    accept="application/json"
                >

                <li class="list-inline-item">
                    <a
                        class="btn btn-primary"
                        role="button"
                        href="javascript:void(0)"
                        @click="$refs.file.click()"
                    >
                        <i class="fas fa-file-import"></i> Импорт
                    </a>
                </li>

                <li class="list-inline-item">
                    <a
                        v-if="parser"
                        class="btn btn-primary"
                        role="button"
                        :href="exportUrl"
                        :download="`${parser.name}.json`"
                    >
                        <i class="fas fa-file-export"></i> Экспорт
                    </a>
                </li>
            </ul>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-3 mb-lg-0">
                    <div
                        v-if="parser"
                        class="list-group list-group-flush"
                        style="max-height: 480px; overflow-y: auto"
                    >
                        <button
                            v-for="attr in attrs"
                            :key="attr"
                            class="list-group-item list-group-item-action"
                            type="button"
                            :class="{ active: attr == activeAttr }"
                            @click="activeAttr = attr"
                        >
                            $model->{{ attr }}
                        </button>
                    </div>
                </div>

                <div class="col-lg-8 mb-3 mb-lg-0">
                    <div
                        v-if="parser"
                        class="attr-wrap"
                    >
                        <p class="text-right">
                            <a
                                class="text-danger"
                                href="javascript:void(0)"
                                @click="resetOptions(parser.settings.attributes[activeAttr])"
                            >
                                Сбросить опции
                            </a>
                        </p>

                        <div class="custom-control custom-switch form-group">
                            <input
                                id="attrSkip"
                                v-model="parser.settings.attributes[activeAttr].skip"
                                class="custom-control-input"
                                type="checkbox"
                            >

                            <label
                                class="custom-control-label"
                                for="attrSkip"
                            >
                                Пропускать атрибут
                            </label>
                        </div>

                        <div
                            v-if="parser.settings.attributes[activeAttr].skip"
                            class="alert alert-info shadow-sm"
                            role="alert"
                        >
                            Атрибут будет пропущен.
                        </div>

                        <div class="form-group">
                            <label for="attrConst">Константа</label>

                            <div class="dropdown">
                                <input
                                    id="attrConst"
                                    v-model="parser.settings.attributes[activeAttr].const"
                                    class="form-control"
                                    type="text"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                    @blur="addSuggestion('const', $event.target.value)"
                                >

                                <div
                                    class="dropdown-menu w-100"
                                    aria-labelledby="attrConst"
                                    :class="{ 'd-none': !suggestions.const.length }"
                                >
                                    <a
                                        v-for="(suggestion, index) in suggestions.const"
                                        :key="index"
                                        class="dropdown-item"
                                        href="javascript:void(0)"
                                        @click="parser.settings.attributes[activeAttr].const = suggestion"
                                    >
                                        {{ suggestion }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="custom-control custom-checkbox form-group">
                            <input
                                id="attrArray"
                                v-model="parser.settings.attributes[activeAttr].array"
                                class="custom-control-input"
                                type="checkbox"
                            >

                            <label
                                class="custom-control-label"
                                for="attrArray"
                            >
                                Массив
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="attrSelector">Селектор</label>

                            <div class="dropdown">
                                <textarea
                                    id="attrSelector"
                                    v-model="parser.settings.attributes[activeAttr].selector"
                                    class="form-control text-primary"
                                    rows="1"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                    @blur="addSuggestion('selector', $event.target.value)"
                                >
                                </textarea>

                                <div
                                    class="dropdown-menu w-100"
                                    aria-labelledby="attrSelector"
                                    :class="{ 'd-none': !suggestions.selector.length }"
                                >
                                    <a
                                        v-for="(suggestion, index) in suggestions.selector"
                                        :key="index"
                                        class="dropdown-item"
                                        href="javascript:void(0)"
                                        @click="parser.settings.attributes[activeAttr].selector = suggestion"
                                    >
                                        {{ suggestion }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="attrIndex">Индекс</label>

                            <input
                                id="attrIndex"
                                v-model.number="parser.settings.attributes[activeAttr].index"
                                class="form-control"
                                type="number"
                                step="1"
                            >
                        </div>

                        <div class="form-group">
                            <label for="attrProperty">Свойство</label>

                            <div class="dropdown">
                                <input
                                    id="attrProperty"
                                    v-model="parser.settings.attributes[activeAttr].property"
                                    class="form-control text-primary"
                                    type="text"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                    @blur="addSuggestion('property', $event.target.value)"
                                >

                                <div
                                    class="dropdown-menu w-100"
                                    aria-labelledby="attrProperty"
                                    :class="{ 'd-none': !suggestions.property.length }"
                                >
                                    <a
                                        v-for="(suggestion, index) in suggestions.property"
                                        :key="index"
                                        class="dropdown-item"
                                        href="javascript:void(0)"
                                        @click="parser.settings.attributes[activeAttr].property = suggestion"
                                    >
                                        {{ suggestion }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card card-body shadow-sm mb-3">
                            <div class="card-text text-center">
                                <a
                                    href="javascript:void(0)"
                                    @click="addPattern(parser.settings.attributes[activeAttr])"
                                >
                                    Добавить шаблон
                                </a>
                            </div>

                            <div
                                v-for="(pattern, index) in parser.settings.attributes[activeAttr].patterns"
                                :key="index"
                                class="form-group mt-3 mb-0"
                            >
                                <label :for="`pattern${index}`">Шаблон {{ index + 1 }}</label>

                                <div class="dropdown">
                                    <textarea
                                        :id="`pattern${index}`"
                                        v-model="pattern.pattern"
                                        class="form-control text-primary"
                                        rows="1"
                                        placeholder="Шаблон"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                        @blur="addSuggestion('pattern', $event.target.value)"
                                    >
                                    </textarea>

                                    <div
                                        class="dropdown-menu w-100"
                                        :class="{ 'd-none': !suggestions.pattern.length }"
                                    >
                                        <a
                                            v-for="(suggestion, index) in suggestions.pattern"
                                            :key="index"
                                            class="dropdown-item"
                                            href="javascript:void(0)"
                                            @click="pattern.pattern = suggestion"
                                        >
                                            {{ suggestion }}
                                        </a>
                                    </div>
                                </div>

                                <input
                                    v-model.number="pattern.capturing_group"
                                    class="form-control mt-2"
                                    type="number"
                                    min="0"
                                    step="1"
                                    placeholder="Номер подмаски"
                                >

                                <div class="text-right mt-2">
                                    <a
                                        class="text-danger"
                                        href="javascript:void(0)"
                                        @click="parser.settings.attributes[activeAttr].patterns.splice(index, 1)"
                                    >
                                        Удалить шаблон
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card card-body shadow-sm mb-3">
                            <div class="card-text text-center">
                                <a
                                    href="javascript:void(0)"
                                    @click="addReplacement(parser.settings.attributes[activeAttr])"
                                >
                                    Добавить замену
                                </a>
                            </div>

                            <div
                                v-for="(replacement, index) in parser.settings.attributes[activeAttr].replacements"
                                :key="index"
                                class="form-group mt-3 mb-0"
                            >
                                <label :for="`replacement${index}`">Замена {{ index + 1 }}</label>

                                <div class="dropdown">
                                    <textarea
                                        :id="`replacement${index}`"
                                        v-model="replacement.pattern"
                                        class="form-control text-primary"
                                        rows="1"
                                        placeholder="Шаблон"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                        @blur="addSuggestion('pattern', $event.target.value)"
                                    >
                                    </textarea>

                                    <div
                                        class="dropdown-menu w-100"
                                        :class="{ 'd-none': !suggestions.pattern.length }"
                                    >
                                        <a
                                            v-for="(suggestion, index) in suggestions.pattern"
                                            :key="index"
                                            class="dropdown-item"
                                            href="javascript:void(0)"
                                            @click="replacement.pattern = suggestion"
                                        >
                                            {{ suggestion }}
                                        </a>
                                    </div>
                                </div>

                                <div class="dropdown mt-2">
                                    <textarea
                                        v-model="replacement.replacement"
                                        class="form-control text-primary"
                                        rows="1"
                                        placeholder="Замена"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                        @blur="addSuggestion('replacement', $event.target.value)"
                                    >
                                    </textarea>

                                    <div
                                        class="dropdown-menu w-100"
                                        :class="{ 'd-none': !suggestions.replacement.length }"
                                    >
                                        <a
                                            v-for="(suggestion, index) in suggestions.replacement"
                                            :key="index"
                                            class="dropdown-item"
                                            href="javascript:void(0)"
                                            @click="replacement.replacement = suggestion"
                                        >
                                            {{ suggestion }}
                                        </a>
                                    </div>
                                </div>

                                <div class="text-right mt-2">
                                    <a
                                        class="text-danger"
                                        href="javascript:void(0)"
                                        @click="parser.settings.attributes[activeAttr].replacements.splice(index, 1)"
                                    >
                                        Удалить замену
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="attrLeftString">Строка слева</label>

                            <input
                                id="attrLeftString"
                                v-model="parser.settings.attributes[activeAttr].left_string"
                                class="form-control"
                                type="text"
                            >
                        </div>

                        <div class="form-group">
                            <label for="attrRightString">Строка справа</label>

                            <input
                                id="attrRightString"
                                v-model="parser.settings.attributes[activeAttr].right_string"
                                class="form-control"
                                type="text"
                            >
                        </div>

                        <div class="form-group">
                            <label for="attrDefault">По умолчанию</label>

                            <input
                                id="attrDefault"
                                v-model="parser.settings.attributes[activeAttr].default"
                                class="form-control"
                                type="text"
                            >
                        </div>

                        <div class="form-group">
                            <label for="attrScript">Выполнять скрипт</label>

                            <textarea
                                id="attrScript"
                                v-model="parser.settings.attributes[activeAttr].script"
                                class="form-control text-primary"
                                rows="1"
                            >
                            </textarea>
                        </div>

                        <div class="card card-body shadow-sm mb-3">
                            <div class="card-text text-center">
                                <a
                                    href="javascript:void(0)"
                                    @click="addBranch(parser.settings.attributes[activeAttr])"
                                >
                                    Добавить ветвление
                                </a>
                            </div>

                            <div
                                v-for="(branch, index) in parser.settings.attributes[activeAttr].branches"
                                :key="index"
                                class="input-group mt-3"
                            >
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ index + 1 }}</span>
                                </div>

                                <input
                                    :id="`branchIf${index}`"
                                    class="form-control"
                                    type="text"
                                    placeholder="Если"
                                >

                                <input
                                    :id="`branchThen${index}`"
                                    class="form-control"
                                    type="text"
                                    placeholder="То"
                                >

                                <div class="input-group-append">
                                    <button
                                        class="btn btn-outline-danger"
                                        type="button"
                                        title="Удалить ветвление"
                                        @click="parser.settings.attributes[activeAttr].branches.splice(index, 1)"
                                    >
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <template v-if="parser.settings.attributes[activeAttr].array">
                            <div class="custom-control custom-checkbox form-group">
                                <input
                                    id="attrArraySort"
                                    v-model="parser.settings.attributes[activeAttr].array_sort"
                                    class="custom-control-input"
                                    type="checkbox">

                                <label
                                    class="custom-control-label"
                                    for="attrArraySort"
                                >
                                    Сортировать массив
                                </label>
                            </div>

                            <div class="form-group">
                                <label for="attrOffset">Смещение массива</label>

                                <input
                                    id="attrOffset"
                                    v-model.number="parser.settings.attributes[activeAttr].array_offset"
                                    class="form-control"
                                    type="number"
                                    step="1"
                                >
                            </div>

                            <div class="form-group">
                                <label for="attrLength">Кол-во элементов массива</label>

                                <input
                                    id="attrLength"
                                    v-model.number="parser.settings.attributes[activeAttr].array_length"
                                    class="form-control"
                                    type="number"
                                    min="0"
                                    step="1"
                                >
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="text-right">
                <a
                    class="btn btn-primary"
                    href="javascript:void(0)"
                    role="button"
                    @click="onSave"
                >
                    Сохранить
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    import request from '../utils/request'

    export default {
        name: 'parser-settings-view',

        data () {
            return {
                loading: false,
                parser: null,
                activeAttr: null,

                suggestions: {
                    const: [],
                    selector: [],
                    property: [],
                    pattern: [],
                    replacement: [],
                    branch_if: [],
                    branch_then: []
                }
            }
        },

        computed: {
            attrs () {
                return this.parser ? Object.keys(this.parser.settings.attributes) : []
            },

            exportUrl () {
                if (this.parser) {
                    const data = JSON.stringify(this.parser.settings)
                    return 'data:application/json;charset=utf-8,' + encodeURIComponent(data)
                }

                return null
            }
        },

        created () {
            this.fetchParser()
        },

        methods: {
            fetchParser () {
                const endpoint = `/parsers/${this.$route.params.id}`
                this.loading = true

                request.get(endpoint)
                    .then(({ data }) => {
                        this.loading = false
                        this.parser = data.data
                        this.activeAttr = Object.keys(this.parser.settings.attributes)[0]
                    })
                    .catch(error => console.log(error))
            },

            resetOptions (attr) {
                //
            },

            onSave () {
                const endpoint = `/parsers/${this.parser.id}`

                request.patch(endpoint, this.parser)
                    .then(() => {
                        //
                    })
            },

            addSuggestion (suggestion, value) {
                if (value && !this.suggestions[suggestion].includes(value)) {
                    this.suggestions[suggestion].push(value)
                }
            },

            addBranch (attr) {
                attr.branches.push({
                    if: this.parser.settings.default_branch.if,
                    then: this.parser.settings.default_branch.then
                })
            },

            addReplacement (attr) {
                attr.replacements.push({
                    pattern: this.parser.settings.default_replacement.pattern,
                    replacement: this.parser.settings.default_replacement.replacement
                })
            },

            addPattern (attr) {
                attr.patterns.push({
                    pattern: this.parser.settings.default_pattern.pattern,
                    capturing_group: this.parser.settings.default_pattern.capturing_group
                })
            }
        }
    }
</script>
