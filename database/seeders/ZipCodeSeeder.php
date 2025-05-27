<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ZipCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all unique zip codes from agents and transactions
        $agentZipCodes = DB::table('agents')
            ->whereNotNull('zip_code')
            ->distinct()
            ->pluck('zip_code');
            
        $transactionZipCodes = DB::table('transactions')
            ->whereNotNull('pickup_location')
            ->distinct()
            ->pluck('pickup_location')
            ->map(function($location) {
                // Extract zip code from location string
                preg_match('/\b\d{5}\b/', $location, $matches);
                return $matches[0] ?? null;
            })
            ->filter();
            
        $allZipCodes = $agentZipCodes->merge($transactionZipCodes)->unique();
        
        // Load the free zip code database
        $zipData = $this->loadZipCodeData();
        
        foreach ($allZipCodes as $zipCode) {
            try {
                \Log::info("Processing zip code: {$zipCode}");
                // Check if zip code already exists
                if (DB::table('zip_codes')->where('zip_code', $zipCode)->exists()) {
                    \Log::info("Zip code {$zipCode} already exists in zip_codes table. Skipping.");
                    continue;
                }
                
                // Find zip code in our database
                if (isset($zipData[$zipCode])) {
                    $data = $zipData[$zipCode];
                    DB::table('zip_codes')->insert([
                        'zip_code' => $zipCode,
                        'city' => $data['city'],
                        'state' => $data['state'],
                        'latitude' => $data['latitude'],
                        'longitude' => $data['longitude'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    \Log::info("Inserted zip code: {$zipCode}");
                } else {
                    \Log::warning("Zip code {$zipCode} not found in CSV data. Skipping.");
                }
                
            } catch (\Exception $e) {
                \Log::error("Error processing zip code {$zipCode}: " . $e->getMessage());
                continue;
            }
        }
    }

    /**
     * Load zip code data from a local CSV file
     * You can download a free zip code database from:
     * https://www.unitedstateszipcodes.org/zip-code-database/
     */
    private function loadZipCodeData()
    {
        $zipData = [];
        
        // Check if the CSV file exists
        $csvPath = database_path('data/zip_codes.csv');
        if (!File::exists($csvPath)) {
            \Log::error('Zip code database file not found at: ' . $csvPath);
            return $zipData;
        }
        
        // Read the CSV file
        $handle = fopen($csvPath, 'r');
        if ($handle !== false) {
            // Skip header row
            fgetcsv($handle);
            
            while (($data = fgetcsv($handle)) !== false) {
                $zipCode = $data[0]; // zip
                $zipData[$zipCode] = [
                    'city' => $data[3],        // primary_city
                    'state' => $data[6],       // state
                    'latitude' => $data[12],   // latitude
                    'longitude' => $data[13]   // longitude
                ];
            }
            fclose($handle);
        }
        
        return $zipData;
    }
}
