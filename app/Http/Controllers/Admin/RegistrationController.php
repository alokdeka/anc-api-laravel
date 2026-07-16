<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Nurse;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $query = Nurse::with(['institute', 'approvedBy'])->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('registration_number', 'like', "%{$request->search}%")
                  ->orWhere('name', 'like', "%{$request->search}%")
                  ->orWhere('mobile', 'like', "%{$request->search}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('district')) {
            $query->where('district', $request->district);
        }
        if ($request->filled('qualification')) {
            $query->where('qualification', $request->qualification);
        }

        return response()->json($query->paginate(20));
    }

    public function show(Nurse $nurse)
    {
        return response()->json($nurse->load(['institute', 'approvedBy', 'media']));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'registration_number' => 'required|string|unique:nurses',
            'name'                => 'required|string|max:200',
            'father_husband_name' => 'nullable|string|max:200',
            'dob'                 => 'nullable|date',
            'gender'              => 'nullable|in:male,female,other',
            'qualification'       => 'required|string',
            'institute_id'        => 'nullable|exists:institutes,id',
            'registration_date'   => 'required|date',
            'expiry_date'         => 'nullable|date',
            'status'              => 'in:active,expired,revoked,suspended',
            'address'             => 'nullable|string',
            'district'            => 'nullable|string',
            'mobile'              => 'nullable|string|max:15',
            'email'               => 'nullable|email',
            'remarks'             => 'nullable|string',
        ]);

        $data['approved_by'] = $request->user()->id;
        $nurse = Nurse::create($data);

        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'created',
            'model_type'  => 'Nurse',
            'model_id'    => $nurse->id,
            'description' => "Registered nurse: {$nurse->registration_number} — {$nurse->name}",
            'ip_address'  => $request->ip(),
        ]);

        return response()->json($nurse, 201);
    }

    public function update(Request $request, Nurse $nurse)
    {
        $data = $request->validate([
            'name'                => 'sometimes|string|max:200',
            'qualification'       => 'sometimes|string',
            'institute_id'        => 'nullable|exists:institutes,id',
            'registration_date'   => 'sometimes|date',
            'expiry_date'         => 'nullable|date',
            'status'              => 'in:active,expired,revoked,suspended',
            'address'             => 'nullable|string',
            'district'            => 'nullable|string',
            'mobile'              => 'nullable|string',
            'email'               => 'nullable|email',
            'remarks'             => 'nullable|string',
        ]);

        $oldValues = $nurse->toArray();
        $nurse->update($data);

        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'updated',
            'model_type'  => 'Nurse',
            'model_id'    => $nurse->id,
            'description' => "Updated registration: {$nurse->registration_number}",
            'old_values'  => $oldValues,
            'new_values'  => $data,
            'ip_address'  => $request->ip(),
        ]);

        return response()->json($nurse->load('institute'));
    }

    public function approve(Request $request, Nurse $nurse)
    {
        $nurse->update(['status' => 'active', 'approved_by' => $request->user()->id]);
        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'approved',
            'model_type'  => 'Nurse',
            'model_id'    => $nurse->id,
            'description' => "Approved registration: {$nurse->registration_number}",
            'ip_address'  => $request->ip(),
        ]);
        return response()->json(['message' => 'Registration approved.', 'nurse' => $nurse]);
    }

    public function reject(Request $request, Nurse $nurse)
    {
        $request->validate(['reason' => 'nullable|string|max:500']);
        $nurse->update(['status' => 'suspended', 'remarks' => $request->reason]);
        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'rejected',
            'model_type'  => 'Nurse',
            'model_id'    => $nurse->id,
            'description' => "Rejected/suspended: {$nurse->registration_number}. Reason: {$request->reason}",
            'ip_address'  => $request->ip(),
        ]);
        return response()->json(['message' => 'Registration rejected.']);
    }

    public function revoke(Request $request, Nurse $nurse)
    {
        $nurse->update(['status' => 'revoked']);
        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'revoked',
            'model_type'  => 'Nurse',
            'model_id'    => $nurse->id,
            'description' => "Revoked registration: {$nurse->registration_number}",
            'ip_address'  => $request->ip(),
        ]);
        return response()->json(['message' => 'Registration revoked.']);
    }
}
