<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth')->group(function () {
    Route::get('', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('logout', [LogoutController::class, 'logout'])->name('logout');

    Route::prefix('accounts')->group(function () {
        Route::get('', [AccountsController::class, 'list'])->name('accounts');
        Route::post('', [AccountsController::class, 'store'])->name('accounts');
        Route::get('add', [AccountsController::class, 'add'])->name('accounts.add');
    });

    Route::prefix('transactions')->group(function () {
        Route::get('', [TransactionsController::class, 'list'])->name('transactions');
        Route::post('', [TransactionsController::class, 'store'])->name('transactions');
        Route::get('add', [TransactionsController::class, 'add'])->name('transactions.add');
    });
});

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'form'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::get('register', [RegisterController::class, 'form'])->name('register');
    Route::post('register', [RegisterController::class, 'store'])->name('register');
});
