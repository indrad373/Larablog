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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
});

/*
 * knp pake route resource ? tujuannya adalah agar pengerjaan route yang sering kita lakukan kedepan itu
    lebih cepat dan lebih gampang jadi routenya tidak kita tulis manual tp kita menggunakan standar yang sudah
    disediakan oleh laravel itu sendiri
*/
Route::resource('/category','CategoryController');
