<?php

namespace App\Resources;

class TeamsResource extends Resource
{
    public $resource;
    public $includes = [
        'users' => UsersResource::class
    ];

    /**
     * TeamsResource constructor.
     * @param $teams
     */
    public function __construct($teams)
    {
        $this->resource = $teams;
    }

    public function respond($resource)
    {
        return [
            'id' => $resource->id,
            'name' => $resource->name,
            'description' => $resource->description,
        ];
    }
}