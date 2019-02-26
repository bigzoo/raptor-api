<?php

namespace App\Resources;

class UsersResource extends Resource
{
    public $resource;
    public $includes = [
        'teams' => TeamsResource::class,
    ];

    public function __construct($users)
    {
        $this->resource = $users;
    }

    public function respond($resource)
    {
        return [
            'id' => $resource->id,
            'name' => $resource->name,
            'email' => $resource->email,
        ];
    }
}