<?php

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

Route::view('/', 'home')->name('home');

Route::resource('doc', 'DocumentController')->only([
    'index', 'store', 'show'
]);

Route::post('timezone', 'TimezoneController')->name('timezone');

Route::get('payment', 'PaymentsController@create')->name('payments.create');
Route::post('payment', 'PaymentsController@store')->name('payments.store');
