<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Shipper;
use App\Models\User;
use App\Models\Audit;
use App\Exports\ActivitiesExport;
use App\Exports\MarketingExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with(['user', 'shipper'])->latest();
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isMarketing()) {
                $query->where('user_id', Auth::id());
            }
        }
        $closedActivitiesIds = Activity::select('id', 'parent_id', 'status_type')
            ->whereIn('status_type', ['CLOSING', 'FAILED'])
            ->get()
            ->map(function ($item) {
                return $item->parent_id ?? $item->id;
            })
            ->unique()->toArray();
        $allUserActivities = Activity::select('id', 'parent_id', 'sequence')
            ->when(Auth::user()->isMarketing(), function($q) {
                $q->where('user_id', Auth::id());
            })
            ->get();
        $latestActivityIds = $allUserActivities
            ->groupBy(function ($item) {
                return $item->parent_id ?? $item->id;
            })
            ->map(function ($group) {
                return $group->sortByDesc('sequence')->first()->id;
            })
            ->values()
            ->toArray();
        if ($request->ajax()) {
            return response()->json($query->get());
        }
        $activities = $query->get();
        $shippers = Shipper::orderBy('shipper_name')->get();
        $users = User::whereIn('role', ['MARKETING','ADMIN'])->where('id', '!=', Auth::id())->orderBy('name')->get();
        return view('activities.index', compact('activities', 'shippers', 'users', 'closedActivitiesIds', 'latestActivityIds'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:activities,id',
            'shipper_id' => 'required|exists:shippers,id',
            'activity_type' => 'required|in:VISIT,CALL',
            'visit_date' => 'nullable|date',
            'status_type' => 'required|in:CLOSING,PENDING,FAILED',
            'volume_20' => 'nullable|string|max:5',
            'volume_40' => 'nullable|string|max:5',
            'other_volume' => 'nullable|in:AIR FREIGHT,RAIL FREIGHT,ROAD FREIGHT,EMKL,LCL,OTHER BUSINESS',
            'profit' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);
        $validated['user_id'] = Auth::id();
        if (empty($request->parent_id)) {
            $validated['parent_id'] = null;
            $validated['sequence']  = 1;
        } else {
            $refActivity = Activity::findOrFail($request->parent_id);
            $rootId = $refActivity->parent_id ?? $refActivity->id;
            $lastChildSequence = Activity::where('parent_id', $rootId)->max('sequence');
            $nextSequence = ($lastChildSequence ?? 1) + 1;
            $validated['parent_id'] = $rootId;
            $validated['sequence']  = $nextSequence;
        }
        $activity = Activity::create($validated);
        if ($activity->status_type === 'CLOSING') {
            $shipper = Shipper::find($validated['shipper_id']);
            if ($shipper && $shipper->shipper_concept === 'NEW SHIPPER') {
                $shipper->update(['shipper_concept' => 'EXISTING SHIPPER']);
            }
        }
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
            'shipper_id' => 'required|exists:shippers,id',
            'activity_type' => 'required|in:VISIT,CALL',
            'visit_date' => 'nullable|date',
            'status_type' => 'required|in:CLOSING,PENDING,FAILED',
            'volume_20' => 'nullable|string|max:5',
            'volume_40' => 'nullable|string|max:5',
            'other_volume' => 'nullable|in:AIR FREIGHT,RAIL FREIGHT,ROAD FREIGHT,EMKL,LCL,OTHER BUSINESS',
            'profit' => 'nullable|string',
            'remarks' => 'nullable|string',
            'created_date' => 'nullable|date',
        ]);
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isMarketing() && $activity->user_id !== Auth::id()) {
                return response()->json(null, 403);
            }
            $createdDate = Carbon::parse($activity->created_at)->startOfDay(); 
            $today = Carbon::now()->startOfDay();
            $isDifferentDay = !$createdDate->equalTo($today);
            $reqStatus = $request->status_type;
            $dbStatus = $activity->status_type;
            $isClosingCase = ($dbStatus === 'CLOSING' || $reqStatus === 'CLOSING');
            if ($request->has('shipper_id') && $request->shipper_id != $activity->shipper_id) {
                if ($dbStatus === 'CLOSING' && $isDifferentDay) {
                    return response()->json(null, 403);
                }
            }
            if ($isClosingCase) {
                if ($createdDate->format('Y-m') !== $today->format('Y-m')) {
                     return response()->json(null, 403);
                }
            } else {
                if (!$createdDate->equalTo($today)) {
                    return response()->json(null, 403);
                }
            }
        }
        $oldStatus = $activity->status_type;
        $oldShipperId = $activity->shipper_id;
        $activity->fill(collect($validated)->except(['created_date'])->toArray());
        if ((Auth::user()->isSuperAdmin() || Auth::user()->isAdmin()) && $request->filled('created_date')) {
            $originalTimestamp = $activity->getOriginal('created_at'); 
            $originalTime = Carbon::parse($originalTimestamp)->format('H:i:s');
            $newTimestamp = $request->created_date . ' ' . $originalTime;
            $activity->created_at = $newTimestamp;
        }
        $activity->save();
        $currentShipper = $activity->shipper;
        if ($activity->status_type === 'CLOSING' && $currentShipper->shipper_concept === 'NEW SHIPPER') {
            $currentShipper->update(['shipper_concept' => 'EXISTING SHIPPER']);
        }
        if ($oldStatus === 'CLOSING' && $activity->status_type !== 'CLOSING') {
            $hasOtherClosings = Activity::where('shipper_id', $currentShipper->id)
                ->where('id', '!=', $id) 
                ->where('status_type', 'CLOSING')
                ->exists();
            if (!$hasOtherClosings) {
                $currentShipper->update(['shipper_concept' => 'NEW SHIPPER']);
            }
        }
        if ($oldShipperId != $activity->shipper_id) {
             $oldShipper = Shipper::find($oldShipperId);
             if ($oldShipper && $oldStatus === 'CLOSING') {
                 $hasOtherClosingsOld = Activity::where('shipper_id', $oldShipper->id)
                    ->where('status_type', 'CLOSING')
                    ->exists();
                 if (!$hasOtherClosingsOld) {
                    $oldShipper->update(['shipper_concept' => 'NEW SHIPPER']);
                 }
             }
             if ($activity->status_type === 'CLOSING' && $currentShipper->shipper_concept === 'NEW SHIPPER') {
                $currentShipper->update(['shipper_concept' => 'EXISTING SHIPPER']);
            }
        }
        return response()->json($activity, 200);
    }

    public function destroy($id): JsonResponse
    {
        $activity = Activity::findOrFail($id);
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isMarketing() && $activity->user_id !== Auth::id()) {
                return response()->json(null, 403);
            }
            $date = $activity->created_at->format('Y-m-d');
            $today = Carbon::now()->format('Y-m-d');
            if ($date !== $today) {
                return response()->json(null, 403);
            }
        }
        $shipperId = $activity->shipper_id;
        $wasClosing = ($activity->status_type === 'CLOSING');
        $activity->delete();
        if ($wasClosing) {
            $hasOtherClosings = Activity::where('shipper_id', $shipperId)
                ->where('status_type', 'CLOSING')
                ->exists();
            if (!$hasOtherClosings) {
                Shipper::where('id', $shipperId)
                       ->update(['shipper_concept' => 'NEW SHIPPER']);
            }
        }
        return response()->json(null, 204);
    }

    public function export(Request $request)
    {
        $query = Activity::query();
        $filterInfo = [];
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereDate('created_at', '>=', $request->date_from)
                ->whereDate('created_at', '<=', $request->date_to);
            $filterInfo[] = "DATE : {$request->date_from} To {$request->date_to}";
        }
        if ($request->filled('data')) {
            if ($request->data === 'mine') {
                $query->where('user_id', Auth::id());
                $filterInfo[] = "SCOPE : My Data";
            } elseif (is_numeric($request->data)) {
                $query->where('user_id', $request->data);
                $user = User::find($request->data);
                $userName = $user ? $user->name : $request->data;
                $filterInfo[] = "SCOPE : Data {$userName}";
            }
        }
        if (Auth::user()->isMarketing()) {
            $query->where('user_id', Auth::id());
            if (!in_array("SCOPE : My Data", $filterInfo)) {
                $filterInfo = array_filter($filterInfo, fn($v) => !str_contains($v, 'SCOPE'));
                $filterInfo[] = "SCOPE : My Data";
            }
        }
        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
            $filterInfo[] = "ACTIVITY : {$request->activity_type}";
        }
        if ($request->filled('status_type')) {
            $query->where('status_type', $request->status_type);
            $filterInfo[] = "STATUS : {$request->status_type}";
        }
        $activities = $query->latest()->get();
        $filterString = empty($filterInfo) ? 'All Data' : implode(', ', $filterInfo);
        $description = "Filters : [ {$filterString} ]";
        $dFrom = $request->date_from ?? date('Y-m-d');
        $dTo = $request->date_to ?? date('Y-m-d');
        $fileName = 'Activities ' . $dFrom . ' to ' . $dTo . '.xlsx';
        Audit::create([
            'auditable_type' => 'Activity',
            'auditable_id' => 0,
            'event' => 'exported',
            'user_id' => Auth::id(),
            'description' => $description,
        ]);
        if (Auth::user()->isMarketing()) {
            return Excel::download(new MarketingExport($activities, $dFrom, $dTo), $fileName);
        }
        return Excel::download(new ActivitiesExport($activities, $dFrom, $dTo), $fileName);
    }
}
