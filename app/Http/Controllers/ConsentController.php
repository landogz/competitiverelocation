<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ConsentController extends Controller
{
    public function show()
    {
        return view('consent.show');
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|regex:/^\+?[1-9]\d{1,14}$/',
            'consent' => 'required|accepted',
        ]);

        DB::table('sms_consents')->insert([
            'phone_number' => $request->phone_number,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'consent_text' => 'I agree to receive SMS messages from Competitive Relocation',
            'created_at' => Carbon::now(),
        ]);

        return redirect()->route('consent.success');
    }

    public function success()
    {
        return view('consent.success');
    }
} 