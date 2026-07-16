<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CircularResource;
use App\Models\Circular;
use Illuminate\Http\Request;

class CircularController extends Controller
{
    public function index(Request $request)
    {
        $query = Circular::published()->with(['author', 'media'])->latest('published_at');

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }
        if ($request->filled('year')) {
            $query->byYear($request->year);
        }
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->boolean('important')) {
            $query->where('is_important', true);
        }

        $circulars = $query->paginate($request->input('per_page', 15));

        return CircularResource::collection($circulars);
    }

    public function show(string $slug)
    {
        $circular = Circular::published()
            ->with(['author', 'media'])
            ->where('slug', $slug)
            ->firstOrFail();

        return new CircularResource($circular);
    }
}
