<?php
/** var Factory $factory */
namespace Database\Factories;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sale::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'value_percentage' =>$this->faker->numberBetween($min = 5, $max = 50),
        ];
    }
}
