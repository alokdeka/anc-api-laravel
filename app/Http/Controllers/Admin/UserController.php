<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('roles')->latest()->paginate(20);
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:super_admin,registrar,editor,exam_cell',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
        ]);

        $user->assignRole($data['role']);

        AuditLog::create([
            'user_id' => $request->user()->id, 'action' => 'created',
            'model_type' => 'User', 'model_id' => $user->id,
            'description' => "Created admin user: {$user->email} with role {$data['role']}",
            'ip_address' => $request->ip(),
        ]);

        return response()->json($user->load('roles'), 201);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'      => 'sometimes|string|max:100',
            'email'     => 'sometimes|email|unique:users,email,' . $user->id,
            'password'  => 'nullable|string|min:8|confirmed',
            'role'      => 'sometimes|in:super_admin,registrar,editor,exam_cell',
            'is_active' => 'boolean',
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        if (isset($data['role'])) {
            $user->syncRoles([$data['role']]);
        }

        AuditLog::create([
            'user_id' => $request->user()->id, 'action' => 'updated',
            'model_type' => 'User', 'model_id' => $user->id,
            'description' => "Updated admin user: {$user->email}",
            'ip_address' => $request->ip(),
        ]);

        return response()->json($user->load('roles'));
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'Cannot delete your own account.'], 422);
        }

        AuditLog::create([
            'user_id' => $request->user()->id, 'action' => 'deleted',
            'model_type' => 'User', 'model_id' => $user->id,
            'description' => "Deleted admin user: {$user->email}",
            'ip_address' => $request->ip(),
        ]);

        $user->delete();
        return response()->json(['message' => 'User deleted.']);
    }
}
