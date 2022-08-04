<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::factory()->create([
             'name' => 'admin',
             'email' => 'admin@laragigs.test',
             'email_verified_at' => now(),
             'password' => bcrypt(123),
             'remember_token' => Str::random(10),
         ]);

         \App\Models\User::factory(10)->create();
        \App\Models\Listing::factory(340)->create();

    }
}
