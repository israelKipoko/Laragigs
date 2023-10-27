<?php

use App\Models\Listings;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListingsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Show all listing
Route::get('/',[ListingsController::class, 'index']);

//Show create form
Route::get('/listing/create', [ListingsController::class, 'create'])->middleware('auth');

//Store listing data
Route::post('/listing',[ListingsController::class, 'store'])->middleware('auth');

//Show edit form
Route::get('/listing/{listing}/edit',[ListingsController::class,'edit'])->middleware('auth');

//Update listing
Route::put('/listing/{listing}', [ListingsController::class, 'update'])->middleware('auth');

//Delete listing
Route::delete('/listing/{listing}', [ListingsController::class, 'delete'])->middleware('auth');

//Manage Listings
Route::get('/lisitng/manage', [ListingsController::class, 'manage'])->middleware('auth');

//Show single listing
Route::get('/listing/{listing}',[ListingsController::class, 'show']);

//Show Register/Create form
Route::get('/register',[UserController::class, 'create'])->middleware('guest');

//Create New User
Route::post('/users',[UserController::class, 'store']);

//Log user out
Route::post('/logout',[UserController::class, 'logout'])->middleware('auth');

//Show Login form
Route::get('/login',[UserController::class, 'login'])->name('login')->middleware('guest');

//Log user in
Route::post('/users/authenticate',[UserController::class, 'authenticate']);

