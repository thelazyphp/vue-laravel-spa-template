import ToolsColumn from './ToolsColumn'

export default [
    {
        prop: 'id',
        name: '#',
        sort: true
    },
    {
        component: ToolsColumn,

        componentData: {
            category: 'commercials'
        }
    },
    {
        prop: 'contract_date',
        name: 'Дата',
        sort: true,

        filter (row) {
            if (row.contract_date) {
                const date = new Date(row.contract_date)
                return `${date.getDate()}.${date.getMonth() + 1}.${date.getFullYear()}`
            }
        }
    },
    {
        prop: 'isolated_type',
        name: 'Тип / назначение ИП',
        sort: true,

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
        sort: true
    },
    {
        prop: 'street',
        name: 'Адрес',
        sort: true,

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
        sort: true
    },
    {
        prop: 'rooms_count',
        name: 'Кол-во комнат',
        sort: true
    },
    {
        prop: 'floor',
        name: 'Этаж / этажность',
        sort: true
    },
    {
        prop: 'price',
        name: 'Цена',
        sort: true,

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
        sort: true
    },
    {
        prop: 'type',
        name: 'Тип / назначение',
        sort: true,

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
        sort: true
    },
    {
        prop: 'exploitation_date',
        name: 'Год постройки',
        sort: true,

        filter (row) {
            if (row.exploitation_date) {
                return (new Date(row.exploitation_date)).getFullYear()
            }
        },

        tooltip (row) {
            if (row.exploitation_date) {
                const date = new Date(row.exploitation_date)
                return `Дата ввода в эксплуатацию: ${date.getDate()}.${date.getMonth() + 1}.${date.getFullYear()}`
            }
        }
    }
]
