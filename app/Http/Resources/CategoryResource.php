<?php

namespace App\Http\Resources;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CategoryResource
 * @package App\Http\Resources
 * @mixin Category
 */

class CategoryResource extends JsonResource
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
            'category_sales' => SaleResource::collection($this->whenLoaded('sales')),
            'category_goods' => GoodResource::collection($this->whenLoaded('categoryGoods'))

        ];
    }
}
