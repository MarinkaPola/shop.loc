<?php

namespace App\Http\Resources;
use App\Models\Good;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class GoodResource
 * @package App\Http\Resources
 * @mixin Good
 */
class GoodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'photo' => $this->photo,
            'feature' => $this->feature,
            'count' => $this->count,
            'price' => $this->price,
            'brand' => $this->brand,
            'category_id' => $this->category_id,
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'sale_goods' => SaleResource::collection($this->whenLoaded('sales'))

        ];
    }
}
