<?php

use App\Http\Controllers\ActorsController;
use App\Models\Actor;
use App\Models\Thumbnail;
use Illuminate\Support\Facades\DB;
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

Route::get('/phpinfo', function () {
    phpinfo();
});
Route::get('/thumbnails/{image}', function () {
})->middleware('cache.images');
Route::get('/', [ActorsController::class, 'index'])->name('actors.index');
Route::get('/actors/{id}', [ActorsController::class, 'show'])->name('actors.show');
Route::post('/actors', [ActorsController::class, 'store'])->name('actors.store');
