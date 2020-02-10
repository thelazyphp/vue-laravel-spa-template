export default [
    {
        prop: 'id',
        name: '№',
        sort: true,
        display: true
    },
    {
        prop: 'contract_date',
        name: 'Дата',
        sort: true,
        display: true,

        filter (row) {
            if (row.contract_date) {
                return new Date(row.contract_date).toLocaleDateString()
            }
        }
    },
    {
        prop: 'isolated_type',
        name: 'Тип / назначение ИП',
        sort: true,
        display: false,

        filter (row) {
            if (row.isolated_type) {
                let value = row.isolated_type

                if (row.isolated_role) {
                    value += ` /<br>${row.isolated_role}`
                }

                return value
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
        prop: 'size',
        name: 'Площадь (м<sup>2</sup>)',
        sort: true,
        display: true
    },
    {
        prop: 'rooms_count',
        name: 'Кол-во комнат',
        sort: true,
        display: true
    },
    {
        prop: 'floor',
        name: 'Этаж / этажность',
        sort: true,
        display: true
    },
    {
        prop: 'price',
        name: 'Цена',
        sort: true,
        display: true,

        tooltip (row) {
            if (
                row.price_description
                && row.price_description != '-'
                && row.price_description != '--'
            ) {
                return row.price_description
            }
        }
    },
    {
        prop: 'price_currency',
        name: 'Валюта',
        sort: true,
        display: true
    },
    {
        prop: 'type',
        name: 'Тип / назначение',
        sort: true,
        display: false,

        filter (row) {
            if (row.type) {
                let value = row.type

                if (row.role) {
                    value += ` /<br>${row.role}`
                }

                return value
            }
        }
    },
    {
        prop: 'walls_type',
        name: 'Стены',
        sort: true,
        display: true
    },
    {
        prop: 'exploitation_date',
        name: 'Год постройки',
        sort: true,
        display: true,

        filter (row) {
            if (row.exploitation_date) {
                return (new Date(row.exploitation_date)).getFullYear()
            }
        },

        tooltip (row) {
            if (row.exploitation_date) {
                return `Дата ввода в эксплуатацию: ${new Date(row.exploitation_date).toLocaleDateString()}`
            }
        }
    },
    {
        component: {
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
                </ul>
            `
        }
    }
]
