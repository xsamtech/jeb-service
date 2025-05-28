<?php
/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */

use App\Http\Controllers\Web\DashboardController;
// use App\Http\Controllers\Web\HomeController;
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
    Route::get('/delete/panels/{id}', [DashboardController::class, 'removePanel'])->whereNumber('id')->name('dashboard.panel.delete');
    Route::get('/users', [DashboardController::class, 'users'])->name('dashboard.users');
    Route::post('/users', [DashboardController::class, 'addUser']);
    Route::get('/users/{id}', [DashboardController::class, 'userDatas'])->whereNumber('id')->name('dashboard.user.datas');
    Route::put('/users/{id}', [DashboardController::class, 'updateUser'])->whereNumber('id');
    Route::get('/delete/users/{id}', [DashboardController::class, 'removeUser'])->whereNumber('id')->name('dashboard.user.delete');
    Route::get('/statistics', [DashboardController::class, 'statistics'])->name('dashboard.statistics');
    Route::get('/account', [DashboardController::class, 'account'])->name('dashboard.account');
    Route::get('/account/settings', [DashboardController::class, 'account'])->name('dashboard.account.settings');
    Route::post('/account/settings', [DashboardController::class, 'updateAccount']);
});

require __DIR__.'/auth.php';
