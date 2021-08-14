<?php

namespace Database\Factories;

use App\Models\RiderLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class RiderLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RiderLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status'=>'in'
        ];
    }
}
