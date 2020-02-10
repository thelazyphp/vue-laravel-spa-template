export default [
    {
        name: 'Фото',
        display: true,

        component: {
            props: ['row'],

            template: `
                <div
                    ref="image"
                    class="rounded"
                    style="width: 100px; height: 100px"
                >
                </div>
            `,

            computed: {
                fallbackImage () {
                    return 'images/image_not_found.jpg'
                }
            },

            mounted () {
                this.$refs.image.style.backgroundImage = `url("${this.row.images[0] || this.fallbackImage}"), url("${this.fallbackImage}")`
                this.$refs.image.style.backgroundSize = 'cover'
                this.$refs.image.style.backgroundRepeat = 'no-repeat'
                this.$refs.image.style.backgroundPosition = 'center center'
            }
        }
    },
    {
        prop: 'id',
        name: '№',
        sort: true,
        display: true
    },
    {
        prop: 'updated_at',
        name: 'Дата',
        sort: true,
        display: true,

        filter (row) {
            if (row.updated_at) {
                return new Date(row.updated_at).toLocaleDateString()
            }
        }
    },
    {
        prop: 'locality',
        name: 'Нас. пункт',
        sort: true,
        display: true
    },
    {
        prop: 'street',
        name: 'Адрес',
        sort: true,
        display: true,

        filter (row) {
            if (row.street) {
                let value = row.street

                if (row.house) {
                    value += ` ${row.house}`
                }

                return value
            }
        }
    },
    {
        prop: 'rooms_count',
        name: 'Комнат',
        sort: true,
        display: true
    },
    {
        prop: 'floor',
        name: 'Этаж / этажность',
        sort: true,
        display: true,

        filter (row) {
            if (row.floor) {
                let value = row.floor

                if (row.floors_total) {
                    value += ` / ${row.floors_total}`
                }

                return value
            }
        }
    },
    {
        prop: 'construction_year',
        name: 'Год постройки',
        sort: true,
        display: true
    },
    {
        prop: 'size',
        name: 'Площадь общ. / жил. / кухни (м<sup>2</sup>)',
        sort: true,
        display: true,

        filter (row) {
            let parts = []

            parts.push(
                row.size ? row.size : '-'
            )

            parts.push(
                row.living_size ? row.living_size : '-'
            )

            parts.push(
                row.kitchen_size ? row.kitchen_size : '-'
            )

            return parts.join(' / ')
        }
    },
    {
        prop: 'walls_type',
        name: 'Стены',
        sort: true,
        display: true
    },
    {
        prop: 'balcony_type',
        name: 'Сан. узел',
        sort: true,
        display: true
    },
    {
        prop: 'bathroom_type',
        name: 'Балкон',
        sort: true,
        display: true
    },
    {
        name: 'Цена за м<sup>2</sup>',
        display: true,

        filter (row) {
            if (row.price && row.size) {
                let value = Math.round(
                    row.price / row.size
                )

                if (row.price_currency) {
                    value += ` ${row.price_currency}`
                }

                return value
            }
        }
    },
    {
        prop: 'price',
        name: 'Цена',
        sort: true,
        display: true,

        filter (row) {
            if (row.price) {
                let value = row.price

                if (row.price_currency) {
                    value += ` ${row.price_currency}`
                }

                return value
            }
        }
    },
    {
        component: {
            props: ['row'],

            template: `
                <ul class="list-inline mb-0">
                    <a
                        href="javascript:void(0)"
                        class="list-inline-item"
                        title="Показать"
                    >
                        <i class="fas fa-search"></i>
                    </a>
                    <a
                        href="javascript:void(0)"
                        class="list-inline-item"
                        title="Добавить в избранное"
                    >
                        <i class="far fa-star"></i>
                    </a>
                    <a
                        class="list-inline-item"
                        target="_blank"
                        title="Посмотреть на сайте"
                        :href="row.url"
                    >
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </ul>
            `
        }
    }
]
