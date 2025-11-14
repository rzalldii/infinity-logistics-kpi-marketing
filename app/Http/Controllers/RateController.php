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
            'container' => 'required|in:GP,RF',
            'container_20' => 'nullable|numeric|min:0',
            'container_40' => 'nullable|numeric|min:0',
            'liner' => 'required|string|max:255',
            'valid' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        $validated['user_id'] = Auth::id();
        Rate::create($validated);
        return response()->json(['success' => 'Data added successfully!']);
    }

    public function edit($id): JsonResponse
    {
        $rate = Rate::findOrFail($id);
        if (Auth::user()->isMarketing() && $rate->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized!'], 403);
        }
        return response()->json($rate);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $rate = Rate::findOrFail($id);
        $validated = $request->validate([
            'pol' => 'required|string|max:255',
            'pod' => 'required|string|max:255',
            'container' => 'required|in:GP,RF',
            'container_20' => 'nullable|numeric|min:0',
            'container_40' => 'nullable|numeric|min:0',
            'liner' => 'required|string|max:255',
            'valid' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isMarketing() && $rate->user_id !== Auth::id()) {
                return response()->json(['error' => 'You cannot edit other people\'s data!'], 403);
            }
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
