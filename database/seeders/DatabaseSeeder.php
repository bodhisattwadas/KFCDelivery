<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StoreModel;
use App\Models\RiderLog;
use App\Models\User;
use App\Models\StoreRiderModel;

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
         //StoreModel::factory(10)->create();
         // User::factory(10)->create();
        User::factory(env('SEEDER', 10))->create()->each(function(User $u) {
           StoreRiderModel::factory(1)->create(['rider_code' => $u->id]);
           RiderLog::factory(1)->create(['rider_code' => $u->id]);
        });
    }
}
