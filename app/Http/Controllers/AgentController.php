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

    public function syncAgents(Request $request)
    {
        try {
            // Get existing company names to avoid duplicates
            $existingCompanies = Agent::pluck('company_name')->toArray();
            
            // Fetch agents from API
            $response = Http::get('https://competitiverelocation.com/wp-json/landogz/v1/data');
            
            if (!$response->successful()) {
                throw new \Exception('API request failed: ' . $response->body());
            }
            
            $apiData = $response->json();
            
            if (!isset($apiData['success']) || !$apiData['success'] || !isset($apiData['data'])) {
                throw new \Exception('Invalid API response format');
            }
            
            $apiAgents = $apiData['data'];
            $newAgentsCount = 0;
            $skippedCount = 0;
            
            foreach ($apiAgents as $apiAgent) {
                // Skip if company already exists
                if (in_array($apiAgent['company_name'], $existingCompanies)) {
                    $skippedCount++;
                    continue;
                }
                
                // Create new agent
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
                    'num_trucks' => $apiAgent['num_trucks'] ?? null,
                    'truck_size' => $apiAgent['truck_size'] ?? null,
                    'num_crews' => $apiAgent['num_crews'] ?? null,
                    'is_active' => ($apiAgent['status'] ?? '') === 'approved',
                    'corporate_sales' => $apiAgent['corporate_sales'] ?? null,
                    'consumer_sales' => $apiAgent['consumer_sales'] ?? null,
                    'local_sales' => $apiAgent['local_sales'] ?? null,
                    'long_distance_sales' => $apiAgent['long_distance_sales'] ?? null,
                    'delivery_service_sales' => $apiAgent['delivery_service_sales'] ?? null,
                    'total_sales' => $apiAgent['total_sales'] ?? null,
                    'services' => [
                        'local_moving' => ($apiAgent['local_moving_service'] ?? '') === 'yes',
                        'delivery' => ($apiAgent['delivery_service'] ?? '') === 'yes',
                        'labor' => ($apiAgent['labor_services'] ?? '') === 'yes',
                        'commercial' => ($apiAgent['commercial_moving'] ?? '') === 'yes',
                        'booking_agent' => ($apiAgent['booking_agent'] ?? '') === 'yes',
                        'general_freight' => ($apiAgent['general_freight'] ?? '') === 'yes'
                    ],
                    'truck_image' => $apiAgent['truck_image'] ?? null
                ]);
                
                $newAgentsCount++;
            }
            
            return response()->json([
                'success' => true,
                'message' => "Sync completed successfully. Added {$newAgentsCount} new agents. Skipped {$skippedCount} existing agents."
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
