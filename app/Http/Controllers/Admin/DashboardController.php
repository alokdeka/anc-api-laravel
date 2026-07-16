<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Circular;
use App\Models\Institute;
use App\Models\Event;
use App\Models\Form;
use App\Models\Program;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_institutes'        => Institute::count(),
            'active_institutes'       => Institute::where('status', 'active')->count(),
            'total_circulars'         => Circular::count(),
            'draft_circulars'         => Circular::where('is_published', false)->count(),
            'total_events'            => Event::count(),
            'unread_messages'         => ContactMessage::where('is_read', false)->count(),
            'total_forms'             => Form::count(),
            'total_programs'          => Program::count(),
        ];

        $recent_circulars = Circular::latest()->limit(5)->get(['id', 'title', 'is_published', 'created_at']);
        $recent_messages  = ContactMessage::where('is_read', false)->latest()->limit(10)->get();

        return response()->json([
            'stats'            => $stats,
            'recent_circulars' => $recent_circulars,
            'recent_messages'  => $recent_messages,
        ]);
    }
}
