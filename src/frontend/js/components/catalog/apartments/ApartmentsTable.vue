<template>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <v-table-header></v-table-header>

                    <v-table-header
                        title="Сортировать по номеру объявления"
                        sort-prop="id"
                        :is-sortable="true"
                        :sort-value="sortValue"
                        @click.native="updateSortValue('id')">№</v-table-header>

                    <v-table-header
                        title="Сортировать по дате/времени размещения"
                        sort-prop="published_at"
                        :is-sortable="true"
                        :sort-value="sortValue"
                        @click.native="updateSortValue('published_at')">Размещено</v-table-header>

                    <v-table-header
                        title="Сортировать по количеству комнат"
                        sort-prop="rooms"
                        :is-sortable="true"
                        :sort-value="sortValue"
                        @click.native="updateSortValue('rooms')">Комнат</v-table-header>

                    <v-table-header
                        title="Сортировать по этажу"
                        sort-prop="floor"
                        :is-sortable="true"
                        :sort-value="sortValue"
                        @click.native="updateSortValue('floor')">Этаж</v-table-header>

                    <v-table-header
                        title="Сортировать по этажности"
                        sort-prop="floors"
                        :is-sortable="true"
                        :sort-value="sortValue"
                        @click.native="updateSortValue('floors')">Этажность</v-table-header>

                    <v-table-header
                        title="Сортировать по году постройки"
                        sort-prop="year_built"
                        :is-sortable="true"
                        :sort-value="sortValue"
                        @click.native="updateSortValue('year_built')">Год постройки</v-table-header>

                    <v-table-header
                        title="Сортировать по общей площади"
                        sort-prop="size_total"
                        :is-sortable="true"
                        :sort-value="sortValue"
                        @click.native="updateSortValue('size_total')">Пл.общ.</v-table-header>

                    <v-table-header
                        title="Сортировать по жилой площади"
                        sort-prop="size_living"
                        :is-sortable="true"
                        :sort-value="sortValue"
                        @click.native="updateSortValue('size_living')">Пл.жил.</v-table-header>

                    <v-table-header
                        title="Сортировать по площади кухни"
                        sort-prop="size_kitchen"
                        :is-sortable="true"
                        :sort-value="sortValue"
                        @click.native="updateSortValue('size_kitchen')">Пл.кухни</v-table-header>

                    <v-table-header
                        title="Сортировать по типу стен"
                        sort-prop="walls"
                        :is-sortable="true"
                        :sort-value="sortValue"
                        @click.native="updateSortValue('walls')">Стены</v-table-header>

                    <v-table-header
                        title="Сортировать по типу балкона"
                        sort-prop="balcony"
                        :is-sortable="true"
                        :sort-value="sortValue"
                        @click.native="updateSortValue('balcony')">Балкон</v-table-header>

                    <v-table-header
                        title="Сортировать по типу санузла"
                        sort-prop="bathroom"
                        :is-sortable="true"
                        :sort-value="sortValue"
                        @click.native="updateSortValue('bathroom')">Санузел</v-table-header>

                    <v-table-header
                        title="Сортировать по цене"
                        sort-prop="price_amount"
                        :is-sortable="true"
                        :sort-value="sortValue"
                        @click.native="updateSortValue('price_amount')">Цена</v-table-header>

                    <v-table-header
                        title="Сортировать по цене за квадратный метр"
                        sort-prop="price_per_sqm"
                        :is-sortable="true"
                        :sort-value="sortValue"
                        @click.native="updateSortValue('price_per_sqm')">Цена/м<sup>2</sup></v-table-header>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="item in items"
                    :key="item.id"
                >
                    <td>
                        <button
                            type="button"
                            class="btn btn-link text-danger"
                            :title="item.is_favorited ? 'Удалить из избранных' : 'Добавить в избранные'"
                        >
                            <i :class="['fa-heart', item.is_favorited ? 'fas' : 'far']"></i>
                        </button>
                    </td>

                    <td>{{ item.id }}</td>
                    <td>{{ new Date(item.published_at).toLocaleString() }}</td>
                    <td>{{ item.rooms }}</td>
                    <td>{{ item.floor }}</td>
                    <td>{{ item.floors }}</td>
                    <td>{{ item.year_built }}</td>
                    <td :title="item.size.total ? (item.size.total + ' м2') : null">{{ item.size.total }}</td>
                    <td :title="item.size.total ? (item.size.living + ' м2') : null">{{ item.size.living }}</td>
                    <td :title="item.size.total ? (item.size.kitchen + ' м2') : null">{{ item.size.kitchen }}</td>
                    <td :title="item.walls.label">{{ item.walls.value }}</td>
                    <td :title="item.balcony.label">{{ item.balcony.value }}</td>
                    <td :title="item.bathroom.label">{{ item.bathroom.value }}</td>
                    <td :title="item.price.amount ? (item.price.amount + (item.price.currency ? (' ' + item.price.currency) : '')) : 'Цена договорная'">{{ item.price.amount ? item.price.amount : 'догов.' }}</td>
                    <td :title="item.price.amount && item.size.total ? (Math.round(item.price.amount / item.size.total) + (item.price.currency ? (' ' + item.price.currency + '/м2') : '')) : null">{{ item.price.amount && item.size.total ? (Math.round(item.price.amount / item.size.total)) : null }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import VTableHeader from '../../VTableHeader'

    export default {
        name: 'ApartmentsTable',
        components: { VTableHeader },

        props: {
            sortValue: String,

            items: {
                type: Array,

                default () {
                    return []
                }
            },
        },

        methods: {
            updateSortValue (prop) {
                this.$emit(
                    'update-sort-value',
                    this.sortValue == prop ? `-${prop}` : prop
                )
            },
        },
    }
</script>

<style scoped>
    .table th, td {
        white-space: nowrap;
    }

    .table > thead > tr > th {
        border-top: none;
        border-bottom: none;
    }

    .table > tbody > tr > td {
        vertical-align: middle;
    }
</style>
