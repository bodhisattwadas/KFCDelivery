<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StoreModel;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         //MiscModel::factory(10)->create();
         //ProductModel::factory(10)->create();
         StoreModel::factory(10)->create();
    }
}