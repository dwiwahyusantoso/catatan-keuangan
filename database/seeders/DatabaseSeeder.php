<?php

namespace Database\Seeders;

use App\Models\Saving;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // Transaction::factory(10)->create();
        Saving::factory(10)->create();
    }
}
