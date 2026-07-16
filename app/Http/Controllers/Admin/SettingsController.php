<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Setting;
use Illuminate\Http\Request;

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
}
