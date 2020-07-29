<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'company_id' => $this->company_id,
            'image' => $this->whenLoaded('image'),
            'role' => $this->role,
            'f_name' => $this->f_name,
            'm_name' => $this->m_name,
            'l_name' => $this->l_name,
            'email' => $this->email,

            'employees' => $this->when($this->isManager(), function () {
                return $this->employees();
            }),

            'preferred_catalog_items_category' => $this->preferred_catalog_items_category,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
