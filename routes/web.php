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
    // return \Illuminate\Support\Str::random(32);
    return $router->app->version();
});

$router->post('register', 'AuthController@register');
$router->post('login', 'AuthController@login');

$router->group(['middleware' => 'auth'], function() use ($router) {
    $router->get('getMe', 'AuthController@profile');

    $router->group(['prefix' => 'activity'], function () use ($router) {
        $router->post("create", "ActivityController@create");
        $router->post("update/{id}", "ActivityController@update");
        $router->get('show/{id}', "ActivityController@show");
        $router->get('complete/{id}', "ActivityController@completeActivity");
        $router->get('all', "ActivityController@getAllActivityByUser");
        $router->get('remove/{id}', "ActivityController@destroy");
        $router->post("changeContributors/{id}", "ActivityController@changeContributor");
    });

    $router->group(['prefix' => 'comment'], function () use ($router) {
        $router->post("create", "CommentController@create");
        $router->get('show/{id}', "CommentController@show");
        $router->get('remove/{id}', "CommentController@destroy");
    });
});
