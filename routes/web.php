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
// Route::get('/', [HomeController::class, 'index'])->name('home');
// Route::get('/services', [HomeController::class, 'services'])->name('services');
// Route::post('/services', [HomeController::class, 'selectServices']);
// Route::get('/order', [HomeController::class, 'order'])->name('order');
// Route::post('/order', [HomeController::class, 'sendOrder']);
// Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

/*
|--------------------------------------------------------------------------
| ADMIN Web Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.home');
    Route::get('/panels', [DashboardController::class, 'panels'])->name('dashboard.panels');
    Route::post('/panels', [DashboardController::class, 'addPanel']);
    Route::get('/panels/{id}', [DashboardController::class, 'panelDatas'])->whereNumber('id')->name('dashboard.panel.datas');
    Route::put('/panels/{id}', [DashboardController::class, 'updatePanel'])->whereNumber('id');
    Route::get('/delete/panels/{id}', [DashboardController::class, 'removeCustomer'])->whereNumber('id')->name('dashboard.panel.delete');
    Route::get('/customers', [DashboardController::class, 'customers'])->name('dashboard.customers');
    Route::post('/customers', [DashboardController::class, 'addCustomer']);
    Route::get('/customers/{id}', [DashboardController::class, 'customerDatas'])->whereNumber('id')->name('dashboard.customer.datas');
    Route::put('/customers/{id}', [DashboardController::class, 'updateCustomer'])->whereNumber('id');
    Route::get('/delete/customers/{id}', [DashboardController::class, 'removeCustomer'])->whereNumber('id')->name('dashboard.customer.delete');
    Route::get('/statistics', [DashboardController::class, 'statistics'])->name('dashboard.statistics');
    Route::get('/account', [DashboardController::class, 'account'])->name('dashboard.account');
});

require __DIR__.'/auth.php';
