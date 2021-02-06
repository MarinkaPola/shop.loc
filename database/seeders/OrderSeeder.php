<?php

namespace Database\Seeders;
use App\Models\Good;
use App\Models\Order;
use Illuminate\Database\Seeder;



class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        $orders = Order::factory(30)
            ->create();
        foreach ($orders as $order) {
            $order->orderGoods()->attach(Good::inRandomOrder()->take(2)->get());

        }
    }
}
