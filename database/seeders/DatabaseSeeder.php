<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Medicine;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 10 users using the user factory 
        User::factory(10)->create();

        // Create 20 medicines using the medicine factory 
        Medicine::factory(20)->create();
    }  
}