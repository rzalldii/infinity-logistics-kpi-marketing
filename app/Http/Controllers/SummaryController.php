<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use App\Exports\SummariesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class SummaryController extends Controller
{
    public function index(Request $request)
    {
        $currentYear = now()->year;
        $dates = Activity::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month')
            ->whereNotNull('created_at')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
        $currentYearMonths = [];
        $pastYears = [];
        foreach ($dates as $d) {
            if ($d->year == $currentYear) {
                $val = $d->year . '-' . str_pad($d->month, 2, '0', STR_PAD_LEFT);
                $label = Carbon::create()->month($d->month)->format('F');
                $currentYearMonths[$val] = $label;
            } else {
                if (!isset($pastYears[(string)$d->year])) {
                    $pastYears[(string)$d->year] = "Summary " . $d->year;
                }
            }
        }
        $currentMonthVal = now()->format('Y-m');
        if (!isset($currentYearMonths[$currentMonthVal])) {
            $currentYearMonths = [$currentMonthVal => now()->format('F')] + $currentYearMonths;
        }
        $filterOptions = $currentYearMonths;
        $filterOptions[(string)$currentYear] = "Summary " . $currentYear;
        foreach ($pastYears as $yearVal => $yearLabel) {
            $filterOptions[$yearVal] = $yearLabel;
        }
        $period = $request->get('period', $currentMonthVal);
        $performanceData = $this->getPerformance($period);
        if (strlen($period) === 4) {
            $selectedMonth = 'Year ' . $period;
            $isCurrentMonth = false;
        } else {
            $selectedMonth = Carbon::createFromFormat('Y-m-d', $period . '-01')->format('F Y');
            $isCurrentMonth = ($period === now()->format('Y-m'));
        }
        if ($request->ajax()) {
            return response()->json([
                'data' => $performanceData,
                'month' => $selectedMonth,
                'is_current_month' => $isCurrentMonth
            ]);
        }
        return view('activities.summaries', compact('performanceData', 'selectedMonth', 'isCurrentMonth', 'filterOptions', 'period'));
    }

    public function edit($id): JsonResponse
    {
        $user = User::findOrFail($id);
        return response()->json([
            'user_id' => $user->id,
            'target_activity' => $user->target_activity,
            'target_volume' => $user->target_volume,
            'target_profit' => $user->target_profit,
        ]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'target_activity' => 'nullable|string',
            'target_volume'   => 'nullable|string',
            'target_profit'   => 'nullable|string',
        ]);
        $user->update($validated);
        return response()->json($user, 200);
    }

    private function getPerformance($period)
    {
        $marketingUsers = User::whereIn('role', ['MARKETING', 'ADMIN'])
            ->orderBy('name')
            ->get();
        if (strlen($period) === 4) {
            $startOfPeriod = Carbon::createFromFormat('Y', $period)->startOfYear();
            $endOfPeriod = Carbon::createFromFormat('Y', $period)->endOfYear();
            $multiplier = 12;
        } else {
            $startOfPeriod = Carbon::createFromFormat('Y-m-d', $period . '-01')->startOfMonth();
            $endOfPeriod = Carbon::createFromFormat('Y-m-d', $period . '-01')->endOfMonth();
            $multiplier = 1;
        }
        $performanceData = [];
        foreach ($marketingUsers as $user) {
            $targets = [
                'activity_total' => ((float)$user->target_activity) * $multiplier,
                'volume_total'   => ((float)$user->target_volume) * $multiplier,
                'profit_total'   => ((float)$user->target_profit) * $multiplier,
            ];
            $stats = Activity::where('user_id', $user->id)
                ->whereBetween('created_at', [$startOfPeriod, $endOfPeriod])
                ->selectRaw("
                    SUM(CASE WHEN activity_type = 'QUOTE' THEN 1 ELSE 0 END) as count_quote,
                    SUM(CASE WHEN activity_type = 'CALL' THEN 1 ELSE 0 END) as count_call,
                    SUM(CASE WHEN activity_type = 'VISIT' THEN 1 ELSE 0 END) as count_visit,
                    SUM(CAST(COALESCE(volume_20, 0) AS DECIMAL(10,2))) as sum_20,
                    SUM(CAST(COALESCE(volume_40, 0) AS DECIMAL(10,2))) as sum_40,
                    SUM(CASE WHEN other_volume = 'AIR FREIGHT' THEN 1 ELSE 0 END) as count_air,
                    SUM(CASE WHEN other_volume = 'RAIL FREIGHT' THEN 1 ELSE 0 END) as count_rail,
                    SUM(CASE WHEN other_volume = 'ROAD FREIGHT' THEN 1 ELSE 0 END) as count_road,
                    SUM(CASE WHEN other_volume = 'EMKL' THEN 1 ELSE 0 END) as count_emkl,
                    SUM(CASE WHEN other_volume = 'LCL' THEN 1 ELSE 0 END) as count_lcl,
                    SUM(CASE WHEN other_volume = 'OTHER BUSINESS' THEN 1 ELSE 0 END) as count_other_biz,
                    SUM(CAST(REGEXP_REPLACE(COALESCE(profit, '0'), '[^0-9.-]', '') AS DECIMAL(15,2))) as total_profit
                ")
                ->first();
            $actualActivity = (int)$stats->count_quote + (int)$stats->count_call + (int)$stats->count_visit;
            $actualVolumeContainer = (float)$stats->sum_20 + (float)$stats->sum_40;
            $actualVolumeOthers = (int)$stats->count_air + (int)$stats->count_rail + 
                                  (int)$stats->count_road + (int)$stats->count_emkl + 
                                  (int)$stats->count_lcl + (int)$stats->count_other_biz;
            $actualVolumeTotal = $actualVolumeContainer + $actualVolumeOthers;
            $actualProfit = (float)$stats->total_profit;
            $calc = function($actual, $target) {
                $percentage = $target > 0 ? round(($actual / $target) * 100) : 0;
                $remaining = max(0, $target - $actual);
                return [
                    'actual'     => $actual,
                    'target'     => $target,
                    'remaining'  => $remaining,
                    'percentage' => $percentage
                ];
            };
            $performanceData[] = [
                'user_id' => $user->id,
                'name'    => $user->name,
                'role'    => $user->role,
                'activities' => [
                    'performance' => $calc($actualActivity, $targets['activity_total']),
                    'breakdown'   => [
                        'quote' => (int)$stats->count_quote,
                        'call'  => (int)$stats->count_call,
                        'visit' => (int)$stats->count_visit
                    ]
                ],
                'volume' => [
                    'performance' => $calc($actualVolumeTotal, $targets['volume_total']),
                    'breakdown'   => [
                        '20ft' => (float)$stats->sum_20,
                        '40ft' => (float)$stats->sum_40,
                        'others' => $actualVolumeOthers
                    ]
                ],
                'profit' => [
                    'performance' => $calc($actualProfit, $targets['profit_total'])
                ]
            ];
        }
        return $performanceData;
    }

    public function export(Request $request)
    {
        $period = $request->get('period', now()->format('Y-m'));
        $performanceData = $this->getPerformance($period);
        if (strlen($period) === 4) {
            $selectedMonth = 'Year ' . $period;
        } else {
            $selectedMonth = Carbon::createFromFormat('Y-m-d', $period . '-01')->format('F Y');
        }
        return Excel::download(
            new SummariesExport($performanceData, $selectedMonth),
            'Summary ' . $selectedMonth . '.xlsx'
        );
    }
}
