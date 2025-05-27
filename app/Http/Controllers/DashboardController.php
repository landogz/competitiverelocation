<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getChartData(Request $request)
    {
        try {
            $period = $request->get('period', 'this_year');
            $data = [];
            $categories = [];
            
            switch ($period) {
                case 'today':
                    $startDate = now()->startOfDay();
                    $endDate = now()->endOfDay();
                    $transactions = \App\Models\Transaction::whereBetween('created_at', [$startDate, $endDate])
                        ->get();
                    $total = $transactions->sum('grand_total');
                    $count = $transactions->count();
                    $average = $count > 0 ? $total / $count : 0;
                    $data = [round($average, 2)];
                    $categories = ['Today'];
                    break;
                    
                case 'last_week':
                    $startDate = now()->subWeek()->startOfDay();
                    $endDate = now()->endOfDay();
                    $transactions = \App\Models\Transaction::whereBetween('created_at', [$startDate, $endDate])
                        ->get()
                        ->groupBy(function($date) {
                            return \Carbon\Carbon::parse($date->created_at)->format('D');
                        });
                    
                    $data = [];
                    $categories = [];
                    foreach ($transactions as $day => $dayTransactions) {
                        $total = $dayTransactions->sum('grand_total');
                        $count = $dayTransactions->count();
                        $average = $count > 0 ? $total / $count : 0;
                        $data[] = round($average, 2);
                        $categories[] = $day;
                    }
                    break;
                    
                case 'last_month':
                    $startDate = now()->subMonth()->startOfMonth();
                    $endDate = now()->subMonth()->endOfMonth();
                    $transactions = \App\Models\Transaction::whereBetween('created_at', [$startDate, $endDate])
                        ->get()
                        ->groupBy(function($date) {
                            return \Carbon\Carbon::parse($date->created_at)->format('M d');
                        });
                    
                    $data = [];
                    $categories = [];
                    foreach ($transactions as $day => $dayTransactions) {
                        $total = $dayTransactions->sum('grand_total');
                        $count = $dayTransactions->count();
                        $average = $count > 0 ? $total / $count : 0;
                        $data[] = round($average, 2);
                        $categories[] = $day;
                    }
                    break;
                    
                case 'this_month':
                    $startDate = now()->startOfMonth();
                    $endDate = now()->endOfMonth();
                    $transactions = \App\Models\Transaction::whereBetween('created_at', [$startDate, $endDate])
                        ->get()
                        ->groupBy(function($date) {
                            return \Carbon\Carbon::parse($date->created_at)->format('M d');
                        });
                    $data = [];
                    $categories = [];
                    foreach ($transactions as $day => $dayTransactions) {
                        $total = $dayTransactions->sum('grand_total');
                        $count = $dayTransactions->count();
                        $average = $count > 0 ? $total / $count : 0;
                        $data[] = round($average, 2);
                        $categories[] = $day;
                    }
                    break;
                    
                case 'this_year':
                default:
                    $currentYear = now()->year;
                    $data = [];
                    $categories = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                    
                    for ($month = 1; $month <= 12; $month++) {
                        $total = \App\Models\Transaction::whereYear('created_at', $currentYear)
                            ->whereMonth('created_at', $month)
                            ->sum('grand_total');
                        
                        $count = \App\Models\Transaction::whereYear('created_at', $currentYear)
                            ->whereMonth('created_at', $month)
                            ->count();
                        
                        $average = $count > 0 ? $total / $count : 0;
                        $data[] = round($average, 2);
                    }
                    break;
            }
            
            return response()->json([
                'data' => $data,
                'categories' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'data' => [0],
                'categories' => ['No Data']
            ]);
        }
    }
} 