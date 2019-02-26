<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return [
        "message" => "Welcome to the Raptor API.",
        "code"=> 200
    ];
});

/** @var \Laravel\Lumen\Routing\Router $router */
$router->group(['prefix' => 'v1'], function () use ($router) {
    // Users Crud Routes
    $router->get('users','UsersController@index');
    $router->get('users/{id}', 'UsersController@show');
    $router->patch('users/{id}', 'UsersController@update');
    $router->put('users/{id}', 'UsersController@update');

    // Teams Crud Routes
    $router->get('teams','TeamsController@index');
    $router->get('teams/{id}', 'TeamsController@show');
    $router->patch('teams/{id}', 'TeamsController@update');
    $router->put('teams/{id}', 'TeamsController@update');
});
