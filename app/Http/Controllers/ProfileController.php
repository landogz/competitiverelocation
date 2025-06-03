<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
        ]);

        try {
            $user->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Profile update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating profile: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePhoto(Request $request)
    {
        try {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $user = auth()->user();

            if ($request->hasFile('photo')) {
                $image = $request->file('photo');
                
                // Create image manager with GD driver
                $manager = new ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
                
                // Resize image
                $resized = $manager->read($image)
                    ->resize(150, 150, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });

                // Generate unique filename
                $filename = 'profile-' . $user->id . '-' . time() . '.' . $image->getClientOriginalExtension();
                
                // Ensure directory exists using Storage facade
                Storage::disk('public')->makeDirectory('profile-images');

                // Delete old profile picture if exists
                if ($user->profile_image && Storage::disk('public')->exists(str_replace('/storage/app/public/', '', $user->profile_image))) {
                    Storage::disk('public')->delete(str_replace('/storage/app/public/', '', $user->profile_image));
                }

                // Save new image using Storage facade
                $path = 'profile-images/' . $filename;
                Storage::disk('public')->put($path, $resized->encode());
                
                // Update user profile image path with full storage path
                $user->profile_image = '/storage/app/public/' . $path;
                $user->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Profile photo updated successfully',
                    'photo_url' => asset('storage/app/public/' . $path)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No photo was uploaded'
            ], 400);

        } catch (\Exception $e) {
            \Log::error('Profile photo update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating profile photo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProfileImage()
    {
        $user = auth()->user();
        
        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            return Storage::url($user->profile_image);
        }
        
        // Return default no-profile image
        return Storage::url('profile-images/no-profile.png');
    }
}
