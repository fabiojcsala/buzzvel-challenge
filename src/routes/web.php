<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get  ('/',                      ['as' => 'home',            'uses' => 'SearchController@home']);
Route::post ('/search/order/distance', ['as' => 'search.distance', 'uses' => 'SearchController@distance']);
Route::get  ('/search/order/price',    ['as' => 'search.price',    'uses' => 'SearchController@price']);