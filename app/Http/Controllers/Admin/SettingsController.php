<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        return response()->json(['data' => $settings]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings'   => 'required|array',
            'settings.*' => 'nullable|string',
        ]);

        $user = $request->user();
        if ($user->role !== 'super_admin') {
            $allowedKeys = ['programs_page_content', 'about_page_content', 'contact_page_content', 'cne_eligibility_content'];
            foreach (array_keys($request->settings) as $key) {
                if (!in_array($key, $allowedKeys)) {
                    abort(403, "Unauthorized to update setting: {$key}");
                }
            }
        }

        foreach ($request->settings as $key => $value) {
            Setting::set($key, $value);
        }

        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'updated',
            'model_type'  => 'Setting',
            'description' => 'Updated site settings',
            'new_values'  => $request->settings,
            'ip_address'  => $request->ip(),
        ]);

        return response()->json(['message' => 'Settings updated.']);
    }

    public function uploadPhoto(Request $request)
    {
        abort_if($request->user()->role !== 'super_admin', 403, 'Unauthorized access.');

        $request->validate([
            'file' => 'required|image|max:5120',
            'key'  => 'required|string|in:president_photo,registrar_photo',
        ]);

        $file      = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $filename  = $request->key . '-' . time() . '-' . Str::random(5) . '.' . $extension;

        $path = $file->storeAs('media', $filename, 'public');
        $url  = asset('storage/' . $path);

        Setting::set($request->key, $url);

        return response()->json(['url' => $url]);
    }
}
