<template>
    <div
        class="alert alert-dismissible fade show"
        :class="'alert-' + type"
        role="alert"
    >
        {{ message }}

        <button
            type="button"
            class="close"
            data-dismiss="alert"
            :aria-label="$t('alert.buttons.close')"
            @click="$emit('close')"
        >
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</template>

<script>
    export default {
        props: {
            type: {
                type: String,
                default: 'primary',
                validator: function (value) {
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
            },
            message: String
        }
    }
</script>

<style scoped>
    .close {
        outline: none !important;
    }
</style>
