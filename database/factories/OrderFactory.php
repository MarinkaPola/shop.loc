<?php
/** var Factory $factory */
namespace Database\Factories;
use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'buyer_id' => User::where('role', User::ROLE_USER)->inRandomOrder()->first()->id,
            'payment' => $this->faker->randomElement(Order::PAYMENT),
            'delivery'=> $this->faker->randomElement(Order::DELIVERY),
            'goods_is_paid' =>$this->faker->boolean,
            'sum' =>$this->faker->numberBetween($min = 1, $max = 500),
        ];
    }
}
