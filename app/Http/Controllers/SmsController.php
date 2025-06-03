<?php

namespace App\Http\Controllers;

use App\Models\SmsSetting;
use App\Models\SmsLog;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Exception;

class SmsController extends Controller
{
    protected $twilioClient;

    public function __construct()
    {
        $this->initializeTwilioClient();
    }

    protected function initializeTwilioClient()
    {
        $settings = SmsSetting::where('is_active', true)->first();
        
        if ($settings) {
            try {
                $this->twilioClient = new Client($settings->public_key, $settings->secret_key);
            } catch (Exception $e) {
                $this->twilioClient = null;
            }
        }
    }

    public function index()
    {
        $settings = SmsSetting::first();
        $logs = collect();

        // Try to get logs from Twilio if configured
        if ($this->twilioClient) {
            try {
                $messages = $this->twilioClient->messages->read([], 50); // Get last 50 messages
                foreach ($messages as $message) {
                    $logs->push((object) [
                        'transaction_id' => $message->sid,
                        'message' => $message->body,
                        'recipient' => $message->to,
                        'from' => $message->from,
                        'status' => $message->status,
                        'created_at' => $message->dateCreated,
                        'direction' => $message->direction,
                        'price' => $message->price,
                        'price_unit' => $message->priceUnit,
                        'error_code' => $message->errorCode,
                        'error_message' => $message->errorMessage
                    ]);
                }
            } catch (\Exception $e) {
                // If Twilio fails, fall back to local logs
                $logs = SmsLog::latest()->get();
            }
        } else {
            $logs = SmsLog::latest()->get();
        }

        return view('sms', compact('settings', 'logs'));
    }

    protected function validateTwilioCredentials($accountSid, $authToken)
    {
        try {
            $testClient = new Client($accountSid, $authToken);
            
            // Try to fetch account details to verify credentials
            $account = $testClient->api->v2010->accounts($accountSid)->fetch();
            
            // Check account status
            if ($account->status !== 'active') {
                throw new Exception('Twilio account is not active. Current status: ' . $account->status);
            }

            return [
                'success' => true,
                'client' => $testClient,
                'account' => $account
            ];
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            
            // Make error messages more user-friendly
            if (strpos($errorMessage, 'authenticate') !== false) {
                $errorMessage = 'Invalid Twilio credentials. Please check your Account SID and Auth Token.';
            } elseif (strpos($errorMessage, '[HTTP 404]') !== false) {
                $errorMessage = 'Account not found. Please check your Account SID.';
            } elseif (strpos($errorMessage, 'Connection refused') !== false || 
                     strpos($errorMessage, 'Could not resolve host') !== false) {
                $errorMessage = 'Network error. Please check your internet connection and try again.';
            }

            return [
                'success' => false,
                'message' => $errorMessage
            ];
        }
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'public_key' => 'required|string|min:34|max:34', // Twilio Account SID is always 34 characters
            'secret_key' => 'required|string|min:32', // Twilio Auth Token is at least 32 characters
            'phone_number' => 'required|string|regex:/^\+1\d{10}$/' // Twilio phone number format
        ], [
            'public_key.min' => 'The Account SID should be exactly 34 characters long.',
            'public_key.max' => 'The Account SID should be exactly 34 characters long.',
            'secret_key.min' => 'The Auth Token should be at least 32 characters long.',
            'phone_number.regex' => 'The phone number should be in the format: +1XXXXXXXXXX'
        ]);

        // Validate Twilio credentials
        $validation = $this->validateTwilioCredentials(
            $request->public_key,
            $request->secret_key
        );

        if (!$validation['success']) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $validation['message']
                ], 422);
            }
            
            return redirect()->route('sms.index')
                ->with('error', $validation['message']);
        }

        // If validation successful, save the settings
        $settings = SmsSetting::first() ?? new SmsSetting();
        $settings->public_key = $request->public_key;
        $settings->secret_key = $request->secret_key;
        $settings->phone_number = $request->phone_number;
        $settings->is_active = true;
        $settings->save();

        // Update the Twilio client
        $this->twilioClient = $validation['client'];

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'SMS settings updated successfully! Account status: ' . $validation['account']->status,
                'account_info' => [
                    'friendly_name' => $validation['account']->friendlyName,
                    'status' => $validation['account']->status,
                    'type' => $validation['account']->type
                ]
            ]);
        }

        return redirect()->route('sms.index')
            ->with('success', 'SMS settings updated successfully! Account status: ' . $validation['account']->status);
    }

    public function sendSms(Request $request)
    {
        $request->validate([
            'to' => 'required|string',
            'message' => 'required|string'
        ]);

        if (!$this->twilioClient) {
            return response()->json([
                'success' => false,
                'message' => 'SMS service is not configured properly'
            ], 422);
        }

        // Get the Twilio phone number from settings
        $settings = SmsSetting::where('is_active', true)->first();
        if (!$settings || !$settings->phone_number) {
            return response()->json([
                'success' => false,
                'message' => 'Twilio phone number is not configured'
            ], 422);
        }

        try {
            $message = $this->twilioClient->messages->create(
                $request->to,
                [
                    'from' => $settings->phone_number,
                    'body' => $request->message
                ]
            );

            // Log the SMS
            SmsLog::create([
                'transaction_id' => $message->sid,
                'message' => $request->message,
                'status' => $message->status,
                'recipient' => $request->to,
                'response_data' => $message->toArray()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'SMS sent successfully!',
                'data' => $message->toArray()
            ]);
        } catch (Exception $e) {
            // Log the failed attempt
            SmsLog::create([
                'transaction_id' => 'FAILED_' . time(),
                'message' => $request->message,
                'status' => 'failed',
                'recipient' => $request->to,
                'response_data' => ['error' => $e->getMessage()]
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send SMS: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getLogs()
    {
        if (!$this->twilioClient) {
            return response()->json([
                'success' => false,
                'message' => 'SMS service is not configured properly'
            ], 422);
        }

        try {
            // Get messages from Twilio
            $messages = $this->twilioClient->messages->read([], 50); // Get last 50 messages
            
            $logs = [];
            foreach ($messages as $message) {
                $logs[] = [
                    'transaction_id' => $message->sid,
                    'message' => $message->body,
                    'recipient' => $message->to,
                    'from' => $message->from,
                    'status' => $message->status,
                    'created_at' => $message->dateCreated->format('Y-m-d H:i:s'),
                    'direction' => $message->direction,
                    'price' => $message->price,
                    'price_unit' => $message->priceUnit,
                    'error_code' => $message->errorCode,
                    'error_message' => $message->errorMessage
                ];

                // Update or create local log
                SmsLog::updateOrCreate(
                    ['transaction_id' => $message->sid],
                    [
                        'message' => $message->body,
                        'status' => $message->status,
                        'recipient' => $message->to,
                        'response_data' => $message->toArray()
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'data' => $logs
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch SMS logs: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkStatus($transactionId)
    {
        if (!$this->twilioClient) {
            return response()->json([
                'success' => false,
                'message' => 'SMS service is not configured properly'
            ], 422);
        }

        try {
            $message = $this->twilioClient->messages($transactionId)->fetch();
            
            // Update the log
            $log = SmsLog::where('transaction_id', $transactionId)->first();
            if ($log) {
                $log->status = $message->status;
                $log->response_data = $message->toArray();
                $log->save();
            }

            return response()->json([
                'success' => true,
                'status' => $message->status,
                'data' => $message->toArray()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check status: ' . $e->getMessage()
            ], 500);
        }
    }
}
