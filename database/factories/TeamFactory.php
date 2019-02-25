<?php

$factory->define(App\Models\Team::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->words(2, true),
        'description' => $faker->paragraph,
    ];
});