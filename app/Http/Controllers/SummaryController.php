<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use App\Exports\SummariesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SummaryController extends Controller
{
    public function index(Request $request)
    {
        $monthOffset = (int) $request->get('month_offset', 0);
        $performanceData = $this->getPerformance($monthOffset);
        $selectedMonth = now()->subMonths($monthOffset)->format('F Y');
        $isCurrentMonth = ($monthOffset === 0);
        if ($request->ajax()) {
            return response()->json([
                'data' => $performanceData,
                'month' => $selectedMonth,
                'is_current_month' => $isCurrentMonth
            ]);
        }
        return view('activities.summaries', compact('performanceData', 'selectedMonth', 'isCurrentMonth'));
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

    private function getPerformance($monthOffset = 0)
    {
        $marketingUsers = User::whereIn('role', ['MARKETING', 'ADMIN'])
            ->orderBy('name')
            ->get();
        $targetDate = now()->subMonths($monthOffset);
        $startOfMonth = $targetDate->copy()->startOfMonth();
        $endOfMonth = $targetDate->copy()->endOfMonth();
        $performanceData = [];
        foreach ($marketingUsers as $user) {
            $targets = [
                'activity_total' => $user->target_activity,
                'volume_total'   => $user->target_volume,
                'profit_total'   => $user->target_profit,
            ];
            $stats = Activity::where('user_id', $user->id)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
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
        $monthOffset = $request->get('month_offset', 0);
        $performanceData = $this->getPerformance($monthOffset);
        $selectedMonth = now()->subMonths($monthOffset)->format('F Y');
        return Excel::download(
            new SummariesExport($performanceData, $selectedMonth),
            'Summary ' . $selectedMonth . '.xlsx'
        );
    }
}
