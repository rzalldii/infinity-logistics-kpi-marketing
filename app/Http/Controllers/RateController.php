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

class RateController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $rates = Rate::latest()->get();
            return response()->json($rates);
        }
        $rates = Rate::latest()->get();
        $users = User::whereIn('role', ['MARKETING','ADMIN'])->where('id', '!=', Auth::id())->orderBy('name')->get();
        return view('rates.index', compact('rates', 'users'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pol' => 'required|string',
            'pod' => 'required|string',
            'container_type' => 'required|in:GP,RF,OT',
            'container_20' => 'nullable|string|max:10',
            'container_40' => 'nullable|string|max:10',
            'liner' => 'required|string',
            'free_time' => 'nullable|string',
            'valid_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        $pols = array_map('trim', explode(',', $validated['pol']));
        $pods = array_map('trim', explode(',', $validated['pod']));
        $pols = array_filter($pols, fn($item) => !empty($item));
        $pods = array_filter($pods, fn($item) => !empty($item));
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
        $validated = $request->validate([
            'pol' => 'required|string',
            'pod' => 'required|string',
            'container_type' => 'required|in:GP,RF,OT',
            'container_20' => 'nullable|string|max:10',
            'container_40' => 'nullable|string|max:10',
            'liner' => 'required|string',
            'free_time' => 'nullable|string',
            'valid_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isMarketing() && $rate->user_id !== Auth::id()) {
                return response()->json(null, 403);
            }
        }
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
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isMarketing() && $rate->user_id !== Auth::id()) {
                return response()->json(null, 403);
            }
        }
        $rate->delete();
        return response()->json(null, 204);
    }

    public function export()
    {
        $rates = Rate::latest()->get();
        $fileName = 'Checking Rates ' . date('Y-m-d') . '.xlsx';
        Audit::create([
            'auditable_type' => 'Rate',
            'auditable_id' => 0,
            'event' => 'exported',
            'user_id' => Auth::id(),
            'description' => 'Exported ' . $rates->count(),
            'old_values' => null,
            'new_values' => request()->all(),
        ]);
        return Excel::download(new RatesExport($rates), $fileName);
    }
}
