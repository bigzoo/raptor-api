<?php

namespace App\Test\Users;

use App\Models\Team;
use App\Models\User;
use App\Resources\TeamsResource;
use App\Resources\UsersResource;
use Laravel\Lumen\Testing\{DatabaseMigrations, DatabaseTransactions};

class TeamCRUDTest extends \TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    /**
     * @group teams
     * @test
     */
    public function testGetTeams()
    {
        $teams = factory(Team::class, 10)->create();
        $transformedTeams = (new TeamsResource($teams))->toArray();
        $this->get('v1/teams')
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'name', 'description'
                    ]
                ],
                'code', 'message'
            ])
            ->seeJsonContains([
                'data' => $transformedTeams
            ])
            ->seeInDatabase('teams', $teams->pluck('name')
                ->flatMap(function ($te){
                    return ['name' => $te];
                })->toArray())
            ->assertResponseOk();
    }

    /**
     * @group teams
     * @test
     */
    public function testGetTeam()
    {
        $team = factory(Team::class)->create();
        $transformedTeam = (new TeamsResource($team))->toArray();
        $this->get('v1/teams/'.$team->id)
            ->seeJsonStructure([
                'data' => [
                    'id', 'name', 'description'
                ],
                'code', 'message'
            ])
            ->seeJsonContains([
                'data' => $transformedTeam
            ])
            ->seeInDatabase('teams',[
                'description' => $team->description,
                'name' => $team->name
            ])
            ->assertResponseOk();
    }

    /**
     * @group teams
     * @test
     */
    public function testUpdateTeam()
    {
        $team = factory(Team::class)->create();
        $updateData = factory(Team::class)->make();
        $this->put('v1/teams/'.$team->id,[
            'name' => $updateData->name,
            'description' => $updateData->description
        ])
            ->seeJsonStructure([
                'data' => [
                    'id', 'name', 'description'
                ],
                'code', 'message'
            ])
            ->seeJsonContains([
                'data' => [
                    'id' => $team->id,
                    'name' => $updateData->name,
                    'description' => $updateData->description
                ]
            ])
            ->seeInDatabase('teams',[
                'name' => $updateData->name,
                'description' => $updateData->description
            ])
            ->assertResponseOk();
    }

    /**
     * @group teams
     * @test
     */
    public function testGetTeamWithUsers()
    {
        $team = factory(Team::class)->create();
        $users = factory(User::class, 2)->create();
        $transformedUsers = (new UsersResource($users))->toArray();
        $team->users()->attach($users);
        $this->get('v1/teams/'.$team->id.'/?include=users')
            ->seeJsonStructure([
                'data' => [
                    'users' => [
                        '*' => [
                            'id', 'name', 'email'
                        ]
                    ],
                    'id', 'name', 'description'
                ],
                'code', 'message'
            ])
            ->seeJson([
                'data' => [
                    'users' => $transformedUsers,
                    'id' => $team->id,
                    'name' => $team->name,
                    'description' => $team->description
                ]
            ])
            ->seeInDatabase('team_user',
                [
                    'team_id' => $team->id,
                    'user_id' => $users->first()->id
                ]
            )
            ->seeInDatabase('team_user',
                [
                    'team_id' => $team->id,
                    'user_id' => $users->last()->id
                ])
            ->assertResponseOk();
    }

}