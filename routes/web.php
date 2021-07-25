<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CardsController;
use App\Http\Controllers\ColumnsController;

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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', function () {
    return redirect()->route('cards.index');
})->name('home');

Route::get('cards', [CardsController::class, 'index'])->name('cards.index');


Route::middleware(['cors'])->group(function () {
    Route::post('cards', [CardsController::class, 'store'])->name('cards.store');
    Route::put('/cards/sync', [CardsController::class, 'sync'])->name('cards.sync');
    //Route::put('/cards/{card}', [CardsController::class, 'update'])->name('cards.update');
});


