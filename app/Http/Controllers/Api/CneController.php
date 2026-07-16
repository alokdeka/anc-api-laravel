<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CneContent;
use Illuminate\Http\Request;

class CneController extends Controller
{
    public function index()
    {
        $contents = CneContent::all()->keyBy('section')->map(fn($c) => [
            'title'   => $c->title,
            'content' => $c->content,
        ]);

        return response()->json(['data' => $contents]);
    }
}
