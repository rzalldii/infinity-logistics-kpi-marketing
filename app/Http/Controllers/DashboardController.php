<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use App\Models\Shipper;
use App\Models\Activity;
use App\Models\Audit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedUserId = $request->input('user_id');
        $selectedYear   = (int) $request->input('year', now()->year);
        $user = Auth::user();
        $queryUserId = $selectedUserId === 'mine' ? $user->id : $selectedUserId;
        $applyUserFilter = function ($query) use ($user, $queryUserId) {
            if ($user->isSuperAdmin() || $user->isAdmin()) {
                if ($queryUserId) {
                    $query->where('user_id', $queryUserId);
                }
            } elseif ($user->isMarketing()) {
                $query->where('user_id', $user->id);
            }
        };
        $totals = [
            'rates'      => Rate::query(),
            'shippers'   => Shipper::query(),
            'activities' => Activity::query(),
        ];
        $currentMonth = [
            'rates'      => Rate::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year),
            'shippers'   => Shipper::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year),
            'activities' => Activity::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year),
        ];
        foreach ($totals as $key => $q) {
            $applyUserFilter($q);
            $totals[$key] = $q->count();
        }
        foreach ($currentMonth as $key => $q) {
            $applyUserFilter($q);
            $currentMonth[$key] = $q->count();
        }
        $targetUserId   = ($user->isSuperAdmin() || $user->isAdmin()) ? $queryUserId : ($user->isMarketing() ? $user->id : null);
        $performance    = $this->getPerformanceStats($targetUserId);
        $line           = $this->getLineChart($user, $targetUserId, $selectedYear);
        $availableYears = Activity::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        $dailyReport    = $this->getReportData('today', $queryUserId, $user);
        $weeklyReport   = $this->getReportData('week', $queryUserId, $user);
        $monthlyReport  = $this->getReportData('month', $queryUserId, $user);
        $auditLogs      = Audit::orderBy('created_at', 'desc')
            ->limit(10)
            ->where('auditable_type', '!=', 'User')
            ->where('event', '!=', 'exported')
            ->whereHas('user', function ($q) {
                $q->where('role', '!=', 'SUPER ADMIN');
            });
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            if ($queryUserId) $auditLogs->where('user_id', $queryUserId);
        } elseif ($user->isMarketing()) {
            $auditLogs->where('user_id', $user->id);
        }
        $logs = $auditLogs->get()->map(function ($log) {
            return [
                'id'             => $log->id,
                'type'           => match ($log->auditable_type) {
                    'Rate'       => 'Checking Rates',
                    'Shipper'    => 'Touch Shippers',
                    'Activity'   => 'Report Activities',
                    'User'       => 'User Management',
                    default      => $log->auditable_type,
                },
                'user'           => $log->user,
                'description'    => $log->description,
                'action'         => ucfirst($log->event),
                'created_at'     => $log->created_at,
            ];
        });
        $users = User::whereIn('role', ['MARKETING','ADMIN'])->where('id', '!=', Auth::id())->orderBy('name')->get();
        return view('pages.dashboard', [
            'totalRates'          => $totals['rates'],
            'totalShippers'       => $totals['shippers'],
            'totalActivities'     => $totals['activities'],
            'ratesThisMonth'      => $currentMonth['rates'],
            'shippersThisMonth'   => $currentMonth['shippers'],
            'activitiesThisMonth' => $currentMonth['activities'],
            'selectedUserId'      => $selectedUserId,
            'performance'         => $performance,
            'line'                => $line,
            'availableYears'      => $availableYears,
            'selectedYear'        => $selectedYear,
            'dailyReport'         => $dailyReport,
            'weeklyReport'        => $weeklyReport,
            'monthlyReport'       => $monthlyReport,
            'logs'                => $logs,
            'users'               => $users,
        ]);
    }

    private function getPerformanceStats($userId)
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $targetUsers = User::whereIn('role', ['MARKETING', 'ADMIN']);
        if ($userId) {
            $targetUsers->where('id', $userId);
        }
        $users = $targetUsers->get();
        $totalActualActivity = 0;
        $totalActualVolume = 0;
        $totalActualProfit = 0;
        $totalTargetActivity = 0;
        $totalTargetVolume = 0;
        $totalTargetProfit = 0;
        $breakdownQuote = 0;
        $breakdownCall = 0;
        $breakdownVisit = 0;
        $breakdown20 = 0;
        $breakdown40 = 0;
        $breakdownOthers = 0;
        foreach ($users as $user) {
            $totalTargetActivity += (int) $user->target_activity;
            $totalTargetVolume   += (int) $user->target_volume;
            $totalTargetProfit   += (float) $user->target_profit;
            $stats = Activity::where('user_id', $user->id)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->selectRaw("
                    SUM(CASE WHEN activity_type = 'QUOTE' THEN 1 ELSE 0 END) as count_quote,
                    SUM(CASE WHEN activity_type = 'CALL' THEN 1 ELSE 0 END) as count_call,
                    SUM(CASE WHEN activity_type = 'VISIT' THEN 1 ELSE 0 END) as count_visit,
                    SUM(CAST(COALESCE(volume_20, 0) AS DECIMAL(10,2))) as sum_20,
                    SUM(CAST(COALESCE(volume_40, 0) AS DECIMAL(10,2))) as sum_40,
                    SUM(CASE WHEN other_volume IN ('AIR FREIGHT', 'RAIL FREIGHT', 'ROAD FREIGHT', 'EMKL', 'LCL', 'OTHER BUSINESS') THEN 1 ELSE 0 END) as count_others,
                    SUM(CAST(REPLACE(REPLACE(REGEXP_REPLACE(COALESCE(profit, '0'), '[^0-9,.]', ''),'.', ''),',', '.') AS DECIMAL(15,2))) as total_profit
                ")
                ->first();
            $userActivity = (int)$stats->count_quote + (int)$stats->count_call + (int)$stats->count_visit;
            $userVolume   = (float)$stats->sum_20 + (float)$stats->sum_40 + (int)$stats->count_others;
            $userProfit   = (float)$stats->total_profit;
            $totalActualActivity += $userActivity;
            $totalActualVolume   += $userVolume;
            $totalActualProfit   += $userProfit;
            $breakdownQuote += (int)$stats->count_quote;
            $breakdownCall  += (int)$stats->count_call;
            $breakdownVisit += (int)$stats->count_visit;
            $breakdown20    += (float)$stats->sum_20;
            $breakdown40    += (float)$stats->sum_40;
            $breakdownOthers += (int)$stats->count_others;
        }
        $calcPerformance = function($actual, $target) {
            return [
                'actual'     => $actual,
                'target'     => $target,
                'remaining'  => max(0, $target - $actual),
                'percentage' => $target > 0 ? round(($actual / $target) * 100) : 0
            ];
        };
        return [
            'activities' => [
                'performance' => $calcPerformance($totalActualActivity, $totalTargetActivity),
                'breakdown' => [
                    'quote' => $breakdownQuote,
                    'call'  => $breakdownCall,
                    'visit' => $breakdownVisit
                ]
            ],
            'volume' => [
                'performance' => $calcPerformance($totalActualVolume, $totalTargetVolume),
                'breakdown' => [
                    '20ft' => $breakdown20,
                    '40ft' => $breakdown40,
                    'others_breakdown' => [
                        'TOTAL_OTHERS' => $breakdownOthers 
                    ]
                ]
            ],
            'profit' => [
                'performance' => $calcPerformance($totalActualProfit, $totalTargetProfit)
            ]
        ];
    }

    private function getLineChart($user, $targetUserId, $year = null)
    {
        $year = $year ?? now()->year;
        $labels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $colors = ['#1d7af3','#59d05d','#f3545d','#fdaf4b','#a855f7','#14b8a6','#f97316','#ec4899','#a16207'];
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            $users = $targetUserId
                ? User::where('id', $targetUserId)->get()
                : User::whereIn('role', ['MARKETING', 'ADMIN'])->orderBy('name')->get();
        } else {
            $users = User::where('id', $user->id)->get();
        }
        $datasets = [];
        foreach ($users as $index => $u) {
            $results = Activity::where('user_id', $u->id)
                ->whereYear('created_at', $year)
                ->selectRaw("
                    MONTH(created_at) as month,
                    SUM(CAST(REPLACE(REPLACE(REGEXP_REPLACE(COALESCE(profit, '0'), '[^0-9,.]', ''),'.', ''),',', '.') AS DECIMAL(15,2))) as total_profit
                ")
                ->groupBy('month')
                ->pluck('total_profit', 'month');
            $data = [];
            for ($m = 1; $m <= 12; $m++) {
                $data[] = round((float)($results[$m] ?? 0), 2);
            }
            $color = $colors[$index % count($colors)];
            $datasets[] = [
                'label'                => $u->name,
                'borderColor'          => $color,
                'pointBorderColor'     => '#FFF',
                'pointBackgroundColor' => $color,
                'pointBorderWidth'     => 2,
                'pointHoverRadius'     => 4,
                'pointHoverBorderWidth'=> 1,
                'pointRadius'          => 4,
                'backgroundColor'      => 'transparent',
                'fill'                 => true,
                'borderWidth'          => 2,
                'data'                 => $data,
            ];
        }
        return [
            'labels'   => $labels,
            'datasets' => $datasets,
        ];
    }

    private function getReportData($period, $selectedUserId = null, $user = null)
    {
        $query = Activity::join('shippers', 'activities.shipper_id', '=', 'shippers.id');
        if ($period === 'today') {
            $query->whereDate('activities.created_at', now()->toDateString());
        } elseif ($period === 'week') {
            $query->whereBetween('activities.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($period === 'month') {
            $query->whereMonth('activities.created_at', now()->month)
                  ->whereYear('activities.created_at', now()->year);
        }
        $user = $user ?? Auth::user();
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            if ($selectedUserId) {
                $query->where('activities.user_id', $selectedUserId);
            }
        } elseif ($user->isMarketing()) {
            $query->where('activities.user_id', $user->id);
        }
        $stats = $query->selectRaw("
            SUM(CASE WHEN shippers.shipper_concept = 'NEW SHIPPER' THEN 1 ELSE 0 END) as new_shipper_count,
            SUM(CASE WHEN shippers.shipper_concept = 'EXISTING SHIPPER' THEN 1 ELSE 0 END) as existing_shipper_count,
            SUM(CASE WHEN shippers.shipper_type = 'DIRECT SHIPPER' THEN 1 ELSE 0 END) as direct_shipper_count,
            SUM(CASE WHEN shippers.shipper_type = 'FORWARDING' THEN 1 ELSE 0 END) as forwarding_count,
            SUM(CASE WHEN shippers.shipper_type = 'VENDORING' THEN 1 ELSE 0 END) as vendoring_count,
            SUM(CASE WHEN shippers.shipper_type = 'TRADING' THEN 1 ELSE 0 END) as trading_count,
            SUM(CASE WHEN activities.status_type = 'CLOSING' THEN 1 ELSE 0 END) as closing_count,
            SUM(CASE WHEN activities.status_type = 'PENDING' THEN 1 ELSE 0 END) as pending_count,
            SUM(CASE WHEN activities.status_type = 'FAILED' THEN 1 ELSE 0 END) as failed_count
        ")->first();
        return (object) [
            'new_shipper_count'      => $stats->new_shipper_count ?? 0,
            'existing_shipper_count' => $stats->existing_shipper_count ?? 0,
            'direct_shipper_count'   => $stats->direct_shipper_count ?? 0,
            'forwarding_count'       => $stats->forwarding_count ?? 0,
            'vendoring_count'        => $stats->vendoring_count ?? 0,
            'trading_count'          => $stats->trading_count ?? 0,
            'closing_count'          => $stats->closing_count ?? 0,
            'pending_count'          => $stats->pending_count ?? 0,
            'failed_count'           => $stats->failed_count ?? 0,
        ];
    }
}
