<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
                    return $agent->services;
                })
                ->addColumn('status', function($agent) {
                    return $agent->is_active;
                })
                ->addColumn('unique_url', function($agent) {
                    return $agent->unique_url ?? '-';
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
            // Get existing external IDs to avoid duplicates
            $existingExternalIds = Agent::pluck('external_id')->toArray();
            
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
                // Skip if agent already exists (using external_id)
                if (in_array($apiAgent['id'], $existingExternalIds)) {
                    $skippedCount++;
                    continue;
                }
                
                // Create services array
                $services = [
                    'local_moving' => ($apiAgent['local_moving_service'] ?? '') === 'yes',
                    'delivery' => ($apiAgent['delivery_service'] ?? '') === 'yes',
                    'labor' => ($apiAgent['labor_services'] ?? '') === 'yes',
                    'commercial' => ($apiAgent['commercial_moving'] ?? '') === 'yes',
                    'booking_agent' => ($apiAgent['booking_agent'] ?? '') === 'yes',
                    'general_freight' => ($apiAgent['general_freight'] ?? '') === 'yes'
                ];

                // Clean numeric values
                $salesFields = [
                    'corporate_sales',
                    'consumer_sales',
                    'local_sales',
                    'long_distance_sales',
                    'delivery_service_sales',
                    'total_sales'
                ];

                foreach ($salesFields as $field) {
                    if (isset($apiAgent[$field])) {
                        $apiAgent[$field] = str_replace(',', '', $apiAgent[$field]);
                        $apiAgent[$field] = is_numeric($apiAgent[$field]) ? $apiAgent[$field] : 0;
                    }
                }

                // Create user account for the agent
                try {
                    $user = \App\Models\User::firstOrCreate(
                        ['email' => $apiAgent['email'] ?? ''],
                        [
                            'first_name' => $apiAgent['contact_name'] ?? 'Agent',
                            'last_name' => $apiAgent['company_name'] ?? '',
                            'password' => bcrypt('12345678'),
                            'privilege' => 'agent',
                            'phone' => $apiAgent['phone_number'] ?? null,
                            'address' => $apiAgent['address'] ?? null,
                            'city' => $apiAgent['city'] ?? null,
                            'state' => $apiAgent['state'] ?? null,
                            'zip_code' => $apiAgent['zip_code'] ?? null
                        ]
                    );
                } catch (\Exception $e) {
                    // If user creation fails, try to find existing user
                    $user = \App\Models\User::where('email', $apiAgent['email'] ?? '')->first();
                    if (!$user) {
                        throw $e; // Re-throw if we can't find the user
                    }
                }
                
                // Create new agent and link to user
                $agent = Agent::create([
                    'external_id' => $apiAgent['id'],
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
                    'services' => $services,
                    'truck_image' => $apiAgent['truck_image'] ?? null,
                    'unique_url' => 'https://competitiverelocation.com/delivery-services/?agent=' . $apiAgent['id'] . '&ref=' . str_replace(' ', '-', strtolower($apiAgent['company_name']))
                ]);

                // Handle truck image data if present
                if (isset($apiAgent['truck_image_data']) && !empty($apiAgent['truck_image_data'])) {
                    try {
                        // Decode base64 image data
                        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $apiAgent['truck_image_data']));
                        
                        // Generate unique filename
                        $filename = 'truck_' . $apiAgent['id'] . '_' . time() . '.jpg';
                        
                        // Save image to storage
                        $path = Storage::disk('public')->put('truck_images/' . $filename, $imageData);
                        
                        if ($path) {
                            $agent->truck_image = 'storage/truck_images/' . $filename;
                            $agent->save();
                        }
                    } catch (\Exception $e) {
                        Log::error('Error processing truck image for agent', [
                            'agent_id' => $apiAgent['id'],
                            'error' => $e->getMessage()
                        ]);
                    }
                }
                
                // Now link user to agent
                $user->agent_id = $agent->id;
                $user->save();

                // Send welcome email to the agent
                if (!empty($agent->email)) {
                    $welcomeTemplate = \App\Models\EmailTemplate::where('name', 'WELCOME NEW AGENTS')->first();
                    if ($welcomeTemplate) {
                        \Mail::to($agent->email)->send(
                            new \App\Mail\TestEmailTemplate($welcomeTemplate)
                        );
                    }
                }
                
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
