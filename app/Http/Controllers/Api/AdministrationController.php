<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Administration;
use Illuminate\Http\Request;

class AdministrationController extends Controller
{
    public function index()
    {
        $members = Administration::active()->with('media')->get()->map(fn($m) => [
            'id'          => $m->id,
            'name'        => $m->name,
            'designation' => $m->designation,
            'role_type'   => $m->role_type,
            'bio'         => $m->bio,
            'email'       => $m->email,
            'phone'       => $m->phone,
            'photo_url'   => $m->getFirstMediaUrl('photo'),
        ]);

        return response()->json(['data' => $members]);
    }
}
