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
})->name('home');

/*
 * knp pake route resource ? tujuannya adalah agar pengerjaan route yang sering kita lakukan kedepan itu
    lebih cepat dan lebih gampang jadi routenya tidak kita tulis manual tp kita menggunakan standar yang sudah
    disediakan oleh laravel itu sendiri
*/

//route manual tampil_hapus
Route::get('/post/tampil_trash', 'PostController@tampil_trash')->name('post.tampil_trash');
//route manual restore
Route::get('/post/restore/{id}', 'PostController@restore')->name('post.restore');
//route manual permanent_delete
Route::delete('/post/permanent_delete/{id}', 'PostController@permanent_delete')->name('post.permanent_delete');

Route::resource('/category','CategoryController');
Route::resource('/tag', 'TagController');
Route::resource('/post', 'PostController');
