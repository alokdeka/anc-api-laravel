<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Form;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function index(Request $request)
    {
        $query = Form::with('media')->orderBy('sort_order');
        if ($request->filled('category')) $query->byCategory($request->category);
        return response()->json($query->get());
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'forms' => 'required|array',
            'forms.*.id' => 'required|integer|exists:forms,id',
            'forms.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->forms as $form) {
            Form::where('id', $form['id'])->update(['sort_order' => $form['sort_order']]);
        }

        return response()->json(['message' => 'Forms reordered successfully.']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|string',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
            'sort_order'  => 'integer',
            'external_url'=> 'nullable|string|max:1000',
        ]);

        $data['uploaded_by'] = $request->user()->id;
        $form = Form::create($data);

        if ($request->hasFile('file')) {
            $form->addMediaFromRequest('file')->toMediaCollection('file');
        }

        AuditLog::create([
            'user_id' => $request->user()->id, 'action' => 'created',
            'model_type' => 'Form', 'model_id' => $form->id,
            'description' => "Uploaded form: {$form->title}",
            'ip_address' => $request->ip(),
        ]);

        return response()->json($form->load('media'), 201);
    }

    public function update(Request $request, Form $form)
    {
        $data = $request->validate([
            'title'       => 'sometimes|string|max:255',
            'category'    => 'sometimes|string',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
            'sort_order'  => 'integer',
            'external_url'=> 'nullable|string|max:1000',
        ]);

        $form->update($data);

        if ($request->hasFile('file')) {
            $form->clearMediaCollection('file');
            $form->addMediaFromRequest('file')->toMediaCollection('file');
        }

        return response()->json($form->load('media'));
    }

    public function destroy(Request $request, Form $form)
    {
        $form->delete();
        AuditLog::create([
            'user_id' => $request->user()->id, 'action' => 'deleted',
            'model_type' => 'Form', 'model_id' => $form->id,
            'description' => "Deleted form: {$form->title}",
            'ip_address' => $request->ip(),
        ]);
        return response()->json(['message' => 'Form deleted.']);
    }
}
