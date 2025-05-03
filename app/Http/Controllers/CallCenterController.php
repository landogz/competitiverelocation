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
        $leads = Lead::orderBy('created_at', 'desc')->get();
        return view('callcenter', compact('leads'));
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'company' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:new,contacted,qualified,unqualified,converted',
            'source' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|string|max:255',
        ]);

        $lead = Lead::create($validated);

        // Create initial log entry
        LeadLog::create([
            'lead_id' => $lead->id,
            'type' => 'note',
            'content' => 'Lead created',
            'user_id' => Auth::id() ?? null,
        ]);

        return redirect()->route('callcenter.index')
            ->with('success', 'Lead created successfully.');
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'company' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:new,contacted,qualified,unqualified,converted',
            'source' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|string|max:255',
        ]);

        $lead->update($validated);

        return redirect()->route('callcenter.index')
            ->with('success', 'Lead updated successfully.');
    }

    /**
     * Remove the specified lead from storage.
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('callcenter.index')
            ->with('success', 'Lead deleted successfully.');
    }

    /**
     * Store a new log for the specified lead.
     */
    public function storeLog(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:50',
            'content' => 'required|string',
        ]);

        $log = $lead->logs()->create([
            'type' => $validated['type'],
            'content' => $validated['content'],
            'user_id' => Auth::id() ?? null,
        ]);

        return redirect()->route('callcenter.show', $lead)
            ->with('success', 'Log added successfully.');
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
            $response = Http::get('https://competitiverelocation.com/wp-json/landogz/v1/leads');
            
            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch leads from API'
                ], 500);
            }

            $data = $response->json();
            $newCount = 0;

            if ($data['success'] && isset($data['data'])) {
                foreach ($data['data'] as $lead) {
                    // Check if lead already exists using phone number
                    $existingLead = Lead::where('phone', $lead['phone'])->first();

                    if (!$existingLead) {
                        // Get agent company name if agent_id exists
                        $companyName = null;
                        if (isset($lead['agent_id'])) {
                            $agent = Agent::where('external_id', $lead['agent_id'])->first();
                            if ($agent) {
                                $companyName = $agent->company_name;
                            }
                        }

                        // Create new lead
                        Lead::create([
                            'name' => $lead['name'],
                            'phone' => $lead['phone'],
                            'email' => $lead['email'],
                            'company' => $companyName,
                            'status' => 'new',
                            'source' => 'website'
                        ]);

                        $newCount++;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'new_count' => $newCount,
                'message' => $newCount > 0 ? "Added $newCount new leads" : "No new leads found"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function datatable(Request $request)
    {
        $query = Lead::withCount('logs');

        // Apply privilege-based filtering
        if (Auth::user()->privilege === 'agent') {
            $query->where('company', Auth::user()->last_name);
        }

        // Apply search filter
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }

        // Get total records count
        $totalRecords = $query->count();

        // Apply sorting
        if ($request->has('order')) {
            $columns = ['created_at', 'name', 'phone', 'email', 'company', 'status'];
            $column = $columns[$request->order[0]['column']];
            $direction = $request->order[0]['dir'];
            $query->orderBy($column, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Apply pagination
        $leads = $query->skip($request->start)
                      ->take($request->length)
                      ->get();

        // Format data for DataTables
        $data = [];
        foreach ($leads as $lead) {
            $data[] = [
                'DT_RowData' => [
                    'source' => $lead->source,
                    'notes' => $lead->notes
                ],
                'created_at' => $lead->created_at->format('m/d/Y'),
                'name' => $lead->name,
                'phone' => $lead->phone ? '<a href="tel:'.$lead->phone.'" class="clickable-link"><i class="fas fa-phone-alt"></i>'.$lead->phone.'</a>' : '-',
                'email' => $lead->email ? '<a href="mailto:'.$lead->email.'" class="clickable-link"><i class="fas fa-envelope"></i>'.$lead->email.'</a>' : '-',
                'company' => $lead->company,
                'status' => '<span class="badge bg-'.($lead->status === 'new' ? 'warning' : ($lead->status === 'contacted' ? 'info' : ($lead->status === 'qualified' ? 'success' : ($lead->status === 'unqualified' ? 'danger' : 'primary')))).'">'.ucfirst($lead->status).'</span>',
                'actions' => '
                    <button type="button" class="btn btn-sm btn-warning view-logs-btn position-relative" data-lead-id="'.$lead->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="View Logs">
                        '.($lead->logs_count > 0 ? '<span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6em; padding: 0.25em 0.4em;">'.$lead->logs_count.'</span>' : '').'
                        <i class="fas fa-eye"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-primary edit-lead-btn" data-lead-id="'.$lead->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Lead">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger delete-lead-btn" data-lead-id="'.$lead->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Lead">
                        <i class="fas fa-trash"></i>
                    </button>
                '
            ];
        }

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }
}
