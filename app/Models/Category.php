<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;



class Category extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
       // 'area_id'
    ];


    public function categoryGoods(): HasMany
    {
        return $this->hasMany(Good::class, 'category_id');
    }

    public function areaCategory()
    {
        return $this-> belongsTo(Area::class, 'area_id');
    }

    public function sales(): MorphToMany
    {
        return $this->morphToMany(Sale::class, 'saleable');
    }
}
