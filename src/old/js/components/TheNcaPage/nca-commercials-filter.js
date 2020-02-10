const filter = {
    size_min: null,
    size_max: null,
    price_min: null,
    price_max: null,
    country: null,
    province: null,
    area: null,
    locality: null,
    district: null,
    street: null,
    house: null
}

const inputGroups = [
    {
        inputs: [
            {
                label: 'Площадь (м<sup>2</sup>)',
                name: 'size',
                type: 'range',
                min: 0,
                step: 0.1,
                placeholderMin: 'От',
                placeholderMax: 'До'
            },
            {
                label: 'Цена',
                name: 'price',
                type: 'range',
                min: 0,
                step: 1,
                placeholderMin: 'От',
                placeholderMax: 'До'
            }
        ]
    },
    {
        name: 'Дополнительные параметры поиска',
        collapse: true,

        inputs: [
            {
                label: 'Страна',
                name: 'country',
                type: 'select',
                options: 'country',

                defaultOption: {
                    name: 'Любая',
                    value: null
                }
            },
            {
                label: 'Область',
                name: 'province',
                type: 'select',
                options: 'province',

                defaultOption: {
                    name: 'Любая',
                    value: null
                }
            },
            {
                label: 'Район',
                name: 'area',
                type: 'select',
                options: 'area',

                defaultOption: {
                    name: 'Любой',
                    value: null
                }
            },
            {
                label: 'Нас. пункт',
                name: 'locality',
                type: 'select',
                options: 'locality',

                defaultOption: {
                    name: 'Любой',
                    value: null
                }
            },
            {
                label: 'Район нас. пункта',
                name: 'district',
                type: 'select',
                options: 'district',

                defaultOption: {
                    name: 'Любой',
                    value: null
                }
            },
            {
                label: 'Улица',
                name: 'street',
                type: 'select',
                options: 'street',

                defaultOption: {
                    name: 'Любая',
                    value: null
                }
            },
            {
                label: 'Номер дома',
                name: 'house',
                type: 'select',
                options: 'house',

                defaultOption: {
                    name: 'Любой',
                    value: null
                }
            }
        ]
    }
]

export { filter, inputGroups }
