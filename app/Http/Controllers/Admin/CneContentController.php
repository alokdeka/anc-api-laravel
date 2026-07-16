<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CneContent;
use Illuminate\Http\Request;

class CneContentController extends Controller
{
    public function show($section)
    {
        $content = CneContent::where('section', $section)->first();
        if (!$content) {
            return response()->json(['message' => 'Section not found'], 404);
        }
        return response()->json(['data' => $content]);
    }

    public function update(Request $request, $section)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $content = CneContent::where('section', $section)->first();
        if (!$content) {
            $content = new CneContent(['section' => $section]);
        }

        $content->title = $request->title;
        $content->content = $request->content;
        $content->save();

        return response()->json(['message' => 'CNE Content updated successfully', 'data' => $content]);
    }
}
