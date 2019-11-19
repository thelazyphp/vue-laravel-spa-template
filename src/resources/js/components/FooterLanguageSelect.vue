<template>
    <div class="dropup">
        <a
            id="dropdownMenuButton"
            href="#"
            class="dropdown-toggle"
            role="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
        >
            {{ $t('language', $i18n.locale) }}
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
            <a
                v-for="locale in locales"
                :key="locale"
                href="#"
                class="dropdown-item"
                :class="{ 'active': locale == $i18n.locale }"
                @click="$i18n.locale = locale"
            >
                {{ $t('language', locale) }}
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        computed: {
            locales () {
                return Object.keys(this.$i18n.messages)
            }
        }
    }
</script>
