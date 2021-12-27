<?php

namespace App\Http\Resources;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class OrderResource
 * @package App\Http\Resources
 * @mixin Order
 */
class OrderResource extends JsonResource
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
            'payment' => $this->payment,
            'delivery' => $this->delivery,
            'goods_is_paid' => $this->goods_is_paid,
            'buyer_id' => $this->buyer_id,
            'sum' => $this->sum,
            'buyer' => UserResource::make($this->whenLoaded('buyer')),
            'order_goods' => GoodResource::collection($this->whenLoaded('orderGoods')),
            'info' => $this->info,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
