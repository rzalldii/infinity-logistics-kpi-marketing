<?php

namespace App\Http\Controllers;

use App\Models\Shipper;
use App\Models\User;
use App\Models\Audit;
use App\Exports\ShippersExport;
use Maatwebsite\Excel\Facades\Excel;
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
        $users = User::whereIn('role', ['MARKETING','ADMIN'])->where('id', '!=', Auth::id())->orderBy('name')->get();
        return view('shippers.index', compact('shippers', 'users'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'shipper_name' => 'required|string',
            'shipper_concept' => 'required|in:NEW SHIPPER,EXISTING SHIPPER',
            'shipper_type' => 'required|in:DIRECT SHIPPER,FORWARDING,VENDORING,TRADING',
            'shipper_city' => 'required|string',
            'shipper_address' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'email_address' => 'nullable|email',
            'export' => 'nullable|string',
            'import' => 'nullable|string',
            'domestic' => 'nullable|string',
            'commodity' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        $exists = Shipper::where('shipper_name', $validated['shipper_name'])
            ->where('shipper_type', $validated['shipper_type'])
            ->where('shipper_concept', $validated['shipper_concept'])
            ->where('shipper_city', $validated['shipper_city'])
            ->exists();
        if ($exists) {
            return response()->json(['data' => []], 422);
        }
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
            'shipper_concept' => 'required|in:NEW SHIPPER,EXISTING SHIPPER',
            'shipper_type' => 'required|in:DIRECT SHIPPER,FORWARDING,VENDORING,TRADING',
            'shipper_city' => 'required|string',
            'shipper_address' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'email_address' => 'nullable|email',
            'export' => 'nullable|string',
            'import' => 'nullable|string',
            'domestic' => 'nullable|string',
            'commodity' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isMarketing() && $shipper->user_id !== Auth::id()) {
                return response()->json(null, 403);
            }
        }
        $exists = Shipper::where('shipper_name', $validated['shipper_name'])
            ->where('shipper_type', $validated['shipper_type'])
            ->where('shipper_concept', $validated['shipper_concept'])
            ->where('shipper_city', $validated['shipper_city'])
            ->where('id', '!=', $id)
            ->exists();
        if ($exists) {
            return response()->json(['data' => []], 422);
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

    public function export(Request $request)
    {
        $query = Shipper::query();
        $filterInfo = [];
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
        if ($request->filled('shipper_concept')) {
            $query->where('shipper_concept', $request->shipper_concept);
            $filterInfo[] = "CONCEPT : {$request->shipper_concept}";
        }
        if ($request->filled('shipper_type')) {
            $query->where('shipper_type', $request->shipper_type);
            $filterInfo[] = "TYPE : {$request->shipper_type}";
        }
        if ($request->filled('shipper_city')) {
            $query->where('shipper_city', $request->shipper_city);
            $filterInfo[] = "CITY : {$request->shipper_city}";
        }
        $shippers = $query->get();
        $filterString = empty($filterInfo) ? 'All Data' : implode(', ', $filterInfo);
        $description = "Filters : [ {$filterString} ]";
        $fileName = 'Touch Shippers ' . date('Y-m-d') . '.xlsx';
        Audit::create([
            'auditable_type' => 'Shipper',
            'auditable_id' => 0,
            'event' => 'exported',
            'user_id' => Auth::id(),
            'description' => $description,
        ]);
        return Excel::download(new ShippersExport($shippers), $fileName);
    }
}
