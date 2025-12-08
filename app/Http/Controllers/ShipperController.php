<?php

namespace App\Http\Controllers;

use App\Models\Shipper;
use App\Models\User;
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
        $users = User::whereIn('role', ['marketing', 'admin', 'super_admin'])->where('id', '!=', Auth::id())->orderBy('name')->get();
        return view('shippers.index', compact('shippers', 'users'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'shipper_name' => 'required|string',
            'shipper_type' => 'required|in:DIRECT SHIPPER,FORWARDING,TRADING,EMKL / TRANSPORTER',
            'shipper_city' => 'required|string',
            'shipper_address' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'email_address' => 'nullable|email',
            'export' => 'nullable|string',
            'import' => 'nullable|string',
            'domestic' => 'nullable|string',
            'commodity' => 'required|string',
            'input_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        $validated['user_id'] = Auth::id();
        $shipper = Shipper::create($validated);
        return response()->json($shipper, 201);
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
            'shipper_name' => 'required|string',
            'shipper_type' => 'required|in:DIRECT SHIPPER,FORWARDING,TRADING,EMKL / TRANSPORTER',
            'shipper_city' => 'required|string',
            'shipper_address' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'email_address' => 'nullable|email',
            'export' => 'nullable|string',
            'import' => 'nullable|string',
            'domestic' => 'nullable|string',
            'commodity' => 'required|string',
            'input_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isMarketing() && $shipper->user_id !== Auth::id()) {
                return response()->json(null, 403);
            }
        }
        $shipper->update($validated);
        return response()->json($shipper, 200);
    }

    public function destroy($id): JsonResponse
    {
        $shipper = Shipper::findOrFail($id);
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isMarketing() && $shipper->user_id !== Auth::id()) {
                return response()->json(null, 403);
            }
        }
        $shipper->delete();
        return response()->json(null, 204);
    }
}
