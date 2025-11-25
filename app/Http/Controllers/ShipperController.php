<?php

namespace App\Http\Controllers;

use App\Models\Shipper;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ShipperController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $shippers = Shipper::latest()->get();
            return response()->json($shippers);
        }
        $shippers = Shipper::latest()->get();
        return view('shippers.index', compact('shippers'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'shipper_name' => 'required|string|max:255',
            'city_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'email_address' => 'nullable|email|max:255',
            'input' => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ]);
        $validated['user_id'] = Auth::id();
        Shipper::create($validated);
        return response()->json(['success' => 'Data added successfully!']);
    }

    public function edit($id): JsonResponse
    {
        $shipper = Shipper::findOrFail($id);
        return response()->json($shipper);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $shipper = Shipper::findOrFail($id);
        $validated = $request->validate([
            'shipper_name' => 'required|string|max:255',
            'city_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'email_address' => 'nullable|email|max:255',
            'input' => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ]);
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isMarketing() && $shipper->user_id !== Auth::id()) {
                return response()->json(['error' => 'You cannot edit other people\'s data!'], 403);
            }
        }
        $shipper->update($validated);
        return response()->json(['success' => 'Data updated successfully!']);
    }

    public function destroy($id): JsonResponse
    {
        $shipper = Shipper::findOrFail($id);
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isMarketing() && $shipper->user_id !== Auth::id()) {
                return response()->json(['error' => 'You cannot delete other people\'s data!'], 403);
            }
        }
        $shipper->delete();
        return response()->json(['success' => 'Data deleted successfully!']);
    }
}
