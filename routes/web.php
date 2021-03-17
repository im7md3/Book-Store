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

Auth::routes();
//home url
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'GalleryController@index')->name('gallery.index');

//book url
Route::get('book/{book}', 'BooksController@details')->name('book.details');
Route::post('book/{book}/rate', 'BooksController@rate')->name('book.rate');
Route::get('/search', 'GalleryController@search')->name('search');

//category url
Route::get('/categories', 'CategoriesController@list')->name('gallery.categories.index');
Route::get('/categories/search', 'CategoriesController@search')->name('gallery.categories.search');
Route::get('/categories/{category}', 'CategoriesController@result')->name('gallery.categories.show');

//author url
Route::get('/authors', 'AuthorsController@list')->name('gallery.authors.index');
Route::get('/authors/search', 'AuthorsController@search')->name('gallery.authors.search');
Route::get('/authors/{author}', 'AuthorsController@result')->name('gallery.authors.show');

//publisher url
Route::get('/publishers', 'PublishersController@list')->name('gallery.publishers.index');
Route::get('/publishers/search', 'PublishersController@search')->name('gallery.publishers.search');
Route::get('/publishers/{publisher}', 'PublishersController@result')->name('gallery.publishers.show');

Route::prefix('/admin')->middleware('can:update-books')->group(function () {
    //admin url
    Route::get('/', 'AdminsController@index')->name('admin.index');

    //admin books url
    Route::resource('/books', 'BooksController');

    //admin categories url
    Route::resource('/categories', 'CategoriesController');

    //admin publishers url
    Route::resource('/publishers', 'PublishersController');

    //admin authors url
    Route::resource('/authors', 'AuthorsController');
    
    //admin users url
    Route::resource('/users', 'UsersController');

});


//Cart url
Route::post('/cart','CartController@addToCart')->name('cart.add');
Route::get('/cart','CartController@viewCart')->name('cart.view');
Route::post('/removeOne/{book}','CartController@removeOne')->name('cart.remove_one');
Route::post('/removeAll/{book}','CartController@removeAll')->name('cart.remove_all');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
