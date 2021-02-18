<?php

namespace Database\Seeders;
use App\Models\Category;
use App\Models\Good;
use Illuminate\Database\Seeder;

class GoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     */
    public function run()
    {
        Category::all()->each(function (Category $category){
            $category->categoryGoods()->saveMany(Good::factory( 4)->make());
        });
    }


}
