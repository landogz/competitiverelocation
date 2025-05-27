<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Agent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Debug message to log
        if ($user && $user->privilege === 'agent') {
            Log::info('Agent user accessing calendar', [
                'user_id' => $user->id,
                'agent_id' => $user->agent_id,
                'privilege' => $user->privilege
            ]);
        }
        
        return view('calendar');
    }
    
    public function getEvents(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Log the SQL queries for debugging
            DB::enableQueryLog();
            
            $query = Transaction::with('agent');
            
            // Debug logging to track user and agent relationship
            if ($user) {
                Log::info('Calendar events request', [
                    'user_id' => $user->id,
                    'user_privilege' => $user->privilege,
                    'user_agent_id' => $user->agent_id,
                    'has_agent' => !is_null($user->agent_id),
                ]);
            }
            
            // If user is an agent, only show their assigned transactions
            if ($user && $user->privilege === 'agent') {
                if ($user->agent_id) {
                    $query->where('assigned_agent', $user->agent_id);
                    Log::info('Filtering transactions for agent', [
                        'agent_id' => $user->agent_id,
                        'query_sql' => $query->toSql(),
                        'query_bindings' => $query->getBindings()
                    ]);
                } else {
                    Log::warning('Agent user has no agent_id assigned', [
                        'user_id' => $user->id
                    ]);
                    
                    // Return empty data if agent has no agent_id
                    return response()->json([]);
                }
            }
            
            // Apply date range filtering if start and end parameters are provided
            if ($request->has('start') && $request->has('end')) {
                try {
                    $startDate = Carbon::parse($request->input('start'));
                    $endDate = Carbon::parse($request->input('end'));
                    
                    $query->whereDate('date', '>=', $startDate->toDateString())
                          ->whereDate('date', '<=', $endDate->toDateString());
                    
                    Log::info('Date filtering applied', [
                        'start_date' => $startDate->toDateString(),
                        'end_date' => $endDate->toDateString()
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error parsing calendar date range: ' . $e->getMessage());
                    // Continue without date filtering if parsing fails
                }
            }
            
            // Get transactions based on filters
            $transactions = $query->get();
            
            // Log the executed queries
            $queryLog = DB::getQueryLog();
            Log::info('Transaction queries', [
                'queries' => $queryLog
            ]);
            
            Log::info('Transactions fetched for calendar', [
                'count' => $transactions->count(),
                'user_id' => $user ? $user->id : 'guest',
                'user_privilege' => $user ? $user->privilege : 'guest',
            ]);
            
            $events = [];
            
            foreach ($transactions as $transaction) {
                // Create event data only if date is valid
                if (!$transaction->date) {
                    Log::warning('Transaction has no date', ['transaction_id' => $transaction->id]);
                    continue;
                }
                
                try {
                    $event = [
                        'id' => $transaction->id,
                        'title' => $transaction->firstname . ' ' . $transaction->lastname,
                        'start' => $transaction->date->format('Y-m-d\TH:i:s'),
                        // Set end time to 1 hour after start if no specific end time is available
                        'end' => $transaction->date->copy()->addHour()->format('Y-m-d\TH:i:s'),
                        'extendedProps' => [
                            'transaction_id' => $transaction->transaction_id,
                            'email' => $transaction->email,
                            'phone' => $transaction->phone,
                            'lead_source' => $transaction->lead_source,
                            'lead_type' => $transaction->lead_type,
                            'status' => $transaction->status,
                            'assigned_agent' => $transaction->assigned_agent,
                            'agent_name' => $transaction->agent ? $transaction->agent->contact_name : 'Unassigned',
                            'agent_company' => $transaction->agent ? $transaction->agent->company_name : null,
                            'sales_name' => $transaction->sales_name,
                            'sales_email' => $transaction->sales_email,
                            'sales_location' => $transaction->sales_location,
                            'pickup_location' => $transaction->pickup_location,
                            'delivery_location' => $transaction->delivery_location,
                            'service' => $transaction->service,
                            'services' => $transaction->services,
                            'miles' => $transaction->miles,
                            'add_mile' => $transaction->add_mile,
                            'mile_rate' => $transaction->mile_rate,
                            'service_rate' => $transaction->service_rate,
                            'no_of_items' => $transaction->no_of_items,
                            'no_of_crew' => $transaction->no_of_crew,
                            'crew_rate' => $transaction->crew_rate,
                            'delivery_rate' => $transaction->delivery_rate,
                            'subtotal' => $transaction->subtotal,
                            'software_fee' => $transaction->software_fee,
                            'truck_fee' => $transaction->truck_fee,
                            'downpayment' => $transaction->downpayment,
                            'grand_total' => $transaction->grand_total,
                            'coupon_code' => $transaction->coupon_code,
                            'payment_id' => $transaction->payment_id,
                            'insurance_number' => $transaction->insurance_number,
                            'insurance_document' => $transaction->insurance_document ? asset($transaction->insurance_document) : null,
                            'created_at' => $transaction->created_at ? $transaction->created_at->format('Y-m-d H:i:s') : null,
                            'uploaded_image' => $transaction->uploaded_image,
                        ]
                    ];
                    
                    $events[] = $event;
                } catch (\Exception $e) {
                    Log::error('Error formatting transaction for calendar: ' . $e->getMessage(), [
                        'transaction_id' => $transaction->id
                    ]);
                    // Skip this transaction if there was an error
                    continue;
                }
            }
            
            return response()->json($events);
        } catch (\Exception $e) {
            Log::error('Calendar events error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to load calendar events: ' . $e->getMessage()], 500);
        }
    }
} 