<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/login', 'HomeController@login');

Route::get('/drinks', 'HomeController@listDrinks');

Route::put('/favorite-drink', 'HomeController@chooseFavoriteDrink');

Route::post('/calculate-caffeine-intake', 'HomeController@calculateCaffeineIntake');

Route::post('/logout', 'HomeController@logout');
