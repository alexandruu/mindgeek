<?php

use App\Http\Controllers\Actor;
use App\Services\PornhubImages;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

Route::get('/', [Actor::class, 'index'])->name('actor.index');
Route::get('/actor/{actor}', [Actor::class, 'show'])->name('actor.show');
Route::post('/actor', [Actor::class, 'store'])->name('actor.store');
