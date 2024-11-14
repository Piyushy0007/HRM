<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
// Route::view('/', 'auth/login');

Route::post('image','HomeController@image');
Route::get('/map', 'GeoFenceController@index')->name('map');

//Route::get('/', 'HomeController@index')->name('home');
Route::get('/{any}', 'HomeController@index')->name('home')->where('any', '.*');

Auth::routes();





