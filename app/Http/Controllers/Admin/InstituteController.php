<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Institute;
use Illuminate\Http\Request;

class InstituteController extends Controller
{
    public function index(Request $request)
    {
        $query = Institute::latest();
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        return response()->json($query->paginate(20));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'file_path'    => 'nullable|string',
            'external_url' => 'nullable|url',
            'status'       => 'in:active,inactive',
        ]);

        $institute = Institute::create($data);

        AuditLog::create([
            'user_id' => $request->user()->id, 'action' => 'created',
            'model_type' => 'Institute', 'model_id' => $institute->id,
            'description' => "Added institute list: {$institute->title}",
            'ip_address' => $request->ip(),
        ]);

        return response()->json($institute, 201);
    }

    public function show(Institute $institute)
    {
        return response()->json($institute);
    }

    public function update(Request $request, Institute $institute)
    {
        $data = $request->validate([
            'title'        => 'sometimes|string|max:255',
            'file_path'    => 'nullable|string',
            'external_url' => 'nullable|url',
            'status'       => 'in:active,inactive',
        ]);

        $oldValues = $institute->toArray();
        $institute->update($data);

        AuditLog::create([
            'user_id' => $request->user()->id, 'action' => 'updated',
            'model_type' => 'Institute', 'model_id' => $institute->id,
            'description' => "Updated institute list: {$institute->title}",
            'old_values' => $oldValues, 'new_values' => $data,
            'ip_address' => $request->ip(),
        ]);

        return response()->json($institute);
    }

    public function destroy(Request $request, Institute $institute)
    {
        AuditLog::create([
            'user_id' => $request->user()->id, 'action' => 'deleted',
            'model_type' => 'Institute', 'model_id' => $institute->id,
            'description' => "Deleted institute list: {$institute->title}",
            'ip_address' => $request->ip(),
        ]);
        $institute->delete();
        return response()->json(['message' => 'Institute deleted.']);
    }
}
