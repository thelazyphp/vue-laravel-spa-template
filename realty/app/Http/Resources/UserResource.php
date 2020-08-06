<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\ImageResource;
use App\Image;

class UserResource extends JsonResource
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
            'company' => new CompanyResource($this->company),
            'image' => new ImageResource(Image::find($this->image_id)),
            'role' => $this->role,
            'f_name' => $this->f_name,
            'm_name' => $this->m_name,
            'l_name' => $this->l_name,
            'email' => $this->email,
            'preferred_catalog_items_category' => $this->preferred_catalog_items_category,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
