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

Route::get('/', [\App\Http\Controllers\IndexController::class, 'index']);
Route::get('list', [\App\Http\Controllers\IndexController::class, 'list']);
Route::get('list2', [\App\Http\Controllers\IndexController::class, 'list2']);
Route::view('list3', 'list3');
Route::get('getCommentIds', [\App\Http\Controllers\IndexController::class, 'getCommentsIds']);
Route::get('getCommentByIds', [\App\Http\Controllers\IndexController::class, 'getCommentsByIds']);