<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use App\Models\User;
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
        $users = User::whereIn('role', ['marketing'])->where('id', '!=', Auth::id())->orderBy('name')->get();
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
        $pods = array_map('trim', explode(',', $validated['pod']));
        $pods = array_filter($pods, function($pod) {
            return !empty($pod);
        });
        $createdRates = [];
        $duplicateCount = 0;
        foreach ($pods as $pod) {
            $exists = Rate::where('pol', $validated['pol'])
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
                'pol' => $validated['pol'],
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
        // $rate = Rate::findOrFail($id);
        // $validated = $request->validate([
        //     'pol' => 'required|string',
        //     'pod' => 'required|string',
        //     'container_type' => 'required|in:GP,RF,OT',
        //     'container_20' => 'nullable|string|max:10',
        //     'container_40' => 'nullable|string|max:10',
        //     'liner' => 'required|string',
        //     'free_time' => 'nullable|string',
        //     'valid_date' => 'required|date',
        //     'notes' => 'nullable|string',
        // ]);
        // if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
        //     if (Auth::user()->isMarketing() && $rate->user_id !== Auth::id()) {
        //         return response()->json(null, 403);
        //     }
        // }
        // if (strpos($validated['pod'], ',') !== false) {
        //     $pods = array_map('trim', explode(',', $validated['pod']));
        //     $pods = array_filter($pods, function($pod) {
        //         return !empty($pod);
        //     });
        //     $exists = Rate::where('pol', $validated['pol'])
        //         ->where('pod', $pods[0])
        //         ->where('container_type', $validated['container_type'])
        //         ->where('liner', $validated['liner'])
        //         ->where('valid_date', $validated['valid_date'])
        //         ->where('id', '!=', $id)
        //         ->exists();
        //     if (!$exists) {
        //         $rate->update([
        //             'pol' => $validated['pol'],
        //             'pod' => $pods[0],
        //             'container_type' => $validated['container_type'],
        //             'container_20' => $validated['container_20'],
        //             'container_40' => $validated['container_40'],
        //             'liner' => $validated['liner'],
        //             'free_time' => $validated['free_time'],
        //             'valid_date' => $validated['valid_date'],
        //             'notes' => $validated['notes'],
        //         ]);
        //     }
        //     for ($i = 1; $i < count($pods); $i++) {
        //         $existsNew = Rate::where('pol', $validated['pol'])
        //             ->where('pod', $pods[$i])
        //             ->where('container_type', $validated['container_type'])
        //             ->where('liner', $validated['liner'])
        //             ->where('valid_date', $validated['valid_date'])
        //             ->exists();
        //         if ($existsNew) {
        //             continue;
        //         }
        //         Rate::create([
        //             'pol' => $validated['pol'],
        //             'pod' => $pods[$i],
        //             'container_type' => $validated['container_type'],
        //             'container_20' => $validated['container_20'],
        //             'container_40' => $validated['container_40'],
        //             'liner' => $validated['liner'],
        //             'free_time' => $validated['free_time'],
        //             'valid_date' => $validated['valid_date'],
        //             'notes' => $validated['notes'],
        //             'user_id' => Auth::id(),
        //         ]);
        //     }
        //     return response()->json($rate, 200);
        // }
        // $exists = Rate::where('pol', $validated['pol'])
        //     ->where('pod', $validated['pod'])
        //     ->where('container_type', $validated['container_type'])
        //     ->where('liner', $validated['liner'])
        //     ->where('valid_date', $validated['valid_date'])
        //     ->where('id', '!=', $id)
        //     ->exists();
        // if ($exists) {
        //     return response()->json(['data' => []], 422);
        // }
        // $rate->update($validated);
        // return response()->json($rate, 200);
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
}
