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
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\CallCenterController;
use App\Http\Controllers\SalesRepController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ServiceRateController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\BestAgentsController;
use App\Http\Controllers\CalendarController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\CreditCardAuthorizationController;

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
        $user = Auth::user();
        
        // Check if user is an agent
        if ($user->privilege === 'agent') {
            // Get sales_rep data
            $salesRep = DB::table('sales_reps')
                ->where('user_id', $user->id)
                ->first();
            
            // Redirect based on position
            if ($salesRep && $salesRep->position === 'Driver') {
                return redirect('/driver');
            } else if ($salesRep && $salesRep->position === 'Sales Representative') {
                return redirect('/dashboard');
            }
        }
        
        // Default redirect for other users
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

// Credit Card Authorization Routes - These should be outside the auth middleware
Route::post('/credit-card-authorization', [CreditCardAuthorizationController::class, 'store'])->name('credit-card-authorization.store');
Route::get('/credit-card-authorization/{transaction_id}', [\App\Http\Controllers\CreditCardAuthorizationController::class, 'show'])->name('credit-card-authorization.show');
Route::put('/credit-card-authorization/{id}', [\App\Http\Controllers\CreditCardAuthorizationController::class, 'update'])->name('credit-card-authorization.update');

// Payment Routes - These should be outside the auth middleware
Route::get('/payment/{id}', [StripeController::class, 'showPaymentPage'])->name('payment.show');
Route::post('/payment/process', [StripeController::class, 'processPayment'])->name('payment.process');
Route::post('/payment/confirm', [StripeController::class, 'confirmPayment'])->name('payment.confirm');
Route::get('/payment/success', [StripeController::class, 'paymentSuccess'])->name('payment.success');

// All other routes should be protected with auth middleware
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/registercustomer', function () {
        return view('registercustomer');
    });

    Route::get('/loadboard', [TransactionController::class, 'index'])
        ->name('loadboard.index')
        ->middleware('not.agent');

    Route::get('/loadboard-agent', [TransactionController::class, 'index_agent'])
        ->name('loadboard-agent.index_agent')
        ->middleware('agent');

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
    Route::get('/agents/get-data', [AgentController::class, 'getData'])->name('agents.getData');
    Route::post('/agents/sync', [AgentController::class, 'syncAgents'])->name('agents.syncAgents');
    Route::post('/agents/update', [AgentController::class, 'update'])->name('agents.update');

    Route::get('/salesreps', [SalesRepController::class, 'index'])->name('salesreps.index');

    Route::get('/callcenter', [CallCenterController::class, 'index'])->name('callcenter.index');
    Route::get('/callcenter/create', [CallCenterController::class, 'create'])->name('callcenter.create');
    Route::post('/callcenter', [CallCenterController::class, 'store'])->name('callcenter.store');
    Route::get('/callcenter/{lead}', [CallCenterController::class, 'show'])->name('callcenter.show');
    Route::get('/callcenter/{lead}/edit', [CallCenterController::class, 'edit'])->name('callcenter.edit');
    Route::put('/callcenter/{lead}', [CallCenterController::class, 'update'])->name('callcenter.update');
    Route::delete('/callcenter/{lead}', [CallCenterController::class, 'destroy'])->name('callcenter.destroy');
    Route::post('/callcenter/{lead}/logs', [CallCenterController::class, 'storeLog'])->name('callcenter.logs.store');
    Route::get('/callcenter/{lead}/logs', [CallCenterController::class, 'viewLogs'])->name('callcenter.logs.view');
    Route::post('/callcenter/{lead}/send-email', [CallCenterController::class, 'sendEmail'])->name('callcenter.send-email');
    Route::post('/callcenter/datatable', [CallCenterController::class, 'datatable'])->name('callcenter.datatable');
    Route::get('/callcenter/{id}/data', [CallCenterController::class, 'getLeadData'])->name('callcenter.get-lead-data');

    Route::get('/salesreports', [SalesReportController::class, 'index'])->name('salesreports.index');
    Route::get('/salesreports/export', [SalesReportController::class, 'export'])->name('salesreports.export');
    Route::get('/salesreports/performance-data', [SalesReportController::class, 'getPerformanceData'])->name('salesreports.performance-data');
    Route::get('/salesreports/stats-data', [SalesReportController::class, 'getStatsData'])->name('salesreports.stats-data');
    Route::get('/salesreports/category-data', [SalesReportController::class, 'getCategoryData'])->name('salesreports.category-data');
    Route::get('/salesreports/agent-data', [SalesReportController::class, 'getAgentData'])->name('salesreports.agent-data');

    Route::get('/bestagents', [BestAgentsController::class, 'index'])->name('bestagents.index');
    Route::get('/bestagents/stats-data', [BestAgentsController::class, 'getStatsData'])->name('bestagents.stats-data');
    Route::get('/bestagents/performance-data', [BestAgentsController::class, 'getPerformanceData'])->name('bestagents.performance-data');
    Route::get('/bestagents/export', [BestAgentsController::class, 'export'])->name('bestagents.export');
    Route::get('/bestagents/detailed-stats', [BestAgentsController::class, 'getDetailedAgentStats'])->name('bestagents.detailed-stats');

    Route::get('/servicerates', [ServiceRateController::class, 'index'])->name('servicerates.index');
    Route::post('/servicerates/{id}', [ServiceRateController::class, 'update'])->name('servicerates.update');
    Route::post('/servicerates-batch', [ServiceRateController::class, 'updateBatch'])->name('servicerates.update-batch');

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

    Route::get('/calendar_trans', function () {
        return view('calendar_from_trans');
    });

    // Transaction Calendar Routes
    Route::get('/transaction-calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/events', [CalendarController::class, 'getEvents'])->name('calendar.events');

    Route::get('/customer', function () {
        return view('customer.customer');
    });

    Route::get('/driver', function () {
        return view('customer.driver');
    });

    Route::get('/driver/inventory/{transactionId}', [DriverController::class, 'getInventory'])->name('driver.inventory');

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
    Route::get('/stripe', [StripeController::class, 'index'])->name('stripe.index');
    Route::post('/stripe/settings', [StripeController::class, 'updateSettings'])->name('stripe.settings.update');
    Route::get('/stripe/connect', [StripeController::class, 'connect'])->name('stripe.connect');
    Route::post('/stripe/callback', [StripeController::class, 'callback'])->name('stripe.callback');
    Route::post('/stripe/validate-keys', [StripeController::class, 'validateKeys'])->name('stripe.validate-keys');

    // Password Update Route (for logged-in users)
    Route::post('/password/update', [AuthController::class, 'updatePassword'])->name('password.update');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('user.profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-photo', [ProfileController::class, 'updatePhoto'])->name('profile.update.photo');

    // Leads Routes
    Route::get('/leads', [TransactionController::class, 'create'])->name('leads.create');
    Route::get('/leads/{id}/edit', [TransactionController::class, 'edit'])->name('leads.edit');
    Route::post('/leads', [TransactionController::class, 'store'])->name('leads.store');
    Route::put('/leads/{id}', [TransactionController::class, 'update'])->name('leads.update');
    Route::post('/leads/{id}/update-field', [TransactionController::class, 'updateField'])->name('leads.update-field');
    Route::post('/leads/{id}/upload-insurance-document', [TransactionController::class, 'uploadInsuranceDocument'])->name('leads.upload-insurance-document');
    Route::post('/leads/{id}/update-inventory', [TransactionController::class, 'updateInventoryItem'])->name('leads.update-inventory');
    Route::get('/leads/{id}/added-inventory-items', [TransactionController::class, 'getAddedInventoryItems'])->name('leads.added-inventory-items');
    Route::post('/leads/{id}/send-email', [LeadController::class, 'sendEmail'])->name('leads.send-email');
    Route::get('/leads/{id}/sent-emails', [LeadController::class, 'getSentEmails'])->name('leads.sent-emails');
    Route::get('/leads/{id}', [LeadController::class, 'show'])->name('leads.show');

    Route::post('/transactions/sync', [TransactionController::class, 'syncFromApi'])->name('transactions.sync');
    Route::post('/transactions/{transaction}/status', [TransactionController::class, 'updateStatus'])->name('transactions.status');
    Route::get('/transactions/get-counts', [TransactionController::class, 'getCounts'])->name('transactions.getCounts');
    Route::post('/transactions/{transactionId}/accept', [TransactionController::class, 'acceptJob'])->name('transactions.accept');
    Route::post('/transactions/{transactionId}/decline', [TransactionController::class, 'declineJob'])->name('transactions.decline');

    Route::post('/transactions/datatable', [TransactionController::class, 'datatable'])->name('transactions.datatable');
    Route::post('/transactions/agent-datatable', [TransactionController::class, 'agentDatatable'])->name('transactions.agent-datatable');

    Route::get('/email-templates', [EmailTemplateController::class, 'index'])->name('email-templates.index');
    Route::post('/email-templates', [EmailTemplateController::class, 'store'])->name('email-templates.store');
    Route::get('/email-templates/{template}', [EmailTemplateController::class, 'show'])->name('email-templates.show');
    Route::put('/email-templates/{template}', [EmailTemplateController::class, 'update'])->name('email-templates.update');
    Route::delete('/email-templates/{template}', [EmailTemplateController::class, 'destroy'])->name('email-templates.destroy');
    Route::post('/email-templates/{template}/test', [EmailTemplateController::class, 'test'])->name('email-templates.test');
    Route::post('/email-templates/send-custom', [EmailTemplateController::class, 'sendCustomEmail'])->name('email-templates.send-custom');

    Route::post('/leads/sync', [CallCenterController::class, 'sync'])->name('leads.sync');
    Route::post('/leads/datatable', [CallCenterController::class, 'datatable'])->name('leads.datatable');

    // Sales Representatives Routes
    Route::get('/salesreps', [SalesRepController::class, 'index'])->name('salesreps.index');
    Route::get('/salesreps/agents', [SalesRepController::class, 'getAgents'])->name('salesreps.agents');
    Route::post('/salesreps', [SalesRepController::class, 'store'])->name('salesreps.store');
    Route::get('/salesreps/{salesRep}', [SalesRepController::class, 'show'])->name('salesreps.show');
    Route::put('/salesreps/{salesRep}', [SalesRepController::class, 'update'])->name('salesreps.update');
    Route::delete('/salesreps/{salesRep}', [SalesRepController::class, 'destroy'])->name('salesreps.destroy');
    Route::post('/salesreps/{id}/reset-password', [SalesRepController::class, 'resetPassword'])->name('salesreps.reset-password');

    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');

    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');

    Route::post('/loadboard/send-email', [TransactionController::class, 'sendEmail'])->name('loadboard.sendEmail');

    // Driver Routes
    Route::get('/driver', [DriverController::class, 'index'])->name('driver.dashboard');
    Route::get('/driver/transaction/{id}', [DriverController::class, 'getTransactionDetails'])->name('driver.transaction.details');
    Route::patch('/driver/transaction/{id}/status', [DriverController::class, 'updateStatus'])->name('driver.transaction.status');
    Route::get('/driver/transactions', [DriverController::class, 'getAssignedTransactions'])->name('driver.transactions');

    // Job Time Tracking Routes
    Route::post('/driver/job-time/{id}/start', [DriverController::class, 'startJobTime'])->name('driver.job-time.start');
    Route::get('/driver/job-time/{id}', [DriverController::class, 'getJobTime'])->name('driver.job-time.get');
    Route::post('/driver/job-time/{id}/end', [DriverController::class, 'endJobTime'])->name('driver.job-time.end');

    // Inventory Routes
    Route::get('/driver/inventory/{id}', [DriverController::class, 'getInventory'])->name('driver.inventory');

    // Transaction routes
    Route::post('/transaction/save', [TransactionController::class, 'saveTransaction'])->name('transaction.save');
});

// Add this route at the end of the file
Route::get('/debug/calendar-agents', function() {
    $transactions = \App\Models\Transaction::with('agent')->get();
    $agents = \App\Models\Agent::all();
    $users = \App\Models\User::where('privilege', 'agent')->get();
    
    return [
        'transaction_count' => $transactions->count(),
        'agent_count' => $agents->count(),
        'agent_users_count' => $users->count(),
        'sample_transactions' => $transactions->take(5)->map(function($transaction) {
            return [
                'id' => $transaction->id,
                'name' => $transaction->firstname . ' ' . $transaction->lastname,
                'date' => $transaction->date,
                'assigned_agent' => $transaction->assigned_agent,
                'agent' => $transaction->agent ? [
                    'id' => $transaction->agent->id,
                    'company' => $transaction->agent->company_name
                ] : null
            ];
        }),
        'agents' => $agents->map(function($agent) {
            return [
                'id' => $agent->id,
                'company' => $agent->company_name,
                'transaction_count' => \App\Models\Transaction::where('assigned_agent', $agent->id)->count()
            ];
        }),
        'agent_users' => $users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'agent_id' => $user->agent_id,
                'assigned_transactions' => \App\Models\Transaction::where('assigned_agent', $user->agent_id)->count()
            ];
        })
    ];
});