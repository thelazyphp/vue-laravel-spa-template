<?php

use Illuminate\Support\Facades\Route;

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

Route::prefix('/v1')->namespace('API\v1')->group(function () {
    Route::get('/test', function () {
        (new \App\Parsing\Parsers\Irr\IrrApartmentsParser())->start();
    });

    Route::prefix('/auth')->group(function () {
        Route::post('/register', 'AuthController@register');
        Route::post('/login', 'AuthController@login');
        Route::post('/refresh-token', 'AuthController@refreshToken');
        Route::match(['get', 'post'], '/logout', 'AuthController@logout');
    });

    Route::resource('users', 'UserController');
    Route::resource('images', 'ImageController')->only('store');
    Route::resource('catalog', 'CatalogController')->only('index', 'show');
    Route::resource('companies', 'CompanyController')->only('show', 'update');
});
