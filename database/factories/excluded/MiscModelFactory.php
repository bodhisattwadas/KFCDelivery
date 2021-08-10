<?php

namespace Database\Factories;

use App\Models\MiscModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class MiscModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MiscModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'pickup_otp'=>rand(999,9999),
        ];
    }
}
