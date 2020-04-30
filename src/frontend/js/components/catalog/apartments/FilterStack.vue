<template></template>

<script>
    export default {
        name: 'FilterStack',

        computed: {
            filters () {
                return [
                    {
                        label: 'Комнат',
                        prop: 'rooms',
                        value: [],
                    },
                    {
                        label: 'Этаж',
                        prop: 'floor',
                        value: { min: null, max: null },
                    },
                    {
                        label: 'Этажность',
                        prop: 'floors',
                        value: { min: null, max: null },
                    },
                    {
                        label: 'Год постройки',
                        prop: 'year_built',
                        value: { min: null, max: null },
                    },
                    {
                        label: 'Площадь общая',
                        prop: 'size_total',
                        value: { min: null, max: null },
                    },
                    {
                        label: 'Площадь жилая',
                        prop: 'size_living',
                        value: { min: null, max: null },
                    },
                    {
                        label: 'Площадь кухни',
                        prop: 'size_kitchen',
                        value: { min: null, max: null },
                    },
                    {
                        label: 'Дата публикации',
                        prop: 'published_at',
                        value: { date_min: null, date_max: null },
                    },
                    {
                        label: 'Время публикации',
                        prop: 'published_at',
                        value: { time_min: null, time_max: null },
                    },
                ]
            },
        },
    }
</script>
