<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Listing;
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

//all listings
Route::get('/',[ListingController::class,'index']);

//show create form
Route::get('/listings/create',[ListingController::class,'create'])->name('create')->middleware('auth');

//store listing
Route::post('/listings',[ListingController::class,'store'])->middleware('auth');

//show edit form
Route::get('/listings/{listing}/edit',[ListingController::class,'edit'])->middleware('auth');

//patch submit values to edit
Route::patch('/listings/{listing}',[ListingController::class,'update'])->middleware('auth');

//Delete 
Route::delete('/listings/{listing}',[ListingController::class,'delete'])->middleware('auth');

//single listing
Route::get('/listings/{listing}',[ListingController::class,'show']);

//show register create form
Route::get('/register',[UserController::class,'create'])->middleware('guest');

//create new user
Route::post('/users', [UserController::class, 'store']);

//logout
Route::post('/logout',[UserController::class,'logout'])->middleware('auth');

//show login
Route::get('/login',[UserController::class,'login'])->name('login')->middleware('guest');

// user login
Route::post('/users/authenticate',[UserController::class,'authenticate']);

