<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\userController;
// use App\Http\Controllers\postsController;
// use App\Http\Controllers\blogController;
// use App\Http\Controllers\blogController;

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
// Route::resource('user',userController::class);
// Route::get('Login',[userController::class,'login']);
// Route::post('DoLogin',[userController::class,'doLogin']);
// Route::get('LogOut',[userController::class,'logOut']);
// Route::resource('post',postController::class);

//Auth::routes();
// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');


Route::post('follow/{user}','FollowsController@store');

Route::get('/','PostsController@index');
Route::get('/p/create','PostsController@create');
Route::get('/p/{post}','PostsController@show');
Route::post('/p','PostsController@store');


Route::get('/profile/{user}', 'ProfilesController@index')->name('profile.show');
Route::get('/profile/{user}/edit', 'ProfilesController@edit')->name('profile.edit');  
Route::patch('/profile/{user}', 'ProfilesController@update')->name('profile.update');  




