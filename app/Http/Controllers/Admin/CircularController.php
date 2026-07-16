<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Circular;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CircularController extends Controller
{
    public function index(Request $request)
    {
        $query = Circular::with(['author', 'media'])->latest();

        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }
        if ($request->filled('is_published')) {
            $query->where('is_published', $request->boolean('is_published'));
        }

        return response()->json($query->paginate(20));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string',
            'content'      => 'nullable|string',
            'summary'      => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'is_important' => 'boolean',
            'external_url' => 'nullable|string|max:1000',
        ]);

        $data['slug']      = Str::slug($data['title']) . '-' . Str::random(5);
        $data['author_id'] = $request->user()->id;

        $circular = Circular::create($data);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $extension = $file->getClientOriginalExtension();
            $circular->addMediaFromRequest('attachment')
                     ->usingFileName('attachment-' . time() . '-' . Str::random(5) . '.' . $extension)
                     ->toMediaCollection('attachments');
        }

        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'created',
            'model_type'  => 'Circular',
            'model_id'    => $circular->id,
            'description' => "Created circular: {$circular->title}",
            'new_values'  => $data,
            'ip_address'  => $request->ip(),
        ]);

        return response()->json($circular->load('media'), 201);
    }

    public function show(Circular $circular)
    {
        return response()->json($circular->load(['author', 'media']));
    }

    public function update(Request $request, Circular $circular)
    {
        $data = $request->validate([
            'title'        => 'sometimes|string|max:255',
            'category'     => 'sometimes|string',
            'content'      => 'nullable|string',
            'summary'      => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'is_important' => 'boolean',
            'external_url' => 'nullable|string|max:1000',
        ]);

        $oldValues = $circular->toArray();
        $circular->update($data);

        if ($request->hasFile('attachment')) {
            $circular->clearMediaCollection('attachments');
            $file = $request->file('attachment');
            $extension = $file->getClientOriginalExtension();
            $circular->addMediaFromRequest('attachment')
                     ->usingFileName('attachment-' . time() . '-' . Str::random(5) . '.' . $extension)
                     ->toMediaCollection('attachments');
        }

        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'updated',
            'model_type'  => 'Circular',
            'model_id'    => $circular->id,
            'description' => "Updated circular: {$circular->title}",
            'old_values'  => $oldValues,
            'new_values'  => $data,
            'ip_address'  => $request->ip(),
        ]);

        return response()->json($circular->load('media'));
    }

    public function destroy(Request $request, Circular $circular)
    {
        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'deleted',
            'model_type'  => 'Circular',
            'model_id'    => $circular->id,
            'description' => "Deleted circular: {$circular->title}",
            'ip_address'  => $request->ip(),
        ]);

        $circular->delete();
        return response()->json(['message' => 'Circular deleted.']);
    }
}
