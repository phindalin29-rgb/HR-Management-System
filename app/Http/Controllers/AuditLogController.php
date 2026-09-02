<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;
use Maatwebsite\Excel\Facades\Excel;

class AuditLogController extends Controller
{
    /** Display Activity / Audit Log (Admin only) */
    public function index(Request $request)
    {
        $query = AuditLog::query()->orderByDesc('created_at');

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs    = $query->paginate(25)->withQueryString();
        $modules = AuditLog::select('module')->distinct()->pluck('module');

        return view('usermanagement.activitylog', compact('logs', 'modules'));
    }

    /** Export the audit log to an Excel file */
    public function export(Request $request)
    {
        $query = AuditLog::query()->orderByDesc('created_at');

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $rows = $query->get()->map(function ($log) {
            return [
                'Date'       => $log->created_at,
                'User'       => $log->user_name,
                'Action'     => $log->action,
                'Module'     => $log->module,
                'Record ID'  => $log->record_id,
                'IP Address' => $log->ip_address,
                'Details'    => $log->description,
            ];
        });

        return Excel::download(
            new \App\Exports\AuditLogExport($rows),
            'audit-log-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}
