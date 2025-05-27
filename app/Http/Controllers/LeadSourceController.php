<?php

namespace App\Http\Controllers;

use App\Models\LeadSource;
use Illuminate\Http\Request;

class LeadSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leadSources = LeadSource::all();
        return view('leadsource', compact('leadSources'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:lead_sources'
        ]);

        $leadSource = LeadSource::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Lead Source created successfully!',
            'data' => $leadSource
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(LeadSource $leadSource)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeadSource $leadSource)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeadSource $leadSource)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:lead_sources,title,' . $leadSource->id
        ]);

        $leadSource->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Lead Source updated successfully!',
            'data' => $leadSource
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeadSource $leadSource)
    {
        $leadSource->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lead Source deleted successfully!'
        ]);
    }
}
