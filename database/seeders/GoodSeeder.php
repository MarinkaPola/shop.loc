<?php

namespace Database\Seeders;
use App\Models\User;
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
        $goods = Good::factory(40)
            ->create();
        foreach ($goods as $good) {
            $good->goodUsers()->attach(User::where('role', User::ROLE_USER)->inRandomOrder()->take(2)->get());

        }
    }

}
