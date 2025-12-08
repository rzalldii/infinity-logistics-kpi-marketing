<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Shipper;
use App\Models\User;
use App\Exports\ActivitiesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with(['user', 'shipper'])->latest('report_date');
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isMarketing()) {
                $query->where('user_id', Auth::id());
            }
        }
        if ($request->ajax()) {
            return response()->json($query->get());
        }
        $activities = $query->get();
        $shippers = Shipper::orderBy('shipper_name')->get();
        $users = User::whereIn('role', ['marketing', 'admin', 'super_admin'])->where('id', '!=', Auth::id())->orderBy('name')->get();
        $dailyReport = $this->getDailyReport();
        $weeklyReport = $this->getWeeklyReport();
        $monthlyReport = $this->getMonthlyReport();
        return view('activities.index', compact('activities', 'shippers', 'dailyReport', 'weeklyReport', 'monthlyReport', 'users'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'report_date'   => 'required|date',
            'concept_type'  => 'required|in:NEW SHIPPER,FOLLOW UP',
            'shipper_id'    => 'required|exists:shippers,id',
            'activity_type' => 'required|in:VISIT,CALL',
            'visit_date'    => 'nullable|date',
            'status'        => 'nullable|in:CLOSING,PENDING,FAILED',
            'status_detail' => 'nullable|string',
            'prospect'      => 'nullable|string',
        ]);
        $validated['user_id'] = Auth::id();
        $activity = Activity::create($validated);
        return response()->json($activity, 201);
    }

    public function edit($id): JsonResponse
    {
        $activity = Activity::with('shipper')->findOrFail($id);
        return response()->json($activity);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $activity = Activity::findOrFail($id);
        $validated = $request->validate([
            'report_date'   => 'required|date',
            'concept_type'  => 'required|in:NEW SHIPPER,FOLLOW UP',
            'shipper_id'    => 'required|exists:shippers,id',
            'activity_type' => 'required|in:VISIT,CALL',
            'visit_date'    => 'nullable|date',
            'status'        => 'nullable|in:CLOSING,PENDING,FAILED',
            'status_detail' => 'nullable|string',
            'prospect'      => 'nullable|string',
        ]);
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isMarketing() && $activity->user_id !== Auth::id()) {
                return response()->json(null, 403);
            }
            $reportDate = Carbon::parse($activity->report_date)->format('Y-m-d');
            $today = Carbon::now()->format('Y-m-d');
            if ($reportDate !== $today) {
                return response()->json(null, 403);
            }
        }
        $activity->update($validated);
        return response()->json($activity, 200);
    }

    public function destroy($id): JsonResponse
    {
        $activity = Activity::findOrFail($id);
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isMarketing() && $activity->user_id !== Auth::id()) {
                return response()->json(null, 403);
            }
            $reportDate = Carbon::parse($activity->report_date)->format('Y-m-d');
            $today = Carbon::now()->format('Y-m-d');
            if ($reportDate !== $today) {
                return response()->json(null, 403);
            }
        }
        $activity->delete();
        return response()->json(null, 204);
    }

    private function getDailyReport()
    {
        $query = Activity::with('shipper')
            ->whereDate('report_date', now()->toDateString());
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isMarketing()) {
                $query->where('user_id', Auth::id());
            }
        }
        $baseStats = $query->selectRaw("
            SUM(CASE WHEN concept_type = 'NEW SHIPPER' THEN 1 ELSE 0 END) as new_shipper_count,
            SUM(CASE WHEN concept_type = 'FOLLOW UP' THEN 1 ELSE 0 END) as follow_up_count,
            SUM(CASE WHEN activity_type = 'VISIT' THEN 1 ELSE 0 END) as visit_count,
            SUM(CASE WHEN activity_type = 'CALL' THEN 1 ELSE 0 END) as call_count,
            SUM(CASE WHEN status = 'CLOSING' THEN 1 ELSE 0 END) as closing_count,
            SUM(CASE WHEN status = 'PENDING' THEN 1 ELSE 0 END) as pending_count,
            SUM(CASE WHEN status = 'FAILED' THEN 1 ELSE 0 END) as failed_count
        ")->first();
        $shipperTypeCounts = $this->getShipperTypeCount('today');
        return (object) array_merge(
            (array) $baseStats,
            $shipperTypeCounts
        );
    }

    private function getWeeklyReport()
    {
        $query = Activity::with('shipper')
            ->whereBetween('report_date', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ]);
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isMarketing()) {
                $query->where('user_id', Auth::id());
            }
        }
        $baseStats = $query->selectRaw("
            SUM(CASE WHEN concept_type = 'NEW SHIPPER' THEN 1 ELSE 0 END) as new_shipper_count,
            SUM(CASE WHEN concept_type = 'FOLLOW UP' THEN 1 ELSE 0 END) as follow_up_count,
            SUM(CASE WHEN activity_type = 'VISIT' THEN 1 ELSE 0 END) as visit_count,
            SUM(CASE WHEN activity_type = 'CALL' THEN 1 ELSE 0 END) as call_count,
            SUM(CASE WHEN status = 'CLOSING' THEN 1 ELSE 0 END) as closing_count,
            SUM(CASE WHEN status = 'PENDING' THEN 1 ELSE 0 END) as pending_count,
            SUM(CASE WHEN status = 'FAILED' THEN 1 ELSE 0 END) as failed_count
        ")->first();
        $shipperTypeCounts = $this->getShipperTypeCount('week');
        return (object) array_merge(
            (array) $baseStats,
            $shipperTypeCounts
        );
    }

    private function getMonthlyReport()
    {
        $query = Activity::with('shipper')
            ->whereYear('report_date', now()->year)
            ->whereMonth('report_date', now()->month);
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isMarketing()) {
                $query->where('user_id', Auth::id());
            }
        }
        $baseStats = $query->selectRaw("
            SUM(CASE WHEN concept_type = 'NEW SHIPPER' THEN 1 ELSE 0 END) as new_shipper_count,
            SUM(CASE WHEN concept_type = 'FOLLOW UP' THEN 1 ELSE 0 END) as follow_up_count,
            SUM(CASE WHEN activity_type = 'VISIT' THEN 1 ELSE 0 END) as visit_count,
            SUM(CASE WHEN activity_type = 'CALL' THEN 1 ELSE 0 END) as call_count,
            SUM(CASE WHEN status = 'CLOSING' THEN 1 ELSE 0 END) as closing_count,
            SUM(CASE WHEN status = 'PENDING' THEN 1 ELSE 0 END) as pending_count,
            SUM(CASE WHEN status = 'FAILED' THEN 1 ELSE 0 END) as failed_count
        ")->first();
        $shipperTypeCounts = $this->getShipperTypeCount('month');
        return (object) array_merge(
            (array) $baseStats,
            $shipperTypeCounts
        );
    }

    private function getShipperTypeCount($period = 'today')
    {
        $query = Activity::join('shippers', 'activities.shipper_id', '=', 'shippers.id');
        if ($period === 'today') {
            $query->whereDate('activities.report_date', now()->toDateString());
        } elseif ($period === 'week') {
            $query->whereBetween('activities.report_date', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ]);
        } elseif ($period === 'month') {
            $query->whereYear('activities.report_date', now()->year)
                  ->whereMonth('activities.report_date', now()->month);
        }
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isMarketing()) {
                $query->where('activities.user_id', Auth::id());
            }
        }
        $result = $query->selectRaw("
            SUM(CASE WHEN shippers.shipper_type = 'DIRECT SHIPPER' THEN 1 ELSE 0 END) as direct_shipper_count,
            SUM(CASE WHEN shippers.shipper_type = 'FORWARDING' THEN 1 ELSE 0 END) as forwarding_count,
            SUM(CASE WHEN shippers.shipper_type = 'TRADING' THEN 1 ELSE 0 END) as trading_count,
            SUM(CASE WHEN shippers.shipper_type = 'EMKL / TRANSPORTER' THEN 1 ELSE 0 END) as emkl_count
        ")->first();
        return [
            'direct_shipper_count' => $result->direct_shipper_count ?? 0,
            'forwarding_count' => $result->forwarding_count ?? 0,
            'trading_count' => $result->trading_count ?? 0,
            'emkl_count' => $result->emkl_count ?? 0,
        ];
    }

    public function exportExcel(Request $request)
    {
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');
        $fileName = 'activities_' 
                . Carbon::parse($dateFrom)->format('Y-m-d') 
                . '_to_' 
                . Carbon::parse($dateTo)->format('Y-m-d') 
                . '.xlsx';
        return Excel::download(
            new ActivitiesExport($dateFrom, $dateTo),
            $fileName
        );
    }
}
