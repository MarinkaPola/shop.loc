<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Review extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'text',
        'mark'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function (self $model) {
            $model->author_id = auth()->user()->id;
        });
    }


    public function review_good()
    {
        return $this-> belongsTo(Good::class, 'good_id');
    }
    public function author()
    {
        return $this-> belongsTo(User::class, 'author_id');
    }

}
