<?php

namespace Database\Seeders;

use App\Models\ServiceRate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delivery Service
        $deliveryRates = [
            ['value_range' => 'Less than $750', 'rate' => 79.99, 'description' => 'light assembly'],
            ['value_range' => '$750 - Less than $1500', 'rate' => 159.99, 'description' => 'light assembly'],
            ['value_range' => '$1500 - Less than $2000', 'rate' => 179.99, 'description' => 'light assembly'],
            ['value_range' => '$2000 - Less than $2750', 'rate' => 194.99, 'description' => 'light assembly'],
            ['value_range' => '$2750 - Less than $4000', 'rate' => 224.99, 'description' => 'light assembly'],
            ['value_range' => '$4000 - Less than $6000', 'rate' => 284.99, 'description' => 'light assembly'],
            ['value_range' => '$6000 - Less than $8000', 'rate' => 314.99, 'description' => 'light assembly'],
            ['value_range' => '$8000 - Less than $10000', 'rate' => 344.99, 'description' => 'light assembly'],
            ['value_range' => '$10000 and more', 'rate' => 384.99, 'description' => 'light assembly'],
        ];

        foreach ($deliveryRates as $rateData) {
            ServiceRate::create([
                'service_type' => 'delivery',
                'title' => 'Delivery Service',
                'value_range' => $rateData['value_range'],
                'rate' => $rateData['rate'],
                'description' => $rateData['description'],
                'badge_color' => 'primary',
                'icon' => 'fas fa-truck'
            ]);
        }

        // Furniture Removal
        ServiceRate::create([
            'service_type' => 'furniture_removal',
            'title' => 'Furniture Removal',
            'rate' => 175.00,
            'description' => 'Base rate for furniture removal',
            'badge_color' => 'success',
            'icon' => 'fas fa-couch'
        ]);

        ServiceRate::create([
            'service_type' => 'furniture_removal_additional',
            'title' => 'Additional Items',
            'rate' => 75.00,
            'description' => 'Any additional items',
            'badge_color' => 'success',
            'icon' => 'fas fa-couch'
        ]);

        // Moving Service
        $movingRates = [
            ['category' => '2 men', 'rate' => 270.00],
            ['category' => '3 men', 'rate' => 465.00],
            ['category' => '4 men', 'rate' => 465.00], 
            ['category' => '5 men', 'rate' => 465.00]
        ];

        foreach ($movingRates as $rateData) {
            ServiceRate::create([
                'service_type' => 'moving',
                'title' => 'Moving Service',
                'category' => $rateData['category'],
                'rate' => $rateData['rate'],
                'description' => '3 hours minimum (2 hours labor, 1 hour travel)',
                'badge_color' => 'info',
                'icon' => 'fas fa-people-carry'
            ]);
        }

        // Other services
        $otherServices = [
            [
                'service_type' => 'cleaning',
                'title' => 'Cleaning Service',
                'rate' => 135.00,
                'unit' => 'hourly',
                'description' => 'Professional cleaning service',
                'badge_color' => 'warning',
                'icon' => 'fas fa-broom'
            ],
            [
                'service_type' => 'rearranging',
                'title' => 'Rearranging',
                'rate' => 150.00,
                'unit' => 'hourly',
                'description' => 'Professional rearranging service',
                'badge_color' => 'danger',
                'icon' => 'fas fa-boxes'
            ],
            [
                'service_type' => 'mattress_removal',
                'title' => 'Mattress Removal',
                'rate' => 125.00,
                'unit' => 'hourly',
                'description' => 'Professional mattress removal service',
                'badge_color' => 'secondary',
                'icon' => 'fas fa-bed'
            ],
            [
                'service_type' => 'hoisting',
                'title' => 'Hoisting',
                'rate' => 350.00,
                'unit' => 'hourly',
                'description' => 'Professional hoisting service',
                'badge_color' => 'dark',
                'icon' => 'fas fa-crane'
            ],
            [
                'service_type' => 'exterminator',
                'title' => 'Exterminator Service',
                'rate' => 650.00,
                'unit' => 'hourly',
                'description' => 'Exterminator, washing and replacing moving blankets',
                'badge_color' => 'purple',
                'icon' => 'fas fa-bug'
            ]
        ];

        foreach ($otherServices as $service) {
            ServiceRate::create($service);
        }
    }
}
