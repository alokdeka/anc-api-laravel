<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use Illuminate\Http\Request;

class InstituteController extends Controller
{
    public function index(Request $request)
    {
        $query = Institute::where('status', 'active');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $institutes = $query->orderBy('created_at', 'desc')->paginate($request->input('per_page', 20));

        return response()->json($institutes);
    }
}
