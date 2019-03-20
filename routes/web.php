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
    return $router->app->version();
});

$router->group(["prefix" => "checklists", "as" => "checklists"], function() use ($router) {
    $router->get('/', [ 'uses' => 'ChecklistController@index', 'as' => "index"]);
    $router->post('/', ['uses' => 'ChecklistController@store', 'as' => 'store']);
    $router->get('/{id:[\d]+}', ['uses' => 'ChecklistController@show', 'as' => 'show']);
    $router->put('/{id:[\d]+}', 'ChecklistController@update');
    $router->delete('/{id:[\d]+}', 'ChecklistController@destroy');
    $router->group(["prefix" => "{checklist_id}"], function() use($router){
        $router->group(["prefix" => "items", "as" => "items"], function() use ($router) {
            $router->get('/', [ 'uses' => 'ItemController@index', 'as' => "index"]);
            $router->post('/', ['uses' => 'ItemController@store', 'as' => 'store']);
            $router->get('/{id:[\d]+}', ['uses' => 'ItemController@show', 'as' => 'show']);
            $router->put('/{id:[\d]+}', 'ItemController@update');
            $router->delete('/{id:[\d]+}', 'ItemController@destroy');
            $router->post('complete', ['uses' => 'ItemController@complete', 'as' => 'complete']);
            $router->post('incomplete', ['uses' => 'ItemController@incomplete', 'as' => 'incomplete']);
        });
    });
});
