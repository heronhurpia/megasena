<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\OptionController;

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

Route::get('/', function () {
	return view('welcome');
})->name('welcome');

Auth::routes();

Route::get('/home',            		[HomeController::class, 'index'])->name('home')->middleware('auth');;

Route::post('/option/store',		[OptionController::class, 'store']);
Route::post('/option/store_file',	[OptionController::class, 'store_file']);
Route::post('/option/destroy',		[OptionController::class, 'destroy']);
Route::post('/option/check',		[OptionController::class, 'check']);

Route::get('/tasks',             [TaskController::class, 'index'])->name('tasks')->middleware('auth','can:admin');
Route::post('/tasks/store',      [TaskController::class, 'store']);
Route::post('/tasks/destroy',    [TaskController::class, 'destroy']);
Route::post('/tasks/check',      [TaskController::class, 'check']);
