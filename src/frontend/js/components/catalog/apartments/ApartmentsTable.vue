<template>
    <div class="table-responsive">
        <table class="table table-sm table-hover">
            <thead>
                <tr>
                    <th scope="col"></th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по номеру"
                        @click="updateSortValue('id')"><v-sort-icon
                            sort-prop="id"
                            :sort-value="sortValue"></v-sort-icon> №</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по дате публикации"
                        @click="updateSortValue('published_at')"><v-sort-icon
                            sort-prop="published_at"
                            :sort-value="sortValue"></v-sort-icon> Опубликовано</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по количеству комнат"
                        @click="updateSortValue('rooms')"><v-sort-icon
                            sort-prop="rooms"
                            :sort-value="sortValue"></v-sort-icon> Комнат</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по этажу"
                        @click="updateSortValue('floor')"><v-sort-icon
                            sort-prop="floor"
                            :sort-value="sortValue"></v-sort-icon> Этаж</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по этажности"
                        @click="updateSortValue('floors')"><v-sort-icon
                            sort-prop="floors"
                            :sort-value="sortValue"></v-sort-icon> Этажность</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по году постройки"
                        @click="updateSortValue('year_built')"><v-sort-icon
                            sort-prop="year_built"
                            :sort-value="sortValue"></v-sort-icon> Год постройки</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по общей площади"
                        @click="updateSortValue('size_total')"><v-sort-icon
                            sort-prop="size_total"
                            :sort-value="sortValue"></v-sort-icon> Пл.общ.</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по жилой площади"
                        @click="updateSortValue('size_living')"><v-sort-icon
                            sort-prop="size_living"
                            :sort-value="sortValue"></v-sort-icon> Пл.жил.</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по площади кухни"
                        @click="updateSortValue('size_kitchen')"><v-sort-icon
                            sort-prop="size_kitchen"
                            :sort-value="sortValue"></v-sort-icon> Пл.кухни</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по типу стен"
                        @click="updateSortValue('walls')"><v-sort-icon
                            sort-prop="walls"
                            :sort-value="sortValue"></v-sort-icon> Стены</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по типу балкона"
                        @click="updateSortValue('balcony')"><v-sort-icon
                            sort-prop="balcony"
                            :sort-value="sortValue"></v-sort-icon> Балкон</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по типу санузла"
                        @click="updateSortValue('bathroom')"><v-sort-icon
                            sort-prop="bathroom"
                            :sort-value="sortValue"></v-sort-icon> Санузел</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по цене"
                        @click="updateSortValue('price_amount')"><v-sort-icon
                            sort-prop="price_amount"
                            :sort-value="sortValue"></v-sort-icon> Цена</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по цене за квадратный метр"
                        @click="updateSortValue('price_per_sqm')"><v-sort-icon
                            sort-prop="price_per_sqm"
                            :sort-value="sortValue"></v-sort-icon> Цена/кв.м</th>

                    <th
                        scope="col"
                        class="sortable"
                        title="Сортировать по типу продавца"
                        @click="updateSortValue('seller_is_private')"><v-sort-icon
                            sort-prop="seller_is_private"
                            :sort-value="sortValue"></v-sort-icon> Продавец</th>

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
                    <td :title="item.seller.is_private ? 'Частное лицо' : 'Агентство/застройщик'">{{ item.seller.is_private ? 'ч' : 'а' }}</td>

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
    import VSortIcon from '../../VSortIcon'

    export default {
        name: 'ApartmentsTable',
        components: { VSortIcon },

        props: {
            sortValue: String,

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

        watch: {
            checkedItems (value) {
                this.$emit('check-items', value)
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
        },

        methods: {
            isSortAsc (prop) {
                return this.sortValue == prop
            },

            isSortDesc (prop) {
                return this.sortValue == `-${prop}`
            },

            updateSortValue (prop) {
                this.$emit(
                    'update-sort-value',
                    this.isSortAsc(prop) ? `-${prop}` : prop
                )
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
