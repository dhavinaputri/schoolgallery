<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::orderByDesc('created_at')->paginate(20);
        return view('admin.activity.index', compact('logs'));
    }
}


