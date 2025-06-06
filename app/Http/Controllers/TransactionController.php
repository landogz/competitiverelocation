<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\LeadSource;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;

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
            ->orderByDesc('transactions.id')
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


    public function index_leads()
    {
        // Join lead_sources and agents tables to fetch titles and company names efficiently
        $transactions = Transaction::query()
            ->leftJoin('lead_sources', 'transactions.lead_source', '=', 'lead_sources.id')
            ->leftJoin('agents', 'transactions.assigned_agent', '=', 'agents.id')
            ->where('transactions.status', 'lead')
            ->where(function($query) {
                $query->whereNull('transactions.assigned_agent')
                      ->orWhere('transactions.assigned_agent', '!=', '');
            })
            ->select(
                'transactions.*',
                'lead_sources.title as lead_source_title',
                'agents.company_name as assigned_agent_company_name'
            )
            ->orderByDesc('transactions.id')
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

        return view('loadboard-leads', compact('transactions', 'templates', 'placeholders'));
    }

    public function index_agent()
    {
        // Get the authenticated agent
        $agentId = auth()->user()->agent_id;
        $agent = $agentId ? \App\Models\Agent::find($agentId) : null;
        
        if (!$agent) {
            return redirect()->route('dashboard')->with('error', 'Agent profile not found');
        }

        // Get agent's coordinates
        $agentCoordinates = $this->getCoordinatesFromAddress(
            $agent->address,
            $agent->city,
            $agent->state,
            $agent->zip_code,
            $agent->country
        );

        if (!$agentCoordinates) {
            \Log::error('Could not get coordinates for agent', [
                'agent_id' => $agent->id,
                'address' => $agent->address,
                'city' => $agent->city,
                'state' => $agent->state,
                'zip_code' => $agent->zip_code
            ]);
            
            // If we can't get coordinates, return only the agent's assigned jobs
            $transactions = Transaction::query()
                ->where('assigned_agent', $agent->id)
                ->leftJoin('lead_sources', 'transactions.lead_source', '=', 'lead_sources.id')
                ->leftJoin('agents', 'transactions.assigned_agent', '=', 'agents.id')
                ->select(
                    'transactions.*',
                    'lead_sources.title as lead_source_title',
                    'agents.company_name as assigned_agent_company_name'
                )
                ->orderByDesc('transactions.id')
                ->get();
        } else {
            // Get all transactions
            $transactions = Transaction::query()
                ->leftJoin('lead_sources', 'transactions.lead_source', '=', 'lead_sources.id')
                ->leftJoin('agents', 'transactions.assigned_agent', '=', 'agents.id')
                ->select(
                    'transactions.*',
                    'lead_sources.title as lead_source_title',
                    'agents.company_name as assigned_agent_company_name'
                )
                ->orderByDesc('transactions.id')
                ->get();

            // Filter transactions by distance
            $transactions = $transactions->filter(function ($transaction) use ($agentCoordinates, $agent) {
                // Always include transactions assigned to this agent
                if ($transaction->assigned_agent == $agent->id) {
                    return true;
                }

                // Get coordinates for the transaction's pickup location
                $jobCoordinates = $this->getCoordinatesFromAddress($transaction->pickup_location);
                
                if (!$jobCoordinates) {
                    return false;
                }

                // Calculate distance between agent and job
                $distance = $this->calculateDistance(
                    $agentCoordinates['lat'],
                    $agentCoordinates['lng'],
                    $jobCoordinates['lat'],
                    $jobCoordinates['lng']
                );

                // Only include jobs within 50 miles
                return $distance <= 50;
            });
        }

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

        $totalTransactions = $transactions->count();
        $pendingTransactions = $transactions->where('status', 'pending')->count();
        $inProgressTransactions = $transactions->where('status', 'in_progress')->count();
        $completedTransactions = $transactions->where('status', 'completed')->count();
        $leadStatusTransactions = $transactions->where('status', 'lead')->count();

        return view('loadboard-agent', compact(
            'transactions', 'templates', 'placeholders',
            'totalTransactions', 'pendingTransactions', 'inProgressTransactions', 'completedTransactions',
            'leadStatusTransactions'
        ));
    }

    /**
     * Get nearby zip codes within a specified radius
     * 
     * @param string $zipCode The center zip code
     * @param int $radiusMiles The radius in miles
     * @return array Array of nearby zip codes
     */
    private function getNearbyZipCodes($zipCode, $radiusMiles)
    {
        // You can use a zip code database or API to get nearby zip codes
        // For now, we'll use a simple approach with a zip code database table
        // You'll need to create a zip_codes table with latitude and longitude
        
        try {
            $centerZip = DB::table('zip_codes')
                ->where('zip_code', $zipCode)
                ->first();

            if (!$centerZip) {
                return [];
            }

            // Calculate the bounding box for the radius
            $lat = $centerZip->latitude;
            $lon = $centerZip->longitude;
            
            // 1 degree of latitude is approximately 69 miles
            // 1 degree of longitude varies but we'll use an average
            $latDelta = $radiusMiles / 69;
            $lonDelta = $radiusMiles / (69 * cos(deg2rad($lat)));

            // Get all zip codes within the bounding box
            $nearbyZips = DB::table('zip_codes')
                ->whereBetween('latitude', [$lat - $latDelta, $lat + $latDelta])
                ->whereBetween('longitude', [$lon - $lonDelta, $lon + $lonDelta])
                ->pluck('zip_code')
                ->toArray();

            return $nearbyZips;
        } catch (\Exception $e) {
            \Log::error('Error getting nearby zip codes: ' . $e->getMessage());
            return [];
        }
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
            $skipped = 0;
            $errors = [];

            foreach ($transactions as $data) {
                try {
                    // Check if transaction already exists
                    $existingTransaction = Transaction::where('transaction_id', $data['id'])->first();

                    // Skip if transaction already exists in the database
                    if ($existingTransaction) {
                        $skipped++;
                        Log::info('Skipped existing transaction', [
                            'transaction_id' => $data['id']
                        ]);
                        continue;
                    }

                    $transactionData = [
                        'transaction_id' => $data['id'],
                        'firstname' => $data['firstname'] ?? null,
                        'lastname' => $data['lastname'] ?? null,
                        'email' => $data['email'] ?? null,
                        'phone' => $data['phone'] ?? null,
                        'lead_source' => $data['lead_source'] ?? null,
                        'lead_type' => $data['lead_type'] ?? 'local',
                        'assigned_agent' => $data['assigned_agent'] ?? null,
                        'sales_name' => $data['sales_name'] ?? null,
                        'sales_email' => $data['sales_email'] ?? null,
                        'sales_location' => $data['sales_location'] ?? null,
                        'date' => !empty($data['date']) && $data['date'] !== '1970-01-01 00:00:00' ? 
                                date('Y-m-d H:i:s', strtotime($data['date'])) : null,
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
                        'created_at' => !empty($data['created_at']) && $data['created_at'] !== '1970-01-01 00:00:00' ? 
                                date('Y-m-d H:i:s', strtotime($data['created_at'])) : now(),
                        'last_synced_at' => now(),
                        'status' => 'pending'
                    ];

                    // Log the transaction data for debugging
                    Log::info('Transaction sync debug', [
                        'api_id' => $data['id'],
                        'exists' => false,
                        'transactionData' => $transactionData
                    ]);

                    // Create new transaction
                    Transaction::create($transactionData);
                    $created++;
                    Log::info('Created new transaction', [
                        'transaction_id' => $data['id']
                    ]);

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
                'skipped' => $skipped,
                'errors' => count($errors),
                'error_details' => $errors
            ]);

            return response()->json([
                'success' => true,
                'message' => "Sync completed: {$created} new, {$skipped} skipped",
                'new_count' => $created,
                'skipped_count' => $skipped,
                'data' => [
                    'created' => $created,
                    'skipped' => $skipped,
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
        $templates = \App\Models\EmailTemplate::all();
        
        // Define placeholders for transaction data
        $placeholders = [
            'Transaction' => [
                'First Name' => '{firstname}',
                'Last Name' => '{lastname}',
                'Email' => '{email}',
                'Phone' => '{phone}',
                'Lead Source' => '{lead_source}',
                'Lead Type' => '{lead_type}',
                'Assigned Agent' => '{assigned_agent}',
                'Sales Name' => '{sales_name}',
                'Sales Email' => '{sales_email}',
                'Sales Location' => '{sales_location}',
                'Date' => '{date}',
                'Pickup Location' => '{pickup_location}',
                'Delivery Location' => '{delivery_location}',
                'Miles' => '{miles}',
                'Service' => '{service}',
                'Service Rate' => '{service_rate}',
                'Number of Items' => '{no_of_items}',
                'Number of Crew' => '{no_of_crew}',
                'Crew Rate' => '{crew_rate}',
                'Delivery Rate' => '{delivery_rate}',
                'Subtotal' => '{subtotal}',
                'Software Fee' => '{software_fee}',
                'Truck Fee' => '{truck_fee}',
                'Down Payment' => '{downpayment}',
                'Grand Total' => '{grand_total}',
                'Coupon Code' => '{coupon_code}',
                'Payment ID' => '{payment_id}',
                'Status' => '{status}'
            ]
        ];
        
        return view('leads', compact('leadSources', 'agents', 'inventoryItems', 'templates', 'placeholders'));
    }

    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        $leadSources = \App\Models\LeadSource::pluck('title', 'id');
        $agents = \App\Models\Agent::pluck('company_name', 'id');
        $inventoryItems = \App\Models\InventoryItem::with('category')->get();
        $templates = \App\Models\EmailTemplate::all();
        
        // Define placeholders for transaction data
        $placeholders = [
            'Transaction' => [
                'First Name' => '{firstname}',
                'Last Name' => '{lastname}',
                'Email' => '{email}',
                'Phone' => '{phone}',
                'Lead Source' => '{lead_source}',
                'Lead Type' => '{lead_type}',
                'Assigned Agent' => '{assigned_agent}',
                'Sales Name' => '{sales_name}',
                'Sales Email' => '{sales_email}',
                'Sales Location' => '{sales_location}',
                'Date' => '{date}',
                'Pickup Location' => '{pickup_location}',
                'Delivery Location' => '{delivery_location}',
                'Miles' => '{miles}',
                'Service' => '{service}',
                'Service Rate' => '{service_rate}',
                'Number of Items' => '{no_of_items}',
                'Number of Crew' => '{no_of_crew}',
                'Crew Rate' => '{crew_rate}',
                'Delivery Rate' => '{delivery_rate}',
                'Subtotal' => '{subtotal}',
                'Software Fee' => '{software_fee}',
                'Truck Fee' => '{truck_fee}',
                'Down Payment' => '{downpayment}',
                'Grand Total' => '{grand_total}',
                'Coupon Code' => '{coupon_code}',
                'Payment ID' => '{payment_id}',
                'Status' => '{status}'
            ]
        ];
        
        return view('leads', compact('transaction', 'leadSources', 'agents', 'inventoryItems', 'templates', 'placeholders'));
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
            'firstname', 'lastname', 'email', 'phone', 'phone2', 'lead_source', 'lead_type', 'assigned_agent',
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
                )
            ->orderByDesc('transactions.created_at');
            
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
                      ->orWhere('transactions.date', 'like', "%{$search}%")
                      ->orWhere('transactions.miles', 'like', "%{$search}%")
                      ->orWhere('transactions.grand_total', 'like', "%{$search}%")
                      ->orWhere('transactions.status', 'like', "%{$search}%")
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


    public function datatable_leads(Request $request)
    {
        try {
            $query = Transaction::query()
                ->leftJoin('agents', 'transactions.assigned_agent', '=', 'agents.id')
                ->where('transactions.status', 'lead')
                ->where(function($query) {
                    $query->whereNull('transactions.assigned_agent')
                          ->orWhere('transactions.assigned_agent', '!=', '');
                })
                ->select(
                    'transactions.*',
                    'agents.company_name as assigned_agent_company_name'
                )
            ->orderByDesc('transactions.created_at');
            
            // Get total records count (before search)
            $totalRecords = (clone $query)->count();
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
                      ->orWhere('transactions.date', 'like', "%{$search}%")
                      ->orWhere('transactions.miles', 'like', "%{$search}%")
                      ->orWhere('transactions.grand_total', 'like', "%{$search}%")
                      ->orWhere('transactions.status', 'like', "%{$search}%")
                      ->orWhere('agents.company_name', 'like', "%{$search}%");
                });
                
                // Update filtered count after search
                $filteredRecords = (clone $query)->count();
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

    public function agentDatatable(Request $request)
    {
        try {
        // Get the authenticated agent
        $agentId = auth()->user()->agent_id;
        $agent = $agentId ? \App\Models\Agent::find($agentId) : null;
        
        if (!$agent) {
                return response()->json(['error' => 'Agent not found'], 404);
            }

            // Get agent's coordinates
            $agentCoordinates = $this->getCoordinatesFromAddress(
                $agent->address,
                $agent->city,
                $agent->state,
                $agent->zip_code,
                $agent->country
            );

            if (!$agentCoordinates) {
                \Log::error('Could not get coordinates for agent', [
                    'agent_id' => $agent->id,
                    'address' => $agent->address,
                    'city' => $agent->city,
                    'state' => $agent->state,
                    'zip_code' => $agent->zip_code
                ]);
                
                // If we can't get coordinates, return only the agent's assigned jobs
                $query = Transaction::query()
                    ->where('assigned_agent', $agent->id)
                    ->leftJoin('lead_sources', 'transactions.lead_source', '=', 'lead_sources.id')
                    ->leftJoin('agents', 'transactions.assigned_agent', '=', 'agents.id')
                    ->select(
                        'transactions.*',
                        'lead_sources.title as lead_source_title',
                        'agents.company_name as assigned_agent_company_name'
                    )
                    ->orderByDesc('transactions.id');
                $transactions = $query->get();
            } else {
                $query = Transaction::query()
                    ->leftJoin('lead_sources', 'transactions.lead_source', '=', 'lead_sources.id')
                    ->leftJoin('agents', 'transactions.assigned_agent', '=', 'agents.id')
                    ->select(
                        'transactions.*',
                        'lead_sources.title as lead_source_title',
                        'agents.company_name as assigned_agent_company_name'
                    )
                    ->orderByDesc('transactions.id');
                $transactions = $query->get();
                // Filter transactions by distance OR assigned to agent
                $transactions = $transactions->filter(function ($transaction) use ($agentCoordinates, $agent) {
                    // Always include transactions assigned to this agent
                    if ($transaction->assigned_agent == $agent->id) {
                        return true;
                    }
                    // Get coordinates for the transaction's pickup location
                    $jobCoordinates = $this->getCoordinatesFromAddress($transaction->pickup_location);
                    if (!$jobCoordinates) {
                        return false;
                    }
                    // Calculate distance between agent and job
                    $distance = $this->calculateDistance(
                        $agentCoordinates['lat'],
                        $agentCoordinates['lng'],
                        $jobCoordinates['lat'],
                        $jobCoordinates['lng']
                    );
                    // Only include jobs within 50 miles
                    return $distance <= 50;
                });
            }

            // Get total count before any filtering
            $totalRecords = $transactions->count();

            // Apply search if provided
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $transactions = $transactions->filter(function ($transaction) use ($search) {
                    return str_contains(strtolower($transaction->transaction_id), strtolower($search)) ||
                           str_contains(strtolower($transaction->services), strtolower($search));
                });
            }

            // Get filtered count after search
            $filteredRecords = $transactions->count();

            // Apply pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
            $transactions = $transactions->slice($start, $length);

            // Convert to DataTables format - ensure it's an array, not an object
            $data = [];
            foreach ($transactions as $transaction) {
                $data[] = [
                    'id' => $transaction->id,
                    'transaction_id' => $transaction->transaction_id,
                    'date' => $transaction->date,
                    'services' => $transaction->services,
                    'status' => $transaction->status,
                    'assigned_agent' => $transaction->assigned_agent,
                    'pickup_location' => $transaction->pickup_location,
                    'delivery_location' => $transaction->delivery_location,
                ];
            }

        return response()->json([
                'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in agentDatatable: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while processing your request',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    private function getCoordinatesFromAddress($address, $city = null, $state = null, $zipCode = null, $country = null)
    {
        try {
            // Build the full address
            $fullAddress = array_filter([
                $address,
                $city,
                $state,
                $zipCode,
                $country
            ]);
            
            $addressString = implode(', ', $fullAddress);

            // Use Google Maps Geocoding API with Laravel's HTTP client
            $apiKey = config('services.google.maps_api_key');
            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                'address' => $addressString,
                'key' => $apiKey
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if ($data['status'] === 'OK') {
                    return [
                        'lat' => $data['results'][0]['geometry']['location']['lat'],
                        'lng' => $data['results'][0]['geometry']['location']['lng']
                    ];
                }
            }

            \Log::error('Geocoding failed', [
                'address' => $addressString,
                'response' => $response->json()
            ]);
            return null;
        } catch (\Exception $e) {
            \Log::error('Error getting coordinates: ' . $e->getMessage());
            return null;
        }
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 3959; // Radius of the earth in miles

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta/2) * sin($latDelta/2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta/2) * sin($lonDelta/2);
            
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;

        return $distance;
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

    /**
     * Accept a job and assign it to the current agent
     */
    public function acceptJob($transactionId)
    {
        try {
            // Get the authenticated agent
            $agentId = auth()->user()->agent_id;
            $agent = $agentId ? \App\Models\Agent::find($agentId) : null;
            
            if (!$agent) {
                return response()->json([
                    'success' => false,
                    'message' => 'Agent profile not found'
                ], 404);
            }

            // Get the transaction
            $transaction = Transaction::findOrFail($transactionId);

            // Check if transaction is already assigned
            if ($transaction->assigned_agent) {
                return response()->json([
                    'success' => false,
                    'message' => 'This job is already assigned to another agent'
                ], 400);
            }

            // Update the transaction
            $transaction->update([
                'assigned_agent' => $agent->id,
                'status' => 'in_progress'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Job accepted and marked as in progress'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to accept job: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Decline a job
     */
    public function declineJob($transactionId)
    {
        try {
            // Get the transaction
            $transaction = Transaction::findOrFail($transactionId);

            // Check if transaction is already assigned to this agent
            $agentId = auth()->user()->agent_id;
            $agent = $agentId ? \App\Models\Agent::find($agentId) : null;
            
            if (!$agent) {
                return response()->json([
                    'success' => false,
                    'message' => 'Agent profile not found'
                ], 404);
            }

            if ($transaction->assigned_agent !== $agent->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only decline jobs assigned to you'
                ], 400);
            }

            // Update the transaction
            $transaction->update([
                'assigned_agent' => null,
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Job declined successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to decline job: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $lead = \App\Models\Transaction::findOrFail($id);
            
            // Parse the services array to extract service names
            $serviceNames = '';
            if (!empty($lead->services)) {
                // Check if services is already an array or a JSON string
                if (is_array($lead->services)) {
                    $services = $lead->services;
                } else {
                    $services = json_decode($lead->services, true);
                }
                
                if (is_array($services)) {
                    $names = array_map(function($service) {
                        return $service['name'] ?? '';
                    }, $services);
                    $serviceNames = implode(', ', array_filter($names));
                }
            }
            
            // Convert lead to array and add parsed service name
            $leadData = $lead->toArray();
            $leadData['service_name'] = $serviceNames;
            $leadData['service'] = $serviceNames; // Also update the service field itself
            
            // Combine firstname and lastname into name field
            $leadData['name'] = trim(($leadData['firstname'] ?? '') . ' ' . ($leadData['lastname'] ?? ''));

            if (isset($leadData['date']) && $leadData['date']) {
                $leadData['date'] = date('F j, Y', strtotime($leadData['date']));
            } else {
                $leadData['date'] = '';
            }

            if (isset($leadData['created_at']) && $leadData['created_at']) {
                $leadData['created_at'] = date('F j, Y', strtotime($leadData['created_at']));
            } else {
                $leadData['created_at'] = '';
            }
            
            return response()->json($leadData);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch lead data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'recipient' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
            'load_id' => 'required|integer',
            'template_id' => 'required|integer'
        ]);

        try {
            $transaction = Transaction::findOrFail($request->load_id);
            
            // Create sent email record
            $sentEmail = \App\Models\SentEmail::create([
                'transaction_id' => $transaction->id,
                'recipient' => $request->recipient,
                'subject' => $request->subject,
                'message' => $request->message,
                'template_id' => $request->template_id,
                'user_id' => auth()->id(),
                'status' => 'pending' // Set initial status as pending
            ]);

            try {
                \Mail::to($request->recipient)
                    ->send(new \App\Mail\CustomEmail($request->subject, $request->message));

                // Update status to sent if email was sent successfully
                $sentEmail->update(['status' => 'sent']);

                return response()->json(['success' => true, 'message' => 'Email sent successfully']);
            } catch (\Exception $mailError) {
                // Update status to failed if email sending failed
                $sentEmail->update(['status' => 'failed']);
                
                \Log::error('Email sending failed: ' . $mailError->getMessage());
                \Log::error('Email configuration: ' . json_encode([
                    'mail_driver' => config('mail.default'),
                    'mail_host' => config('mail.mailers.smtp.host'),
                    'mail_port' => config('mail.mailers.smtp.port'),
                    'mail_username' => config('mail.mailers.smtp.username'),
                    'mail_encryption' => config('mail.mailers.smtp.encryption'),
                ]));

                return response()->json(['success' => false, 'message' => $mailError->getMessage()], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveTransaction(Request $request)
    {
        try {
        
            // Check if this is an update for an existing transaction without services
            if ($request->has('transaction_id')) {
                $transaction = Transaction::where('id', $request->transaction_id)->first();


                if ($transaction && $transaction->status == 'lead') {

                       // Format services array
                        $services = [];
                        if ($request->has('services')) {
                            $services = is_array($request->services) ? $request->services : json_decode($request->services, true);
                        } else {
                            // Create default service object if none provided
                            $services = [[
                                'id' => null,
                                'name' => $request->service ?? 'MOVING SERVICES',
                                'rate' => '$' . number_format($request->service_rate ?? 0, 2),
                                'subtotal' => '$' . number_format($request->subtotal ?? 0, 2),
                                'crew_rate' => '$' . number_format($request->crew_rate ?? 0, 2),
                                'no_of_crew' => $request->no_of_crew ?? '0',
                                'delivery_cost' => '$' . number_format($request->delivery_rate ?? 0, 2),
                                'purchased_amount' => ''
                            ]];
                        }

                        
                    // Update only basic transaction data
                    $transaction->update([
                        'firstname' => $request->firstname,
                        'lastname' => $request->lastname,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'lead_source' => $request->lead_source,
                        'lead_type' => $request->lead_type ?? 'local',
                        'assigned_agent' => $request->assigned_agent,
                        'sales_name' => $request->sales_name,
                        'sales_email' => $request->sales_email,
                        'sales_location' => $request->sales_location,
                        'date' => $request->date ? date('Y-m-d H:i:s', strtotime($request->date)) : date('Y-m-d H:i:s'),
                        'pickup_location' => $request->pickup_location,
                        'delivery_location' => $request->delivery_location,
                        'miles' => $request->miles,
                        'add_mile' => $request->add_mile,
                        'mile_rate' => $request->mile_rate,
                        'service' => $request->service,
                        'service_rate' => $request->service_rate,
                        'no_of_items' => $request->no_of_items,
                        'no_of_crew' => $request->no_of_crew,
                        'crew_rate' => $request->crew_rate,
                        'delivery_rate' => $request->delivery_rate,
                        'subtotal' => $request->subtotal,
                        'software_fee' => $request->software_fee,
                        'truck_fee' => $request->truck_fee,
                        'downpayment' => $request->downpayment,
                        'grand_total' => $request->grand_total,
                        'coupon_code' => $request->coupon_code,
                        'services' => $services,
                        'status' => collect($services)->contains(function($service) {
                            return $service['name'] === 'MOVING SERVICES';
                        }) ? 'pending' : 'in_progress',
                        'insurance_number' => $request->insurance_number,
                        'last_synced_at' => now(),
                    ]);

                    // Handle image uploads if present
                    if ($request->hasFile('uploaded_image')) {
                        $uploadedImages = [];
                        foreach ($request->file('uploaded_image') as $image) {
                            $filename = time() . '_' . $image->getClientOriginalName();
                            $path = 'profile-images/' . $filename;
                            Storage::disk('public')->put($path, file_get_contents($image));
                            $uploadedImages[] = '/storage/app/public/' . $path;
                        }
                        $transaction->uploaded_image = implode(', ', $uploadedImages);
                        $transaction->save();
                    }


                    
                        // Send About Us Email (template ID 16)
                        try {
                            $aboutUsTemplate = \App\Models\EmailTemplate::find(16);
                            if ($aboutUsTemplate) {
                                // Process base64 images in the template content
                                $content = $aboutUsTemplate->content;
                                
                                // Find all base64 images in the content
                                if (preg_match_all('/<img[^>]+src="(data:image\/[^;]+;base64,[^"]+)"/', $content, $matches)) {
                                    foreach ($matches[1] as $index => $base64Src) {
                                        // Extract the image data
                                        list($type, $data) = explode(';', $base64Src);
                                        list(, $data) = explode(',', $data);
                                        
                                        // Decode the base64 data
                                        $imageData = base64_decode($data);
                                        
                                        // Generate a unique filename
                                        $filename = 'image_' . time() . '_' . $index . '.png';
                                        
                                        // Save the image to storage
                                        $path = 'profile-images/' . $filename;
                                        Storage::disk('public')->put($path, $imageData);
                                        
                                        // Get the URL for the image with full domain
                                        $imageUrl = 'https://competitiverelocationcrm.com/storage/app/public/' . $path;
                                        
                                        // Replace the base64 image with the URL in the content
                                        $content = str_replace($base64Src, $imageUrl, $content);
                                    }
                                }

                                $sentEmail = \App\Models\SentEmail::create([
                                    'transaction_id' => $transaction->id,
                                    'recipient' => $transaction->email,
                                    'subject' => $aboutUsTemplate->subject,
                                    'message' => $content,
                                    'template_id' => 16,
                                    'user_id' => auth()->id(),
                                    'status' => 'pending'
                                ]);

                                \Mail::to($transaction->email)
                                    ->send(new \App\Mail\CustomEmail($aboutUsTemplate->subject, $content));

                                $sentEmail->update(['status' => 'sent']);
                            }
                        } catch (\Exception $e) {
                            \Log::error('Failed to send About Us email: ' . $e->getMessage());
                        }

                        // Send Call and Confirm Move Email (template ID 35)
                        try {
                            $confirmMoveTemplate = \App\Models\EmailTemplate::find(35);
                            if ($confirmMoveTemplate) {
                                // Replace placeholders with real data
                                $content = $confirmMoveTemplate->content;
                                $replacements = [
                                    '{name}' => $transaction->firstname . ' ' . $transaction->lastname,
                                    '{date}' => $transaction->date ? date('F j, Y', strtotime($transaction->date)) : 'Not specified',
                                    '{pickup_location}' => $transaction->pickup_location ?? 'Not specified',
                                    '{delivery_location}' => $transaction->delivery_location ?? 'Not specified',
                                    '{sales_name}' => $transaction->sales_name ?? 'Not specified',
                                    '{firstname}' => $transaction->firstname,
                                    '{lastname}' => $transaction->lastname
                                ];

                                $content = str_replace(array_keys($replacements), array_values($replacements), $content);

                                $sentEmail = \App\Models\SentEmail::create([
                                    'transaction_id' => $transaction->id,
                                    'recipient' => $transaction->email,
                                    'subject' => $confirmMoveTemplate->subject,
                                    'message' => $content,
                                    'template_id' => 35,
                                    'user_id' => auth()->id(),
                                    'status' => 'pending'
                                ]);

                                \Mail::to($transaction->email)
                                    ->send(new \App\Mail\CustomEmail($confirmMoveTemplate->subject, $content));

                                $sentEmail->update(['status' => 'sent']);
                            }
                        } catch (\Exception $e) {
                            \Log::error('Failed to send Call and Confirm Move email: ' . $e->getMessage());
                        }

                        // Send Photo Upload Request Email
                        try {
                            $content = '<!DOCTYPE html>
                            <html>
                            <head>
                                <meta charset="utf-8">
                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                <title>Complete Your Info – Upload Photos Today</title>
                                <style>
                                    body {
                                        font-family: Arial, sans-serif;
                                        line-height: 1.6;
                                        color: #333;
                                        max-width: 600px;
                                        margin: 0 auto;
                                        padding: 20px;
                                    }
                                    .email-container {
                                        background-color: #ffffff;
                                        border-radius: 8px;
                                        padding: 30px;
                                        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                                    }
                                    .header {
                                        text-align: center;
                                        margin-bottom: 30px;
                                    }
                                    .content {
                                        margin-bottom: 30px;
                                    }
                                    .button {
                                        display: inline-block;
                                        padding: 12px 24px;
                                        background-color: #4CAF50;
                                        color: white !important;
                                        text-decoration: none;
                                        border-radius: 4px;
                                        margin: 20px 0;
                                    }
                                    .footer {
                                        text-align: center;
                                        margin-top: 30px;
                                        padding-top: 20px;
                                        border-top: 1px solid #eee;
                                        color: #666;
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="email-container">
                                    <div class="header">
                                        <h2>Complete Your Info – Upload Photos Today</h2>
                                    </div>
                                    <div class="content">
                                        <p>Hi ' . $transaction->firstname . ',</p>
                                        <p>Thank you for choosing our service. To proceed, please upload the requested photos directly to your customer dashboard at your earliest convenience.</p>
                                        <p><a href="https://competitiverelocationcrm.com/customer/' . $transaction->id . '" class="button">Upload Photos Now</a></p>
                                        <p>If you have any questions or need assistance, feel free to reach out.</p>
                                    </div>
                                    <div class="footer">
                                        <p>All the best,<br>Competitive Relocation Service</p>
                                    </div>
                                </div>
                            </body>
                            </html>';

                            $sentEmail = \App\Models\SentEmail::create([
                                'transaction_id' => $transaction->id,
                                'recipient' => $transaction->email,
                                'subject' => 'Complete Your Info – Upload Photos Today',
                                'message' => $content,
                                'template_id' => null,
                                'user_id' => auth()->id(),
                                'status' => 'pending'
                            ]);

                            \Mail::to($transaction->email)
                                ->send(new \App\Mail\CustomEmail('Complete Your Info – Upload Photos Today', $content));

                            $sentEmail->update(['status' => 'sent']);
                        } catch (\Exception $e) {
                            \Log::error('Failed to send Photo Upload Request email: ' . $e->getMessage());
                        }

                                return response()->json([
                                    'success' => true,
                                    'message' => 'Transaction updated successfully',
                                    'transaction' => $transaction
                                ]);
                            }
                        }

            // Continue with existing validation and new transaction creation logic
            // Generate unique transaction ID for new transactions
            $lastTransaction = Transaction::orderBy('id', 'desc')->first();
            $newTransactionId = $lastTransaction ? $lastTransaction->id + 1 : 1;
            $transactionId = str_pad($newTransactionId, 6, '0', STR_PAD_LEFT);

            // Format services array
            $services = [];
            if ($request->has('services')) {
                $services = is_array($request->services) ? $request->services : json_decode($request->services, true);
            } else {
                // Create default service object if none provided
                $services = [[
                    'id' => null,
                    'name' => $request->service ?? 'MOVING SERVICES',
                    'rate' => '$' . number_format($request->service_rate ?? 0, 2),
                    'subtotal' => '$' . number_format($request->subtotal ?? 0, 2),
                    'crew_rate' => '$' . number_format($request->crew_rate ?? 0, 2),
                    'no_of_crew' => $request->no_of_crew ?? '0',
                    'delivery_cost' => '$' . number_format($request->delivery_rate ?? 0, 2),
                    'purchased_amount' => ''
                ]];
            }

            // Handle image uploads
            $uploadedImages = [];
            if ($request->hasFile('uploaded_image')) {
                foreach ($request->file('uploaded_image') as $image) {
                    $filename = time() . '_' . $image->getClientOriginalName();
                    $path = 'profile-images/' . $filename;
                    Storage::disk('public')->put($path, file_get_contents($image));
                    $uploadedImages[] = '/storage/app/public/' . $path;
                }
            } elseif ($request->has('uploaded_image')) {
                // Handle image URLs
                $uploadedImages = array_filter(array_map('trim', explode(',', $request->uploaded_image)));
            }

            // Format date properly
            $date = $request->date ? date('Y-m-d H:i:s', strtotime($request->date)) : date('Y-m-d H:i:s');
            
            // Prepare the data
            $transactionData = [
                'transaction_id' => $transactionId,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'phone' => $request->phone,
                'lead_source' => $request->lead_source,
                'lead_type' => $request->lead_type ?? 'local',
                'assigned_agent' => $request->assigned_agent,
                'sales_name' => $request->sales_name,
                'sales_email' => $request->sales_email,
                'sales_location' => $request->sales_location,
                'date' => $date,
                'pickup_location' => $request->pickup_location,
                'delivery_location' => $request->delivery_location,
                'miles' => $request->miles,
                'add_mile' => $request->add_mile,
                'mile_rate' => $request->mile_rate,
                'service' => $request->service,
                'service_rate' => $request->service_rate,
                'no_of_items' => $request->no_of_items,
                'no_of_crew' => $request->no_of_crew,
                'crew_rate' => $request->crew_rate,
                'delivery_rate' => $request->delivery_rate,
                'subtotal' => $request->subtotal,
                'software_fee' => $request->software_fee,
                'truck_fee' => $request->truck_fee,
                'downpayment' => $request->downpayment,
                'grand_total' => $request->grand_total,
                'coupon_code' => $request->coupon_code,
                'payment_id' => $request->payment_id,
                'uploaded_image' => !empty($uploadedImages) ? implode(', ', $uploadedImages) : null,
                'services' => $services,
                'status' => collect($services)->contains(function($service) {
                    return $service['name'] === 'MOVING SERVICES';
                }) ? 'pending' : 'in_progress',
                'insurance_number' => $request->insurance_number,
                'insurance_document' => $request->insurance_document,
                'last_synced_at' => now(),
            ];

            // Create the transaction
            $transaction = Transaction::create($transactionData);

            // Send About Us Email (template ID 16)
            try {
                $aboutUsTemplate = \App\Models\EmailTemplate::find(16);
                if ($aboutUsTemplate) {
                    // Process base64 images in the template content
                    $content = $aboutUsTemplate->content;
                    
                    // Find all base64 images in the content
                    if (preg_match_all('/<img[^>]+src="(data:image\/[^;]+;base64,[^"]+)"/', $content, $matches)) {
                        foreach ($matches[1] as $index => $base64Src) {
                            // Extract the image data
                            list($type, $data) = explode(';', $base64Src);
                            list(, $data) = explode(',', $data);
                            
                            // Decode the base64 data
                            $imageData = base64_decode($data);
                            
                            // Generate a unique filename
                            $filename = 'image_' . time() . '_' . $index . '.png';
                            
                            // Save the image to storage
                            $path = 'profile-images/' . $filename;
                            Storage::disk('public')->put($path, $imageData);
                            
                            // Get the URL for the image with full domain
                            $imageUrl = 'https://competitiverelocationcrm.com/storage/app/public/' . $path;
                            
                            // Replace the base64 image with the URL in the content
                            $content = str_replace($base64Src, $imageUrl, $content);
                        }
                    }

                    $sentEmail = \App\Models\SentEmail::create([
                        'transaction_id' => $transaction->id,
                        'recipient' => $transaction->email,
                        'subject' => $aboutUsTemplate->subject,
                        'message' => $content,
                        'template_id' => 16,
                        'user_id' => auth()->id(),
                        'status' => 'pending'
                    ]);

                    \Mail::to($transaction->email)
                        ->send(new \App\Mail\CustomEmail($aboutUsTemplate->subject, $content));

                    $sentEmail->update(['status' => 'sent']);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send About Us email: ' . $e->getMessage());
            }

            // Send Call and Confirm Move Email (template ID 35)
            try {
                $confirmMoveTemplate = \App\Models\EmailTemplate::find(35);
                if ($confirmMoveTemplate) {
                    // Replace placeholders with real data
                    $content = $confirmMoveTemplate->content;
                    $replacements = [
                        '{name}' => $transaction->firstname . ' ' . $transaction->lastname,
                        '{date}' => $transaction->date ? date('F j, Y', strtotime($transaction->date)) : 'Not specified',
                        '{pickup_location}' => $transaction->pickup_location ?? 'Not specified',
                        '{delivery_location}' => $transaction->delivery_location ?? 'Not specified',
                        '{sales_name}' => $transaction->sales_name ?? 'Not specified',
                        '{firstname}' => $transaction->firstname,
                        '{lastname}' => $transaction->lastname
                    ];

                    $content = str_replace(array_keys($replacements), array_values($replacements), $content);

                    $sentEmail = \App\Models\SentEmail::create([
                        'transaction_id' => $transaction->id,
                        'recipient' => $transaction->email,
                        'subject' => $confirmMoveTemplate->subject,
                        'message' => $content,
                        'template_id' => 35,
                        'user_id' => auth()->id(),
                        'status' => 'pending'
                    ]);

                    \Mail::to($transaction->email)
                        ->send(new \App\Mail\CustomEmail($confirmMoveTemplate->subject, $content));

                    $sentEmail->update(['status' => 'sent']);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send Call and Confirm Move email: ' . $e->getMessage());
            }

            // Send Photo Upload Request Email
            try {
                $content = '<!DOCTYPE html>
                <html>
                <head>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Complete Your Info – Upload Photos Today</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            line-height: 1.6;
                            color: #333;
                            max-width: 600px;
                            margin: 0 auto;
                            padding: 20px;
                        }
                        .email-container {
                            background-color: #ffffff;
                            border-radius: 8px;
                            padding: 30px;
                            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                        }
                        .header {
                            text-align: center;
                            margin-bottom: 30px;
                        }
                        .content {
                            margin-bottom: 30px;
                        }
                        .button {
                            display: inline-block;
                            padding: 12px 24px;
                            background-color: #4CAF50;
                            color: white !important;
                            text-decoration: none;
                            border-radius: 4px;
                            margin: 20px 0;
                        }
                        .footer {
                            text-align: center;
                            margin-top: 30px;
                            padding-top: 20px;
                            border-top: 1px solid #eee;
                            color: #666;
                        }
                    </style>
                </head>
                <body>
                    <div class="email-container">
                        <div class="header">
                            <h2>Complete Your Info – Upload Photos Today</h2>
                        </div>
                        <div class="content">
                            <p>Hi ' . $transaction->firstname . ',</p>
                            <p>Thank you for choosing our service. To proceed, please upload the requested photos directly to your customer dashboard at your earliest convenience.</p>
                            <p><a href="https://competitiverelocationcrm.com/customer/' . $transaction->id . '" class="button">Upload Photos Now</a></p>
                            <p>If you have any questions or need assistance, feel free to reach out.</p>
                        </div>
                        <div class="footer">
                            <p>All the best,<br>Competitive Relocation Service</p>
                        </div>
                    </div>
                </body>
                </html>';

                $sentEmail = \App\Models\SentEmail::create([
                    'transaction_id' => $transaction->id,
                    'recipient' => $transaction->email,
                    'subject' => 'Complete Your Info – Upload Photos Today',
                    'message' => $content,
                    'template_id' => null,
                    'user_id' => auth()->id(),
                    'status' => 'pending'
                ]);

                \Mail::to($transaction->email)
                    ->send(new \App\Mail\CustomEmail('Complete Your Info – Upload Photos Today', $content));

                $sentEmail->update(['status' => 'sent']);
            } catch (\Exception $e) {
                \Log::error('Failed to send Photo Upload Request email: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaction saved successfully',
                'transaction' => $transaction
            ]);

        } catch (\Exception $e) {
            \Log::error('Error saving transaction: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save transaction: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadCustomerImage(Request $request, $transactionId)
    {
        // Validate images
        $request->validate([
            'uploaded_image.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120' // 5MB max per image
        ]);

        // Find the transaction
        $transaction = \App\Models\Transaction::find($transactionId);
        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Transaction not found.'], 404);
        }

        $uploadedImages = [];
        if ($request->hasFile('uploaded_image')) {
            foreach ($request->file('uploaded_image') as $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $path = 'profile-images/' . $filename;
                \Illuminate\Support\Facades\Storage::disk('public')->put($path, file_get_contents($image));
                $uploadedImages[] = '/storage/app/public/' . $path;
                
            }
        }

        // Merge with existing images if any
        $existing = $transaction->uploaded_image ? array_filter(array_map('trim', explode(',', $transaction->uploaded_image))) : [];
        $allImages = array_merge($existing, $uploadedImages);

        // Save back to transaction
        $transaction->uploaded_image = implode(', ', $allImages);
        $transaction->save();

        return response()->json([
            'success' => true,
            'uploaded_image' => $transaction->uploaded_image,
            'message' => 'Images uploaded successfully.'
        ]);
    }
} 