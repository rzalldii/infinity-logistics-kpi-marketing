<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Shipper;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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
        $weeklyReport = $this->getWeeklyReport();
        $monthlyReport = $this->getMonthlyReport();

        return view('activities.index', compact('activities', 'shippers', 'weeklyReport', 'monthlyReport'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'report_date'   => 'required|date',
            'concept_type'  => 'required|in:new_shipper,follow_up',
            'shipper_id'    => 'required|exists:shippers,id',
            'activity_type' => 'required|in:visit,call',
            'visit_date'    => 'nullable|date',
            'prospect'      => 'nullable|string',
            'status'        => 'nullable|in:CLOSING,PENDING,FAILED',
            'status_detail' => 'nullable|string',
        ]);
        $validated['user_id'] = Auth::id();
        Activity::create($validated);
        return response()->json(['success' => 'Data added successfully!']);
    }

    public function edit($id): JsonResponse
    {
        $activities = Activity::findOrFail($id);
        return response()->json($activities);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $activities = Activity::findOrFail($id);
        $validated = $request->validate([
            'report_date'   => 'required|date',
            'concept_type'  => 'required|in:new_shipper,follow_up',
            'shipper_id'    => 'required|exists:shippers,id',
            'activity_type' => 'required|in:visit,call',
            'visit_date'    => 'nullable|date',
            'prospect'      => 'nullable|string',
            'status'        => 'nullable|in:CLOSING,PENDING,FAILED',
            'status_detail' => 'nullable|string',
        ]);
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isMarketing() && $activities->user_id !== Auth::id()) {
                return response()->json(['error' => 'You cannot edit other people\'s data!'], 403);
            }
        }
        $activities->update($validated);
        return response()->json(['success' => 'Data updated successfully!']);
    }

    public function destroy($id): JsonResponse
    {
        $activities = Activity::findOrFail($id);
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isMarketing() && $activities->user_id !== Auth::id()) {
                return response()->json(['error' => 'You cannot delete other people\'s data!'], 403);
            }
        }
        $activities->delete();
        return response()->json(['success' => 'Data deleted successfully!']);
    }

    private function getWeeklyReport()
    {
        $query = Activity::whereBetween('report_date', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ]);

        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isMarketing()) {
                $query->where('user_id', Auth::id());
            }
        }

        return $query->selectRaw("
            SUM(CASE WHEN concept_type = 'new_shipper' THEN 1 ELSE 0 END) as new_shipper_count,
            SUM(CASE WHEN concept_type = 'follow_up'   THEN 1 ELSE 0 END) as follow_up_count,
            SUM(CASE WHEN activity_type = 'visit'      THEN 1 ELSE 0 END) as visit_count,
            SUM(CASE WHEN activity_type = 'call'       THEN 1 ELSE 0 END) as call_count,
            SUM(CASE WHEN status = 'CLOSING'           THEN 1 ELSE 0 END) as closing_count,
            SUM(CASE WHEN status = 'PENDING'           THEN 1 ELSE 0 END) as pending_count,
            SUM(CASE WHEN status = 'FAILED'            THEN 1 ELSE 0 END) as failed_count
        ")->first();
    }

    private function getMonthlyReport()
    {
        $query = Activity::whereYear('report_date', now()->year)
            ->whereMonth('report_date', now()->month);

        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isMarketing()) {
                $query->where('user_id', Auth::id());
            }
        }

        return $query->selectRaw("
            SUM(CASE WHEN concept_type = 'new_shipper' THEN 1 ELSE 0 END) as new_shipper_count,
            SUM(CASE WHEN concept_type = 'follow_up'   THEN 1 ELSE 0 END) as follow_up_count,
            SUM(CASE WHEN activity_type = 'visit'      THEN 1 ELSE 0 END) as visit_count,
            SUM(CASE WHEN activity_type = 'call'       THEN 1 ELSE 0 END) as call_count,
            SUM(CASE WHEN status = 'CLOSING'           THEN 1 ELSE 0 END) as closing_count,
            SUM(CASE WHEN status = 'PENDING'           THEN 1 ELSE 0 END) as pending_count,
            SUM(CASE WHEN status = 'FAILED'            THEN 1 ELSE 0 END) as failed_count
        ")->first();
    }
}
