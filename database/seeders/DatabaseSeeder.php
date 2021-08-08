<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MiscModel;
use App\Models\ProductModel;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         MiscModel::factory(10)->create();
         ProductModel::factory(10)->create();
    }
}
