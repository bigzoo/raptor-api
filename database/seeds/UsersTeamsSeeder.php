<?php

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTeamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teams = Team::all();
        $users = User::all();
        $users->each(function (User $user) use ($teams){
            $user->teams()->attach($teams->random());
        });
        $teams->each(function (Team $team) use ($users){
            $team->users()->attach($users->random());
        });

    }
}
