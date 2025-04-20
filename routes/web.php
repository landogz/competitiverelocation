<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadSourceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;

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
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return redirect('/auth/login');
});

Route::get('/forgot', function () {
    return view('auth.forgot');
});

// Auth Routes - These should be outside the auth middleware
Route::get('/auth/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/auth/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes - These should be outside the auth middleware
Route::get('/password/reset', [AuthController::class, 'showForgotForm'])->name('password.request');
Route::post('/password/email', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.reset.submit');

// All other routes should be protected with auth middleware
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

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

    Route::get('/agents', [AgentController::class, 'index'])->name('agents.index');
    Route::get('/agents/data', [AgentController::class, 'data'])->name('agents.data');
    Route::post('/agents/sync', [AgentController::class, 'syncAgents'])->name('agents.syncAgents');
    Route::post('/agents/update', [AgentController::class, 'update'])->name('agents.update');

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

    Route::get('/stripe', [StripeController::class, 'index'])->name('stripe.index');
    Route::post('/stripe/settings', [StripeController::class, 'updateSettings'])->name('stripe.settings.update');

    Route::get('/sms', [SmsController::class, 'index'])->name('sms.index');
    Route::post('/sms/settings', [SmsController::class, 'updateSettings'])->name('sms.settings.update');
    Route::post('/sms/send', [SmsController::class, 'sendSms'])->name('sms.send');
    Route::get('/sms/logs', [SmsController::class, 'getLogs'])->name('sms.logs');
    Route::get('/sms/status/{transactionId}', [SmsController::class, 'checkStatus'])->name('sms.status');

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

    // Stripe Connect Routes
    Route::get('/stripe/connect', [StripeController::class, 'connect'])->name('stripe.connect');
    Route::post('/stripe/callback', [StripeController::class, 'callback'])->name('stripe.callback');

    // Password Update Route (for logged-in users)
    Route::post('/password/update', [AuthController::class, 'updatePassword'])->name('password.update');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-photo', [ProfileController::class, 'updatePhoto'])->name('profile.update.photo');
});