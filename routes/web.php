<?php

use App\Http\Controllers\SamController;
use App\Http\Controllers\TestController;
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

Route::get('/test', [TestController::class, 'index'])->name('test');
Route::get('/sam/education', [SamController::class, 'education'])->name('sam.education');
Route::get('/sam/test', [SamController::class, 'test'])->name('sam.test');
Route::get('/sam/test2', [SamController::class, 'test2'])->name('sam.test2');
Route::get('/sam/test3', [SamController::class, 'test3'])->name('sam.test3');

Route::get('/sam/getStates', [SamController::class, 'getStates'])->name('getStates');
Route::get('/sam/getZillas', [SamController::class, 'getZillas'])->name('getZillas');
Route::get('/sam/getBibhags', [SamController::class, 'getBibhags'])->name('getBibhags');