<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CreditCardAuthorization;

class CreditCardAuthorizationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'full_name' => 'nullable|string',
            'name' => 'nullable|string',
            'title' => 'nullable|string',
            'card_type' => 'nullable|string',
            'last_8_digits' => 'nullable|string',
            'cvc' => 'nullable|string',
            'expiration_date' => 'nullable|string',
            'cardholder_name' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'street_address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip_code' => 'nullable|string',
            'signature' => 'nullable|string',
            'date' => 'nullable|date',
        ]);
        $authorization = CreditCardAuthorization::create($validated);
        return response()->json(['success' => true, 'data' => $authorization]);
    }

    public function show($transactionId)
    {
        $auth = \App\Models\CreditCardAuthorization::where('transaction_id', $transactionId)->first();
        if (!$auth) {
            return response()->json(['success' => false, 'data' => null]);
        }
        return response()->json(['success' => true, 'data' => $auth]);
    }

    public function update(Request $request, $id)
    {
        $auth = \App\Models\CreditCardAuthorization::findOrFail($id);
        $validated = $request->validate([
            'full_name' => 'nullable|string',
            'name' => 'nullable|string',
            'title' => 'nullable|string',
            'card_type' => 'nullable|string',
            'last_8_digits' => 'nullable|string',
            'cvc' => 'nullable|string',
            'expiration_date' => 'nullable|string',
            'cardholder_name' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'street_address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip_code' => 'nullable|string',
            'signature' => 'nullable|string',
            'date' => 'nullable|date',
            'comments' => 'nullable|string',
            'transaction_id' => 'nullable|exists:transactions,id'
        ]);
        $auth->update($validated);
        return response()->json(['success' => true, 'data' => $auth]);
    }
} 