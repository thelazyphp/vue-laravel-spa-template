<template>
    <div class="alert alert-dismissible fade show" role="alert" :class="'alert-' + type">
        {{ message }}

        <button type="button" class="close" data-dismiss="alert" @click="$emit('close')" :aria-label="$t('components.alert.close')">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</template>

<script>
    export default {
        name: 'alert',

        props: {
            type: String,
            message: String
        }
    }
</script>

<style scoped>
    .close {
        outline: none !important;
    }
</style>
