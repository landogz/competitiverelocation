<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function store(TransactionRequest $request)
    {
        try {
            // Split the name into firstname and lastname
            $nameParts = explode(' ', $request->name);
            $firstname = $nameParts[0];
            $lastname = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : '';

            // Format pickup and delivery locations as strings
            $pickupLocation = $request->from_areacode . ', ' . $request->from_zip . ', ' . $request->from_state . ', ' . $request->from_city;
            $deliveryLocation = $request->to_areacode . ', ' . $request->to_zip . ', ' . $request->to_state . ', ' . $request->to_city;

            // Generate transaction ID in format "001302"
            $lastTransaction = DB::table('transactions')->orderBy('id', 'desc')->first();
            $newTransactionId = $lastTransaction ? $lastTransaction->id + 1 : 1;
            $transactionId = str_pad($newTransactionId, 6, '0', STR_PAD_LEFT);

            // Create transaction record
            $transactionId = DB::table('transactions')->insertGetId([
                'transaction_id' => $transactionId,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $request->email,
                'phone' => $request->phone,
                'phone2' => $request->ext,
                'lead_type' => 'long_distance',
                'status' => 'lead',
                'date' => $request->move_date,
                'pickup_location' => $pickupLocation,
                'delivery_location' => $deliveryLocation,
                'miles' => $request->distance,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Send About Us Email (template ID 16)
            try {
                $aboutUsTemplate = \App\Models\EmailTemplate::find(16);
                if ($aboutUsTemplate) {
                    // Process base64 images in the template content
                    $content = $aboutUsTemplate->content;
                    
                    // Find all base64 images in the content
                    if (preg_match_all('/<img[^>]+src="(data:image\/[^;]+;base64,[^"]+)"/', $content, $matches)) {
                        foreach ($matches[1] as $index => $base64Src) {
                            // Extract the image data
                            list($type, $data) = explode(';', $base64Src);
                            list(, $data) = explode(',', $data);
                            
                            // Decode the base64 data
                            $imageData = base64_decode($data);
                            
                            // Generate a unique filename
                            $filename = 'image_' . time() . '_' . $index . '.png';
                            
                            // Save the image to storage
                            $path = 'profile-images/' . $filename;
                            Storage::disk('public')->put($path, $imageData);
                            
                            // Get the URL for the image with full domain
                            $imageUrl = 'https://competitiverelocationcrm.com/storage/app/public/' . $path;
                            
                            // Replace the base64 image with the URL in the content
                            $content = str_replace($base64Src, $imageUrl, $content);
                        }
                    }

                    $sentEmail = \App\Models\SentEmail::create([
                        'transaction_id' => $transactionId,
                        'recipient' => $request->email,
                        'subject' => $aboutUsTemplate->subject,
                        'message' => $content,
                        'template_id' => 16,
                        'user_id' => 1,
                        'status' => 'pending'
                    ]);

                    \Mail::to($request->email)
                        ->send(new \App\Mail\CustomEmail($aboutUsTemplate->subject, $content));

                    $sentEmail->update(['status' => 'sent']);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send About Us email: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Lead created successfully',
                'data' => [
                    'transaction_id' => $transactionId,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'ext' => $request->ext,
                    'pickup_location' => $pickupLocation,
                    'delivery_location' => $deliveryLocation,
                    'distance' => $request->distance,
                    'move_date' => $request->move_date
                ]
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Failed to create lead: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create lead: ' . $e->getMessage()
            ], 500);
        }
    }
}
