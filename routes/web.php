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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['namespace' => 'App\Http\Controllers'], function(){
    Route::middleware(['auth', 'verified'])->group(function() {
        Route::get('/', 'HomeController@index')->name('home');
        Route::post('/domain-submit', 'HomeController@insertDomain')->name('insert-domain-form');
        Route::get('/list-index/{id}', 'HomeController@listIndex')->name('list-index');
        Route::get('/user-profile', 'HomeController@profile')->name('user-profile');
        Route::post('/password-reset', 'HomeController@passwordReset')->name('password-reset');
    });
});
