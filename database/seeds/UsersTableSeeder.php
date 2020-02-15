<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(\App\User::all()->count() === 0) {
            factory(\App\User::class)->create();
        }
    }
}
