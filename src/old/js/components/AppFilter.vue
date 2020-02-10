<template>
    <form>
        <div
            v-for="(group, groupIndex) in inputGroups"
            :key="groupIndex"
            class="input-group-wrapper"
        >
            <p v-if="group.name || group.collapse">
                <a
                    :href="group.collapse ? `#input-group-${groupIndex}` : 'javascript:void(0)'"
                    :class="{ 'dropdown-toggle': group.collapse }"
                    :role="group.collapse ? 'button' : null"
                    :data-toggle="group.collapse ? 'collapse' : null"
                    :aria-expanded="group.collapse ? false : null"
                    :aria-controls="group.collapse ? `input-group-${groupIndex}` : null"
                >
                    {{ group.name }}
                </a>
            </p>

            <div
                :id="`input-group-${groupIndex}`"
                class="form-row"
                :class="{ 'collapse': group.collapse }"
            >
                <div
                    v-for="(input, inputIndex) in group.inputs"
                    :key="inputIndex"
                    class="form-group col-lg-3"
                >
                    <label
                        class="text-muted"
                        :for="`group-${groupIndex}-input-${inputIndex}`"
                        :class="{ 'sr-only': showLabels === false }"
                        v-html="input.label"
                    >
                    </label>

                    <br v-if="showLabels && ['radio', 'switch', 'checkbox', 'btn-radio', 'btn-checkbox'].includes(input.type)">

                    <div
                        v-if="input.type == 'radio'"
                        key="radio-input"
                        :id="`group-${groupIndex}-input-${inputIndex}`"
                        class="radio-input-wrapper"
                    >
                        <div
                            v-for="(option, optionIndex) in getOptions(input)"
                            :key="optionIndex"
                            class="custom-control custom-radio"
                            :class="{ 'custom-control-inline': input.inline }"
                        >
                            <input
                                :id="`group-${groupIndex}-input-${inputIndex}-radio-${optionIndex}`"
                                v-model="filterData[input.name]"
                                type="radio"
                                class="custom-control-input"
                                :name="input.name"
                                :value="option.value"
                                :disabled="input.disabled"
                            >

                            <label
                                class="custom-control-label"
                                :for="`group-${groupIndex}-input-${inputIndex}-radio-${optionIndex}`"
                            >
                                {{ option.name }}
                            </label>
                        </div>
                    </div>

                    <div
                        v-else-if="input.type == 'switch'"
                        key="switch-input"
                        :id="`group-${groupIndex}-input-${inputIndex}`"
                        class="switch-input-wrapper"
                    >
                        <div
                            v-for="(option, optionIndex) in getOptions(input)"
                            :key="optionIndex"
                            class="custom-control custom-switch"
                            :class="{ 'custom-control-inline': input.inline }"
                        >
                            <input
                                :id="`group-${groupIndex}-input-${inputIndex}-switch-${optionIndex}`"
                                v-model="filterData[input.name]"
                                type="checkbox"
                                class="custom-control-input"
                                :name="input.name"
                                :value="option.value"
                                :disabled="input.disabled"
                            >

                            <label
                                class="custom-control-label"
                                :for="`group-${groupIndex}-input-${inputIndex}-switch-${optionIndex}`"
                            >
                                {{ option.name }}
                            </label>
                        </div>
                    </div>

                    <div
                        v-else-if="input.type == 'checkbox'"
                        key="checkbox-input"
                        :id="`group-${groupIndex}-input-${inputIndex}`"
                        class="checkbox-input-wrapper"
                    >
                        <div
                            v-for="(option, optionIndex) in getOptions(input)"
                            :key="optionIndex"
                            class="custom-control custom-checkbox"
                            :class="{ 'custom-control-inline': input.inline }"
                        >
                            <input
                                :id="`group-${groupIndex}-input-${inputIndex}-checkbox-${optionIndex}`"
                                v-model="filterData[input.name]"
                                type="checkbox"
                                class="custom-control-input"
                                :name="input.name"
                                :value="option.value"
                                :disabled="input.disabled"
                            >

                            <label
                                class="custom-control-label"
                                :for="`group-${groupIndex}-input-${inputIndex}-checkbox-${optionIndex}`"
                            >
                                {{ option.name }}
                            </label>
                        </div>
                    </div>

                    <select
                        v-else-if="input.type == 'select'"
                        key="select-input"
                        :id="`group-${groupIndex}-input-${inputIndex}`"
                        v-model="filterData[input.name]"
                        class="custom-select"
                        :class="inputSize ? `custom-select-${inputSize}` : null"
                        :name="input.name"
                        :multiple="input.multiple"
                        :disabled="input.disabled"
                    >
                        <option
                            v-if="input.defaultOption"
                            :value="input.defaultOption.value"
                        >
                            {{ input.defaultOption.name }}
                        </option>

                        <option
                            v-for="(option, optionIndex) in getOptions(input)"
                            :key="optionIndex"
                            :value="option.value"
                        >
                            {{ option.name }}
                        </option>
                    </select>

                    <div
                        v-else-if="input.type == 'range'"
                        key="range-input"
                        class="input-group"
                        :class="inputSize ? `input-group-${inputSize}` : null"
                    >
                        <input
                            :id="`group-${groupIndex}-input-${inputIndex}`"
                            v-model="filterData[`${input.name}_min`]"
                            type="number"
                            class="form-control"
                            :min="input.min"
                            :max="input.max"
                            :step="input.step"
                            :name="input.name"
                            :placeholder="input.placeholderMin"
                            :disabled="input.disabledMin"
                        >

                        <input
                            v-model="filterData[`${input.name}_max`]"
                            type="number"
                            class="form-control"
                            :min="input.min"
                            :max="input.max"
                            :step="input.step"
                            :name="input.name"
                            :placeholder="input.placeholderMax"
                            :disabled="input.disabledMax"
                        >
                    </div>

                    <div
                        v-else-if="input.type == 'btn-radio'"
                        key="btn-radio-input"
                        :id="`group-${groupIndex}-input-${inputIndex}`"
                        class="btn-radio-input-wrapper"
                    >
                        <input
                            v-for="(option, optionIndex) in getOptions(input)"
                            :key="optionIndex"
                            :ref="`group${groupIndex}input${inputIndex}btnRadio${optionIndex}`"
                            v-model="filterData[input.name]"
                            type="radio"
                            class="d-none"
                            :name="input.name"
                            :value="option.value"
                        >

                        <div
                            class="btn-group btn-group-toggle"
                            data-toggle="buttons"
                            :class="inputSize ? `btn-group-${inputSize}` : null"
                        >
                            <label
                                v-for="(option, optionIndex) in getOptions(input)"
                                :key="optionIndex"
                                class="btn btn-outline-primary"
                                :class="{ active: filterData[input.name] == option.value }"
                                @click="$refs[`group${groupIndex}input${inputIndex}btnRadio${optionIndex}`][0].click()"
                            >
                                <input
                                    type="radio"
                                    :name="input.name"
                                    :value="option.value"
                                >

                                {{ option.name }}
                            </label>
                        </div>
                    </div>

                    <div
                        v-else-if="input.type == 'btn-checkbox'"
                        key="btn-checkbox-input"
                        :id="`group-${groupIndex}-input-${inputIndex}`"
                        class="btn-checkbox-input-wrapper"
                    >
                        <input
                            v-for="(option, optionIndex) in getOptions(input)"
                            :key="optionIndex"
                            :ref="`group${groupIndex}input${inputIndex}btnCheckbox${optionIndex}`"
                            v-model="filterData[input.name]"
                            type="checkbox"
                            class="d-none"
                            :name="input.name"
                            :value="option.value"
                        >

                        <div
                            class="btn-group btn-group-toggle"
                            data-toggle="buttons"
                            :class="inputSize ? `btn-group-${inputSize}` : null"
                        >
                            <label
                                v-for="(option, optionIndex) in getOptions(input)"
                                :key="optionIndex"
                                class="btn btn-outline-primary"
                                @click="$refs[`group${groupIndex}input${inputIndex}btnCheckbox${optionIndex}`][0].click()"
                            >
                                <input
                                    type="checkbox"
                                    :name="input.name"
                                    :value="option.value"
                                >

                                {{ option.name }}
                            </label>
                        </div>
                    </div>

                    <input
                        v-else
                        key="default-input"
                        :id="`group-${groupIndex}-input-${inputIndex}`"
                        v-model="filterData[input.name]"
                        class="form-control"
                        :type="input.type"
                        :class="inputSize ? `form-control-${inputSize}` : null"
                        :name="input.name"
                        :placeholder="input.placeholder"
                        :disabled="input.disabled"
                    >
                </div>
            </div>
        </div>
    </form>
</template>

<script>
    export default {
        name: 'AppFilter',

        props: {
            filterData: {
                type: Object,
                required: true
            },

            inputGroups: {
                type: Array,
                required: true
            },

            showLabels: {
                type: Boolean,
                default: false
            },

            filterOptions: {
                type: Object,

                default () {
                    return {}
                }
            },

            inputSize: {
                type: String,

                validator (value) {
                    return ['sm', 'lg'].includes(value)
                }
            }
        },

        methods: {
            getOptions (input) {
                return typeof input.options == 'string'
                    ? this.filterOptions[input.options] : input.options
            }
        }
    }
</script>
