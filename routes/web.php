<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
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
        Route::get('', [AccountController::class, 'list'])->name('accounts');
        Route::get('add', [AccountController::class, 'add'])->name('accounts.add');

        Route::post('', [AccountController::class, 'store'])->name('accounts');
    });

    Route::prefix('transactions')->group(function () {
        Route::get('', [TransactionController::class, 'list'])->name('transactions');
        Route::get('add', [TransactionController::class, 'add'])->name('transactions.add');

        Route::post('', [TransactionController::class, 'store'])->name('transactions');
    });

    Route::middleware('admin')->group(function () {
        Route::prefix('organizations')->group(function () {
            Route::get('', [OrganizationController::class, 'list'])->name('organizations');
            Route::get('add', [OrganizationController::class, 'add'])->name('organizations.add');
            Route::get('{id}', [OrganizationController::class, 'edit'])->name('organizations.edit');
            Route::get('{id}/delete', [OrganizationController::class, 'delete'])->name('organizations.delete');

            Route::post('', [OrganizationController::class, 'store'])->name('organizations');
            Route::post('{id}', [OrganizationController::class, 'update'])->name('organizations.update');
            Route::delete('{id}', [OrganizationController::class, 'destroy'])->name('organizations.destroy');
        });

        Route::prefix('banks')->group(function () {
            Route::get('', [BankController::class, 'list'])->name('banks');
            Route::get('add', [BankController::class, 'add'])->name('banks.add');
            Route::get('{id}', [BankController::class, 'edit'])->name('banks.edit');
            Route::get('{id}/delete', [BankController::class, 'delete'])->name('banks.delete');

            Route::post('', [BankController::class, 'store'])->name('banks');
            Route::post('{id}', [BankController::class, 'update'])->name('banks.update');
            Route::delete('{id}', [BankController::class, 'destroy'])->name('banks.destroy');
        });
    });
});

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'form'])->name('login');
    Route::get('register', [RegisterController::class, 'form'])->name('register');

    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::post('register', [RegisterController::class, 'store'])->name('register');
});
