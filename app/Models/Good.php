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


    public function order()
    {
        return $this-> belongsTo(Order::class, 'order_id');
    }

    public function goodUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('count')->withTimestamps();
    }

    public function category()
    {
        return $this-> belongsTo(Category::class, 'category_id');
    }



}
