<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create users
        $this->call(UserSeeder::class);
        // create teams
        $this->call(TeamSeeder::class);
        // create user team relations
        $this->call(UsersTeamsSeeder::class);
    }
}
