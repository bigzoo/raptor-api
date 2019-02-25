<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'team', 'description'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}