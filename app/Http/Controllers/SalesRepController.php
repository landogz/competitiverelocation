<?php

namespace App\Http\Controllers;

use App\Models\SalesRep;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SalesRepController extends Controller
{
    public function index()
    {
        if (auth()->user()->privilege === 'agent') {
            // Get the agent's ID from the users table
            $agentId = auth()->user()->agent_id;
            $agent = $agentId ? Agent::find($agentId) : null;
            
            if ($agent) {
                $salesReps = SalesRep::with('agent')
                    ->where('agent_id', $agent->id)
                    ->get();
            } else {
                $salesReps = collect();
            }
        } else {
            $salesReps = SalesRep::with('agent')->get();
        }
        $agents = Agent::all();
        return view('salesreps', compact('salesReps', 'agents'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'position' => 'required|string|max:255',
                'office' => 'required|string|max:255',
                'email' => 'required|email|unique:sales_reps,email|unique:users,email',
                'phone' => 'required|string|max:20',
                'agent_id' => 'required|exists:agents,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Start database transaction
            \DB::beginTransaction();

            // Create user account
            $user = \App\Models\User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => bcrypt('12345678'),
                'privilege' => 'agent',
                'agent_id' => $request->agent_id
            ]);

            // Create sales rep
            $salesRep = SalesRep::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'position' => $request->position,
                'office' => $request->office,
                'email' => $request->email,
                'phone' => $request->phone,
                'agent_id' => $request->agent_id,
                'user_id' => $user->id
            ]);

            // Commit transaction
            \DB::commit();

            // Send welcome email
            $agent = \App\Models\Agent::find($request->agent_id);
            $companyName = $agent ? $agent->company_name : null;
            \Mail::to($request->email)->send(new \App\Mail\SalesRepWelcomeMail($request->email, '12345678', $companyName));

            return response()->json([
                'success' => true,
                'message' => 'Sales representative created successfully',
                'salesRep' => $salesRep->load('agent')
            ]);

        } catch (\Exception $e) {
            // Rollback transaction on error
            \DB::rollBack();
            
            \Log::error('Error creating sales rep: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(SalesRep $salesRep)
    {
        return response()->json([
            'success' => true,
            'salesRep' => $salesRep->load('agent')
        ]);
    }

    public function update(Request $request, SalesRep $salesRep)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'office' => 'required|string|max:255',
            'email' => 'required|email|unique:sales_reps,email,' . $salesRep->id,
            'phone' => 'required|string|max:20',
            'agent_id' => 'required|exists:agents,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Update user account
        if ($salesRep->user) {
            $salesRep->user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'agent_id' => $request->agent_id
            ]);
        }

        $salesRep->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Sales representative updated successfully',
            'salesRep' => $salesRep->load('agent')
        ]);
    }

    public function destroy(SalesRep $salesRep)
    {
        // Delete associated user account if exists
        if ($salesRep->user) {
            $salesRep->user->delete();
        }

        $salesRep->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sales representative deleted successfully'
        ]);
    }

    public function getAgents()
    {
        $agents = Agent::select('id', 'company_name')->get();
        return response()->json($agents);
    }

    public function resetPassword($id)
    {
        $salesRep = \App\Models\SalesRep::with('user', 'agent')->findOrFail($id);
        if ($salesRep->user) {
            $salesRep->user->password = bcrypt('12345678');
            $salesRep->user->save();

            // Send password reset success email
            $email = $salesRep->user->email;
            $company = $salesRep->agent ? $salesRep->agent->company_name : null;
            \Mail::to($email)->send(new \App\Mail\SalesRepPasswordResetMail($email, '12345678', $company));

            return response()->json(['success' => true, 'message' => 'Password reset to 12345678']);
        } else {
            return response()->json(['success' => false, 'message' => 'No user account associated with this sales rep.'], 404);
        }
    }
} 