<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AgentController extends Controller
{
    public function index()
    {
        return view('agents');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $query = Agent::query();

            return DataTables::of($query)
                ->addColumn('services', function($agent) {
                    $services = [];
                    if ($agent->local_moving_service === 'yes') $services[] = 'Local Moving';
                    if ($agent->delivery_service === 'yes') $services[] = 'Delivery';
                    if ($agent->labor_services === 'yes') $services[] = 'Labor';
                    if ($agent->commercial_moving === 'yes') $services[] = 'Commercial';
                    return $services;
                })
                ->addColumn('status', function($agent) {
                    return $agent->is_active;
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        return abort(404);
    }

    public function sync()
    {
        try {
            Artisan::call('agents:sync');
            return response()->json([
                'success' => true,
                'message' => 'Agents data synchronized successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync agents data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getData()
    {
        $agents = Agent::all();
        return response()->json(['data' => $agents]);
    }

    public function syncAgents()
    {
        try {
            // Get existing company names from database
            $existingCompanies = Agent::pluck('company_name')->toArray();
            
            // Fetch agents from the API
            $response = Http::get('https://api.example.com/agents'); // Replace with your actual API endpoint
            $apiAgents = $response->json();

            $newAgentsCount = 0;
            $skippedCount = 0;

            foreach ($apiAgents as $apiAgent) {
                // Skip if company already exists
                if (in_array($apiAgent['company_name'], $existingCompanies)) {
                    $skippedCount++;
                    continue;
                }

                // Add new agent
                Agent::create([
                    'company_name' => $apiAgent['company_name'],
                    'company_website' => $apiAgent['company_website'] ?? null,
                    'contact_name' => $apiAgent['contact_name'] ?? null,
                    'contact_title' => $apiAgent['contact_title'] ?? null,
                    'email' => $apiAgent['email'] ?? null,
                    'phone_number' => $apiAgent['phone_number'] ?? null,
                    'address' => $apiAgent['address'] ?? null,
                    'city' => $apiAgent['city'] ?? null,
                    'state' => $apiAgent['state'] ?? null,
                    'zip_code' => $apiAgent['zip_code'] ?? null,
                    'services' => $apiAgent['services'] ?? [],
                    'num_trucks' => $apiAgent['num_trucks'] ?? 0,
                    'truck_size' => $apiAgent['truck_size'] ?? null,
                    'num_crews' => $apiAgent['num_crews'] ?? 0,
                    'truck_image' => $apiAgent['truck_image'] ?? null,
                    'corporate_sales' => $apiAgent['corporate_sales'] ?? 0,
                    'consumer_sales' => $apiAgent['consumer_sales'] ?? 0,
                    'local_sales' => $apiAgent['local_sales'] ?? 0,
                    'long_distance_sales' => $apiAgent['long_distance_sales'] ?? 0,
                    'delivery_service_sales' => $apiAgent['delivery_service_sales'] ?? 0,
                    'total_sales' => $apiAgent['total_sales'] ?? 0,
                    'is_active' => $apiAgent['is_active'] ?? true
                ]);
                $newAgentsCount++;
            }

            return response()->json([
                'success' => true,
                'message' => "Sync completed. Added {$newAgentsCount} new agents. Skipped {$skippedCount} existing agents."
            ]);

        } catch (\Exception $e) {
            Log::error('Agent sync failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync agents: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $agent = Agent::findOrFail($request->agent_id);
            
            $validated = $request->validate([
                'company_name' => 'required|string|max:255',
                'company_website' => 'nullable|string|max:255',
                'contact_name' => 'nullable|string|max:255',
                'contact_title' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone_number' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'zip_code' => 'nullable|string|max:20',
                'num_trucks' => 'nullable|integer|min:0',
                'truck_size' => 'nullable|string|max:100',
                'num_crews' => 'nullable|integer|min:0',
                'is_active' => 'required|boolean',
                'corporate_sales' => 'nullable|integer|min:0',
                'consumer_sales' => 'nullable|integer|min:0',
                'local_sales' => 'nullable|integer|min:0',
                'long_distance_sales' => 'nullable|integer|min:0',
                'delivery_service_sales' => 'nullable|integer|min:0',
                'total_sales' => 'nullable|integer|min:0'
            ]);

            $agent->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Agent updated successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Agent update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update agent: ' . $e->getMessage()
            ], 500);
        }
    }
}
