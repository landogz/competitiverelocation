<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function show($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            $salesReps = \App\Models\SalesRep::where('agent_id', $transaction->assigned_agent)->get();
            // Parse services if it's a string
            if (is_string($transaction->services)) {
                $transaction->services = json_decode($transaction->services, true);
            }

            // Get inventory items
            $inventory = $transaction->inventoryItems()
                ->with('category')
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->item,
                        'category_name' => $item->category->name,
                        'quantity' => $item->pivot->quantity,
                        'cubic_ft' => $item->cubic_ft
                    ];
                });

            return view('customer.customer', [
                'transaction' => $transaction,
                'inventory' => $inventory,
                'salesReps' => $salesReps
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in CustomerController@show', [
                'error' => $e->getMessage(),
                'transaction_id' => $id
            ]);
            
            return response()->json([
                'error' => 'Failed to load customer details. Please try again later.'
            ], 404);
        }
    }

    /**
     * Get inventory items for a transaction
     */
    public function getInventory($transactionId)
    {
        try {
            $transaction = \App\Models\Transaction::findOrFail($transactionId);
    
            $inventory = $transaction->inventoryItems()
                ->with('category')
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->item,
                        'category_name' => $item->category->name,
                        'quantity' => $item->pivot->quantity,
                        'cubic_ft' => $item->cubic_ft
                    ];
                });
    
            return response()->json([
                'success' => true,
                'inventory' => $inventory
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in CustomerController@getInventory', [
                'error' => $e->getMessage(),
                'transaction_id' => $transactionId
            ]);
            return response()->json([
                'error' => 'Failed to load inventory items'
            ], 500);
        }
    }

    /**
     * Get payment activity for a transaction
     */
    public function getPaymentActivity($transactionId)
    {
        try {
            $transaction = \App\Models\Transaction::findOrFail($transactionId);
            
            // Get payment history from transaction_payments table
            $payments = DB::table('transaction_payments')
                ->where('transaction_id', $transactionId)
                ->select([
                    'status',
                    'amount',
                    'created_at',
                    'payment_method',
                    'currency'
                ])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($payment) {
                    // Format payment method for display
                    $paymentMethod = $payment->payment_method;
                    if (str_starts_with($paymentMethod, 'pm_')) {
                        $paymentMethod = 'Credit Card (••••' . substr($paymentMethod, -4) . ')';
                    } elseif (empty($paymentMethod)) {
                        $paymentMethod = 'Credit Card';
                    }

                    return [
                        'status' => ucfirst(strtolower($payment->status)),
                        'amount' => number_format($payment->amount, 2),
                        'created_at' => date('n/j/Y, g:i:s A', strtotime($payment->created_at)),
                        'payment_method' => $paymentMethod,
                        'currency' => strtoupper($payment->currency)
                    ];
                });

            // Calculate totals
            $totalPaid = $payments->sum(function($payment) {
                return floatval($payment['amount']);
            });

            $grandTotal = floatval($transaction->total_amount ?? 0);
            $remainingBalance = $grandTotal - $totalPaid;

            return response()->json([
                'success' => true,
                'payments' => $payments,
                'summary' => [
                    'total_paid' => number_format($totalPaid, 2),
                    'grand_total' => number_format($grandTotal, 2),
                    'remaining_balance' => number_format($remainingBalance, 2)
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in CustomerController@getPaymentActivity', [
                'error' => $e->getMessage(),
                'transaction_id' => $transactionId
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to load payment activity'
            ], 500);
        }
    }
} 