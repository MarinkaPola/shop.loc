<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AreaSeeder::class,
            CategorySeeder::class,
            GoodSeeder::class,
            UserSeeder::class,
            OrderSeeder::class,
            SaleSeeder::class
        ]);
    }
}
