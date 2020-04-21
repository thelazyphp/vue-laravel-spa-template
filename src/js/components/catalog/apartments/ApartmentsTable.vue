<template>
    <div class="table-responsive">
        <table class="table table-sm table-hover">
            <thead>
                <tr>
                    <th scope="col"></th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по номеру"><i class="fas fa-long-arrow-alt-down"></i> №</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по дате публикации">Опубликована</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по количеству комнат">Комнат</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по этажу">Этаж</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по этажности">Этажность</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по году постройки">Год постройки</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по общей площади">Пл.общ.</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по жилой площади">Пл.жил.</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по площади кухни">Пл.кухни</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по типу стен">Стены</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по типу балкона">Балкон</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по типу санузла">Санузел</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по цене">Цена</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по цене за квадратный метр">Цена/кв.м</th>

                    <th scope="col">
                        <div
                            title="Отметить все объявления"
                            class="custom-control custom-checkbox"
                        >
                            <input
                                id="allAdsCheck"
                                v-model="allAdsCheck"
                                type="checkbox"
                                class="custom-control-input">

                            <label
                                class="custom-control-label"
                                for="allAdsCheck"></label>
                        </div>
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="item in items"
                    :key="item.id"
                >
                    <td>
                        <a
                            href="javascript:void(0)"
                            role="button"
                            :title="item.is_favorited ? 'Удалить из избранных' : 'Добавить в избранные'"
                            @click="$store.dispatch('catalog/toggleFavorited', { category: 'apartments', id: item.id })"><i :class="['fa-star', item.is_favorited ? 'fas' : 'far']"></i></a>
                    </td>

                    <td>{{ item.id }}</td>
                    <td>{{ new Date(item.published_at).toLocaleString() }}</td>
                    <td>{{ item.rooms }}</td>
                    <td>{{ item.floor }}</td>
                    <td>{{ item.floors }}</td>
                    <td>{{ item.year_built }}</td>
                    <td>{{ item.size.total }}</td>
                    <td>{{ item.size.living }}</td>
                    <td>{{ item.size.kitchen }}</td>
                    <td :title="item.walls.label">{{ item.walls.value }}</td>
                    <td :title="item.balcony.label">{{ item.balcony.value }}</td>
                    <td :title="item.bathroom.label">{{ item.bathroom.value }}</td>
                    <td :title="item.price.amount ? (item.price.amount + (item.price.currency ? (' ' + item.price.currency) : '')) : 'Цена договорная'">{{ item.price.amount ? item.price.amount : 'догов.' }}</td>
                    <td :title="item.price.amount && item.size.total ? (Math.round(item.price.amount / item.size.total) + (item.price.currency ? (' ' + item.price.currency + '/кв.м') : '')) : null">{{ item.price.amount && item.size.total ? (Math.round(item.price.amount / item.size.total)) : null }}</td>

                    <td>
                        <div
                            class="custom-control custom-checkbox"
                            :title="`Отметить объявление №${item.id}`"
                        >
                            <input
                                :id="`adCheck${item.id}`"
                                v-model="checkedItems"
                                type="checkbox"
                                class="custom-control-input"
                                :value="item">

                            <label
                                class="custom-control-label"
                                :for="`adCheck${item.id}`"></label>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: 'ApartmentsTable',

        props: {
            items: {
                type: Array,

                default () {
                    return []
                }
            }
        },

        data () {
            return {
                checkedItems: []
            }
        },

        computed: {
            allAdsCheck: {
                get () {
                    return this.items.length
                        && this.checkedItems.length == this.items.length
                },

                set (value) {
                    this.checkedItems = []

                    if (value) {
                        this.items.forEach(item => this.checkedItems.push(item))
                    }
                }
            }
        }
    }
</script>

<style scoped>
    .table {
        font-size: 13px;
    }

    .table th, td {
        white-space: nowrap;
    }

    .table > thead > tr > th {
        border-top: none;
    }

    .table > thead > tr > th.sortable {
        cursor: pointer;
    }

    .table > tbody > tr > td {
        vertical-align: middle;
    }
</style>
