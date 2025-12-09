<?php

namespace App\Http\Controllers;

use App\Models\Audit;
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
                'auditable_type' => $log->auditable_type,
                'auditable_id' => $log->auditable_id,
                'user' => $log->user,
                'description' => $log->description,
                'action' => ucfirst($log->event),
                'created_at' => $log->created_at,
                'old_values' => $log->old_values, 
                'new_values' => $log->new_values,
            ];
        });
        $users = User::whereIn('role', ['marketing', 'admin', 'super_admin'])
            ->where('id', '!=', Auth::id())
            ->orderBy('name')
            ->get();
        return view('pages.audit', compact('logs', 'users'));
    }
}
