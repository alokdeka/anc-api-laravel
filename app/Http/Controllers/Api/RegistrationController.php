<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NurseResource;
use App\Models\Nurse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class RegistrationController extends Controller
{
    public function verify(Request $request)
    {
        $request->validate([
            'registration_number' => 'required|string|max:50',
        ]);

        // Rate-limit: 10 requests per minute per IP
        $key = 'verify:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'message' => "Too many verification requests. Please try again in {$seconds} seconds.",
            ], 429);
        }
        RateLimiter::hit($key, 60);

        $nurse = Nurse::with(['institute', 'media'])
            ->where('registration_number', strtoupper(trim($request->registration_number)))
            ->first();

        if (!$nurse) {
            return response()->json([
                'found'   => false,
                'message' => 'No registration found with the provided registration number.',
            ]);
        }

        return response()->json([
            'found' => true,
            'data'  => new NurseResource($nurse),
        ]);
    }
}
