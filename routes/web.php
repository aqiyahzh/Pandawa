<?php

use App\Http\Controllers\Admin\AboutPageController;
use Illuminate\Support\Facades\Route;
use App\Models\Merchandise;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MerchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\OrderAdminController;
use App\Http\Controllers\Admin\KontakController;
use App\Http\Controllers\Admin\GaleriController;



// --------------------
// STATIC PAGES
// --------------------
Route::view('/', 'pages.home')->name('home');
Route::view('/about', 'pages.about')->name('about');
Route::view('/history', 'pages.history')->name('history');
Route::view('/gallery', 'pages.gallery')->name('gallery');
Route::view('/contact', 'pages.contact')->name('contact');

// --------------------
// MERCH PUBLIC
// --------------------
Route::get('/merchandise', function () {
    $items = Merchandise::all();
    return view('pages.merchandise', compact('items'));
})->name('merchandise');

Route::get('/merchandise/{id}', function ($id) {
    $item = Merchandise::findOrFail($id);
    return view('pages.merchdetail', compact('item'));
})->name('merchdetail');

Route::post('/order/store', [OrderController::class, 'store'])
    ->name('order.store');

// --------------------
// ADMIN LOGIN
// --------------------
Route::get('/login', fn() => redirect()->route('admin.login'))
    ->name('login');

Route::get('/admin/login', [AuthController::class, 'showLogin'])
    ->name('admin.login');

Route::post('/admin/login', [AuthController::class, 'login'])
    ->name('login.process');

// --------------------
// ADMIN DASHBOARD
// --------------------
Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('admin.dashboard');

Route::get('/admin/password', [AuthController::class, 'showPassword'])
    ->name('password');

Route::post('/admin/logout', [AuthController::class, 'logout'])
    ->name('admin.logout');

// --------------------
// ADMIN MERCH CRUD
// --------------------

Route::prefix('admin')->middleware('auth')->group(function () {

    // LIST
    Route::get('/merch', [MerchController::class, 'index'])
        ->name('admin.merch.index');

    // CREATE
    Route::get('/merch/create', [MerchController::class, 'create'])
        ->name('admin.merch.create');

    // STORE
    Route::post('/merch/store', [MerchController::class, 'store'])
        ->name('admin.merch.store');

    // EDIT
    Route::get('/merch/{id}/edit', [MerchController::class, 'edit'])
        ->name('admin.merch.edit');

    // UPDATE
    Route::put('/merch/{id}', [MerchController::class, 'update'])
        ->name('admin.merch.update');

    // DELETE
    Route::delete('/merch/{id}', [MerchController::class, 'destroy'])
        ->name('admin.merch.destroy');
});

// --------------------
// ADMIN SETTINGS
// --------------------

Route::prefix('admin')->middleware('auth')->group(function () {
    // settings
    Route::get('/settings', [SettingController::class, 'edit'])->name('admin.settings.edit');
    Route::post('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
    // about page settings
    Route::get('/settings/aboutpage', [AboutPageController::class, 'edit'])->name('admin.settings.aboutpage');
    Route::post('/settings/aboutpage', [AboutPageController::class, 'update'])->name('admin.settings.aboutpage.update');
    // contact page settings
    Route::get('/settings/adminkontak', [KontakController::class, 'edit'])->name('admin.settings.adminkontak');
    Route::post('/settings/adminkontak', [KontakController::class, 'update'])->name('admin.settings.adminkontak.update');

    // gallery admin
    Route::get('/settings/admingaleri', [GaleriController::class, 'edit'])->name('admin.settings.admingaleri');
    Route::post('/settings/admingaleri', [GaleriController::class, 'update'])->name('admin.settings.admingaleri.update');
    

    // orders list
    Route::get('/orders', [OrderAdminController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{id}/edit', [OrderAdminController::class, 'edit'])->name('admin.orders.edit');
    Route::put('/orders/{id}', [OrderAdminController::class, 'update'])->name('admin.orders.update');
    Route::delete('/orders/{id}', [OrderAdminController::class, 'destroy'])->name('admin.orders.destroy');
});
