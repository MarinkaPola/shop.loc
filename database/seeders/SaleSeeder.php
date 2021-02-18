<?php

namespace Database\Seeders;
use App\Models\Good;
use App\Models\Sale;
use App\Models\Area;
use App\Models\Category;
use Database\Factories\SaleFactory;
use Illuminate\Database\Seeder;



class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sales = Sale::factory( 3)->create();
        foreach ($sales as $sale) {
            $sale->areas()->attach(Area::inRandomOrder()->take(1)->get());
            $sale->categories()->attach(Category::inRandomOrder()->take(2)->get());
            $sale->goods()->attach(Good::inRandomOrder()->take(3)->get());
        }

    }
}

