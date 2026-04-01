<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use App\Models\User;
use App\Models\Audit;
use App\Exports\RatesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RateController extends Controller
{
    public function index(Request $request)
    {
        $rates = Rate::latest()->get();
        if ($request->ajax()) {
            return response()->json($rates);
        }
        $users = User::whereIn('role', ['MARKETING','ADMIN'])->where('id', '!=', Auth::id())->orderBy('name')->get();
        return view('rates.index', compact('rates', 'users'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pol' => 'required|string',
            'pod' => 'required|string',
            'container_type' => 'required|in:GP,RF',
            'container_20' => 'nullable|string|max:10',
            'container_40' => 'nullable|string|max:10',
            'liner' => 'required|string',
            'free_time' => 'nullable|string',
            'valid_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        $pols = array_values(array_filter(array_map('trim', explode(',', $validated['pol'])), fn($item) => !empty($item)));
        $pods = array_values(array_filter(array_map('trim', explode(',', $validated['pod'])), fn($item) => !empty($item)));
        $createdRates = [];
        $duplicateCount = 0;
        foreach ($pols as $pol) {
            foreach ($pods as $pod) {
                $exists = Rate::where('pol', $pol)
                    ->where('pod', $pod)
                    ->where('container_type', $validated['container_type'])
                    ->where('liner', $validated['liner'])
                    ->where('valid_date', $validated['valid_date'])
                    ->exists();
                if ($exists) {
                    $duplicateCount++;
                    continue;
                }
                $rate = Rate::create([
                    'pol' => $pol,
                    'pod' => $pod,
                    'container_type' => $validated['container_type'],
                    'container_20' => $validated['container_20'],
                    'container_40' => $validated['container_40'],
                    'liner' => $validated['liner'],
                    'free_time' => $validated['free_time'],
                    'valid_date' => $validated['valid_date'],
                    'notes' => $validated['notes'],
                    'user_id' => Auth::id(),
                ]);
                $createdRates[] = $rate;
            }
        }
        if (empty($createdRates) && $duplicateCount > 0) {
            return response()->json([
                'data' => []
            ], 422);
        }
        return response()->json($createdRates, 201);
    }

    public function edit($id): JsonResponse
    {
        $rate = Rate::findOrFail($id);
        return response()->json($rate);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $rate = Rate::findOrFail($id);
        if (Auth::user()->isMarketing() && $rate->user_id !== Auth::id()) {
            return response()->json(null, 403);
        }
        $validated = $request->validate([
            'pol' => 'required|string',
            'pod' => 'required|string',
            'container_type' => 'required|in:GP,RF',
            'container_20' => 'nullable|string|max:10',
            'container_40' => 'nullable|string|max:10',
            'liner' => 'required|string',
            'free_time' => 'nullable|string',
            'valid_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        $exists = Rate::where('pol', $validated['pol'])
            ->where('pod', $validated['pod'])
            ->where('container_type', $validated['container_type'])
            ->where('liner', $validated['liner'])
            ->where('valid_date', $validated['valid_date'])
            ->where('id', '!=', $id)
            ->exists();
        if ($exists) {
            return response()->json(['data' => []], 422);
        }
        $rate->update($validated);
        return response()->json($rate, 200);
    }

    public function destroy($id): JsonResponse
    {
        $rate = Rate::findOrFail($id);
        if (Auth::user()->isMarketing() && $rate->user_id !== Auth::id()) {
            return response()->json(null, 403);
        }
        $rate->delete();
        return response()->json(null, 204);
    }

    public function export(Request $request)
    {
        $query = Rate::query();
        $filterInfo = [];
        if ($request->filled('data')) {
            if ($request->data === 'mine') {
                $query->where('user_id', Auth::id());
                $filterInfo[] = "SCOPE : My Data";
            } elseif (is_numeric($request->data)) {
                if (Auth::user()->isMarketing() && $request->data != Auth::id()) {
                    abort(403);
                }
                $query->where('user_id', $request->data);
                $user = User::find($request->data);
                $userName = $user ? $user->name : $request->data;
                $filterInfo[] = "SCOPE : Data {$userName}";
            }
        }
        if ($request->filled('pol')) {
            $query->where('pol', $request->pol);
            $filterInfo[] = "POL : {$request->pol}";
        }
        if ($request->filled('pod')) {
            $query->where('pod', $request->pod);
            $filterInfo[] = "POD : {$request->pod}";
        }
        if ($request->filled('liner')) {
            $query->where('liner', $request->liner);
            $filterInfo[] = "LINER : {$request->liner}";
        }
        if ($request->filled('valid_date')) {
            try {
                $date = Carbon::createFromFormat('M y', $request->valid_date);
                $query->whereYear('valid_date', $date->year)
                    ->whereMonth('valid_date', $date->month);
                $filterInfo[] = "VALID : {$request->valid_date}";
            } catch (\Exception $e) {
                $query->whereDate('valid_date', $request->valid_date);
                $filterInfo[] = "VALID : {$request->valid_date}";
            }
        }
        $rates = $query->get();
        $filterString = empty($filterInfo) ? 'All Data' : implode(', ', $filterInfo);
        $description = "Filters : [ {$filterString} ]";
        $fileName = 'Checking Rates ' . date('Y-m-d') . '.xlsx';
        Audit::create([
            'auditable_type' => 'Rate',
            'auditable_id' => 0,
            'event' => 'exported',
            'user_id' => Auth::id(),
            'description' => $description,
        ]);
        return Excel::download(new RatesExport($rates), $fileName);
    }
}
