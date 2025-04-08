<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadSourceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InventoryItemController;

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

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/forgot', function () {
    return view('auth.forgot');
});

Route::get('/register', function () {
    return view('auth.register');
});


Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/registercustomer', function () {
    return view('registercustomer');
});

Route::get('/loadboard', function () {
    return view('loadboard');
});

Route::get('/leads', function () {
    return view('leads');
});

Route::get('/local', function () {
    return view('local');
});


Route::get('/longdistance', function () {
    return view('longdistance');
});


Route::get('/delivery', function () {
    return view('delivery');
});


Route::get('/furniture', function () {
    return view('furniture');
});


Route::get('/moving', function () {
    return view('moving');
});


Route::get('/mattress', function () {
    return view('mattress');
});


Route::get('/rearranging', function () {
    return view('rearranging');
});


Route::get('/cleaning', function () {
    return view('cleaning');
});


Route::get('/housting', function () {
    return view('housting');
});

Route::get('/agents', function () {
    return view('agents');
});

Route::get('/salesreps', function () {
    return view('salesreps');
});

Route::get('/callcenter', function () {
    return view('callcenter');
});

Route::get('/salesreports', function () {
    return view('salesreports');
});

Route::get('/bestagents', function () {
    return view('bestagents');
});

Route::get('/servicerates', function () {
    return view('servicerates');
});


Route::get('/settings', function () {
    return view('settings');
})->name('settings.index');


Route::get('/stripe', function () {
    return view('stripe');
});

Route::get('/sms', function () {
    return view('sms');
});

Route::get('/calendar', function () {
    return view('calendar');
});

Route::get('/profile', function () {
    return view('profile');
});


Route::get('/customer', function () {
    return view('customer.customer');
});

Route::get('/leadsource', [LeadSourceController::class, 'index'])->name('leadsource.index');
Route::post('/leadsource_save', [LeadSourceController::class, 'store'])->name('leadsource.store');
Route::put('/leadsource/{leadSource}', [LeadSourceController::class, 'update'])->name('leadsource.update');
Route::delete('/leadsource/{leadSource}', [LeadSourceController::class, 'destroy'])->name('leadsource.destroy');

// Category routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

// Inventory Item routes
Route::get('/inventory-items', [InventoryItemController::class, 'index'])->name('inventory-items.index');
Route::post('/inventory-items', [InventoryItemController::class, 'store'])->name('inventory-items.store');
Route::put('/inventory-items/{inventoryItem}', [InventoryItemController::class, 'update'])->name('inventory-items.update');
Route::delete('/inventory-items/{inventoryItem}', [InventoryItemController::class, 'destroy'])->name('inventory-items.destroy');