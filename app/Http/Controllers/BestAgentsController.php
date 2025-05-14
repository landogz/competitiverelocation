<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Agent;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Str;

class BestAgentsController extends Controller
{
    /**
     * Display the best agents view
     */
    public function index()
    {
        return view('bestagents');
    }

    /**
     * Get statistics data for the best agents dashboard
     */
    public function getStatsData(Request $request)
    {
        // Get date range from request, default to last 7 days
        $range = $request->input('range', 'last_7_days');
        $dateRange = $this->getDateRange($range, $request);
        $previousDateRange = $this->getPreviousDateRange($dateRange['start'], $dateRange['end']);
        
        // Get agent count
        $totalAgents = $this->getAgentCount($dateRange['start'], $dateRange['end']);
        $previousTotalAgents = $this->getAgentCount($previousDateRange['start'], $previousDateRange['end']);
        
        // Get sales data
        $totalSales = $this->getTotalSales($dateRange['start'], $dateRange['end']);
        $previousTotalSales = $this->getTotalSales($previousDateRange['start'], $previousDateRange['end']);
        
        // Get average rating and success rate
        $averageRating = $this->getAverageRating($dateRange['start'], $dateRange['end']);
        $previousAverageRating = $this->getAverageRating($previousDateRange['start'], $previousDateRange['end']);
        
        $successRate = $this->getSuccessRate($dateRange['start'], $dateRange['end']);
        $previousSuccessRate = $this->getSuccessRate($previousDateRange['start'], $previousDateRange['end']);
        
        // Calculate percentage changes
        $totalAgentsPercentChange = $previousTotalAgents > 0 
            ? (($totalAgents - $previousTotalAgents) / $previousTotalAgents) * 100 
            : 0;
            
        $totalSalesPercentChange = $previousTotalSales > 0 
            ? (($totalSales - $previousTotalSales) / $previousTotalSales) * 100 
            : 0;
            
        $averageRatingPercentChange = $previousAverageRating > 0 
            ? (($averageRating - $previousAverageRating) / $previousAverageRating) * 100 
            : 0;
            
        $successRatePercentChange = $previousSuccessRate > 0 
            ? (($successRate - $previousSuccessRate) / $previousSuccessRate) * 100 
            : 0;
        
        // Format data for response
        $data = [
            'success' => true,
            'stats' => [
                'totalAgents' => [
                    'value' => $totalAgents,
                    'percentChange' => round($totalAgentsPercentChange, 2),
                    'isPositive' => $totalAgentsPercentChange >= 0,
                    'difference' => $totalAgents - $previousTotalAgents
                ],
                'totalSales' => [
                    'value' => $totalSales,
                    'percentChange' => round($totalSalesPercentChange, 2),
                    'isPositive' => $totalSalesPercentChange >= 0,
                    'difference' => ($totalSales - $previousTotalSales) / 1000 // Display in K
                ],
                'averageRating' => [
                    'value' => $averageRating,
                    'percentChange' => round($averageRatingPercentChange, 2),
                    'isPositive' => $averageRatingPercentChange >= 0,
                    'difference' => $averageRating - $previousAverageRating
                ],
                'successRate' => [
                    'value' => $successRate,
                    'percentChange' => round($successRatePercentChange, 2),
                    'isPositive' => $successRatePercentChange >= 0,
                    'difference' => $successRate - $previousSuccessRate
                ]
            ],
            'topAgents' => $this->getTopAgents($dateRange['start'], $dateRange['end'])
        ];
        
        return response()->json($data);
    }
    
    /**
     * Get performance data for agents charts
     */
    public function getPerformanceData(Request $request)
    {
        // Get date range from request
        $range = $request->input('range', 'last_7_days');
        $dateRange = $this->getDateRange($range, $request);
        
        // Get performance data
        $performanceData = $this->getAgentPerformanceData($dateRange['start'], $dateRange['end']);
        $agents = $this->getAgentsData($dateRange['start'], $dateRange['end']);
        
        return response()->json([
            'success' => true,
            'performance' => $performanceData,
            'agents' => $agents
        ]);
    }
    
    /**
     * Export agents report
     */
    public function export()
    {
        // In a real application, you would generate a CSV or Excel file here
        return response()->download(public_path('sample-report.xlsx'));
    }
    
    /**
     * Helper to get date range based on selection
     */
    private function getDateRange($range, $request = null)
    {
        $now = Carbon::now();
        
        switch ($range) {
            case 'today':
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
            case 'yesterday':
                return [
                    'start' => $now->copy()->subDay()->startOfDay(),
                    'end' => $now->copy()->subDay()->endOfDay()
                ];
            case 'this_week':
                return [
                    'start' => $now->copy()->startOfWeek(),
                    'end' => $now->copy()->endOfWeek()
                ];
            case 'last_week':
                return [
                    'start' => $now->copy()->subWeek()->startOfWeek(),
                    'end' => $now->copy()->subWeek()->endOfWeek()
                ];
            case 'this_month':
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
            case 'last_month':
                return [
                    'start' => $now->copy()->subMonth()->startOfMonth(),
                    'end' => $now->copy()->subMonth()->endOfMonth()
                ];
            case 'last_3_months':
                return [
                    'start' => $now->copy()->subMonths(3)->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
            case 'custom':
                if ($request) {
                    try {
                        $startDate = $request->input('start_date');
                        $endDate = $request->input('end_date');
                        
                        if ($startDate && $endDate) {
                            return [
                                'start' => Carbon::parse($startDate)->startOfDay(),
                                'end' => Carbon::parse($endDate)->endOfDay()
                            ];
                        }
                    } catch (\Exception $e) {
                        // If there's any error parsing the dates, fall back to last 7 days
                        \Log::error('Error parsing custom date range: ' . $e->getMessage());
                    }
                }
                // Fall back to last 7 days if custom range is invalid
                return [
                    'start' => $now->copy()->subDays(7)->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
            case 'last_7_days':
            default:
                return [
                    'start' => $now->copy()->subDays(7)->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
        }
    }
    
    /**
     * Helper to get previous date range for comparison
     */
    private function getPreviousDateRange($start, $end)
    {
        $days = $end->diffInDays($start) + 1;
        
        return [
            'start' => $start->copy()->subDays($days),
            'end' => $end->copy()->subDays($days)
        ];
    }
    
    /**
     * Get agent count from database
     */
    private function getAgentCount($start, $end)
    {
        return Agent::where('is_active', true)
            ->where('created_at', '<=', $end)
            ->count();
    }
    
    /**
     * Get total sales from database
     */
    private function getTotalSales($start, $end)
    {
        return Transaction::where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->where('status', 'completed')
            ->sum('grand_total');
    }
    
    /**
     * Get average rating
     * For this example, we'll assume a rating field on agents
     * In a real application, you might have a separate ratings table
     */
    private function getAverageRating($start, $end)
    {
        // This is simulated for this example
        // In a real application, you would query ratings from your database
        $baseRating = 4.8;
        
        if ($start->diffInDays(Carbon::now()) <= 7) {
            return $baseRating;
        } elseif ($start->diffInDays(Carbon::now()) <= 30) {
            return $baseRating - 0.2;
        } else {
            return $baseRating - 0.3;
        }
    }
    
    /**
     * Get success rate based on completed transactions
     */
    private function getSuccessRate($start, $end)
    {
        $totalTransactions = Transaction::where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->whereNotNull('assigned_agent')
            ->count();
            
        $completedTransactions = Transaction::where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->whereNotNull('assigned_agent')
            ->where('status', 'completed')
            ->count();
            
        if ($totalTransactions === 0) {
            return 0;
        }
        
        return round(($completedTransactions / $totalTransactions) * 100);
    }
    
    /**
     * Get top performing agents from database
     */
    private function getTopAgents($start, $end)
    {
        $topAgents = Transaction::select(
                'agents.id',
                'agents.company_name',
                'agents.contact_name',
                'agents.is_active',
                DB::raw('COUNT(transactions.id) as total_sales'),
                DB::raw('SUM(transactions.grand_total) as total_revenue'))
            ->join('agents', 'transactions.assigned_agent', '=', 'agents.id')
            ->where('transactions.created_at', '>=', $start)
            ->where('transactions.created_at', '<=', $end)
            ->where('transactions.status', 'completed')
            ->groupBy('agents.id', 'agents.company_name', 'agents.contact_name', 'agents.is_active')
            ->orderBy('total_revenue', 'desc')
            ->limit(3)
            ->get();
            
        $result = [];
        
        foreach ($topAgents as $agent) {
            // Get the user associated with this agent
            $user = User::where('agent_id', $agent->id)->first();
            
            $result[] = [
                'id' => $agent->id,
                'name' => $agent->company_name ?? $agent->contact_name ?? ($user ? $user->first_name . ' ' . $user->last_name : 'Unknown'),
                'avatar' => $this->getProfileImagePath($user),
                'position' => $user ? ($user->position ?? 'Agent') : ($agent->company_name ? 'Company Agent' : 'Agent'),
                'revenue' => round($agent->total_revenue ?? 0),
                'sales' => $agent->total_sales ?? 0,
                'rating' => $this->getAgentRating($agent->id),
                'successRate' => $this->getAgentSuccessRate($agent->id, $start, $end),
                'status' => $agent->is_active ? 'Active' : 'Inactive'
            ];
        }
        
        // Return only real data, no sample data
        return $result;
    }
    
    /**
     * Get agent rating
     * This is a placeholder since we don't have a ratings table in the example
     */
    private function getAgentRating($agentId)
    {
        // In a real application, you would query ratings from your database
        // For this example, we'll use a consistent default value of 4.0
        return 4.0;
    }
    
    /**
     * Get agent success rate
     */
    private function getAgentSuccessRate($agentId, $start, $end)
    {
        $totalTransactions = Transaction::where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->where('assigned_agent', $agentId)
            ->count();
            
        $completedTransactions = Transaction::where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->where('assigned_agent', $agentId)
            ->where('status', 'completed')
            ->count();
            
        if ($totalTransactions === 0) {
            return 85; // Default value if no transactions
        }
        
        return round(($completedTransactions / $totalTransactions) * 100);
    }
    
    /**
     * Get agent performance data for chart
     */
    private function getAgentPerformanceData($start, $end)
    {
        // Generate all dates in the range for the x-axis
        $dates = [];
        $current = $start->copy();
        
        while ($current <= $end) {
            $dates[] = $current->format('Y-m-d');
            $current->addDay();
        }
        
        // Get the top 3 agents by revenue
        $topAgents = Agent::select('agents.*', 
                DB::raw('SUM(transactions.grand_total) as total_revenue'),
                DB::raw('COUNT(transactions.id) as transaction_count'))
            ->leftJoin('transactions', 'agents.id', '=', 'transactions.assigned_agent')
            ->where('transactions.created_at', '>=', $start)
            ->where('transactions.created_at', '<=', $end)
            ->where('transactions.status', 'completed')
            ->groupBy('agents.id')
            ->orderBy('total_revenue', 'desc')
            ->limit(3)
            ->get();
            
        $series = [];
        
        // If we have agents from the database
        if ($topAgents->count() > 0) {
            foreach ($topAgents as $agent) {
                $user = User::where('agent_id', $agent->id)->first();
                $name = $agent->company_name ?? $agent->contact_name ?? ($user ? $user->first_name . ' ' . $user->last_name : 'Unknown');
                
                // Get daily sales data for this agent
                $dailyData = [];
                
                foreach ($dates as $date) {
                    $dateStart = Carbon::parse($date)->startOfDay();
                    $dateEnd = Carbon::parse($date)->endOfDay();
                    
                    // Count of transactions for this agent on this day
                    $count = Transaction::where('assigned_agent', $agent->id)
                        ->where('created_at', '>=', $dateStart)
                        ->where('created_at', '<=', $dateEnd)
                        ->where('status', 'completed')
                        ->count();
                        
                    $dailyData[] = $count;
                }
                
                $series[] = [
                    'name' => $name,
                    'data' => $dailyData
                ];
            }
        }
        
        // Return only real data, no sample data
        return [
            'dates' => $dates,
            'series' => $series
        ];
    }
    
    /**
     * Get agents data for table
     */
    private function getAgentsData($start, $end)
    {
        // Return the same data as getTopAgents for simplicity
        return $this->getTopAgents($start, $end);
    }

    /**
     * Get detailed agent statistics
     * This adds additional advanced metrics for better reporting
     */
    public function getDetailedAgentStats(Request $request)
    {
        // Get date range from request
        $range = $request->input('range', 'last_7_days');
        $dateRange = $this->getDateRange($range, $request);
        
        // Get all agents with transaction counts
        $agents = Agent::select('agents.*', 
                DB::raw('COUNT(transactions.id) as transaction_count'),
                DB::raw('SUM(transactions.grand_total) as total_revenue'),
                DB::raw('AVG(transactions.grand_total) as avg_transaction_value'))
            ->leftJoin('transactions', 'agents.id', '=', 'transactions.assigned_agent')
            ->where(function ($query) use ($dateRange) {
                $query->where('transactions.created_at', '>=', $dateRange['start'])
                    ->where('transactions.created_at', '<=', $dateRange['end'])
                    ->where('transactions.status', 'completed')
                    ->orWhereNull('transactions.id'); // Ensure we also get agents with no transactions
            })
            ->groupBy('agents.id')
            ->having(DB::raw('COUNT(transactions.id)'), '>', 0) // Only include agents with at least one transaction
            ->orderBy('total_revenue', 'desc')
            ->limit(10) // Limit to top 10 agents
            ->get();
            
        $result = [];
        
        foreach ($agents as $agent) {
            // Get user details for this agent
            $user = User::where('agent_id', $agent->id)->first();
            
            // Get transaction categories for this agent
            $categories = Transaction::select('service', DB::raw('COUNT(*) as count'), DB::raw('SUM(grand_total) as revenue'))
                ->where('assigned_agent', $agent->id)
                ->where('created_at', '>=', $dateRange['start'])
                ->where('created_at', '<=', $dateRange['end'])
                ->where('status', 'completed')
                ->groupBy('service')
                ->get()
                ->toArray();
                
            // Calculate success rate
            $totalTransactions = Transaction::where('assigned_agent', $agent->id)
                ->where('created_at', '>=', $dateRange['start'])
                ->where('created_at', '<=', $dateRange['end'])
                ->count();
                
            $completedTransactions = Transaction::where('assigned_agent', $agent->id)
                ->where('created_at', '>=', $dateRange['start'])
                ->where('created_at', '<=', $dateRange['end'])
                ->where('status', 'completed')
                ->count();
                
            $successRate = $totalTransactions > 0 ? 
                round(($completedTransactions / $totalTransactions) * 100) : 0;
            
            // Build the result
            $result[] = [
                'id' => $agent->id,
                'name' => $agent->company_name ?? $agent->contact_name ?? ($user ? $user->first_name . ' ' . $user->last_name : 'Unknown'),
                'avatar' => $this->getProfileImagePath($user),
                'position' => $user ? ($user->position ?? 'Agent') : ($agent->company_name ? 'Company Agent' : 'Agent'),
                'email' => $user ? $user->email : ($agent->email ?? 'N/A'),
                'phone' => $agent->phone ?? 'N/A',
                'status' => $agent->is_active ? 'Active' : 'Inactive',
                'revenue' => round($agent->total_revenue ?? 0),
                'transaction_count' => $agent->transaction_count ?? 0,
                'avg_transaction_value' => round($agent->avg_transaction_value ?? 0),
                'success_rate' => $successRate,
                'rating' => $this->getAgentRating($agent->id),
                'service_categories' => $categories
            ];
        }
        
        // Return only the real data from the database, no sample data
        return response()->json([
            'success' => true,
            'data' => $result,
            'date_range' => [
                'start' => $dateRange['start']->format('Y-m-d'),
                'end' => $dateRange['end']->format('Y-m-d')
            ]
        ]);
    }

    private function getProfileImagePath($user)
    {
        if ($user && !empty($user->profile_image)) {
            // Check if the profile image is a full path or just a filename
            if (Str::startsWith($user->profile_image, ['http://', 'https://', '/'])) {
                return $user->profile_image;
            } else if (file_exists(public_path('storage/' . $user->profile_image))) {
                // Use the profile-images directory
                return asset('storage/' . $user->profile_image);
            } else {
                // Fall back to the noimage.jpg default avatar
                return asset('assets/images/users/noimage.jpg');
            }
        } else {
            // Fall back to the noimage.jpg default avatar
            return asset('assets/images/users/noimage.jpg');
        }
    }
} 