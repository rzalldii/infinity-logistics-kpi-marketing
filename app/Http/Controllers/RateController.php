<?php

namespace App\Http\Controllers;

use App\Models\Rate;
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
        return view('rates.index', compact('rates'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pol' => 'required|string|max:255',
            'pod' => 'required|string|max:255',
            'container_type' => 'required|in:GP,RF,OT,HC',
            'container_20' => 'nullable|string|max:10',
            'container_40' => 'nullable|string|max:10',
            'liner' => 'required|string|max:255',
            'free_time' => 'nullable|string|max:255',
            'valid_date' => 'required|date',
            'notes' => 'nullable|string|max:255',
        ]);
        $pods = array_map('trim', explode(',', $validated['pod']));
        $pods = array_filter($pods, function($pod) {
            return !empty($pod);
        });
        foreach ($pods as $pod) {
            Rate::create([
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
        }
        return response()->json(['success' => 'Data added successfully!']);
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
            'pol' => 'required|string|max:255',
            'pod' => 'required|string|max:255',
            'container_type' => 'required|in:GP,RF,OT,HC',
            'container_20' => 'nullable|string|max:10',
            'container_40' => 'nullable|string|max:10',
            'liner' => 'required|string|max:255',
            'free_time' => 'nullable|string|max:255',
            'valid_date' => 'required|date',
            'notes' => 'nullable|string|max:255',
        ]);
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isMarketing() && $rate->user_id !== Auth::id()) {
                return response()->json(['error' => 'You cannot edit other people\'s data!'], 403);
            }
        }
        if (strpos($validated['pod'], ',') !== false) {
            $pods = array_map('trim', explode(',', $validated['pod']));
            $pods = array_filter($pods, function($pod) {
                return !empty($pod);
            });
            $rate->update([
                'pol' => $validated['pol'],
                'pod' => $pods[0],
                'container_type' => $validated['container_type'],
                'container_20' => $validated['container_20'],
                'container_40' => $validated['container_40'],
                'liner' => $validated['liner'],
                'free_time' => $validated['free_time'],
                'valid_date' => $validated['valid_date'],
                'notes' => $validated['notes'],
            ]);
            for ($i = 1; $i < count($pods); $i++) {
                Rate::create([
                    'pol' => $validated['pol'],
                    'pod' => $pods[$i],
                    'container_type' => $validated['container_type'],
                    'container_20' => $validated['container_20'],
                    'container_40' => $validated['container_40'],
                    'liner' => $validated['liner'],
                    'free_time' => $validated['free_time'],
                    'valid_date' => $validated['valid_date'],
                    'notes' => $validated['notes'],
                    'user_id' => Auth::id(),
                ]);
            }
            return response()->json();
        }
        $rate->update($validated);
        return response()->json(['success' => 'Data updated successfully!']);
    }

    public function destroy($id): JsonResponse
    {
        $rate = Rate::findOrFail($id);
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isMarketing() && $rate->user_id !== Auth::id()) {
                return response()->json(['error' => 'You cannot delete other people\'s data!'], 403);
            }
        }
        $rate->delete();
        return response()->json(['success' => 'Data deleted successfully!']);
    }
}
