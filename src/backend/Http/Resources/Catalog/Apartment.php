<?php

namespace App\Http\Resources\Catalog;

use App\Models\Source;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Catalog\Apartment as ApartmentModel;

class Apartment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'is_favorited' => $this->isFavorited(),
            'id'           => $this->id,
            'source'       => Source::find($this->source_id),
            'url'          => $this->url,

            'sell_type' => [
                'label' => ApartmentModel::FILTER_OPTIONS['sell_type'][$this->sell_type],
                'value' => $this->sell_type,
                'title' => ApartmentModel::FILTER_OPTIONS['sell_type'][$this->sell_type],
            ],

            'internal_id' => $this->internal_id,
            'title'       => $this->title,
            'description' => $this->description,
            'images'      => $this->images,
            'video'       => $this->video,
            'rooms'       => $this->rooms,
            'floor'       => $this->floor,
            'floors'      => $this->floors,
            'year_built'  => $this->year_built,

            'size' => [
                'total'   => $this->size_total,
                'living'  => $this->size_living,
                'kitchen' => $this->size_kitchen,
            ],

            'walls' => [
                'label' => $this->walls ? ApartmentModel::FILTER_OPTIONS['walls'][$this->walls] : null,
                'value' => $this->walls,
                'title' => $this->walls ? ApartmentModel::FILTER_OPTIONS['walls'][$this->walls] : null,
            ],

            'balcony' => [
                'label' => $this->balcony ? ApartmentModel::FILTER_OPTIONS['balcony'][$this->balcony] : null,
                'value' => $this->balcony,
                'title' => $this->balcony ? ApartmentModel::FILTER_OPTIONS['balcony'][$this->balcony] : null,
            ],

            'bathroom' => [
                'label' => $this->bathroom ? ApartmentModel::FILTER_OPTIONS['bathroom'][$this->bathroom] : null,
                'value' => $this->bathroom,
                'title' => $this->bathroom ? ApartmentModel::FILTER_OPTIONS['bathroom'][$this->bathroom] : null,
            ],

            'seller' => [
                'is_private' => $this->seller_is_private,

                'company' => [
                    'image'  => $this->seller_company_image,
                    'name'   => $this->seller_company_name,
                    'site'   => $this->seller_company_site,
                    'email'  => $this->seller_company_email,
                    'phones' => $this->seller_company_phones,
                ],

                'contact_person' => [
                    'image'  => $this->seller_contact_person_image,
                    'name'   => $this->seller_contact_person_name,
                    'email'  => $this->seller_contact_person_email,
                    'phones' => $this->seller_contact_person_phones,
                ],
            ],

            'price' => [
                'history'  => $this->price_history,
                'per_sqm'  => $this->price_per_sqm,
                'amount'   => $this->price_amount,
                'currency' => $this->price_currency,
            ],

            'address' => [
                'coordinates' => $this->address_coordinates,
                'formatted'   => $this->address_formatted,

                'components' => [
                    'country'  => $this->address_components_country,
                    'province' => $this->address_components_province,
                    'area'     => $this->address_components_area,
                    'locality' => $this->address_components_locality,
                    'district' => $this->address_components_district,
                    'street'   => $this->address_components_street,
                    'house'    => $this->address_components_house,
                ]
            ],

            'is_published' => $this->is_published,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
            'published_at' => $this->published_at,
        ];
    }
}
