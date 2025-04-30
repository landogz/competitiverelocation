<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

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

    private function getAvailablePlaceholders()
    {
        return [
            'Transaction' => [
                'First Name' => '{firstname}',
                'Last Name' => '{lastname}',
                'Email' => '{email}',
                'Phone' => '{phone}',
                'Service' => '{service}',
                'Date' => '{date}',
                'Pickup Location' => '{pickup_location}',
                'Delivery Location' => '{delivery_location}',
                'Miles' => '{miles}',
                'Sales Name' => '{sales_name}',
                'Sales Email' => '{sales_email}',
                'Sales Location' => '{sales_location}',
                'Total Volume' => '{total_volume}',
                'Total Weight' => '{total_weight}',
                'Subtotal' => '{subtotal}',
                'Grand Total' => '{grand_total}',
                'Downpayment' => '{downpayment}',
                'Remaining Balance' => '{remaining_balance}'
            ]
        ];
    }
} 