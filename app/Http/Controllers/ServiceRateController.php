<?php

namespace App\Http\Controllers;

use App\Models\ServiceRate;
use Illuminate\Http\Request;

class ServiceRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $serviceRates = ServiceRate::all();
        return view('servicerates', compact('serviceRates'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $serviceRate = ServiceRate::findOrFail($id);
        
        $validated = $request->validate([
            'rate' => 'required|numeric',
            'value_range' => 'nullable|string',
            'description' => 'nullable|string',
        ]);
        
        $serviceRate->update($validated);
        
        return response()->json(['success' => true, 'message' => 'Service rate updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Update multiple service rates at once
     */
    public function updateBatch(Request $request)
    {
        $updates = $request->all();
        
        foreach ($updates as $id => $data) {
            if (is_numeric($id)) {
                $serviceRate = ServiceRate::find($id);
                if ($serviceRate) {
                    $serviceRate->update($data);
                }
            }
        }
        
        return response()->json(['success' => true, 'message' => 'Service rates updated successfully']);
    }
}
