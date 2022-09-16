<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::get('/streaming', function () {
    return view('pages/streaming');
});

Route::get('/fitness', function () {
    return view('pages/fitness');
});

Route::get('/lastfm', function () {
    return view('auth/lastfm');
});

Route::get('/signin', function () {
    return view('auth/signin');
});

Route::get('/spotify', function () {
    return view('auth/spotify');
});

Route::get('/stravaSignIn', function () {
    return view('auth/stravaSignIn');
});

Route::get('/test', function () {
    return view('auth/test');
});

Route::get('/stravaAuth', function () {
    return view('auth/stravaAuth');
});

Route::get('/graphs', function () {
    return view('pages/graphs');
});

Route::get('/run', function () {
    return view('pages/run');
});

Route::get('/information', function () {
    return view('pages/information');
});

Route::get('/test2', function (){
    return view('pages/test2');
});

Route::get('/main', function (){
    return view('pages/main');
});

use App\Http\Controllers\RunController;
Route::get('main', [RunController::class, 'index'])->name('main.index');
