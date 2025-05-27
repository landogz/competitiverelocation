<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Mail\TestEmailTemplate;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomEmail;
use Illuminate\Support\Facades\Storage;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::all();
        $placeholders = $this->getAvailablePlaceholders();
        return view('email-templates.index', compact('templates', 'placeholders'));
    }

    public function create()
    {
        return view('email-templates.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'subject' => 'required|string|max:255',
                'content' => 'required|string',
                'description' => 'nullable|string|max:255',
            ]);

            $template = EmailTemplate::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Email template created successfully',
                'template' => $template
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function show(EmailTemplate $template)
    {
        return response()->json($template);
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        return view('email-templates.edit', compact('emailTemplate'));
    }

    public function update(Request $request, EmailTemplate $template)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'subject' => 'required|string|max:255',
                'content' => 'required|string',
                'description' => 'nullable|string|max:255',
            ]);

            $template->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Email template updated successfully',
                'template' => $template->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function destroy(EmailTemplate $template)
    {
        try {
            $template->delete();
            return response()->json([
                'success' => true,
                'message' => 'Email template deleted successfully',
                'template' => $template
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function preview(EmailTemplate $emailTemplate)
    {
        return view('email-templates.preview', compact('emailTemplate'));
    }

    public function test(Request $request, EmailTemplate $template)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'recipient_email' => 'required|email',
                'subject' => 'required|string',
                'content' => 'required|string'
            ]);

            // Create a temporary template with the edited content and subject
            $tempTemplate = clone $template;
            $tempTemplate->content = $validated['content'];
            $tempTemplate->subject = $validated['subject'];

            // Send email
            Mail::to($validated['recipient_email'])
                ->send(new TestEmailTemplate($tempTemplate));

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function sendCustomEmail(Request $request)
    {
        try {
            \Log::info('Incoming request data:', [
                'to' => $request->input('to'),
                'cc' => $request->input('cc'),
                'subject' => $request->input('subject'),
                'hasBody' => $request->has('body'),
                'hasAttachments' => $request->hasFile('attachments'),
                'files' => $request->hasFile('attachments') ? array_map(function($file) {
                    return [
                        'name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'mime' => $file->getMimeType(),
                        'error' => $file->getError()
                    ];
                }, $request->file('attachments')) : []
            ]);

            // Validate request
            $validated = $request->validate([
                'to' => 'required|email',
                'cc' => 'nullable|string',
                'subject' => 'required|string|max:255',
                'body' => 'required|string',
                'attachments.*' => 'nullable|file|max:10240' // 10MB max per file
            ]);

            // Process CC emails
            $ccEmails = [];
            if (!empty($validated['cc'])) {
                $ccEmails = array_filter(
                    array_map('trim', explode(',', $validated['cc'])),
                    function($email) {
                        $isValid = filter_var($email, FILTER_VALIDATE_EMAIL);
                        if (!$isValid) {
                            \Log::warning('Invalid CC email address:', ['email' => $email]);
                        }
                        return $isValid;
                    }
                );

                \Log::info('Processed CC emails:', [
                    'original' => $validated['cc'],
                    'validEmails' => $ccEmails
                ]);
            }

            // Process attachments
            $attachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $index => $file) {
                    try {
                        \Log::info('Processing attachment ' . ($index + 1) . ':', [
                            'name' => $file->getClientOriginalName(),
                            'size' => $file->getSize(),
                            'mime' => $file->getMimeType(),
                            'error' => $file->getError(),
                            'path' => $file->getPathname(),
                            'realPath' => $file->getRealPath()
                        ]);

                        // Ensure the storage directory exists
                        if (!Storage::exists('email-attachments')) {
                            Storage::makeDirectory('email-attachments');
                        }

                        // Test if we can write to the directory
                        $testPath = 'email-attachments/test_' . time() . '.txt';
                        if (!Storage::put($testPath, 'test')) {
                            throw new \Exception('Cannot write to storage directory');
                        }
                        Storage::delete($testPath);

                        $path = $file->store('email-attachments');
                        if ($path) {
                            $attachments[] = $path;
                            \Log::info('Attachment stored successfully:', [
                                'path' => $path,
                                'fullPath' => Storage::path($path)
                            ]);
                        } else {
                            throw new \Exception('Failed to store attachment');
                        }
                    } catch (\Exception $e) {
                        \Log::error('Error storing attachment:', [
                            'error' => $e->getMessage(),
                            'file' => $file->getClientOriginalName(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        // Continue processing other attachments even if one fails
                        continue;
                    }
                }
            }

            \Log::info('Sending email with data:', [
                'to' => $validated['to'],
                'cc' => $ccEmails,
                'subject' => $validated['subject'],
                'attachments' => $attachments
            ]);

            // Send email
            Mail::to($validated['to'])
                ->send(new CustomEmail(
                    $validated['subject'],
                    $validated['body'],
                    $ccEmails,
                    $attachments
                ));

            \Log::info('Email sent successfully');

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error:', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error sending email:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getAvailablePlaceholders()
    {
        return [
            'Transaction' => [
                'First Name' => '{firstname}',
                'Last Name' => '{lastname}',
                'Email' => '{email}',
                'Phone' => '{phone}',
                'Lead Source' => '{lead_source}',
                'Lead Type' => '{lead_type}',
                'Assigned Agent' => '{assigned_agent}',
                'Sales Name' => '{sales_name}',
                'Sales Email' => '{sales_email}',
                'Sales Location' => '{sales_location}',
                'Date' => '{date}',
                'Pickup Location' => '{pickup_location}',
                'Delivery Location' => '{delivery_location}',
                'Miles' => '{miles}',
                'Add Mile' => '{add_mile}',
                'Mile Rate' => '{mile_rate}',
                'Service' => '{service}',
                'Service Rate' => '{service_rate}',
                'No of Items' => '{no_of_items}',
                'No of Crew' => '{no_of_crew}',
                'Crew Rate' => '{crew_rate}',
                'Delivery Rate' => '{delivery_rate}',
                'Subtotal' => '{subtotal}',
                'Software Fee' => '{software_fee}',
                'Truck Fee' => '{truck_fee}',
                'Downpayment' => '{downpayment}',
                'Grand Total' => '{grand_total}',
                'Payment ID' => '{payment_id}',
                'Uploaded Image' => '{uploaded_image}',
                'Services' => '{services}',
                'Status' => '{status}',
                'Insurance Number' => '{insurance_number}',
                'Insurance Document' => '{insurance_document}',
                'Last Synced At' => '{last_synced_at}',
                'Created At' => '{created_at}',
                'Updated At' => '{updated_at}',
            ],
            'Lead' => [
                'ID' => '{id}',
                'Transaction ID' => '{transaction_id}',
                'Name' => '{name}',
                'Phone' => '{phone}',
                'Email' => '{email}',
                'Company' => '{company}',
                'Notes' => '{notes}',
                'Status' => '{status}',
                'Source' => '{source}',
                'Created At' => '{created_at}',
                'Updated At' => '{updated_at}',
                'Sales Name' => '{sales_name}',
                'Sales Email' => '{sales_email}',
                'Sales Location' => '{sales_location}',
                'Pickup Location' => '{pickup_location}',
                'Delivery Location' => '{delivery_location}',
                'Miles' => '{miles}',
                'Service Rate' => '{service_rate}',
                'No of Items' => '{no_of_items}',
                'No of Crew' => '{no_of_crew}',
                'Crew Rate' => '{crew_rate}',
                'Subtotal' => '{subtotal}',
                'Software Fee' => '{software_fee}',
                'Truck Fee' => '{truck_fee}',
                'Downpayment' => '{downpayment}',
                'Grand Total' => '{grand_total}',
                'Uploaded Image' => '{uploaded_image}',
                'Date' => '{date}',
                'Service' => '{service}',
            ]
        ];
    }
} 