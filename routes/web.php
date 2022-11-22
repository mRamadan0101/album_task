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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::group(['middleware' => ['auth:web']], function () {
        Route::get('albums', 'AlbumController@index')->name('my_albums');

        Route::get('create_album', 'AlbumController@create')->name('create_album');
        Route::post('store_album', 'AlbumController@store')->name('albums_store');
        Route::post('albums/media', 'AlbumController@storeMedia')->name('albums_storeMedia');
        Route::post('albums/images', 'AlbumController@storeFiles')->name('albums_storeFiles');
        Route::get('show_album/{slug}', 'AlbumController@show')->name('show_album');

        Route::get('edit_album/{slug}', 'AlbumController@edit')->name('edit_album');
        Route::post('update_album/{slug}', 'AlbumController@update')->name('update_album');

        Route::get('delete_album/{slug}', 'AlbumController@delete')->name('delete_album');
        Route::get('confirm_delete/{slug}', 'AlbumController@confirm_delete')->name('confirm_delete');
        Route::get('move_images/{slug}', 'AlbumController@move_images')->name('move_images');

        //move
    });
