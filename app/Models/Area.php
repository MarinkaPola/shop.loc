<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Area extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
    ];



    public function areaCategoies(): HasMany
    {
        return $this->hasMany(Category::class, 'area_id');
    }

}
