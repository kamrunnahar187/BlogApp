<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('auth.login');
    //return view('welcome');
    //return view('home');
});

Route::auth();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/post', 'PostController@post')->middleware('auth');
Route::get('/profile', 'ProfileController@profile')->middleware('auth');
Route::get('/category', 'CategoryController@category')->middleware('auth');
Route::post('/addCategory', 'CategoryController@addCategory')->middleware('auth');
Route::post('/addProfile', 'ProfileController@addProfile')->middleware('auth');
Route::post('/addPost', 'PostController@addPost')->middleware('auth');
Route::get('/view/{id}', 'PostController@viewPost')->middleware('auth');
Route::get('/edit/{id}', 'PostController@edit')->middleware('auth');
Route::post('/editPost/{id}', 'PostController@editPost')->middleware('auth');
Route::get('/category/{id}', 'PostController@viewCategory')->middleware('auth');
Route::get('/delete/{id}', 'PostController@deletePost')->middleware('auth');

Route::get('/like/{id}', 'PostController@like')->middleware('auth');
Route::get('/dislike/{id}', 'PostController@dislike')->middleware('auth');
Route::post('/comment/{id}', 'PostController@comment')->middleware('auth');
Route::post('/search', 'PostController@search')->middleware('auth');
//Route::get('/comment/{id}', 'PostController@comment');