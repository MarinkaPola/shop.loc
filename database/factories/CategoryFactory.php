<?php

namespace Database\Factories;


use App\Models\Category;
use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $area = Area::inRandomOrder()->first();
        return [
            'title' => $this->faker->word,
            'area_id' =>$area->id
        ];
    }
}
