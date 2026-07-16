<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Circular;
use App\Models\Nurse;
use App\Models\Institute;
use App\Models\Examination;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_registrations'     => Nurse::count(),
            'active_registrations'    => Nurse::where('status', 'active')->count(),
            'expiring_soon'           => Nurse::where('status', 'active')
                                              ->whereBetween('expiry_date', [now(), now()->addDays(30)])
                                              ->count(),
            'pending_registrations'   => Nurse::where('status', 'suspended')->count(),
            'total_institutes'        => Institute::count(),
            'active_institutes'       => Institute::where('status', 'active')->count(),
            'total_circulars'         => Circular::published()->count(),
            'draft_circulars'         => Circular::where('is_published', false)->count(),
            'upcoming_exams'          => Examination::upcoming()->count(),
        ];

        $recent_circulars = Circular::latest()->limit(5)->get(['id', 'title', 'is_published', 'created_at']);
        $recent_audit     = AuditLog::with('user')->latest('created_at')->limit(10)->get();

        return response()->json([
            'stats'            => $stats,
            'recent_circulars' => $recent_circulars,
            'recent_activity'  => $recent_audit,
        ]);
    }
}
