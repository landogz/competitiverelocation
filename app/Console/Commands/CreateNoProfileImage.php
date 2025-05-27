<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;

class CreateNoProfileImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:no-profile-image';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the default no-profile image';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Create a new image manager with GD driver
            $manager = new ImageManager(new \Intervention\Image\Drivers\Gd\Driver());

            // Create a new image with a light gray background
            $width = 150;
            $height = 150;
            $bgColor = '#f3f4f6';
            
            // Create the image
            $image = $manager->create($width, $height);
            
            // Fill the background
            $image->fill($bgColor);

            // Ensure the directory exists
            $directory = storage_path('app/public/profile-images');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Save the image
            $image->save($directory . '/no-profile.png');

            $this->info('Default no-profile image created successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to create no-profile image: ' . $e->getMessage());
            return 1;
        }
    }
}
