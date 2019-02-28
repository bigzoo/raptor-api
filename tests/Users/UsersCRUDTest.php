<?php

namespace App\Test\Users;

use App\Models\Team;
use App\Models\User;
use App\Resources\TeamsResource;
use App\Resources\UsersResource;
use Laravel\Lumen\Testing\{DatabaseMigrations, DatabaseTransactions};

class UsersCRUDTest extends \TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    /**
     * @group users
     * @test
     */
    public function testGetUsers()
    {
        $users = factory(User::class, 10)->create();
        $transformedUsers = (new UsersResource($users))->toArray();
        $this->get('v1/users')
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'name', 'email'
                    ]
                ],
                'code', 'message'
            ])
            ->seeJsonContains([
                'data' => $transformedUsers
            ])
            ->seeInDatabase('users', $users->pluck('email')
                ->flatMap(function ($us){
                    return ['email' => $us];
                })->toArray())
            ->assertResponseOk();
    }

    /**
     * @group users
     * @test
     */
    public function testGetUser()
    {
        $user = factory(User::class)->create();
        $transformedUser = (new UsersResource($user))->toArray();
        $this->get('v1/users/'.$user->id)
            ->seeJsonStructure([
                'data' => [
                    'id', 'name', 'email'
                ],
                'code', 'message'
            ])
            ->seeJsonContains([
                'data' => $transformedUser
            ])
            ->seeInDatabase('users',[
                'email' => $user->email
            ])
            ->assertResponseOk();
    }

    /**
     * @group users
     * @test
     */
    public function testUpdateUser()
    {
        $user = factory(User::class)->create();
        $updateData = factory(User::class)->make();
        $this->put('v1/users/'.$user->id,[
            'name' => $updateData->name,
            'email' => $updateData->email
        ])
            ->seeJsonStructure([
                'data' => [
                    'id', 'name', 'email'
                ],
                'code', 'message'
            ])
            ->seeJsonContains([
                'data' => [
                    'id' => $user->id,
                    'name' => $updateData->name,
                    'email' => $updateData->email
                ]
            ])
            ->seeInDatabase('users',[
                'email' => $updateData->email
            ])
            ->assertResponseOk();
    }

    /**
     * @group users
     * @test
     */
    public function testGetUserWithTeams()
    {
        $user = factory(User::class)->create();
        $teams = factory(Team::class, 2)->create();
        $transformedTeams =(new TeamsResource($teams))->toArray();
        $user->teams()->attach($teams);
        $this->get('v1/users/'.$user->id.'/?include=teams')
            ->seeJsonStructure([
                'data' => [
                    'teams' => [
                        '*' => [
                            'id', 'name', 'description'
                        ]
                    ],
                    'id', 'name', 'email'
                ],
                'code', 'message'
            ])
            ->seeJson([
                'data' => [
                    'teams' => $transformedTeams,
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ])
            ->seeInDatabase('team_user',
                [
                    'user_id' => $user->id,
                    'team_id' => $teams->first()->id
                ]
            )
            ->seeInDatabase('team_user',
                [
                    'user_id' => $user->id,
                    'team_id' => $teams->last()->id
                ])
            ->assertResponseOk();
    }

}