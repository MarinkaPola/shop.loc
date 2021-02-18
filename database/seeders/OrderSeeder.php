<?php

namespace Database\Seeders;
use App\Models\Good;
use App\Models\Order;
use App\Models\User;
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
        User::all()->each(function (User $user) {
            $orders = $user->order()->saveMany(Order::factory(1)->make());
            foreach ($orders as $order) {
                $order->orderGoods()->attach(Good::inRandomOrder()->take(2)->get());

            }
            });
    }
}
