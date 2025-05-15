<?php
/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */

use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\HomeController;
use Illuminate\Support\Facades\Route;

// Symbolic link
Route::get('/symlink', function () { return view('symlink'); })->name('generate_symlink');

/*
|--------------------------------------------------------------------------
| GUEST Web Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::post('/services', [HomeController::class, 'selectServices']);
Route::get('/order', [HomeController::class, 'order'])->name('order');
Route::post('/order', [HomeController::class, 'sendOrder']);
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

/*
|--------------------------------------------------------------------------
| ADMIN Web Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('dashboard.home');
    Route::get('/customers', [DashboardController::class, 'customers'])->name('dashboard.customers');
    Route::get('/customers/{id}', [DashboardController::class, 'customerDatas'])->whereNumber('id')->name('dashboard.customers.datas');
    Route::delete('/customers/{id}', [DashboardController::class, 'removeCustomer'])->whereNumber('id');
    Route::get('/statistics', [DashboardController::class, 'statistics'])->name('dashboard.statistics');
});

require __DIR__.'/auth.php';
