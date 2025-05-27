<?php

namespace App\Console\Commands;

use App\Models\Agent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncAgentsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agents:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync agents data from the API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting agents data sync...');

        try {
            // Fetch data from API
            $response = Http::get('https://competitiverelocation.com/wp-json/landogz/v1/data');
            
            if (!$response->successful()) {
                throw new \Exception('Failed to fetch data from API: ' . $response->status());
            }

            $data = $response->json();
            
            if (!isset($data['data']) || !is_array($data['data'])) {
                throw new \Exception('Invalid data format received from API');
            }

            $apiAgents = collect($data['data']);
            $existingAgents = Agent::pluck('external_id')->toArray();
            $processedIds = [];

            foreach ($apiAgents as $agentData) {
                $processedIds[] = $agentData['id'];

                // Clean sales data
                $salesFields = [
                    'corporate_sales',
                    'consumer_sales',
                    'local_sales',
                    'long_distance_sales',
                    'delivery_service_sales',
                    'total_sales'
                ];

                foreach ($salesFields as $field) {
                    $agentData[$field] = str_replace(',', '', $agentData[$field]);
                    $agentData[$field] = is_numeric($agentData[$field]) ? $agentData[$field] : 0;
                }

                // Update or create agent
                Agent::updateOrCreate(
                    ['external_id' => $agentData['id']],
                    [
                        'company_name' => $agentData['company_name'],
                        'contact_name' => $agentData['contact_name'],
                        'contact_title' => $agentData['contact_title'],
                        'address' => $agentData['address'],
                        'city' => $agentData['city'],
                        'state' => $agentData['state'],
                        'zip_code' => $agentData['zip_code'],
                        'phone_number' => $agentData['phone_number'],
                        'email' => $agentData['email'],
                        'company_website' => $agentData['company_website'],
                        'corporate_sales' => $agentData['corporate_sales'],
                        'consumer_sales' => $agentData['consumer_sales'],
                        'local_sales' => $agentData['local_sales'],
                        'long_distance_sales' => $agentData['long_distance_sales'],
                        'delivery_service_sales' => $agentData['delivery_service_sales'],
                        'total_sales' => $agentData['total_sales'],
                        'truck_size' => $agentData['truck_size'],
                        'truck_image' => $agentData['truck_image'],
                        'num_trucks' => $agentData['num_trucks'],
                        'num_crews' => $agentData['num_crews'],
                        'affiliated_company' => $agentData['affiliated_company'],
                        'local_moving_service' => $agentData['local_moving_service'],
                        'delivery_service' => $agentData['delivery_service'],
                        'labor_services' => $agentData['labor_services'],
                        'commercial_moving' => $agentData['commercial_moving'],
                        'carrierInterestReason' => $agentData['carrierInterestReason'],
                        'external_created_at' => $agentData['created_at'],
                        'status' => $agentData['status'],
                        'randomcodes' => $agentData['randomcodes'],
                        'booking_agent' => $agentData['booking_agent'],
                        'general_freight' => $agentData['general_freight'],
                        'is_active' => true
                    ]
                );
            }

            // Deactivate agents that are no longer in the API
            Agent::whereNotIn('external_id', $processedIds)
                ->update(['is_active' => false]);

            $this->info('Agents data sync completed successfully!');
            Log::info('Agents data sync completed successfully');
            
        } catch (\Exception $e) {
            $this->error('Error syncing agents data: ' . $e->getMessage());
            Log::error('Error syncing agents data: ' . $e->getMessage());
        }
    }
}
