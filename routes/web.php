<?php

use App\Http\Controllers\KeywordController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::group(['controller'=> KeywordController::class], function () {
        Route::get('/','index')->name('dashboard');
        Route::post('/keywords/store','store')->name('keywords.store');
        Route::get('/keywords/{keyword}/status','status')->name('keywords.status');
        Route::delete('/keywords/{keyword}/delete','destroy')->name('keywords.destroy');
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
