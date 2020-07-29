<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SellerResource;
use App\CatalogItem;

class CatalogItemResource extends JsonResource
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
            'id' => $this->id,
            'seller' => new SellerResource($this->whenLoaded('seller')),
            'transaction' => $this->transaction,
            'category' => $this->category,
            'type' => $this->type,
            'url' => $this->url,
            'images' => $this->images,
            'title' => $this->title,
            'address' => $this->address,

            $this->mergeWhen($this->category == CatalogItem::CATEGORY_APARTMENTS, [
                'rooms' => $this->rooms,
                'floor' => $this->floor,
                'floors' => $this->floors,
                'year_built' => $this->year_built,

                'size' => [
                    'total' => $this->size_total,
                    'living' => $this->size_living,
                    'kitchen' => $this->size_kitchen,
                ],

                'walls' => $this->walls,
                'balcony' => $this->balcony,
                'bathroom' => $this->bathroom,
            ]),

            $this->mergeWhen($this->category == CatalogItem::CATEGORY_HOUSES, [
                'rooms' => $this->rooms,
                'floors' => $this->floors,
                'year_built' => $this->year_built,

                'size' => [
                    'land' => $this->size_land,
                    'total' => $this->size_total,
                    'living' => $this->size_living,
                    'kitchen' => $this->size_kitchen,
                ],

                'roof' => $this->roof,
                'walls' => $this->walls,
                'bathroom' => $this->bathroom,
            ]),

            $this->mergeWhen($this->category == CatalogItem::CATEGORY_LANDS, [
                'size' => [
                    'land' => $this->size_land,
                ],
            ]),

            $this->mergeWhen($this->category == CatalogItem::CATEGORY_COMMERCIAL_REAL_ESTATE, [
                'rooms' => $this->rooms,
                'floor' => $this->floor,
                'floors' => $this->floors,
                'year_built' => $this->year_built,

                'size' => [
                    'land' => $this->size_land,
                    'total' => $this->size_total,
                    'living' => $this->size_living,
                    'kitchen' => $this->size_kitchen,
                ],

                'roof' => $this->roof,
                'walls' => $this->walls,
                'balcony' => $this->balcony,
                'bathroom' => $this->bathroom,
            ]),

            'price' => [
                'amount' => $this->price_amount,
                'currency' => $this->price_currency,

                'sq_m' => [
                    'amount' => $this->price_sq_m_amount,
                    'currency' => $this->price_sq_m_currency,
                ]
            ],

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'published_at' => $this->published_at,
        ];
    }
}
