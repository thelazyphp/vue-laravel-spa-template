<template>
    <div
        class="alert alert-dismissible fade show"
        :class="`alert-${type}`"
        role="alert"
    >
        <span v-html="message"></span>

        <button
            class="close"
            type="button"
            data-dismiss="alert"
            aria-label="Закрыть"
            @click="$emit('close')"
        >
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</template>

<script>
    export default {
        name: 'AppAlert',

        props: {
            message: {
                type: String,
                required: true
            },

            type: {
                type: String,
                default: 'primary',

                validator (value) {
                    return [
                        'primary',
                        'secondary',
                        'success',
                        'danger',
                        'warning',
                        'info',
                        'light',
                        'dark'
                    ].includes(value)
                }
            }
        }
    }
</script>
