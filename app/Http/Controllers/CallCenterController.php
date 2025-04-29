<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
