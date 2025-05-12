<?php

namespace App\Http\Controllers;

use App\Models\SentEmail;
use App\Models\Transaction;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);
        return response()->json($transaction);
    }

    public function sendEmail(Request $request, $id)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'recipient' => 'required|email',
                'subject' => 'required|string',
                'message' => 'required|string',
                'template_id' => 'required|integer'
            ]);

            $transaction = Transaction::findOrFail($id);
            
            // Create sent email record
            $sentEmail = SentEmail::create([
                'transaction_id' => $transaction->id,
                'recipient' => $validated['recipient'],
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'template_id' => $validated['template_id'],
                'user_id' => auth()->id(),
                'status' => 'pending' // Set initial status as pending
            ]);

            try {
                // Process images in the message
                $message = $validated['message'];
                $images = [];
                
                // Find all base64 images in the message
                preg_match_all('/<img[^>]+src="(data:image\/[^;]+;base64,[^"]+)"/', $message, $matches);
                
                if (!empty($matches[1])) {
                    foreach ($matches[1] as $index => $base64Image) {
                        // Extract image data
                        $imageData = explode(',', $base64Image);
                        $imageType = explode(';', explode(':', $imageData[0])[1])[0];
                        $imageContent = base64_decode($imageData[1]);
                        
                        // Generate unique filename
                        $filename = 'image_' . time() . '_' . $index . '.' . explode('/', $imageType)[1];
                        
                        // Store image temporarily
                        $tempPath = storage_path('app/temp/' . $filename);
                        file_put_contents($tempPath, $imageContent);
                        
                        // Add to images array
                        $images[] = [
                            'path' => $tempPath,
                            'name' => $filename,
                            'type' => $imageType
                        ];
                        
                        // Replace base64 image with CID in message
                        $message = str_replace(
                            $base64Image,
                            'cid:' . $filename,
                            $message
                        );
                    }
                }

                // Send the actual email using Laravel's Mail facade
                Mail::send([], [], function ($message) use ($validated, $images) {
                    $message->to($validated['recipient'])
                           ->subject($validated['subject'])
                           ->html($validated['message']);

                    // Attach images
                    foreach ($images as $image) {
                        $message->attach($image['path'], [
                            'as' => $image['name'],
                            'mime' => $image['type']
                        ]);
                    }
                });

                // Clean up temporary files
                foreach ($images as $image) {
                    if (file_exists($image['path'])) {
                        unlink($image['path']);
                    }
                }

                // Update status to sent if email was sent successfully
                $sentEmail->update(['status' => 'sent']);

                return response()->json([
                    'success' => true,
                    'message' => 'Email sent successfully'
                ]);
            } catch (\Exception $mailError) {
                // Update status to failed if email sending failed
                $sentEmail->update(['status' => 'failed']);
                
                \Log::error('Email sending failed: ' . $mailError->getMessage());
                \Log::error('Email configuration: ' . json_encode([
                    'mail_driver' => config('mail.default'),
                    'mail_host' => config('mail.mailers.smtp.host'),
                    'mail_port' => config('mail.mailers.smtp.port'),
                    'mail_username' => config('mail.mailers.smtp.username'),
                    'mail_encryption' => config('mail.mailers.smtp.encryption'),
                ]));

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send email: ' . $mailError->getMessage()
                ], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Email validation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Transaction not found: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('General error in sendEmail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSentEmails($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            $sentEmails = $transaction->sentEmails()
                ->with('template')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $sentEmails
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching sent emails: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch sent emails: ' . $e->getMessage()
            ], 500);
        }
    }
} 