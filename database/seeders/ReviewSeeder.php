<?php

namespace Database\Seeders;

use App\Models\Good;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;



class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Good::all()->each(function (Good $good) {
                $good->reviews()->saveMany(Review::factory(1)->make(['author_id' => User::inRandomOrder()->first()->id]));
        });
    }
}
