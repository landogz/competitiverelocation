<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Agent;

class CallCenterController extends Controller
{
    /**
     * Display a listing of the leads.
     */
    public function index()
    {
        try {
            // Get leads from local database only
            $leads = Lead::orderBy('date', 'desc')->get();
            $templates = \App\Models\EmailTemplate::all();
            
            return view('callcenter', compact('leads', 'templates'));
        } catch (\Exception $e) {
            \Log::error('Failed to load leads: ' . $e->getMessage());
            return view('callcenter', ['leads' => collect([]), 'templates' => collect([])]);
        }
    }

    /**
     * Show the form for creating a new lead.
     */
    public function create()
    {
        return view('leads.create');
    }

    /**
     * Store a newly created lead in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'nullable|email|max:255',
                'company' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
                'status' => 'required|in:new,contacted,qualified,unqualified,converted',
                'source' => 'nullable|string|max:255'
            ]);

            // Create the lead with validated data
            $lead = Lead::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? null,
                'company' => $validated['company'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => $validated['status'],
                'source' => $validated['source'] ?? 'website',
                'service' => isset($validated['services']) ? $validated['services'] : null
            ]);

            // Create initial log entry
            LeadLog::create([
                'lead_id' => $lead->id,
                'type' => 'note',
                'content' => 'Lead created',
                'user_id' => Auth::id() ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lead created successfully.',
                'data' => $lead
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create lead: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified lead.
     */
    public function show(Lead $lead)
    {
        $logs = $lead->logs()->orderBy('created_at', 'desc')->get();
        return view('leads.show', compact('lead', 'logs'));
    }

    /**
     * Show the form for editing the specified lead.
     */
    public function edit(Lead $lead)
    {
        $users = \App\Models\User::all();
        return view('leads.edit', compact('lead', 'users'));
    }

    /**
     * Update the specified lead in storage.
     */
    public function update(Request $request, Lead $lead)
    {
        // If only status is being updated (from the status modal)
        if ($request->has('status') && count($request->all()) <= 3) { // status + token + method
            $validated = $request->validate([
                'status' => 'required|in:new,contacted,qualified,unqualified,converted',
            ]);
            
            $lead->update(['status' => $validated['status']]);
            
            // Create a log entry for the status update
            LeadLog::create([
                'lead_id' => $lead->id,
                'type' => 'status',
                'content' => 'Status updated to: ' . ucfirst($validated['status']),
                'user_id' => Auth::id() ?? null,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        }
        
        // Full lead update (original behavior)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'company' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:new,contacted,qualified,unqualified,converted',
            'source' => 'nullable|string|max:255'
        ]);

        // Preserve existing notes if not provided in the update
        if (!isset($validated['notes'])) {
            $validated['notes'] = $lead->notes;
        }

        $lead->update($validated);

        // Create a log entry for the update
        LeadLog::create([
            'lead_id' => $lead->id,
            'type' => 'note',
            'content' => 'Lead information updated',
            'user_id' => Auth::id() ?? null,
        ]);

        return redirect()->route('callcenter.index')
            ->with('success', 'Lead updated successfully.');
    }

    /**
     * Remove the specified lead from storage.
     */
    public function destroy($lead)
    {
        try {
            $lead = Lead::find($lead);
            
            if (!$lead) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lead not found'
                ], 404);
            }

            // Delete associated logs first
            $lead->logs()->delete();
            
            // Delete the lead
            $lead->delete();

            return response()->json([
                'success' => true,
                'message' => 'Lead deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete lead: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new log for the specified lead.
     */
    public function storeLog(Request $request, Lead $lead)
    {
        try {
            $validated = $request->validate([
                'type' => 'required|string|max:50',
                'content' => 'required|string',
            ]);

            $log = $lead->logs()->create([
                'type' => $validated['type'],
                'content' => $validated['content'],
                'user_id' => Auth::id() ?? null,
            ]);

            // Get the updated log count
            $logCount = $lead->logs()->count();

            return response()->json([
                'success' => true,
                'message' => 'Log added successfully',
                'log' => [
                    'created_at' => $log->created_at->format('m/d/Y H:i'),
                    'type' => $log->type,
                    'content' => $log->content,
                    'created_by' => $log->user_id ?? 'System'
                ],
                'log_count' => $logCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add log: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View logs for the specified lead.
     */
    public function viewLogs(Lead $lead)
    {
        $logs = $lead->logs()->orderBy('created_at', 'desc')->get();
        return view('leads.logs', compact('lead', 'logs'));
    }

    public function sync(Request $request)
    {
        try {
            $response = Http::get('https://competitiverelocation.com/wp-json/landogz/v1/transactions-pending');
            
            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch transactions from API'
                ], 500);
            }

            $data = $response->json();
            $newCount = 0;
            $updatedCount = 0;

            if ($data['success'] && isset($data['data'])) {
                foreach ($data['data'] as $transactionData) {
                    // Check if lead already exists using transaction_id
                    $existingLead = Lead::where('transaction_id', $transactionData['id'])->first();

                    if ($existingLead) {
                        // Update existing lead while preserving notes and status
                        $existingLead->update([
                            'transaction_id' => $transactionData['id'],
                            'name' => $transactionData['firstname'] . ' ' . $transactionData['lastname'],
                            'phone' => $transactionData['phone'],
                            'email' => $transactionData['email'],
                            'company' => $transactionData['sales_location'] ?? null,
                            'status' => $existingLead->status, // Preserve existing status
                            'source' => 'website',
                            'notes' => $existingLead->notes, // Preserve existing notes
                            'sales_name' => $transactionData['sales_name'] ?? null,
                            'sales_email' => $transactionData['sales_email'] ?? null,
                            'pickup_location' => $transactionData['pickup_location'] ?? null,
                            'delivery_location' => $transactionData['delivery_location'] ?? null,
                            'miles' => $transactionData['miles'] ?? 0,
                            'service' => isset($transactionData['services']) ? $transactionData['services'] : null,
                            'service_rate' => $transactionData['service_rate'] ?? 0,
                            'no_of_items' => $transactionData['no_of_items'] ?? 0,
                            'no_of_crew' => $transactionData['no_of_crew'] ?? 0,
                            'crew_rate' => $transactionData['crew_rate'] ?? 0,
                            'subtotal' => $transactionData['subtotal'] ?? 0,
                            'software_fee' => $transactionData['Software_Fee'] ?? 0,
                            'truck_fee' => $transactionData['Truck_Fee'] ?? 0,
                            'downpayment' => $transactionData['Downpayment'] ?? 0,
                            'grand_total' => $transactionData['grand_total'] ?? 0,
                            'uploaded_image' => $transactionData['uploaded_image'] ?? null,
                            'date' => !empty($transactionData['date']) && $transactionData['date'] !== '1970-01-01 00:00:00' ? 
                                    date('Y-m-d H:i:s', strtotime($transactionData['date'])) : null,
                        ]);
                        $updatedCount++;
                    } else {
                        // Create new lead
                        Lead::create([
                            'transaction_id' => $transactionData['id'],
                            'name' => $transactionData['firstname'] . ' ' . $transactionData['lastname'],
                            'phone' => $transactionData['phone'],
                            'email' => $transactionData['email'],
                            'company' => $transactionData['sales_location'] ?? null,
                            'status' => 'new',
                            'source' => 'website',
                            'notes' => null,
                            'sales_name' => $transactionData['sales_name'] ?? null,
                            'sales_email' => $transactionData['sales_email'] ?? null,
                            'pickup_location' => $transactionData['pickup_location'] ?? null,
                            'delivery_location' => $transactionData['delivery_location'] ?? null,
                            'miles' => $transactionData['miles'] ?? 0,
                            'service' => isset($transactionData['services']) ? $transactionData['services'] : null,
                            'service_rate' => $transactionData['service_rate'] ?? 0,
                            'no_of_items' => $transactionData['no_of_items'] ?? 0,
                            'no_of_crew' => $transactionData['no_of_crew'] ?? 0,
                            'crew_rate' => $transactionData['crew_rate'] ?? 0,
                            'subtotal' => $transactionData['subtotal'] ?? 0,
                            'software_fee' => $transactionData['Software_Fee'] ?? 0,
                            'truck_fee' => $transactionData['Truck_Fee'] ?? 0,
                            'downpayment' => $transactionData['Downpayment'] ?? 0,
                            'grand_total' => $transactionData['grand_total'] ?? 0,
                            'uploaded_image' => $transactionData['uploaded_image'] ?? null,
                            'date' => !empty($transactionData['date']) && $transactionData['date'] !== '1970-01-01 00:00:00' ? 
                                    date('Y-m-d H:i:s', strtotime($transactionData['date'])) : null,
                        ]);
                        $newCount++;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'new_count' => $newCount,
                'updated_count' => $updatedCount,
                'message' => "Added $newCount new leads and updated $updatedCount existing leads"
            ]);

        } catch (\Exception $e) {
            \Log::error('Sync failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function datatable(Request $request)
    {
        try {
            // Fetch leads from API
            $response = Http::get('https://competitiverelocation.com/wp-json/landogz/v1/transactions-pending');
            
            if ($response->successful() && isset($response['data'])) {
                foreach ($response['data'] as $transactionData) {
                    // Check if lead already exists using transaction_id
                    $existingLead = Lead::where('transaction_id', $transactionData['id'])->first();

                    if (!$existingLead) {
                        // Only create new leads, don't update existing ones
                        Lead::create([
                            'transaction_id' => $transactionData['id'],
                            'name' => $transactionData['firstname'] . ' ' . $transactionData['lastname'],
                            'phone' => $transactionData['phone'],
                            'email' => $transactionData['email'],
                            'company' => $transactionData['sales_location'] ?? null,
                            'status' => 'new',
                            'source' => 'website',
                            'notes' => null,
                            'sales_name' => $transactionData['sales_name'] ?? null,
                            'sales_email' => $transactionData['sales_email'] ?? null,
                            'pickup_location' => $transactionData['pickup_location'] ?? null,
                            'delivery_location' => $transactionData['delivery_location'] ?? null,
                            'miles' => $transactionData['miles'] ?? 0,
                            'service' => isset($transactionData['services']) ? json_encode($transactionData['services']) : null,
                            'service_rate' => $transactionData['service_rate'] ?? 0,
                            'no_of_items' => $transactionData['no_of_items'] ?? 0,
                            'no_of_crew' => $transactionData['no_of_crew'] ?? 0,
                            'crew_rate' => $transactionData['crew_rate'] ?? 0,
                            'subtotal' => $transactionData['subtotal'] ?? 0,
                            'software_fee' => $transactionData['Software_Fee'] ?? 0,
                            'truck_fee' => $transactionData['Truck_Fee'] ?? 0,
                            'downpayment' => $transactionData['Downpayment'] ?? 0,
                            'grand_total' => $transactionData['grand_total'] ?? 0,
                            'uploaded_image' => $transactionData['uploaded_image'] ?? null,
                            'date' => !empty($transactionData['date']) && $transactionData['date'] !== '1970-01-01 00:00:00' ? 
                                    date('Y-m-d H:i:s', strtotime($transactionData['date'])) : null,
                        ]);
                    }
                    // Skip updating existing leads
                }
            }
        } catch (\Exception $e) {
            \Log::error('API sync error: ' . $e->getMessage());
        }

        // 1. Get total records before any filters
        $totalRecordsQuery = Lead::query();
        
        // Apply agent filtering to total records count too
        if (auth()->user()->privilege === 'agent') {
            $agentUser = auth()->user();
            
            if (!empty($agentUser->agent_id)) {
                // Find the agent record to get external_id
                $agent = \App\Models\Agent::where('id', $agentUser->agent_id)->first();
                
                if ($agent && !empty($agent->external_id)) {
                    // Filter leads by the agent's external_id
                    $totalRecordsQuery->where('agent_id', $agent->external_id);
                } else {
                    // No valid external_id found, show no results
                    $totalRecordsQuery->where('id', 0);
                }
            } else {
                // User has no agent_id, show no results
                $totalRecordsQuery->where('id', 0);
            }
        }
        
        $totalRecords = $totalRecordsQuery->count();

        // 2. Apply filters to the query
        $query = Lead::query();
        
        // Apply privilege-based filtering for agents
        if (auth()->user()->privilege === 'agent') {
            // Get the user's agent_id
            $agentUser = auth()->user();
            
            if (!empty($agentUser->agent_id)) {
                // Find the agent record to get external_id
                $agent = \App\Models\Agent::where('id', $agentUser->agent_id)->first();
                
                if ($agent && !empty($agent->external_id)) {
                    // Filter leads by the agent's external_id
                    $query->where('agent_id', $agent->external_id);
                } else {
                    // No valid external_id found, show no results
                    $query->where('id', 0);
                }
            } else {
                // User has no agent_id, show no results
                $query->where('id', 0);
            }
        }

        // Apply search filters
        if ($request->has('search') && !empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $query->where(function($q) use ($searchValue) {
                $q->where('name', 'like', "%{$searchValue}%")
                  ->orWhere('phone', 'like', "%{$searchValue}%")
                  ->orWhere('email', 'like', "%{$searchValue}%")
                  ->orWhere('sales_name', 'like', "%{$searchValue}%")
                  ->orWhere('sales_location', 'like', "%{$searchValue}%")
                  ->orWhere('service', 'like', "%{$searchValue}%");
            });
        }

        // 3. Get filtered count before pagination
        $filteredRecords = $query->count();

        // 4. Apply sorting
        if ($request->has('order')) {
            $orderColumn = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir');
            $columns = ['transaction_id', 'name', 'phone', 'email', 'sales_name', 'date', 'status'];
            if (isset($columns[$orderColumn])) {
                $query->orderBy($columns[$orderColumn], $orderDir);
            }
        } else {
            $query->orderBy('transaction_id', 'desc'); // Default sort by transaction_id desc
        }

        // 5. Apply pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $query->skip($start)->take($length);
        $leads = $query->get();

        $data = $leads->map(function($lead) {
            return [
                'id' => $lead->transaction_id,
                'name' => $lead->name,
                'phone' => '<a href="tel:' . $lead->phone . '">' . $lead->phone . '</a>',
                'email' => '<a href="mailto:' . $lead->email . '">' . $lead->email . '</a>',
                'sales_name' => $lead->sales_name,
                'sales_location' => $lead->sales_location,
                'service' => $lead->service,
                'no_of_items' => $lead->no_of_items,
                'grand_total' => '$' . number_format($lead->grand_total, 2),
                'date' => $lead->date ?? '', // raw value for sorting
                'date_display' => $lead->date ? date('M d, Y', strtotime($lead->date)) : '', // formatted for display
                'status' => '<span class="badge bg-' . $this->getStatusBadgeClass($lead->status) . '">' . ucfirst($lead->status) . '</span>',
                'agent_id' => $lead->agent_id,
                'company_name' => $this->getCompanyNameForAgent($lead->agent_id),
                'actions' => view('call-center.actions', compact('lead'))->render()
            ];
        });

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    public function sendEmail(Request $request, Lead $lead)
    {
        try {
            $validated = $request->validate([
                'template_id' => 'required|exists:email_templates,id',
                'subject' => 'required|string|max:255',
                'content' => 'required|string'
            ]);

            // Get the email template
            $template = \App\Models\EmailTemplate::find($validated['template_id']);

            // Create a new log entry for the email
            $lead->logs()->create([
                'type' => 'email',
                'content' => 'Email sent: ' . $validated['subject'],
                'user_id' => Auth::id() ?? null,
            ]);

            // Send the email
            \Illuminate\Support\Facades\Mail::to($lead->email)
                ->send(new \App\Mail\TestEmailTemplate($template));

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single lead's data for email functionality.
     */
    public function getLeadData($id)
    {
        try {
            $lead = Lead::findOrFail($id);
            
            // Parse the service array to extract service names
            $serviceNames = '';
            if (!empty($lead->service) && is_array($lead->service)) {
                $names = array_map(function($service) {
                    return $service['name'] ?? '';
                }, $lead->service);
                $serviceNames = implode(', ', array_filter($names));
            }
            
            // Convert lead to array and add parsed service name
            $leadData = $lead->toArray();
            $leadData['service_name'] = $serviceNames;
            $leadData['service'] = $serviceNames; // Also update the service field itself

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
            \Log::error('Lead data error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch lead data: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getStatusBadgeClass($status)
    {
        return match($status) {
            'new' => 'warning',
            'contacted' => 'info',
            'qualified' => 'success',
            'unqualified' => 'danger',
            default => 'primary'
        };
    }
    
    private function getCompanyNameForAgent($agentId)
    {
        if (empty($agentId) || $agentId == '0') {
            return 'N/A';
        }
        
        $agent = \App\Models\Agent::where('external_id', $agentId)->first();
        return $agent ? $agent->company_name : 'Unknown Agent ('.$agentId.')';
    }
}
