<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    const PAYMENT_BYCARD = 'paymentByCard';
    const PAYMENT_CASH = 'cash';
    const PAYMENT_COD = 'cod';

    const DELIVERY_PICKUP = 'pickup';
    const DELIVERY_COURIER = 'courierDelivery';


    protected $fillable = [
       // 'buyer_id',
        'payment',
        'delivery',
        'goods_is_paid',
        'sum',
    ];



    const PAYMENT = [
        self::PAYMENT_BYCARD,
        self::PAYMENT_CASH,
        self::PAYMENT_COD
    ];

    const DELIVERY = [
        self::DELIVERY_PICKUP,
        self::DELIVERY_COURIER,
    ];

    public function buyer()
    {
        return $this-> belongsTo(User::class, 'buyer_id');
    }
    public function orderGoods(): BelongsToMany
    {
        return $this->belongsToMany(Good::class)->withPivot('count')->withTimestamps();
    }
}
