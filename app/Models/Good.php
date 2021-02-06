<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Good extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
        'photo',
        'feature',
        'count',
        'price',
        'sale',
        'category_id',

    ];


    public function goodOrders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)->withPivot('count')->withTimestamps();
    }

    public function category()
    {
        return $this-> belongsTo(Category::class, 'category_id');
    }



}
