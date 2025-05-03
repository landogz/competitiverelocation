<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CustomEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $body;
    public $cc;
    public $attachments;

    public function __construct($subject, $body, $cc = [], $attachments = [])
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->cc = is_array($cc) ? $cc : [];
        $this->attachments = is_array($attachments) ? $attachments : [];
    }

    public function build()
    {
        try {
            Log::info('Building email with data:', [
                'subject' => $this->subject,
                'hasBody' => !empty($this->body),
                'ccCount' => count($this->cc),
                'ccEmails' => $this->cc,
                'attachments' => $this->attachments
            ]);

            $email = $this->subject($this->subject)
                        ->view('emails.custom')
                        ->with(['body' => $this->body]);

            // Add CC recipients if any
            if (!empty($this->cc)) {
                try {
                    $email->cc($this->cc);
                    Log::info('CC recipients added successfully:', [
                        'cc' => $this->cc
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error adding CC recipients:', [
                        'cc' => $this->cc,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    // Continue without CC if there's an error
                }
            }

            // Add attachments if any
            if (!empty($this->attachments)) {
                foreach ($this->attachments as $index => $attachment) {
                    try {
                        Log::info('Processing attachment ' . ($index + 1) . ':', [
                            'path' => $attachment,
                            'exists' => Storage::exists($attachment),
                            'size' => Storage::exists($attachment) ? Storage::size($attachment) : 0
                        ]);

                        if (Storage::exists($attachment)) {
                            $fullPath = Storage::path($attachment);
                            if (file_exists($fullPath)) {
                                $email->attachFromStorage($attachment);
                                Log::info('Attachment added successfully:', [
                                    'path' => $attachment,
                                    'fullPath' => $fullPath
                                ]);
                            } else {
                                Log::error('Attachment file does not exist:', [
                                    'path' => $attachment,
                                    'fullPath' => $fullPath
                                ]);
                            }
                        } else {
                            Log::error('Attachment not found in storage:', [
                                'path' => $attachment,
                                'storagePath' => Storage::path('')
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error('Error adding attachment:', [
                            'path' => $attachment,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        // Continue with other attachments even if one fails
                        continue;
                    }
                }
            }

            return $email;
        } catch (\Exception $e) {
            Log::error('Error building email:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
} 