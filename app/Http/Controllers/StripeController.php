<?php

namespace App\Http\Controllers;

use App\Models\StripeSetting;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Account;
use Illuminate\Support\Facades\Log;
use App\Models\TransactionPayment;
use Illuminate\Support\Facades\DB;

class StripeController extends Controller
{
    public function index()
    {
        $settings = StripeSetting::first();
        
        // Get all payment records ordered by most recent first
        $payments = \App\Models\TransactionPayment::with('transaction')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('stripe', compact('settings', 'payments'));
    }

    public function connect()
    {
        // Generate a random state parameter for security
        $state = bin2hex(random_bytes(16));
        session(['stripe_state' => $state]);
        
        // Build the OAuth URL
        $params = [
            'response_type' => 'code',
            'client_id' => config('services.stripe.client_id'),
            'scope' => 'read_write',
            'state' => $state,
            'suggested_capabilities[]' => 'transfers',
            'stripe_user[email]' => auth()->user()->email ?? '',
            'stripe_landing' => 'login',
        ];
        
        $url = 'https://connect.stripe.com/oauth/authorize?' . http_build_query($params);
        
        return redirect($url);
    }

    public function callback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()->route('stripe.index')
                ->with('error', 'Stripe connection failed: ' . $request->error_description);
        }
        
        if ($request->state !== session('stripe_state')) {
            return redirect()->route('stripe.index')
                ->with('error', 'Invalid state parameter. Please try again.');
        }
        
        try {
            // Exchange the authorization code for an access token
            $response = \Http::asForm()->post('https://connect.stripe.com/oauth/token', [
                'grant_type' => 'authorization_code',
                'code' => $request->code,
                'client_secret' => config('services.stripe.secret'),
            ]);
            
            if (!$response->successful()) {
                throw new \Exception('Failed to exchange authorization code');
            }
            
            $data = $response->json();
            
            // Get the connected account details
            Stripe::setApiKey(config('services.stripe.secret'));
            $account = Account::retrieve($data['stripe_user_id']);
            
            // Save or update the settings
            $settings = StripeSetting::first() ?? new StripeSetting();
            $settings->secret_key = $data['access_token'];
            $settings->publishable_key = $account->settings->publishable_key;
            $settings->account_id = $data['stripe_user_id'];
            $settings->is_active = true;
            $settings->save();
            
            return redirect()->route('stripe.index')
                ->with('success', 'Successfully connected to Stripe!');
                
        } catch (\Exception $e) {
            return redirect()->route('stripe.index')
                ->with('error', 'Failed to connect to Stripe: ' . $e->getMessage());
        }
    }

    public function updateSettings(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'secret_key' => 'required|string',
                'public_key' => 'required|string',
                'is_active' => 'required|boolean'
            ]);

            // Begin database transaction
            DB::beginTransaction();
            
            try {
                // Update or create settings
                $settings = StripeSetting::updateOrCreate(
                    ['id' => 1],
                    [
                        'secret_key' => $validated['secret_key'],
                        'public_key' => $validated['public_key'],
                        'is_active' => $validated['is_active'],
                        'last_checked_at' => now()
                    ]
                );

                // Commit the transaction
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Stripe settings updated successfully!',
                    'is_active' => $settings->is_active
                ]);

            } catch (\Exception $e) {
                // Rollback the transaction on error
                DB::rollBack();
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Stripe settings update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Stripe settings. Please try again.'
            ], 500);
        }
    }

    public function validateKeys(Request $request)
    {
        try {
            $request->validate([
                'secret_key' => 'required|string',
                'public_key' => 'required|string'
            ]);

            // Set the Stripe key
            Stripe::setApiKey($request->secret_key);

            // Try to make a test API call
            $stripe = new \Stripe\StripeClient($request->secret_key);
            $account = $stripe->accounts->retrieve('acct_current');

            return response()->json([
                'is_valid' => true,
                'message' => 'Keys are valid'
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe key validation failed: ' . $e->getMessage());
            return response()->json([
                'is_valid' => false,
                'message' => 'Invalid keys: ' . $e->getMessage()
            ], 400);
        }
    }

    public function showPaymentPage($id)
    {
        try {
            // Get transaction details from the database
            $transaction = \App\Models\Transaction::findOrFail($id);
            
            // Get Stripe settings
            $settings = StripeSetting::first();
            
            if (!$settings || !$settings->is_active) {
                return view('errors.payment', ['message' => 'Payment system is currently unavailable.']);
            }

            // Parse services JSON if it's a string
            if (is_string($transaction->services)) {
                $transaction->services = json_decode($transaction->services, true);
            }

            // Calculate amount based on service type
            $amount = 0;
            $hasMovingServices = false;
            $serviceNames = [];
            
            if (is_array($transaction->services)) {
                foreach ($transaction->services as $service) {
                    if (isset($service['name'])) {
                        if ($service['name'] === 'MOVING SERVICES') {
                            $hasMovingServices = true;
                            $amount = floatval(str_replace(['$', ','], '', $transaction->downpayment));
                        } else {
                            $serviceNames[] = $service['name'];
                        }
                    }
                }
            }
            
            if (!$hasMovingServices) {
                $amount = floatval(str_replace(['$', ','], '', $transaction->grand_total));
            }

            // Format numeric values
            $transaction->subtotal = floatval(str_replace(['$', ','], '', $transaction->subtotal));
            $transaction->software_fee = floatval(str_replace(['$', ','], '', $transaction->software_fee));
            $transaction->truck_fee = floatval(str_replace(['$', ','], '', $transaction->truck_fee));
            $transaction->downpayment = floatval(str_replace(['$', ','], '', $transaction->downpayment));
            $transaction->grand_total = floatval(str_replace(['$', ','], '', $transaction->grand_total));
            $transaction->mile_rate = floatval(str_replace(['$', ','], '', $transaction->mile_rate));
            
            return view('payment', [
                'transaction' => $transaction,
                'stripeKey' => $settings->public_key,
                'amount' => $amount,
                'hasMovingServices' => $hasMovingServices,
                'serviceNames' => $serviceNames
            ]);

        } catch (\Exception $e) {
            \Log::error('Payment page error: ' . $e->getMessage());
            return view('errors.payment', [
                'message' => 'An error occurred while loading the payment page. Please try again later.'
            ]);
        }
    }

    public function processPayment(Request $request)
    {
        try {
            $request->validate([
                'transaction_id' => 'required|exists:transactions,id',
                // 'payment_method_id' => 'required|string' // Remove this line for live mode
            ]);

            $transaction = \App\Models\Transaction::findOrFail($request->transaction_id);
            $transactionpayment = \App\Models\TransactionPayment::where('transaction_id', $transaction->id)->first();
            $settings = StripeSetting::first();

            if (!$settings || !$settings->is_active) {
                throw new \Exception('Payment system is currently unavailable.');
            }

            // Parse services JSON if it's a string
            if (is_string($transaction->services)) {
                $transaction->services = json_decode($transaction->services, true);
            }

            // Calculate the amount based on service type
            $amount = 0;
            $hasMovingServices = false;
            
            if (is_array($transaction->services)) {
                foreach ($transaction->services as $service) {
                    if (isset($service['name']) && $service['name'] === 'MOVING SERVICES') {
                        $hasMovingServices = true;
                        break;
                    }
                }
            }

            // If this is a remaining balance payment
            if ($request->is_remaining_balance) {
                // Get all successful payments for this transaction
                $totalPaid = \App\Models\TransactionPayment::where('transaction_id', $transaction->id)
                    ->where('status', 'succeeded')
                    ->sum('amount');

                // Check if job times are completed
                $jobTime = \App\Models\JobTime::where('transaction_id', $transaction->transaction_id)->first();
                if (!$jobTime || !$jobTime->end_time || !$jobTime->end_signature) {
                    throw new \Exception('Cannot process payment. Job must be completed with end time and signature first.');
                }
                
                // Calculate remaining balance
                $grandTotal = floatval(str_replace(['$', ','], '', $transaction->grand_total));
                $amount = max($grandTotal - $totalPaid, 0);
            } else {
                // For initial payment, use downpayment if MOVING SERVICES exists and downpayment is set, otherwise use grand_total
                if ($hasMovingServices && floatval(str_replace(['$', ','], '', $transaction->downpayment)) > 0) {
                    $amount = floatval(str_replace(['$', ','], '', $transaction->downpayment));
                } else {
                    $amount = floatval(str_replace(['$', ','], '', $transaction->grand_total));
                }
            }

            // Ensure minimum amount (50 cents for USD)
            if ($amount < 0.50) {
                throw new \Exception('Payment amount must be at least $0.50 USD.');
            }

            // Set Stripe API key from settings
            Stripe::setApiKey($settings->secret_key);

            // Check if customer exists by email
            $customers = \Stripe\Customer::all(['email' => $transaction->email]);
            $customer = null;

            if (count($customers->data) > 0) {
                // Use existing customer
                $customer = $customers->data[0];
            } else {
                // Create new customer
                $customer = \Stripe\Customer::create([
                    'email' => $transaction->email,
                    'name' => $transaction->firstname . ' ' . $transaction->lastname,
                    'phone' => $transaction->phone,
                ]);
            }

            // Determine payment type and description
            $downpaymentValue = isset($transaction->downpayment) ? floatval(str_replace(['$', ','], '', $transaction->downpayment)) : 0;
            $isFullPayment = $hasMovingServices && !$request->is_remaining_balance && $downpaymentValue <= 0;

            if ($isFullPayment) {
                $paymentType = 'full_payment';
                $description = 'Full Payment';
            } elseif ($request->is_remaining_balance) {
                $paymentType = 'remaining_balance';
                $description = 'Remaining Balance Payment';
            } else {
                $paymentType = 'initial_payment';
                $description = 'Initial Payment';
            }

            // Create payment intent
            $paymentIntentData = [
                'amount' => round($amount * 100), // Convert to cents and ensure whole number
                'currency' => 'usd',
                'customer' => $customer->id,
                'description' => $description,
                'payment_method_types' => ['card'],
                'metadata' => [
                    'transaction_id' => $transaction->id,
                    'customer_name' => $transaction->firstname . ' ' . $transaction->lastname,
                    'payment_type' => $paymentType,
                    'service_type' => $hasMovingServices ? 'moving_services' : 'other_services'
                ],
                'setup_future_usage' => 'off_session'
            ];
            // Only set payment_method if provided (for test mode)
            if ($request->has('payment_method_id') && $request->payment_method_id) {
                $paymentIntentData['payment_method'] = $request->payment_method_id;
            }
            $paymentIntent = \Stripe\PaymentIntent::create($paymentIntentData);

            return response()->json([
                'success' => true,
                'clientSecret' => $paymentIntent->client_secret,
                'stripeKey' => $settings->public_key,
                'amount' => $amount,
                'payment_type' => $paymentType
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function confirmPayment(Request $request)
    {
        try {
            $request->validate([
                'transaction_id' => 'required|exists:transactions,id',
                'payment_intent_id' => 'required|string'
            ]);

            $transaction = \App\Models\Transaction::findOrFail($request->transaction_id);
            $settings = StripeSetting::first();

            if (!$settings || !$settings->is_active) {
                throw new \Exception('Payment system is currently unavailable.');
            }

            // Set Stripe API key from settings
            Stripe::setApiKey($settings->secret_key);

            // Retrieve the payment intent
            $paymentIntent = \Stripe\PaymentIntent::retrieve($request->payment_intent_id);
            
            if ($paymentIntent->status === 'succeeded') {
                // Check if payment record already exists
                $existingPayment = TransactionPayment::where('payment_intent_id', $paymentIntent->id)->first();
                
                if (!$existingPayment) {
                    // Create new payment record
                    TransactionPayment::create([
                        'transaction_id' => $transaction->id,
                        'payment_intent_id' => $paymentIntent->id,
                        'amount' => $paymentIntent->amount / 100,
                        'status' => $paymentIntent->status,
                        'payment_method' => $paymentIntent->payment_method ?? null,
                        'currency' => $paymentIntent->currency,
                        'raw_response' => json_encode($paymentIntent)
                    ]);

                    // Update transaction with payment information and status
                    $transaction->payment_id = $paymentIntent->id;
                    
                    // Check if this is the final payment (remaining balance)
                    if ($request->payment_type === 'remaining_balance') {
                        $transaction->status = 'completed';
                    }
                    else if ($request->payment_type === 'initial_payment') {
                        $transaction->status = 'in_progress';
                    }
                    else if ($request->payment_type === 'full_payment') {
                        $transaction->status = 'in_progress';
                    }
                    
                    
                    $transaction->save();

                    Log::info('Payment processed successfully', [
                        'transaction_id' => $transaction->id,
                        'payment_intent_id' => $paymentIntent->id,
                        'amount' => $paymentIntent->amount / 100,
                        'status' => $transaction->status
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Payment successful!',
                    'status' => $transaction->status
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment requires additional confirmation.',
                    'payment_intent_client_secret' => $paymentIntent->client_secret
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 