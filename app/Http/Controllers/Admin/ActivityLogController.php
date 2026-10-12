<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = ActivityLog::with('user')
            ->when($request->module, fn ($q) => $q->where('module', $request->module))
            ->when($request->action, fn ($q) => $q->where('action', $request->action))
            ->orderByDesc('created_at')
            ->paginate(25);

        return view('admin.activity_logs.index', compact('logs'));
    }
}
