<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\AuditLog;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index()
    {
        $files = Storage::disk('public')->files('media');
        $media = [];

        foreach ($files as $file) {
            $media[] = [
                'name' => basename($file),
                'url' => asset('storage/' . $file),
                'size' => Storage::disk('public')->size($file),
                'last_modified' => Storage::disk('public')->lastModified($file),
            ];
        }

        // Sort by newest
        usort($media, function($a, $b) {
            return $b['last_modified'] <=> $a['last_modified'];
        });

        return response()->json(['data' => $media]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // max 10MB
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $filename = pathinfo($originalName, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        
        // Clean filename
        $cleanFilename = Str::slug($filename) . '-' . time() . '.' . $extension;

        $path = $file->storeAs('media', $cleanFilename, 'public');

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'created',
            'model_type' => 'Media',
            'description' => "Uploaded media file: {$cleanFilename}",
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'File uploaded successfully',
            'data' => [
                'name' => $cleanFilename,
                'url' => asset('storage/' . $path),
            ]
        ], 201);
    }

    public function destroy(Request $request, $filename)
    {
        $path = 'media/' . $filename;
        
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            
            AuditLog::create([
                'user_id' => $request->user()->id,
                'action' => 'deleted',
                'model_type' => 'Media',
                'description' => "Deleted media file: {$filename}",
                'ip_address' => $request->ip(),
            ]);

            return response()->json(['message' => 'File deleted successfully']);
        }

        return response()->json(['message' => 'File not found'], 404);
    }
}
