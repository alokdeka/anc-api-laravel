<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::latest();

        if ($request->has('is_read') && $request->is_read !== 'all') {
            $query->where('is_read', $request->boolean('is_read'));
        }

        return response()->json($query->paginate(20));
    }

    public function markAsRead(Request $request, ContactMessage $message)
    {
        $message->update(['is_read' => true]);

        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'updated',
            'model_type'  => 'ContactMessage',
            'model_id'    => $message->id,
            'description' => "Marked contact message from {$message->name} as read",
            'ip_address'  => $request->ip(),
        ]);

        return response()->json(['message' => 'Message marked as read']);
    }

    public function destroy(Request $request, ContactMessage $message)
    {
        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'deleted',
            'model_type'  => 'ContactMessage',
            'model_id'    => $message->id,
            'description' => "Deleted contact message from {$message->name}",
            'ip_address'  => $request->ip(),
        ]);

        $message->delete();

        return response()->json(['message' => 'Message deleted']);
    }
}
