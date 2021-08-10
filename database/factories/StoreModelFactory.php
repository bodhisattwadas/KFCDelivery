<?php

namespace Database\Factories;

use App\Models\StoreModel;
use Illuminate\Database\Eloquent\Factories\Factory;


class StoreModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StoreModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'store_name'=>$this->faker->word(),
            'store_code'=>'k'.rand(100,999),
        ];
    }
}
