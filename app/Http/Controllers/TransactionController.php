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
        return view('loadboard', compact('transactions'));
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

            $updated = 0;
            $created = 0;
            $errors = [];

            foreach ($transactions as $data) {
                try {
                    Log::info('Processing transaction', [
                        'id' => $data['id'] ?? 'unknown',
                        'data' => $data
                    ]);
                    
                    $transactionData = [
                        'firstname' => $data['firstname'] ?? null,
                        'lastname' => $data['lastname'] ?? null,
                        'email' => $data['email'] ?? null,
                        'phone' => $data['phone'] ?? null,
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

                    Log::info('Attempting to save transaction', [
                        'transaction_id' => $data['id'],
                        'data' => $transactionData
                    ]);

                    $transaction = Transaction::updateOrCreate(
                        ['transaction_id' => $data['id']],
                        $transactionData
                    );

                    Log::info('Transaction saved', [
                        'id' => $transaction->id,
                        'was_created' => $transaction->wasRecentlyCreated,
                        'was_updated' => $transaction->wasChanged()
                    ]);

                    if ($transaction->wasRecentlyCreated) {
                        $created++;
                    } else {
                        $updated++;
                    }
                } catch (\Exception $e) {
                    Log::error('Error processing transaction', [
                        'id' => $data['id'] ?? 'unknown',
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
                'updated' => $updated,
                'errors' => count($errors)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sync completed successfully',
                'data' => [
                    'created' => $created,
                    'updated' => $updated,
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

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled'
        ]);

        $transaction->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction status updated successfully'
        ]);
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
} 