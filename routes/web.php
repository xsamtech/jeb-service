<?php

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */

use App\Http\Controllers\Web\DashboardController;
use App\Jobs\CheckExpiredPanelsJob;
// use App\Http\Controllers\Web\HomeController;
use Illuminate\Support\Facades\Route;

// Symbolic link
Route::get('/symlink', function () {
    return view('symlink');
})->name('generate_symlink');

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
Route::get('/test', [DashboardController::class, 'test'])->name('dashboard.test');

Route::middleware('localization')->group(function () {
    Route::get('/orders', [DashboardController::class, 'getOrders']);
    Route::get('/order/{id}', [DashboardController::class, 'getOrderDetails']);
});
Route::middleware(['auth', 'localization'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.home');
    Route::get('/{entity}/{id}', [DashboardController::class, 'datas'])->whereNumber('id')->name('dashboard.home.datas');
    Route::get('/panels', [DashboardController::class, 'panels'])->name('dashboard.panels');
    Route::post('/panels', [DashboardController::class, 'addPanel']);
    Route::get('/panels/{id}', [DashboardController::class, 'panelDatas'])->whereNumber('id')->name('dashboard.panel.datas');
    Route::post('/panels/{id}', [DashboardController::class, 'updatePanel'])->whereNumber('id');
    Route::post('/panel-quantity/{entity}/{id}', [DashboardController::class, 'updatePanelQuantity'])->whereNumber('id')->name('dashboard.panel.updateQuantity');
    Route::get('/expenses', [DashboardController::class, 'expenses'])->name('dashboard.expenses');
    Route::post('/expenses', [DashboardController::class, 'addExpense']);
    Route::get('/expenses/{id}', [DashboardController::class, 'expenseDatas'])->whereNumber('id')->name('dashboard.expense.datas');
    Route::post('/expenses/{id}', [DashboardController::class, 'updateExpense'])->whereNumber('id');
    Route::get('/users', [DashboardController::class, 'users'])->name('dashboard.users');
    Route::post('/users', [DashboardController::class, 'addUser']);
    Route::get('/users/{id}', [DashboardController::class, 'userDatas'])->whereNumber('id')->name('dashboard.user.datas');
    Route::post('/users/{id}', [DashboardController::class, 'updateUser'])->whereNumber('id');
    Route::get('/users/{entity}', [DashboardController::class, 'usersEntity'])->name('dashboard.users.entity');
    Route::post('/users/{entity}', [DashboardController::class, 'addUserEntity']);
    Route::get('/users/{entity}/{id}', [DashboardController::class, 'userEntityDatas'])->whereNumber('id')->name('dashboard.user.entity.datas');
    Route::post('/users/{entity}/{id}', [DashboardController::class, 'updateUserEntity'])->whereNumber('id');
    Route::get('/statistics', [DashboardController::class, 'statistics'])->name('dashboard.statistics');
    Route::get('/account', [DashboardController::class, 'account'])->name('dashboard.account');
    Route::get('/account/settings', [DashboardController::class, 'account'])->name('dashboard.account.settings');
    Route::post('/account/settings', [DashboardController::class, 'updateAccount']);
    Route::delete('/delete/{entity}/{id}', [DashboardController::class, 'removeData'])->whereNumber('id')->name('data.delete');
});
Route::get('/check-panels', function () {
    CheckExpiredPanelsJob::dispatch();

    return "Panneaux vérifiés !";
});

require __DIR__ . '/auth.php';
