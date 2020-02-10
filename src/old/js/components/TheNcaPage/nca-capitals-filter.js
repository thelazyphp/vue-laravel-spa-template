const filter = {
    size_min: null,
    size_max: null,
    land_size_min: null,
    land_size_max: null,
    price_min: null,
    price_max: null,
    walls_type: null,
    floors_total: [],
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
                label: 'Площадь участка (га)',
                name: 'land_size',
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
            },
            {
                label: 'Стены',
                name: 'walls_type',
                type: 'select',
                options: 'walls_type',

                defaultOption: {
                    name: 'Любые',
                    value: null
                }
            },
        ]
    },
    {
        name: 'Дополнительные параметры поиска',
        collapse: true,

        inputs: [
            {
                label: 'Этажность',
                name: 'floors_total',
                type: 'btn-checkbox',

                options: [
                    {
                        name: '1',
                        value: 1
                    },
                    {
                        name: '2 и более',
                        value: 2
                    }
                ]
            },
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
