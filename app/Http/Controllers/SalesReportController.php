<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Agent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
    /**
     * Display the sales reports page
     */
    public function index()
    {
        // Get summary statistics
        $stats = $this->getSummaryStats();

        // Get sales by category data
        $salesByCategory = $this->getSalesByCategory();

        // Get sales performance data
        $salesPerformance = $this->getSalesPerformance();

        // Get recent sales data
        $recentSales = $this->getRecentSales();
        
        // Get agent assignment statistics
        $agentStats = $this->getAgentAssignmentStats();

        return view('salesreports', compact('stats', 'salesByCategory', 'salesPerformance', 'recentSales', 'agentStats'));
    }

    /**
     * Get summary statistics for the dashboard
     */
    private function getSummaryStats()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Total transactions count - add completed status filter
        $totalSales = Transaction::whereMonth('created_at', $currentMonth->month)
            ->where('status', 'completed')
            ->count();
        $lastMonthSales = Transaction::whereMonth('created_at', $lastMonth->month)
            ->where('status', 'completed')
            ->count();
        $salesPercentChange = $this->calculatePercentChange($lastMonthSales, $totalSales);

        // Total revenue - add completed status filter
        $totalRevenue = Transaction::whereMonth('created_at', $currentMonth->month)
            ->where('status', 'completed')
            ->sum('grand_total');
        $lastMonthRevenue = Transaction::whereMonth('created_at', $lastMonth->month)
            ->where('status', 'completed')
            ->sum('grand_total');
        $revenuePercentChange = $this->calculatePercentChange($lastMonthRevenue, $totalRevenue);
        $revenueDifference = $totalRevenue - $lastMonthRevenue;

        // New customers (unique customers this month) - add completed status filter
        $newCustomers = Transaction::whereMonth('created_at', $currentMonth->month)
            ->where('status', 'completed')
            ->distinct('email')
            ->count('email');
        $lastMonthCustomers = Transaction::whereMonth('created_at', $lastMonth->month)
            ->where('status', 'completed')
            ->distinct('email')
            ->count('email');
        $customersPercentChange = $this->calculatePercentChange($lastMonthCustomers, $newCustomers);
        $customersDifference = $newCustomers - $lastMonthCustomers;

        // Average order value
        $avgOrderValue = $totalSales > 0 ? $totalRevenue / $totalSales : 0;
        $lastMonthAvgOrderValue = $lastMonthSales > 0 ? $lastMonthRevenue / $lastMonthSales : 0;
        $avgOrderPercentChange = $this->calculatePercentChange($lastMonthAvgOrderValue, $avgOrderValue);
        $avgOrderDifference = $avgOrderValue - $lastMonthAvgOrderValue;

        return [
            'totalSales' => [
                'value' => $totalSales,
                'percentChange' => $salesPercentChange,
                'isPositive' => $salesPercentChange >= 0,
            ],
            'totalRevenue' => [
                'value' => $totalRevenue,
                'percentChange' => $revenuePercentChange,
                'isPositive' => $revenuePercentChange >= 0,
                'difference' => $revenueDifference
            ],
            'newCustomers' => [
                'value' => $newCustomers,
                'percentChange' => $customersPercentChange,
                'isPositive' => $customersPercentChange >= 0,
                'difference' => $customersDifference
            ],
            'averageOrderValue' => [
                'value' => $avgOrderValue,
                'percentChange' => $avgOrderPercentChange,
                'isPositive' => $avgOrderPercentChange >= 0,
                'difference' => $avgOrderDifference
            ]
        ];
    }

    /**
     * Get sales by category data
     */
    private function getSalesByCategory()
    {
        $categories = [
            'Local Moving' => Transaction::where('lead_type', 'Local')
                ->where('status', 'completed')
                ->count(),
            'Long Distance' => Transaction::where('lead_type', 'Long Distance')
                ->where('status', 'completed')
                ->count(),
            'Storage' => Transaction::where('lead_type', 'Storage')
                ->where('status', 'completed')
                ->count(),
            'Other' => Transaction::where(function($query) {
                    $query->whereNotIn('lead_type', ['Local', 'Long Distance', 'Storage'])
                        ->orWhereNull('lead_type');
                })
                ->where('status', 'completed')
                ->count()
        ];

        return $categories;
    }

    /**
     * Get sales performance data for the last 7 days
     */
    private function getSalesPerformance()
    {
        $dates = [];
        $salesData = [];
        $revenueData = [];

        // Get data for the last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $startDate = Carbon::parse($date)->startOfDay();
            $endDate = Carbon::parse($date)->endOfDay();
            
            $dates[] = $date;
            
            // Count of transactions for the day - add completed status filter
            $dailySales = Transaction::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed')
                ->count();
            $salesData[] = $dailySales;
            
            // Sum of revenue for the day - add completed status filter
            $dailyRevenue = Transaction::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed')
                ->sum('grand_total');
            $revenueData[] = $dailyRevenue;
        }

        return [
            'dates' => $dates,
            'sales' => $salesData,
            'revenue' => $revenueData
        ];
    }

    /**
     * Get recent sales data
     */
    private function getRecentSales()
    {
        $recentTransactions = Transaction::select('id', 'transaction_id', 'firstname', 'lastname', 'email', 'assigned_agent', 'status', 'created_at', 'grand_total')
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return $recentTransactions->map(function ($transaction) {
            $agentName = 'Unassigned';
            $agentEmail = '';
            $agentCompanyName = '';
            $customerName = $transaction->firstname . ' ' . $transaction->lastname;
            
            if ($transaction->assigned_agent) {
                // Find the agent using the assigned_agent field
                $agent = Agent::find($transaction->assigned_agent);
                if ($agent) {
                    $agentName = $agent->contact_name;
                    $agentEmail = $agent->email;
                    $agentCompanyName = $agent->company_name;
                }
            }
            
            // For status, ensure it has a value for display
            $status = $transaction->status ?? 'pending';
            
            return [
                'id' => $transaction->transaction_id ?? $transaction->id,
                'customer' => [
                    'name' => $customerName,
                    'email' => $transaction->email
                ],
                'date' => $transaction->created_at->format('M d, Y'),
                'amount' => $transaction->grand_total,
                'status' => strtolower($status),
                'agent' => [
                    'name' => $agentName,
                    'email' => $agentEmail,
                    'company_name' => $agentCompanyName
                ]
            ];
        });
    }

    /**
     * Export sales report as CSV
     */
    public function export(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        $transactions = Transaction::whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->where('status', 'completed')
            ->get();

        $filename = 'sales_report_' . Carbon::now()->format('Y-m-d') . '.csv';
        
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
        
        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Transaction ID',
                'Customer Name',
                'Customer Email',
                'Date',
                'Service',
                'Status',
                'Assigned Agent',
                'Agent Company',
                'Amount'
            ]);
            
            // Add transaction data
            foreach ($transactions as $transaction) {
                $agentName = 'Unassigned';
                $agentCompany = '';
                
                if ($transaction->assigned_agent) {
                    $agent = Agent::find($transaction->assigned_agent);
                    if ($agent) {
                        $agentName = $agent->contact_name;
                        $agentCompany = $agent->company_name;
                    }
                }
                
                fputcsv($file, [
                    $transaction->transaction_id,
                    $transaction->firstname . ' ' . $transaction->lastname,
                    $transaction->email,
                    $transaction->created_at->format('Y-m-d'),
                    $transaction->service,
                    $transaction->status,
                    $agentName,
                    $agentCompany,
                    $transaction->grand_total
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Calculate percent change between two values
     */
    private function calculatePercentChange($oldValue, $newValue)
    {
        if ($oldValue == 0) {
            return $newValue > 0 ? 100 : 0;
        }
        
        return round((($newValue - $oldValue) / $oldValue) * 100, 2);
    }

    /**
     * Get statistics on agent assignments
     */
    private function getAgentAssignmentStats()
    {
        $totalTransactions = Transaction::where('status', 'completed')->count();
        $assignedTransactions = Transaction::where('status', 'completed')->whereNotNull('assigned_agent')->count();
        $unassignedTransactions = $totalTransactions - $assignedTransactions;
        
        $assignedPercentage = $totalTransactions > 0 ? round(($assignedTransactions / $totalTransactions) * 100, 1) : 0;
        $unassignedPercentage = $totalTransactions > 0 ? round(($unassignedTransactions / $totalTransactions) * 100, 1) : 0;
        
        // Get top 5 agents by transaction count
        $topAgents = Agent::join('transactions', 'agents.id', '=', 'transactions.assigned_agent')
            ->where('transactions.status', 'completed')
            ->select('agents.id', 'agents.contact_name', DB::raw('count(*) as transaction_count'))
            ->groupBy('agents.id', 'agents.contact_name')
            ->orderBy('transaction_count', 'desc')
            ->limit(5)
            ->get();
            
        return [
            'assigned' => $assignedTransactions,
            'unassigned' => $unassignedTransactions,
            'assignedPercentage' => $assignedPercentage,
            'unassignedPercentage' => $unassignedPercentage,
            'topAgents' => $topAgents
        ];
    }

    /**
     * Get performance data for a specific date range
     */
    public function getPerformanceData(Request $request)
    {
        $range = $request->input('range', 'last_7_days');
        
        $dates = [];
        $salesData = [];
        $revenueData = [];
        
        // For debugging
        $debug = [
            'range' => $range,
            'query_info' => []
        ];
        
        // Define date ranges based on selection
        list($currentStart, $currentEnd, $previousStart, $previousEnd) = $this->getDateRanges($range);
        
        // Get performance data for the date range
        $datePoints = [];
        
        // Use different methods based on the range to get date points
        switch ($range) {
            case 'yesterday':
                // Single day
                $datePoints = [$currentStart];
                break;
                
            case 'this_week':
            case 'last_week':
                // Process days in the week
                $currentDate = $currentStart->copy();
                while ($currentDate->lte($currentEnd)) {
                    $datePoints[] = $currentDate->copy();
                    $currentDate->addDay();
                }
                break;
                
            case 'this_month':
            case 'last_month':
                $daysInPeriod = $currentEnd->diffInDays($currentStart) + 1;
                
                if ($daysInPeriod > 10) {
                    // Group by week if there are many days
                    $currentDate = $currentStart->copy();
                    while ($currentDate->lte($currentEnd)) {
                        $weekStart = $currentDate->copy();
                        $weekEnd = $currentDate->copy()->addDays(6)->min($currentEnd);
                        $datePoints[] = ['start' => $weekStart, 'end' => $weekEnd, 'label' => $weekStart->format('M d') . ' - ' . $weekEnd->format('M d')];
                        $currentDate->addDays(7);
                    }
                } else {
                    // Show each day if there are few days
                    $currentDate = $currentStart->copy();
                    while ($currentDate->lte($currentEnd)) {
                        $datePoints[] = $currentDate->copy();
                        $currentDate->addDay();
                    }
                }
                break;
                
            case 'last_3_months':
                // Group by month
                $currentMonth = $currentStart->copy()->startOfMonth();
                while ($currentMonth->lt($currentEnd)) {
                    $monthStart = $currentMonth->copy()->startOfMonth();
                    $monthEnd = $currentMonth->copy()->endOfMonth();
                    $datePoints[] = ['start' => $monthStart, 'end' => $monthEnd, 'label' => $monthStart->format('M Y')];
                    $currentMonth->addMonth();
                }
                break;
                
            case 'last_7_days':
            default:
                // Last 7 days
                for ($i = 6; $i >= 0; $i--) {
                    $datePoints[] = Carbon::now()->subDays($i);
                }
                break;
        }
        
        // Process each date point
        foreach ($datePoints as $point) {
            if (is_array($point)) {
                // Range of dates (week or month)
                $startDate = $point['start'];
                $endDate = $point['end'];
                $dateLabel = $point['label'];
                
                $dates[] = $dateLabel;
                
                $sales = Transaction::whereBetween('created_at', [
                    $startDate->startOfDay(), 
                    $endDate->endOfDay()
                ])
                ->where('status', 'completed')
                ->count();
                $salesData[] = $sales;
                
                $revenue = Transaction::whereBetween('created_at', [
                    $startDate->startOfDay(), 
                    $endDate->endOfDay()
                ])
                ->where('status', 'completed')
                ->sum('grand_total');
                $revenueData[] = $revenue;
                
            } else {
                // Single day
                $dates[] = $point->format('M d');
                
                $sales = Transaction::whereDate('created_at', $point)
                    ->where('status', 'completed')
                    ->count();
                $salesData[] = $sales;
                
                $revenue = Transaction::whereDate('created_at', $point)
                    ->where('status', 'completed')
                    ->sum('grand_total');
                $revenueData[] = $revenue;
            }
        }
        
        return response()->json([
            'success' => true,
            'dates' => $dates,
            'sales' => $salesData,
            'revenue' => $revenueData,
            'range' => $range,
            'debug' => $debug
        ]);
    }

    /**
     * Get stats data for a specific date range
     */
    public function getStatsData(Request $request)
    {
        $range = $request->input('range', 'last_7_days');
        
        // Define date ranges based on selection
        list($currentStart, $currentEnd, $previousStart, $previousEnd) = $this->getDateRanges($range);
        
        // Get stats for the current period
        $currentSales = Transaction::whereBetween('created_at', [$currentStart, $currentEnd])
            ->where('status', 'completed')
            ->count();
        $currentRevenue = Transaction::whereBetween('created_at', [$currentStart, $currentEnd])
            ->where('status', 'completed')
            ->sum('grand_total');
        $currentCustomers = Transaction::whereBetween('created_at', [$currentStart, $currentEnd])
            ->where('status', 'completed')
            ->distinct('email')
            ->count('email');
        $currentAvgOrderValue = $currentSales > 0 ? $currentRevenue / $currentSales : 0;
        
        // Get stats for the previous period
        $previousSales = Transaction::whereBetween('created_at', [$previousStart, $previousEnd])
            ->where('status', 'completed')
            ->count();
        $previousRevenue = Transaction::whereBetween('created_at', [$previousStart, $previousEnd])
            ->where('status', 'completed')
            ->sum('grand_total');
        $previousCustomers = Transaction::whereBetween('created_at', [$previousStart, $previousEnd])
            ->where('status', 'completed')
            ->distinct('email')
            ->count('email');
        $previousAvgOrderValue = $previousSales > 0 ? $previousRevenue / $previousSales : 0;
        
        // Calculate percentage changes
        $salesPercentChange = $this->calculatePercentChange($previousSales, $currentSales);
        $revenuePercentChange = $this->calculatePercentChange($previousRevenue, $currentRevenue);
        $customersPercentChange = $this->calculatePercentChange($previousCustomers, $currentCustomers);
        $avgOrderPercentChange = $this->calculatePercentChange($previousAvgOrderValue, $currentAvgOrderValue);
        
        // Calculate differences
        $salesDifference = $currentSales - $previousSales;
        $revenueDifference = $currentRevenue - $previousRevenue;
        $customersDifference = $currentCustomers - $previousCustomers;
        $avgOrderDifference = $currentAvgOrderValue - $previousAvgOrderValue;
        
        $stats = [
            'totalSales' => [
                'value' => $currentSales,
                'percentChange' => $salesPercentChange,
                'isPositive' => $salesPercentChange >= 0,
                'difference' => $salesDifference
            ],
            'totalRevenue' => [
                'value' => $currentRevenue,
                'percentChange' => $revenuePercentChange,
                'isPositive' => $revenuePercentChange >= 0,
                'difference' => $revenueDifference
            ],
            'newCustomers' => [
                'value' => $currentCustomers,
                'percentChange' => $customersPercentChange,
                'isPositive' => $customersPercentChange >= 0,
                'difference' => $customersDifference
            ],
            'averageOrderValue' => [
                'value' => $currentAvgOrderValue,
                'percentChange' => $avgOrderPercentChange,
                'isPositive' => $avgOrderPercentChange >= 0,
                'difference' => $avgOrderDifference
            ]
        ];
        
        // Add period information for debugging/display
        $periodInfo = [
            'current' => [
                'start' => $currentStart->format('Y-m-d'),
                'end' => $currentEnd->format('Y-m-d'),
                'description' => $this->getDateRangeDescription($range)
            ],
            'previous' => [
                'start' => $previousStart->format('Y-m-d'),
                'end' => $previousEnd->format('Y-m-d'),
                'description' => 'Previous ' . $this->getDateRangeDescription($range)
            ]
        ];
        
        // Check if there's any data for the selected period
        $hasData = $currentSales > 0 || $currentRevenue > 0 || $currentCustomers > 0;
        
        return response()->json([
            'success' => true,
            'stats' => $stats,
            'period' => $periodInfo,
            'range' => $range,
            'hasData' => $hasData
        ]);
    }
    
    /**
     * Get date ranges for the current and previous periods
     */
    private function getDateRanges($range)
    {
        switch ($range) {
            case 'today':
                $currentStart = Carbon::today()->startOfDay();
                $currentEnd = Carbon::today()->endOfDay();
                $previousStart = Carbon::yesterday()->startOfDay();
                $previousEnd = Carbon::yesterday()->endOfDay();
                break;
                
            case 'yesterday':
                $currentStart = Carbon::yesterday()->startOfDay();
                $currentEnd = Carbon::yesterday()->endOfDay();
                $previousStart = Carbon::today()->subDays(2)->startOfDay();
                $previousEnd = Carbon::today()->subDays(2)->endOfDay();
                break;
                
            case 'this_week':
                $currentStart = Carbon::now()->startOfWeek();
                $currentEnd = Carbon::now();
                $previousStart = Carbon::now()->subWeek()->startOfWeek();
                $previousEnd = Carbon::now()->subWeek()->endOfWeek();
                break;
                
            case 'last_week':
                $currentStart = Carbon::now()->subWeek()->startOfWeek();
                $currentEnd = Carbon::now()->subWeek()->endOfWeek();
                $previousStart = Carbon::now()->subWeeks(2)->startOfWeek();
                $previousEnd = Carbon::now()->subWeeks(2)->endOfWeek();
                break;
                
            case 'this_month':
                $currentStart = Carbon::now()->startOfMonth();
                $currentEnd = Carbon::now();
                $previousStart = Carbon::now()->subMonth()->startOfMonth();
                $previousEnd = Carbon::now()->subMonth()->endOfMonth();
                break;
                
            case 'last_month':
                $currentStart = Carbon::now()->subMonth()->startOfMonth();
                $currentEnd = Carbon::now()->subMonth()->endOfMonth();
                $previousStart = Carbon::now()->subMonths(2)->startOfMonth();
                $previousEnd = Carbon::now()->subMonths(2)->endOfMonth();
                break;
                
            case 'last_3_months':
                $currentStart = Carbon::now()->subMonths(3)->startOfMonth();
                $currentEnd = Carbon::now();
                // Previous 3 months before the current 3 months
                $previousStart = Carbon::now()->subMonths(6)->startOfMonth();
                $previousEnd = Carbon::now()->subMonths(3)->subDay()->endOfDay();
                break;
                
            case 'last_7_days':
            default:
                $currentStart = Carbon::now()->subDays(6)->startOfDay();
                $currentEnd = Carbon::now()->endOfDay();
                $previousStart = Carbon::now()->subDays(13)->startOfDay();
                $previousEnd = Carbon::now()->subDays(7)->endOfDay();
                break;
        }
        
        return [$currentStart, $currentEnd, $previousStart, $previousEnd];
    }
    
    /**
     * Get a human-readable description of the date range
     */
    private function getDateRangeDescription($range)
    {
        switch ($range) {
            case 'today':
                return 'Today';
            case 'yesterday':
                return 'Yesterday';
            case 'this_week':
                return 'This Week';
            case 'last_week':
                return 'Last Week';
            case 'this_month':
                return 'This Month';
            case 'last_month':
                return 'Last Month';
            case 'last_3_months':
                return 'Last 3 Months';
            case 'last_7_days':
            default:
                return 'Last 7 Days';
        }
    }

    /**
     * Get category data for a specific date range
     */
    public function getCategoryData(Request $request)
    {
        $range = $request->input('range', 'last_7_days');
        
        // Define date ranges based on selection
        list($currentStart, $currentEnd, $previousStart, $previousEnd) = $this->getDateRanges($range);
        
        // Get sales by category for the current period
        $categories = [
            'Local Moving' => Transaction::where('lead_type', 'Local')
                ->where('status', 'completed')
                ->whereBetween('created_at', [$currentStart, $currentEnd])
                ->count(),
            'Long Distance' => Transaction::where('lead_type', 'Long Distance')
                ->where('status', 'completed')
                ->whereBetween('created_at', [$currentStart, $currentEnd])
                ->count(),
            'Storage' => Transaction::where('lead_type', 'Storage')
                ->where('status', 'completed')
                ->whereBetween('created_at', [$currentStart, $currentEnd])
                ->count(),
            'Other' => Transaction::where(function($query) {
                    $query->whereNotIn('lead_type', ['Local', 'Long Distance', 'Storage'])
                        ->orWhereNull('lead_type');
                })
                ->where('status', 'completed')
                ->whereBetween('created_at', [$currentStart, $currentEnd])
                ->count()
        ];
        
        // Calculate total for percentage calculations
        $total = array_sum($categories);
        
        // Calculate percentages
        $percentages = [];
        foreach ($categories as $key => $value) {
            $percentages[$key] = $total > 0 ? ($value / $total) * 100 : 0;
        }
        
        return response()->json([
            'success' => true,
            'categories' => $categories,
            'percentages' => $percentages,
            'total' => $total,
            'period' => [
                'start' => $currentStart->format('Y-m-d'),
                'end' => $currentEnd->format('Y-m-d'),
                'description' => $this->getDateRangeDescription($range)
            ],
            'range' => $range
        ]);
    }

    /**
     * Get agent assignment data for a specific date range
     */
    public function getAgentData(Request $request)
    {
        $range = $request->input('range', 'last_7_days');
        
        // Define date ranges based on selection
        list($currentStart, $currentEnd, $previousStart, $previousEnd) = $this->getDateRanges($range);
        
        // Get stats for the current period
        $totalTransactions = Transaction::where('status', 'completed')
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->count();
        
        $assignedTransactions = Transaction::where('status', 'completed')
            ->whereNotNull('assigned_agent')
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->count();
        
        $unassignedTransactions = $totalTransactions - $assignedTransactions;
        
        $assignedPercentage = $totalTransactions > 0 ? round(($assignedTransactions / $totalTransactions) * 100, 1) : 0;
        $unassignedPercentage = $totalTransactions > 0 ? round(($unassignedTransactions / $totalTransactions) * 100, 1) : 0;
        
        // Get top 5 agents by transaction count
        $topAgents = Agent::join('transactions', 'agents.id', '=', 'transactions.assigned_agent')
            ->where('transactions.status', 'completed')
            ->whereBetween('transactions.created_at', [$currentStart, $currentEnd])
            ->select('agents.id', 'agents.contact_name', DB::raw('count(*) as transaction_count'))
            ->groupBy('agents.id', 'agents.contact_name')
            ->orderBy('transaction_count', 'desc')
            ->limit(5)
            ->get();
            
        return response()->json([
            'success' => true,
            'assigned' => $assignedTransactions,
            'unassigned' => $unassignedTransactions,
            'assignedPercentage' => $assignedPercentage,
            'unassignedPercentage' => $unassignedPercentage,
            'topAgents' => $topAgents,
            'total' => $totalTransactions,
            'period' => [
                'start' => $currentStart->format('Y-m-d'),
                'end' => $currentEnd->format('Y-m-d'),
                'description' => $this->getDateRangeDescription($range)
            ],
            'range' => $range
        ]);
    }
} 