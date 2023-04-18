<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int     id
 * @property  \App\Models\Category    category
 * @property  string name
 * @property  string brand
 * @property  string sku
 * @property  float  price
 * @property  bool   is_available
 * @property  string created_at
 * @property  string updated_at
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'name' => $this->name,
            'brand' => $this->brand,
            'sku' => $this->sku,
            'price' => $this->price,
            'is_available' => $this->is_available,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
