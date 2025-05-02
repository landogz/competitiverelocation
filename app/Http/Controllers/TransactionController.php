<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\LeadSource;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function index()
    {
        // Join lead_sources and agents tables to fetch titles and company names efficiently
        $transactions = Transaction::query()
            ->leftJoin('lead_sources', 'transactions.lead_source', '=', 'lead_sources.id')
            ->leftJoin('agents', 'transactions.assigned_agent', '=', 'agents.id')
            ->select(
                'transactions.*',
                'lead_sources.title as lead_source_title',
                'agents.company_name as assigned_agent_company_name'
            )
            ->orderByDesc('transactions.created_at')
            ->get();

        // Get email templates
        $templates = \App\Models\EmailTemplate::all();

        // Define placeholders for transaction data
        $placeholders = [
            'Transaction' => [
                'Customer Name' => '{customer_name}',
                'Customer Email' => '{customer_email}',
                'Customer Phone' => '{customer_phone}',
                'Service Type' => '{service_type}',
                'Pickup Location' => '{pickup_location}',
                'Delivery Location' => '{delivery_location}',
                'Move Date' => '{move_date}',
                'Total Amount' => '{total_amount}',
                'Down Payment' => '{down_payment}',
                'Remaining Balance' => '{remaining_balance}',
                'Miles' => '{miles}',
                'Assigned Agent' => '{assigned_agent}',
                'Lead Source' => '{lead_source}',
                'Transaction ID' => '{transaction_id}'
            ]
        ];

        return view('loadboard', compact('transactions', 'templates', 'placeholders'));
    }

    public function syncFromApi()
    {
        try {
            Log::info('Starting API sync process');
            
            $response = Http::get('https://competitiverelocation.com/wp-json/landogz/v1/transactions');
            
            if (!$response->successful()) {
                Log::error('API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch data from API',
                    'error' => $response->body()
                ], 500);
            }

            $responseData = $response->json();
            Log::info('Raw API Response:', ['response' => $responseData]);
            
            $transactions = $responseData['data'] ?? [];
            Log::info('API response received', [
                'count' => count($transactions),
                'first_transaction' => $transactions[0] ?? 'No transactions'
            ]);

            $created = 0;
            $errors = [];

            foreach ($transactions as $data) {
                try {
                    // Log the transaction being processed
                    Log::info('Processing transaction', [
                        'transaction_id' => $data['id'] ?? 'unknown',
                        'email' => $data['email'] ?? 'unknown',
                        'service' => $data['service'] ?? 'unknown'
                    ]);
                    
                    // Check if transaction already exists
                    $existingTransaction = Transaction::where('transaction_id', $data['id'])->first();
                    
                    if ($existingTransaction) {
                        Log::info('Transaction already exists, skipping', [
                            'transaction_id' => $data['id']
                    ]);
                        continue;
                    }
                    
                    $transactionData = [
                        'transaction_id' => $data['id'], // Make sure to include transaction_id
                        'firstname' => $data['firstname'] ?? null,
                        'lastname' => $data['lastname'] ?? null,
                        'email' => $data['email'] ?? null,
                        'phone' => $data['phone'] ?? null,
                        'assigned_agent' => $data['agent_id'] ?? null,
                        'sales_name' => $data['sales_name'] ?? null,
                        'sales_email' => $data['sales_email'] ?? null,
                        'sales_location' => $data['sales_location'] ?? null,
                        'date' => $data['date'] ? \Carbon\Carbon::parse($data['date']) : null,
                        'pickup_location' => $data['pickup_location'] ?? null,
                        'delivery_location' => $data['delivery_location'] ?? null,
                        'miles' => $data['miles'] ?? 0,
                        'add_mile' => $data['add_mile'] ?? 0,
                        'mile_rate' => $data['mile_rate'] ?? 0,
                        'service' => $data['service'] ?? null,
                        'service_rate' => $data['service_rate'] ?? 0,
                        'no_of_items' => $data['no_of_items'] ?? 0,
                        'no_of_crew' => $data['no_of_crew'] ?? 0,
                        'crew_rate' => $data['crew_rate'] ?? 0,
                        'delivery_rate' => $data['delivery_rate'] ?? 0,
                        'subtotal' => $data['subtotal'] ?? 0,
                        'software_fee' => $data['Software_Fee'] ?? 0,
                        'truck_fee' => $data['Truck_Fee'] ?? 0,
                        'downpayment' => $data['Downpayment'] ?? 0,
                        'grand_total' => $data['grand_total'] ?? 0,
                        'coupon_code' => $data['coupon_code'] ?? null,
                        'payment_id' => $data['payment_id'] ?? null,
                        'uploaded_image' => $data['uploaded_image'] ?? null,
                        'services' => $data['services'] ?? [],
                        'status' => 'pending',
                        'last_synced_at' => now(),
                    ];

                    Log::info('Creating new transaction', [
                        'transaction_id' => $data['id'],
                        'data' => $transactionData
                    ]);

                    $transaction = Transaction::create($transactionData);

                    Log::info('Transaction created successfully', [
                        'id' => $transaction->id,
                        'transaction_id' => $transaction->transaction_id
                    ]);

                        $created++;
                } catch (\Exception $e) {
                    Log::error('Error processing transaction', [
                        'transaction_id' => $data['id'] ?? 'unknown',
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    $errors[] = [
                        'id' => $data['id'] ?? 'unknown',
                        'error' => $e->getMessage()
                    ];
                }
            }

            Log::info('Sync completed', [
                'created' => $created,
                'errors' => count($errors),
                'error_details' => $errors
            ]);

            return response()->json([
                'success' => true,
                'message' => $created > 0 ? "Successfully added {$created} new transaction(s)" : "No new transactions to add",
                'new_count' => $created,
                'data' => [
                    'created' => $created,
                    'errors' => $errors
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Sync failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $transaction)
    {
        try {
            $transaction = Transaction::findOrFail($transaction);
            $oldStatus = $transaction->status;
            $transaction->status = $request->status;
            $transaction->save();

        return response()->json([
            'success' => true,
                'message' => 'Transaction status updated successfully',
                'old_status' => $oldStatus
        ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update transaction status'
            ], 500);
        }
    }

    public function create()
    {
        $leadSources = \App\Models\LeadSource::pluck('title', 'id');
        $agents = \App\Models\Agent::pluck('company_name', 'id');
        $inventoryItems = \App\Models\InventoryItem::with('category')->get();
        return view('leads', compact('leadSources', 'agents', 'inventoryItems'));
    }

    public function edit($id)
    {
        $transaction = Transaction::with('inventoryItems')->findOrFail($id);
        $leadSources = \App\Models\LeadSource::pluck('title', 'id');
        $agents = \App\Models\Agent::pluck('company_name', 'id');
        $inventoryItems = \App\Models\InventoryItem::with('category')->get();
        return view('leads', compact('transaction', 'leadSources', 'agents', 'inventoryItems'));
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateTransactionData($request);
        $transaction = Transaction::create($validatedData);
        
        return redirect()->route('loadboard')
            ->with('success', 'Transaction created successfully');
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $validatedData = $this->validateTransactionData($request);
        
        $transaction->update($validatedData);
        
        return redirect()->route('loadboard')
            ->with('success', 'Transaction updated successfully');
    }

    // AJAX: Update a single field for a transaction
    public function updateField(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $field = $request->input('field');
        $value = $request->input('value');
        $allowedFields = [
            'firstname', 'lastname', 'email', 'phone', 'lead_source', 'lead_type', 'assigned_agent',
            'sales_name', 'sales_email', 'sales_location', 'date', 'service', 'no_of_items',
            'pickup_location', 'delivery_location', 'miles', 'insurance_number'
        ];
        if (!in_array($field, $allowedFields)) {
            return response()->json(['success' => false, 'message' => 'Invalid field'], 400);
        }
        $transaction->$field = $value;
        $transaction->save();
        return response()->json(['success' => true, 'message' => 'Updated information']);
    }

    private function validateTransactionData(Request $request)
    {
        return $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'lead_source' => 'nullable|exists:lead_sources,id',
            'lead_type' => 'required|string|in:local,long_distance',
            'assigned_agent' => 'nullable|exists:agents,id',
            'service' => 'required|string|max:255',
            'date' => 'required|date',
            'no_of_items' => 'nullable|integer',
            'pickup_location' => 'required|string|max:255',
            'delivery_location' => 'required|string|max:255',
            'miles' => 'nullable|numeric',
            'sales_name' => 'nullable|string|max:255',
            'sales_email' => 'nullable|email|max:255',
            'sales_location' => 'nullable|string|max:255',
            'uploaded_image' => 'nullable|string'
        ]);
    }

    // AJAX: Update inventory item quantity for a transaction
    public function updateInventoryItem(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $itemId = $request->input('item_id');
        $quantity = $request->input('quantity');
        
        // Validate the input
        $request->validate([
            'item_id' => 'required|exists:inventory_items,id',
            'quantity' => 'required|integer|min:0'
        ]);
        
        // Update or create the pivot record
        $transaction->inventoryItems()->syncWithoutDetaching([
            $itemId => ['quantity' => $quantity]
        ]);
        
        return response()->json([
            'success' => true, 
            'message' => 'Inventory item updated successfully'
        ]);
    }

    /**
     * Get added inventory items for a transaction
     *
     * @param int $id Transaction ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAddedInventoryItems($id)
    {
        $transaction = Transaction::with(['inventoryItems' => function($query) {
            $query->wherePivot('quantity', '>', 0);
        }, 'inventoryItems.category'])->findOrFail($id);
        
        $addedItems = $transaction->inventoryItems
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->item,
                    'category' => $item->category->name,
                    'cubic_ft' => $item->cubic_ft,
                    'quantity' => $item->pivot->quantity,
                    'total_volume' => $item->cubic_ft * $item->pivot->quantity
                ];
            })
            ->sortBy('name')
            ->values();
        
        return response()->json([
            'success' => true,
            'data' => $addedItems
        ]);
    }

    public function uploadInsuranceDocument(Request $request, $id)
    {
        $request->validate([
            'insurance_document' => 'required|file|mimes:pdf,doc,docx|max:10240'
        ]);

        $transaction = Transaction::findOrFail($id);

        if ($request->hasFile('insurance_document')) {
            // Delete old document if exists
            if ($transaction->insurance_document) {
                Storage::disk('public')->delete($transaction->insurance_document);
            }

            // Store new document
            $path = $request->file('insurance_document')->store('insurance_documents', 'public');
            $transaction->insurance_document = $path;
            $transaction->save();

            return response()->json([
                'success' => true,
                'document_url' => Storage::url($path)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No document was uploaded'
        ], 400);
    }

    public function datatable(Request $request)
    {
        try {
            $query = Transaction::query()
                ->leftJoin('agents', 'transactions.assigned_agent', '=', 'agents.id')
                ->select(
                    'transactions.*',
                    'agents.company_name as assigned_agent_company_name'
                );
            
            // Get total records count
            $totalRecords = Transaction::count();
            $filteredRecords = $totalRecords;
            
            // Apply search
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function($q) use ($search) {
                    $q->where('transactions.transaction_id', 'like', "%{$search}%")
                      ->orWhere('transactions.firstname', 'like', "%{$search}%")
                      ->orWhere('transactions.lastname', 'like', "%{$search}%")
                      ->orWhere('transactions.email', 'like', "%{$search}%")
                      ->orWhere('transactions.pickup_location', 'like', "%{$search}%")
                      ->orWhere('transactions.delivery_location', 'like', "%{$search}%")
                      ->orWhere('transactions.services', 'like', "%{$search}%")
                      ->orWhere('agents.company_name', 'like', "%{$search}%");
                });
                
                // Update filtered count after search
                $filteredRecords = $query->count();
            }
            
            // Apply ordering
            if ($request->has('order')) {
                $columns = [
                    'transactions.transaction_id',
                    'transactions.firstname',
                    'transactions.date',
                    'transactions.services',
                    'transactions.pickup_location',
                    'transactions.delivery_location',
                    'transactions.miles',
                    'transactions.grand_total',
                    'agents.company_name',
                    'transactions.status'
                ];
                
                $column = $columns[$request->order[0]['column']];
                $direction = $request->order[0]['dir'];
                $query->orderBy($column, $direction);
            } else {
                $query->orderBy('transactions.transaction_id', 'desc');
            }
            
            // Apply pagination
            $records = $query->skip($request->start)
                           ->take($request->length)
                           ->get();
            
            // Format data for DataTables
            $data = [];
            foreach ($records as $record) {
                $data[] = [
                    'id' => $record->id,
                    'transaction_id' => $record->transaction_id,
                    'firstname' => $record->firstname,
                    'lastname' => $record->lastname,
                    'email' => $record->email,
                    'date' => $record->date,
                    'services' => $record->services,
                    'pickup_location' => $record->pickup_location,
                    'delivery_location' => $record->delivery_location,
                    'miles' => $record->miles,
                    'grand_total' => $record->grand_total,
                    'assigned_agent_company_name' => $record->assigned_agent_company_name,
                    'status' => $record->status
                ];
            }
            
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            \Log::error('DataTable Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'An error occurred while processing your request.'
            ], 500);
        }
    }

    public function getCounts()
    {
        try {
            $counts = [
                'total' => Transaction::count(),
                'pending' => Transaction::where('status', 'pending')->count(),
                'in_progress' => Transaction::where('status', 'in_progress')->count(),
                'completed' => Transaction::where('status', 'completed')->count()
            ];

            return response()->json($counts);
        } catch (\Exception $e) {
            \Log::error('Error getting counts: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to get counts'
            ], 500);
        }
    }
} 