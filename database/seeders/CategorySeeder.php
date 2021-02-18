<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Category;
use Illuminate\Database\Seeder;



class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Area::all()->each(function (Area $area){
        $area->areaCategoies()->saveMany(Category::factory( 2)->make());
        });
        //Category::factory( 20)->create();
    }
}
