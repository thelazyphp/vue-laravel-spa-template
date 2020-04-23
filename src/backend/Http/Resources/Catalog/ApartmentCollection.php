<?php

namespace App\Http\Resources\Catalog;

use App\Models\Source;
use App\Models\Catalog\Apartment;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ApartmentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,

            'included' => [
                'sources' => Source::all(),
            ],

            'meta' => [
                'favorited_total' => Apartment::getFavorited()->count(),

                'filter_props' => [
                    'rooms' => [],

                    'floor' => [
                        'min' => null,
                        'max' => null,
                    ],

                    'floors' => [
                        'min' => null,
                        'max' => null,
                    ],

                    'year_built' => [
                        'min' => null,
                        'max' => null,
                    ],

                    'size_total' => [
                        'min' => null,
                        'max' => null,
                    ],

                    'size_living' => [
                        'min' => null,
                        'max' => null,
                    ],

                    'size_kitchen' => [
                        'min' => null,
                        'max' => null,
                    ],

                    'price_amount' => [
                        'min' => null,
                        'max' => null,
                    ],

                    'walls' => [],
                    'balcony' => [],
                    'bathroom' => [],

                    'images' => [
                        'not' => null,
                    ],

                    'source_id' => [],
                    'seller_is_private' => null,

                    'published_at' => [
                        'date_min' => null,
                        'date_max' => null,
                        'time_min' => null,
                        'time_max' => null,
                    ],
                ],

                'filter_options' => $this->getFilterOptions(),
            ],
        ];
    }

    /**
     * @return array
     */
    public function getFilterOptions()
    {
        $options = [];

        foreach (Apartment::FILTER_OPTIONS['sell_type'] as $key => $value) {
            $options['sell_type'][] = [
                'label' => $value,
                'value' => $key,
                'title' => $value,
            ];
        }

        foreach (Apartment::FILTER_OPTIONS['walls'] as $key => $value) {
            $options['walls'][] = [
                'label' => $value,
                'value' => $key,
                'title' => $value,
            ];
        }

        foreach (Apartment::FILTER_OPTIONS['balcony'] as $key => $value) {
            $options['balcony'][] = [
                'label' => $value,
                'value' => $key,
                'title' => $value,
            ];
        }

        foreach (Apartment::FILTER_OPTIONS['bathroom'] as $key => $value) {
            $options['bathroom'][] = [
                'label' => $value,
                'value' => $key,
                'title' => $value,
            ];
        }

        return $options;
    }
}
