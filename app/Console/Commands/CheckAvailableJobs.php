<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\Agent;
use App\Notifications\AvailableJobsNotification;
use Illuminate\Support\Facades\Log;

class CheckAvailableJobs extends Command
{
    protected $signature = 'jobs:check-available';
    protected $description = 'Check for available jobs and notify agents';

    public function handle()
    {
        $this->info('Checking for available jobs...');

        // Get all agents
        $agents = Agent::all();

        foreach ($agents as $agent) {
            try {
                // Skip if agent has no address
                if (empty($agent->address) || empty($agent->city) || empty($agent->state)) {
                    $this->info("Skipping agent {$agent->company_name} - incomplete address information");
                    continue;
                }

                // Get coordinates for the agent's address
                $agentCoordinates = $this->getCoordinatesFromAddress(
                    $agent->address,
                    $agent->city,
                    $agent->state,
                    $agent->zip_code,
                    $agent->country
                );

                if (!$agentCoordinates) {
                    $this->info("Could not get coordinates for agent {$agent->company_name}'s address");
                    continue;
                }

                // Get available jobs
                $availableJobs = Transaction::whereNull('assigned_agent')
                    ->where('status', 'pending')
                    ->get()
                    ->filter(function ($job) use ($agentCoordinates) {
                        // Get coordinates for the job's pickup location
                        $jobCoordinates = $this->getCoordinatesFromAddress($job->pickup_location);
                        
                        if (!$jobCoordinates) {
                            return false;
                        }

                        // Calculate distance between agent and job
                        $distance = $this->calculateDistance(
                            $agentCoordinates['lat'],
                            $agentCoordinates['lng'],
                            $jobCoordinates['lat'],
                            $jobCoordinates['lng']
                        );

                        // Only include jobs within 50 miles
                        return $distance <= 50;
                    });

                if ($availableJobs->isNotEmpty()) {
                    // Get the location for the notification
                    $location = $agent->city . ', ' . $agent->state;
                    
                    // Send notification to the agent
                    $agent->notify(new AvailableJobsNotification($availableJobs, $location));
                    
                    $this->info("Notified agent {$agent->company_name} about {$availableJobs->count()} available jobs within 50 miles");
                } else {
                    $this->info("No available jobs found within 50 miles for agent {$agent->company_name}");
                }
            } catch (\Exception $e) {
                Log::error("Error checking jobs for agent {$agent->company_name}: " . $e->getMessage());
                $this->error("Error processing agent {$agent->company_name}");
            }
        }

        $this->info('Finished checking available jobs');
    }

    private function getCoordinatesFromAddress($address, $city = null, $state = null, $zipCode = null, $country = null)
    {
        try {
            // Build the full address
            $fullAddress = array_filter([
                $address,
                $city,
                $state,
                $zipCode,
                $country
            ]);
            
            $addressString = implode(', ', $fullAddress);

            // Use Google Maps Geocoding API
            $apiKey = config('services.google.maps_api_key');
            $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($addressString) . "&key=" . $apiKey;
            
            $response = file_get_contents($url);
            $data = json_decode($response, true);

            if ($data['status'] === 'OK') {
                return [
                    'lat' => $data['results'][0]['geometry']['location']['lat'],
                    'lng' => $data['results'][0]['geometry']['location']['lng']
                ];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error getting coordinates: ' . $e->getMessage());
            return null;
        }
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 3959; // Radius of the earth in miles

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta/2) * sin($latDelta/2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta/2) * sin($lonDelta/2);
            
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;

        return $distance;
    }
} 