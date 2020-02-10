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
            category: 'lands'
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
        prop: 'role',
        name: 'Назначение',
        sort: true
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
        name: 'Площадь (га)',
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
    }
]
