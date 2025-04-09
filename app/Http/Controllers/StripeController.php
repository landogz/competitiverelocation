<?php

namespace App\Http\Controllers;

use App\Models\StripeSetting;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Account;
use Illuminate\Support\Facades\Log;

class StripeController extends Controller
{
    public function index()
    {
        $settings = StripeSetting::first();
        return view('stripe', compact('settings'));
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
            $request->validate([
                'secret_key' => 'required|string'
            ]);

            // Test the Stripe key
            Stripe::setApiKey($request->secret_key);
            
            try {
                // Try to retrieve account information to validate key
                $stripe = new \Stripe\StripeClient($request->secret_key);
                $account = $stripe->accounts->retrieve('acct_current');
                
                // If we get here, the key is valid
                $isActive = true;
                $lastError = null;
            } catch (ApiErrorException $e) {
                $isActive = false;
                $lastError = $e->getMessage();
                Log::error('Stripe validation failed: ' . $e->getMessage());
            }

            // Update or create settings
            StripeSetting::updateOrCreate(
                ['id' => 1],
                [
                    'secret_key' => $request->secret_key,
                    'is_active' => $isActive,
                    'last_error' => $lastError,
                    'last_checked_at' => now()
                ]
            );

            return response()->json([
                'success' => true,
                'message' => $isActive ? 'Stripe key validated and saved successfully!' : 'Stripe key saved but validation failed.',
                'is_active' => $isActive,
                'error' => $lastError
            ]);

        } catch (\Exception $e) {
            Log::error('Stripe settings update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Stripe settings: ' . $e->getMessage()
            ], 500);
        }
    }
} 