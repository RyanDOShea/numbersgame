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

Route::get('/', 'NumberGameController@startGame')->name('startGame');

Route::get('/startGame', 'NumberGameController@startGame')->name('startGame');

Route::post('/makeGuess', 'NumberGameController@makeGuess')->name('makeGuess');
Route::get('/resetGame', 'NumberGameController@resetGame')->name('resetGame');