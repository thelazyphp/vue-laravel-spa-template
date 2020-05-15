<template>
    <div
        v-if="filter"
        class="row no-gutters"
    >
        <div
            v-for="(value, key, index) in filter"
            :key="index"
            class="col-auto p-1 m-1 rounded bg-light"
        >
            <template v-if="key == 'price_amount'">Цена: {{ value.min ? `от ${value.min}` : '' }} {{ value.max ? `до ${value.max}` : '' }}</template>
            <template v-if="key == 'rooms'">Комнат: {{ value.join(', ') }}</template>
            <template v-if="key == 'size_total'">Пл.общ.: {{ value.min ? `от ${value.min}` : '' }} {{ value.max ? `до ${value.max}` : '' }}</template>
            <template v-if="key == 'bathroom'">Санузел: {{ value.join(', ') }}</template>
            <template v-if="key == 'year_built'">Год постройки: {{ value.min ? `от ${value.min}` : '' }} {{ value.max ? `до ${value.max}` : '' }}</template>
            <template v-if="key == 'floor'">Этаж: {{ value.min ? `от ${value.min}` : '' }} {{ value.max ? `до ${value.max}` : '' }}</template>
            <template v-if="key == 'floors'">Этажность: {{ value.min ? `от ${value.min}` : '' }} {{ value.max ? `до ${value.max}` : '' }}</template>
            <template v-if="key == 'size_living'">Пл.жил.: {{ value.min ? `от ${value.min}` : '' }} {{ value.max ? `до ${value.max}` : '' }}</template>
            <template v-if="key == 'size_kitchen'">Пл.кухни: {{ value.min ? `от ${value.min}` : '' }} {{ value.max ? `до ${value.max}` : '' }}</template>
            <template v-if="key == 'walls'">Стены: {{ value.join(', ') }}</template>
            <template v-if="key == 'balcony'">Балкон: {{ value.join(', ') }}</template>
        </div>

        <div class="col-auto">
            <button
                type="button"
                title="Сбросить фильтры"
                class="btn btn-link text-danger"
                @click="$emit('reset')"
            >
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ApartmentsFiltersTags',

        props: {
            filter: {
                type: Object,
                default: null
            },
        },
    }
</script>
