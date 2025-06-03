<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function store(TransactionRequest $request)
    {
        try {
            // Split the name into firstname and lastname
            $nameParts = explode(' ', $request->name);
            $firstname = $nameParts[0];
            $lastname = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : '';

            // Format pickup and delivery locations as strings
            $pickupLocation = $request->from_areacode . ', ' . $request->from_zip . ', ' . $request->from_state . ', ' . $request->from_city;
            $deliveryLocation = $request->to_areacode . ', ' . $request->to_zip . ', ' . $request->to_state . ', ' . $request->to_city;

            // Generate transaction ID in format "001302"
            $lastTransaction = DB::table('transactions')->orderBy('id', 'desc')->first();
            $newTransactionId = $lastTransaction ? $lastTransaction->id + 1 : 1;
            $transactionId = str_pad($newTransactionId, 6, '0', STR_PAD_LEFT);

            // Create transaction record
            $transaction = DB::table('transactions')->insert([
                'transaction_id' => $transactionId,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $request->email,
                'phone' => $request->phone,
                'phone2' => $request->ext,
                'lead_type' => 'long_distance',
                'status' => 'lead',
                'date' => $request->move_date,
                'pickup_location' => $pickupLocation,
                'delivery_location' => $deliveryLocation,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Leads created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create leads',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
