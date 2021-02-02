<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use App\Models\Good;
use Illuminate\Database\Eloquent\Factories\Factory;
use  Bezhanov\Faker\Provider\Commerce;


class GoodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Good::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $category = Category::inRandomOrder()->first();
        return [

            'title' => $this->faker->word,
            'photo' => $this->faker->imageUrl($width=400, $height=400, 'cats'),
            'feature' =>$this->faker->text($maxNbChars = 100),
            'count' => $this->faker->numberBetween($min = 1, $max = 5),
            'price' => $this->faker->numberBetween($min = 1, $max = 500),
            'sale'=> $this->faker->numberBetween($min = 0, $max = 70),
            'category_id'=>$category->id,
        ];
    }
}
