<?php

namespace App\Http\Resources;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SaleResource
 * @package App\Http\Resources
 * @mixin Sale
 */
class SaleResource extends JsonResource
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
            'value_percentage' => $this->value_percentage,
            'created_at' => $this->created_at->toDateTimeString(),
            'sale_areas' => AreaResource::collection($this->whenLoaded('areas')),
            'sale_categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'sale_goods' => GoodResource::collection($this->whenLoaded('goods')),
        ];
    }
}
