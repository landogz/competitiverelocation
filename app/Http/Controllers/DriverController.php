<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\JobTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{
    /**
     * Display the driver dashboard with load board
     */
    public function index()
    {
        try {
            // Get the authenticated user's agent ID
            $agentId = Auth::user()->agent_id;

            if (!$agentId) {
                Log::error('Driver access attempted without agent_id', [
                    'user_id' => Auth::id(),
                    'user_email' => Auth::user()->email
                ]);
                return redirect()->route('dashboard')->with('error', 'You do not have the necessary permissions to access the driver dashboard.');
            }

            // Get all in-progress transactions assigned to this agent
            $transactions = Transaction::where('status', 'in_progress')
                ->where('assigned_agent', $agentId)
                ->get();

            // Log the query results for debugging
            Log::info('Driver transactions query', [
                'agent_id' => $agentId,
                'transaction_count' => $transactions->count(),
                'user_id' => Auth::id()
            ]);

            return view('customer.driver', [
                'transactions' => $transactions
            ]);

        } catch (\Exception $e) {
            Log::error('Error in DriverController@index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            
            return redirect()->route('dashboard')
                ->with('error', 'An error occurred while loading the driver dashboard. Please try again later.');
        }
    }

    /**
     * Get transaction details for the view job button
     */
    public function getTransactionDetails($id)
    {
        try {
            $transaction = Transaction::where('transaction_id', $id)
                ->where('assigned_agent', Auth::user()->agent_id)
                ->first();

            if (!$transaction) {
                return response()->json([
                    'error' => 'Transaction not found or not assigned to you'
                ], 404);
            }

            return response()->json($transaction);
        } catch (\Exception $e) {
            Log::error('Error in DriverController@getTransactionDetails', [
                'error' => $e->getMessage(),
                'transaction_id' => $id,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'error' => 'Failed to load transaction details'
            ], 500);
        }
    }

    /**
     * Update transaction status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $transaction = Transaction::where('assigned_agent', Auth::user()->agent_id)
                ->findOrFail($id);

            $validated = $request->validate([
                'status' => 'required|in:in_progress,completed,cancelled'
            ]);

            $transaction->update($validated);

            return response()->json([
                'message' => 'Status updated successfully',
                'transaction' => $transaction
            ]);
        } catch (\Exception $e) {
            Log::error('Error in DriverController@updateStatus', [
                'error' => $e->getMessage(),
                'transaction_id' => $id,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'error' => 'Failed to update transaction status'
            ], 500);
        }
    }

    /**
     * Get driver's assigned transactions
     */
    public function getAssignedTransactions()
    {
        try {
            $transactions = Transaction::where('status', 'in_progress')
                ->where('assigned_agent', Auth::user()->agent_id)
                ->get();

            return response()->json($transactions);
        } catch (\Exception $e) {
            Log::error('Error in DriverController@getAssignedTransactions', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'error' => 'Failed to load assigned transactions'
            ], 500);
        }
    }

    /**
     * Start job time tracking
     */
    public function startJobTime(Request $request, $id)
    {
        try {
            // First check if transaction exists at all
            $transaction = Transaction::where('transaction_id', $id)->first();
            
            if (!$transaction) {
                return response()->json([
                    'error' => 'Transaction not found'
                ], 404);
            }

            // Then check if it's assigned to this agent
            if ($transaction->assigned_agent != Auth::user()->agent_id) {
                return response()->json([
                    'error' => 'This transaction is not assigned to you'
                ], 403);
            }

            // Check if job time already exists
            $jobTime = JobTime::where('transaction_id', $id)
                ->whereNull('end_time')
                ->first();

            if ($jobTime) {
                return response()->json([
                    'error' => 'Job time already started'
                ], 400);
            }

            // Create new job time record
            $jobTime = JobTime::create([
                'transaction_id' => $id,
                'user_id' => Auth::id(),
                'start_time' => now(),
                'start_signature' => $request->input('signature')
            ]);

            return response()->json([
                'message' => 'Job time started successfully',
                'job_time' => $jobTime
            ]);
        } catch (\Exception $e) {
            Log::error('Error in DriverController@startJobTime', [
                'error' => $e->getMessage(),
                'transaction_id' => $id,
                'user_id' => Auth::id(),
                'agent_id' => Auth::user()->agent_id
            ]);
            
            return response()->json([
                'error' => 'Failed to start job time: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * End job time tracking
     */
    public function endJobTime(Request $request, $id)
    {
        try {
            $transaction = Transaction::where('transaction_id', $id)
                ->where('assigned_agent', Auth::user()->agent_id)
                ->firstOrFail();

            $jobTime = JobTime::where('transaction_id', $id)
                ->whereNull('end_time')
                ->first();

            if (!$jobTime) {
                return response()->json([
                    'error' => 'No active job time found'
                ], 400);
            }

            $jobTime->update([
                'end_time' => now(),
                'end_signature' => $request->input('signature')
            ]);

            return response()->json([
                'message' => 'Job time ended successfully',
                'job_time' => $jobTime
            ]);
        } catch (\Exception $e) {
            Log::error('Error in DriverController@endJobTime', [
                'error' => $e->getMessage(),
                'transaction_id' => $id,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'error' => 'Failed to end job time'
            ], 500);
        }
    }

    /**
     * Get job time details
     */
    public function getJobTime($id)
    {
        try {
            $jobTime = JobTime::where('transaction_id', $id)
                ->where('user_id', Auth::id())
                ->first();

            // Get transaction details
            $transaction = Transaction::where('transaction_id', $id)
                ->where('assigned_agent', Auth::user()->agent_id)
                ->first();

            if (!$transaction) {
                return response()->json([
                    'error' => 'Transaction not found or not assigned to you'
                ], 404);
            }

            return response()->json([
                'job_time' => $jobTime,
                'transaction' => $transaction
            ]);
        } catch (\Exception $e) {
            Log::error('Error in DriverController@getJobTime', [
                'error' => $e->getMessage(),
                'transaction_id' => $id,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'error' => 'Failed to get job time details'
            ], 500);
        }
    }

    /**
     * Get inventory items for a transaction
     */
    public function getInventory($transactionId)
    {
        try {
            $transaction = Transaction::where('transaction_id', $transactionId)
                ->where('assigned_agent', Auth::user()->agent_id)
                ->first();

            if (!$transaction) {
                return response()->json([
                    'error' => 'Transaction not found or not assigned to you'
                ], 404);
            }

            $inventory = $transaction->inventoryItems()
                ->with('category')
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->item,
                        'category_name' => $item->category->name,
                        'quantity' => $item->pivot->quantity,
                        'cubic_ft' => $item->cubic_ft,
                        'description' => null // Add description if needed
                    ];
                });

            return response()->json([
                'inventory' => $inventory
            ]);

        } catch (\Exception $e) {
            Log::error('Error in DriverController@getInventory', [
                'error' => $e->getMessage(),
                'transaction_id' => $transactionId,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'error' => 'Failed to load inventory items'
            ], 500);
        }
    }
} 