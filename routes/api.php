<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('/auth')->group(function () {
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/register', [RegisterController::class, 'store'])->name('register');

    Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'user'])->name('user');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::prefix('accounts')->group(function () {
        Route::get('/all', [AccountController::class, 'getAll'])->name('accounts');
        Route::get('/', [AccountController::class, 'get'])->name('accounts');
        Route::get('/{id}', [AccountController::class, 'getOne'])->name('accounts.get-one');

        Route::post('/', [AccountController::class, 'store'])->name('accounts.store');
    });

    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'getAll'])->name('transactions');
        Route::get('/{id}', [TransactionController::class, 'getOne'])->name('transactions.get-one');

        Route::post('/', [TransactionController::class, 'store'])->name('transactions.store');
    });

    Route::prefix('subscriptions')->group(function () {
        Route::get('/', [SubscriptionController::class, 'getAll'])->name('subscriptions');
        Route::get('/{id}', [SubscriptionController::class, 'getOne'])->name('subscriptions.get-one');

        Route::post('/', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    });

    Route::prefix('/organizations')->group(function () {
        Route::get('/all', [OrganizationController::class, 'getAll'])->name('organizations');
        Route::get('/', [OrganizationController::class, 'get'])->name('organizations');
        Route::get('/{id}', [OrganizationController::class, 'getOne'])->name('organizations.get-one');
    });

    Route::prefix('/banks')->group(function () {
        Route::get('/', [BankController::class, 'getAll'])->name('banks');
        Route::get('/{id}', [BankController::class, 'getOne'])->name('banks.get-one');
    });

    Route::middleware('admin')->group(function () {
        Route::prefix('/organizations')->group(function () {
            Route::post('/', [OrganizationController::class, 'store'])->name('organizations.store');
            Route::patch('/{id}', [OrganizationController::class, 'update'])->name('organizations.update');
            Route::delete('/{id}', [OrganizationController::class, 'destroy'])->name('organizations.destroy');
        });

        Route::prefix('/banks')->group(function () {
            Route::post('/', [BankController::class, 'store'])->name('banks.store');
            Route::patch('/{id}', [BankController::class, 'update'])->name('banks.update');
            Route::delete('/{id}', [BankController::class, 'destroy'])->name('banks.destroy');
        });
    });
});
