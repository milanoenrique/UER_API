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

$router->post('/register','UsersController@register');
//Autenticacion
$router->post('/login','AuthController@login');
$router->post('/remember-password',['middleware'=>'auth', 'uses'=>'AuthController@remember_password']);
$router->put('/change-password',['middleware'=>'auth', 'uses'=>'AuthController@change_password']);
$router->post('/block-user',['middleware'=>'auth', 'uses'=>'AuthController@block_user']);
//Fin Autenticacion
$router->get('/countries','CountryController@index');

