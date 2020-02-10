<template>
    <ul class="list-inline">
        <li class="list-inline-item">
            <a
                href="javascript:void(0)"
                class="badge badge-pill"
                data-toggle="tooltip"
                :class="row.P ? 'badge-primary' : 'badge-light'"
                title="Часть"
                @click="invertFlag('P')"
            >
                Ч
            </a>
        </li>
        <li class="list-inline-item">
            <a
                href="javascript:void(0)"
                class="badge badge-pill"
                data-toggle="tooltip"
                :class="row.S ? 'badge-primary' : 'badge-light'"
                title="Доли"
                @click="invertFlag('S')"
            >
                Д
            </a>
        </li>
        <li class="list-inline-item">
            <a
                href="javascript:void(0)"
                class="badge badge-pill"
                data-toggle="tooltip"
                :class="row.NB ? 'badge-primary' : 'badge-light'"
                title="Новостройка"
                @click="invertFlag('NB')"
            >
                НС
            </a>
        </li>
        <li class="list-inline-item">
            <a
                href="javascript:void(0)"
                class="badge badge-pill"
                data-toggle="tooltip"
                :class="row.LP ? 'badge-primary' : 'badge-light'"
                title="Цена занижена"
                @click="invertFlag('LP')"
            >
                ЦЗ
            </a>
        </li>
        <li class="list-inline-item">
            <a
                href="javascript:void(0)"
                class="badge badge-pill"
                data-toggle="tooltip"
                :class="row.DEL ? 'badge-primary' : 'badge-light'"
                title="Удалено"
                @click="invertFlag('DEL')"
            >
                УДАЛ
            </a>
        </li>
    </ul>
</template>

<script>
    import Http from '../../utils/Http'

    export default {
        name: 'ToolsColumn',

        props: {
            row: Object,
            data: Object
        },

        mounted () {
            $('[data-toggle="tooltip"]').tooltip()
        },

        methods: {
            invertFlag (flag) {
                new Http().patch(`/nca/${this.data.category}/${this.row.id}`, {
                    [flag]: ! this.row[flag]
                })
                .then(() => {
                    this.row[flag] = ! this.row[flag]
                })
            }
        }
    }
</script>
