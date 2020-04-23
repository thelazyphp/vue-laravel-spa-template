<?php

namespace App\Utils;

class JandexGeocoder
{
    const AREAS = [
        'Барановичский',
        'Белыничский',
        'Березинский',
        'Березовский',
        'Берестовицкий',
        'Бешенковичский',
        'Бобруйский',
        'Борисовский',
        'Брагинский',
        'Браславский',
        'Брестский',
        'Буда-Кошелевский',
        'Быховский',
        'Верхнедвинский',
        'Ветковский',
        'Вилейский',
        'Витебский',
        'Волковыский',
        'Воложинский',
        'Вороновский',
        'Ганцевичский',
        'Глубокский',
        'Глусский',
        'Гомельский',
        'Горецкий',
        'Городокский',
        'Гродненский',
        'Дзержинский',
        'Добрушский',
        'Докшицкий',
        'Дрибинский',
        'Дрогичинский',
        'Дубровенский',
        'Дятловский',
        'Ельский',
        'Жабинковский',
        'Житковичский',
        'Жлобинский',
        'Зельвенский',
        'Ивановский',
        'Ивацевичский',
        'Ивьевский',
        'Калинковичский',
        'Каменецкий',
        'Кировский',
        'Клецкий',
        'Климовичский',
        'Кличевский',
        'Кобринский',
        'Копыльский',
        'Кореличский',
        'Кормянский',
        'Костюковичский',
        'Краснопольский',
        'Кричевский',
        'Круглянский',
        'Крупский',
        'Лельчицкий',
        'Лепельский',
        'Лидский',
        'Лиозненский',
        'Логойский',
        'Лоевский',
        'Лунинецкий',
        'Любанский',
        'Ляховичский',
        'Малоритский',
        'Минский',
        'Миорский',
        'Могилевский',
        'Мозырский',
        'Молодечненский',
        'Мостовский',
        'Мстиславский',
        'Мядельский',
        'Наровлянский',
        'Несвижский',
        'Новогрудский',
        'Октябрьский',
        'Оршанский',
        'Осиповичский',
        'Островецкий',
        'Ошмянский',
        'Петриковский',
        'Пинский',
        'Полоцкий',
        'Поставский',
        'Пружанский',
        'Пуховичский',
        'Речицкий',
        'Рогачевский',
        'Россонский',
        'Светлогорский',
        'Свислочский',
        'Сенненский',
        'Славгородский',
        'Слонимский',
        'Слуцкий',
        'Смолевичский',
        'Сморгонский',
        'Солигорский',
        'Стародорожский',
        'Столбцовский',
        'Столинский',
        'Толочинский',
        'Узденский',
        'Ушачский',
        'Хойникский',
        'Хотимский',
        'Чаусский',
        'Чашникский',
        'Червенский',
        'Чериковский',
        'Чечерский',
        'Шарковщинский',
        'Шкловский',
        'Шумилинский',
        'Щучинский',
    ];

    /**
     * @param  string  $geocode
     * @return array
     */
    public static function geocode($geocode)
    {
        $apikey = env('JANDEX_API_KEY');
        $geocode = rawurlencode($geocode);

        $data = json_decode(
            file_get_contents(
                "https://geocode-maps.yandex.ru/1.x?format=json&apikey={$apikey}&geocode={$geocode}"
            )
        );

        $addressComponents = [];

        if (
            $data
                ->response
                ->GeoObjectCollection
                ->metaDataProperty
                ->GeocoderResponseMetaData
                ->found > 0
        ) {
            $geoObject = $data
                ->response
                ->GeoObjectCollection
                ->featureMember[0]
                ->GeoObject;

            foreach (
                $geoObject
                    ->metaDataProperty
                    ->GeocoderMetaData
                    ->Address
                    ->Components as $component
            ) {
                if (
                    $component->kind == 'area'
                    && mb_strpos($component->name, 'район') === false
                ) {
                    continue;
                }

                $addressComponents[
                    $component->kind
                ] = $component->name;
            }

            [$long, $lat] = explode(
                ' ', $geoObject->Point->pos, 2
            );

            $addressComponents['lat'] = $lat;
            $addressComponents['long'] = $long;

            if (isset($addressComponents['locality'])) {
                foreach (self::AREAS as $area) {
                    if (
                        mb_stripos(
                            $area,
                            mb_substr($addressComponents['locality'], 0, 4)
                        ) !== false
                    ) {
                        $addressComponents['area'] = "{$area} район";
                    }
                }
            }
        }

        return $addressComponents;
    }

    /**
     * @param  array  $addressComponents
     * @return string
     */
    public function getFormattedAddress($addressComponents)
    {
        $formattedAddress = '';

        if (isset($addressComponents['country'])) {
            $formattedAddress .= $addressComponents['country'];
        }

        if (isset($addressComponents['province'])) {
            $formattedAddress .= ", {$addressComponents['province']}";
        }

        if (isset($addressComponents['area'])) {
            $formattedAddress .= ", {$addressComponents['area']}";
        }

        if (isset($addressComponents['locality'])) {
            $formattedAddress .= ", {$addressComponents['locality']}";
        }

        if (isset($addressComponents['district'])) {
            $formattedAddress .= ", {$addressComponents['district']}";
        }

        if (isset($addressComponents['street'])) {
            $formattedAddress .= ", {$addressComponents['street']}";
        }

        if (isset($addressComponents['house'])) {
            $formattedAddress .= ", {$addressComponents['house']}";
        }

        return $formattedAddress;
    }
}
