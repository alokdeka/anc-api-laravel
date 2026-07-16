<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Form;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function index(Request $request)
    {
        $query = Form::active()->with('media');

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        $forms = $query->get()->groupBy('category')->map(fn($group) => $group->map(fn($f) => [
            'id'          => $f->id,
            'title'       => $f->title,
            'category'    => $f->category,
            'description' => $f->description,
            'file_url'    => $f->external_url ?: $f->getFirstMediaUrl('file'),
            'sort_order'  => $f->sort_order,
        ]));

        return response()->json(['data' => $forms]);
    }
}
