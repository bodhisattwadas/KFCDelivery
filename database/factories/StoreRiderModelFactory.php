<?php

namespace Database\Factories;

use App\Models\StoreRiderModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreRiderModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StoreRiderModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'store_code'=>'345',
        ];
    }
}
