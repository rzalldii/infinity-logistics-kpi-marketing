<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Shipper;
use App\Models\User;
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
        $shipperIds = $auditLogs
            ->where('auditable_type', 'Activity')
            ->flatMap(function ($log) {
                return array_filter([
                    data_get($log->old_values, 'shipper_id'),
                    data_get($log->new_values, 'shipper_id'),
                ]);
            })
            ->unique()
            ->values()
            ->toArray();

        $shippers = !empty($shipperIds)
            ? Shipper::whereIn('id', $shipperIds)->pluck('shipper_name', 'id')
            : collect();
        $logs = $auditLogs->map(function ($log) use ($shippers) {
            $type = match ($log->auditable_type) {
                'Rate' => 'Checking Rates',
                'Shipper' => 'Touch Shippers',
                'Activity' => 'Report Activities',
                'User' => 'User Management',
                default => $log->auditable_type
            };
            $oldValues = $log->old_values ? (array) $log->old_values : [];
            $newValues = $log->new_values ? (array) $log->new_values : [];
            if ($log->auditable_type === 'Activity') {
                if (isset($oldValues['shipper_id'])) {
                    $oldValues['shipper_name'] = $shippers[$oldValues['shipper_id']] ?? $oldValues['shipper_id'];
                    unset($oldValues['shipper_id']);
                }
                if (isset($newValues['shipper_id'])) {
                    $newValues['shipper_name'] = $shippers[$newValues['shipper_id']] ?? $newValues['shipper_id'];
                    unset($newValues['shipper_id']);
                }
            }
            return [
                'id' => $log->id,
                'type' => $type,
                'auditable_type' => $log->auditable_type,
                'auditable_id' => $log->auditable_id,
                'user' => $log->user,
                'description' => $log->description,
                'action' => ucfirst($log->event),
                'created_at' => $log->created_at,
                'old_values' => $oldValues,
                'new_values' => $newValues,
            ];
        });
        $users = User::whereIn('role', ['MARKETING', 'ADMIN', 'SUPER ADMIN'])
            ->where('id', '!=', Auth::id())
            ->orderBy('name')
            ->get();
        return view('pages.audit', compact('logs', 'users'));
    }
}
