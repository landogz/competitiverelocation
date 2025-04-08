<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Category;
use Illuminate\Http\Request;

class InventoryItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventoryItems = InventoryItem::with('category')->get();
        $categories = Category::all();
        
        // Group items by category
        $groupedItems = $inventoryItems->groupBy('category.name');
        
        return response()->json([
            'items' => $inventoryItems,
            'categories' => $categories,
            'groupedItems' => $groupedItems
        ]);
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
            'item' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'cubic_ft' => 'required|numeric|min:0'
        ]);

        $inventoryItem = InventoryItem::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Inventory item created successfully!',
            'data' => $inventoryItem->load('category')
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(InventoryItem $inventoryItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InventoryItem $inventoryItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InventoryItem $inventoryItem)
    {
        $request->validate([
            'item' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'cubic_ft' => 'required|numeric|min:0'
        ]);

        $inventoryItem->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Inventory item updated successfully!',
            'data' => $inventoryItem->load('category')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryItem $inventoryItem)
    {
        $inventoryItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Inventory item deleted successfully!'
        ]);
    }
}
