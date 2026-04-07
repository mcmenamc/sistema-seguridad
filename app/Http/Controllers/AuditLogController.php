<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('action', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->from) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->to) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        return Inertia::render('Logs/Index', [
            'logs' => $query->latest()->paginate(25)->withQueryString(),
            'users' => User::orderBy('name')->get(['id', 'name']),
            'filters' => $request->only(['search', 'from', 'to', 'user_id']),
        ]);
    }
}
