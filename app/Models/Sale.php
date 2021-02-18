<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Sale extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'value_percentage',
    ];


    /**
     * @return MorphToMany
     */
    public function areas(): MorphToMany
    {
        return $this->morphedByMany( Area::class, 'saleable');
    }

    /**
     * @return MorphToMany
     */
    public function categories(): MorphToMany
    {
        return $this->morphedByMany( Category::class, 'saleable');
    }

    /**
     * @return MorphToMany
     */
    public function goods(): MorphToMany
    {
        return $this->morphedByMany( Good::class, 'saleable');
    }

}
