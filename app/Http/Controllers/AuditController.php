<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Rate;
use App\Models\Shipper;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditController extends Controller
{
    public function index()
    {
        $auditLogsQuery = Audit::with('user')
            ->orderBy('created_at', 'desc');
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            $auditLogsQuery->where('user_id', Auth::id());
        }
        $auditLogs = $auditLogsQuery->get();
        $logs = $auditLogs->map(function($log) {
            $type = match($log->auditable_type) {
                'Rate' => 'Checking Rates',
                'Shipper' => 'Touch Shippers',
                'Activity' => 'Report Activities',
                default => $log->auditable_type
            };
            return [
                'id' => $log->id,
                'type' => $type,
                'user' => $log->user,
                'description' => $log->description,
                'detail' => ucfirst($log->event),
                'created_at' => $log->created_at
            ];
        });
        $totalRates = Rate::count();
        $totalShippers = Shipper::count();
        $totalActivities = Activity::count();
        $totalLogs = $logs->count();
        $users = User::whereIn('role', ['marketing', 'admin', 'super_admin'])
            ->where('id', '!=', Auth::id())
            ->orderBy('name')
            ->get();
        return view('pages.audit', compact('logs', 'totalRates', 'totalShippers', 'totalActivities', 'totalLogs', 'users'));
    }
}
