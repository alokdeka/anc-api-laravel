<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Slider::with('media')->orderBy('sort_order')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string',
            'badge' => 'nullable|string|max:100',
            'cta_label' => 'nullable|string|max:100',
            'cta_link' => 'nullable|string|max:255',
            'cta2_label' => 'nullable|string|max:100',
            'cta2_link' => 'nullable|string|max:255',
            'bg_color' => 'nullable|string|max:150',
            'external_image_url' => 'nullable|string|max:1000',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ]);

        if (empty($validated['bg_color'])) {
            $validated['bg_color'] = 'transparent';
        }

        $slider = Slider::create($validated);

        if ($request->hasFile('image')) {
            $slider->addMediaFromRequest('image')->toMediaCollection('slider_image');
        }

        return response()->json([
            'message' => 'Slider created successfully',
            'data' => $slider->load('media')
        ], 201);
    }

    public function show(Slider $slider)
    {
        return response()->json(['data' => $slider->load('media')]);
    }

    public function update(Request $request, Slider $slider)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string',
            'badge' => 'nullable|string|max:100',
            'cta_label' => 'nullable|string|max:100',
            'cta_link' => 'nullable|string|max:255',
            'cta2_label' => 'nullable|string|max:100',
            'cta2_link' => 'nullable|string|max:255',
            'bg_color' => 'nullable|string|max:150',
            'external_image_url' => 'nullable|string|max:1000',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ]);

        if (empty($validated['bg_color']) && array_key_exists('bg_color', $validated)) {
            $validated['bg_color'] = 'transparent';
        }

        $slider->update($validated);

        if ($request->hasFile('image')) {
            $slider->clearMediaCollection('slider_image');
            $slider->addMediaFromRequest('image')->toMediaCollection('slider_image');
            $slider->update(['external_image_url' => null]);
        } elseif ($request->input('remove_image') == 'true' || $request->input('remove_image') == 1) {
            $slider->clearMediaCollection('slider_image');
        } elseif (!empty($validated['external_image_url'])) {
            $slider->clearMediaCollection('slider_image');
        }

        return response()->json([
            'message' => 'Slider updated successfully',
            'data' => $slider->load('media')
        ]);
    }

    public function destroy(Slider $slider)
    {
        $slider->delete();

        return response()->json([
            'message' => 'Slider deleted successfully'
        ]);
    }
}
