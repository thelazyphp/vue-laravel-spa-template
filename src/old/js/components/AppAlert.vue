<template>
    <div
        class="alert alert-dismissible fade show"
        role="alert"
        :class="`alert-${type}`"
    >
        {{ message }}

        <button
            type="button"
            class="close"
            data-dismiss="alert"
            :aria-label="$t('common.close')"
            @click="$emit('close')"
        >
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</template>

<script>
    const TYPES = [
        'primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'
    ]

    export default {
        name: 'AppAlert',

        props: {
            type: {
                type: String,
                default: TYPES[0],

                validator (value) {
                    return TYPES.includes(value)
                }
            },

            message: {
                type: String,
                required: true
            }
        }
    }
</script>

<style scoped>
    .close {
        outline: none !important;
    }
</style>
