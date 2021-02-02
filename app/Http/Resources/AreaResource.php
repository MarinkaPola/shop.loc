<?php

namespace App\Http\Resources;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class AreaResource
 * @package App\Http\Resources
 * @mixin Area
 */
class AreaResource extends JsonResource
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
            'area_categories' => CategoryResource::collection($this->whenLoaded('areaCategoies')),

        ];
    }
}
