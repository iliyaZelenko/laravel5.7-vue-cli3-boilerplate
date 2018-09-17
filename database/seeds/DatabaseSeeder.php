<?php

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
        factory(\App\User::class, 5)->create();

        factory(\App\User::class)->create([
            'name' => 'Vladimir Putin',
            'email' => 'test@test.com',
            'password' => \Hash::make('password')
        ]);
    }
}
