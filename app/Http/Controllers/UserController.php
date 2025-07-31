<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'student', 'teacher'])->latest()->paginate(10);
        $roles = Role::all();
        return view('admin.users', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id'  => 'required|exists:roles,id',
            'nip'      => 'nullable|string|max:255|unique:teachers',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role_id'  => $request->role_id,
            ]);

            // Role ID 4 for Murid
            if ($request->role_id == 4) {
                $currentYear = date('Y');
                $lastStudent = \App\Models\Student::where('nis', 'like', $currentYear . '%')->latest('nis')->first();

                $nextSequence = 1;
                if ($lastStudent) {
                    $lastSequence = (int) substr($lastStudent->nis, 4);
                    $nextSequence = $lastSequence + 1;
                }

                $newNis = $currentYear . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);

                $user->student()->create([
                    'nis'   => $newNis,
                    'class' => 'Unassigned'
                ]);
            }

            // Role ID 3 for Guru
            if ($request->role_id == 3) {
                $user->teacher()->create(['nip' => $request->nip]);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id'  => 'required|exists:roles,id',
            'nis'      => ['nullable', 'string', 'max:255', Rule::unique('students', 'nis')->ignore(optional($user->student)->id), 'required_if:role_id,4'],
            'nip'      => ['nullable', 'string', 'max:255', Rule::unique('teachers', 'nip')->ignore(optional($user->teacher)->id), 'required_if:role_id,3'],
        ]);

        DB::transaction(function () use ($request, $user) {
            $data = $request->only(['name', 'email', 'role_id']);
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }
            $user->update($data);

            // Role changed away from Murid, delete student profile
            if ($request->role_id != 4 && $user->student) {
                $user->student->delete();
            }

            // Role changed away from Guru, delete teacher profile
            if ($request->role_id != 3 && $user->teacher) {
                $user->teacher->delete();
            }

            // Role is Murid, update or create student profile
            if ($request->role_id == 4 && $request->filled('nis')) {
                $user->student()->updateOrCreate(
                    ['user_id' => $user->id],
                    ['nis' => $request->nis]
                );
            }

            // Role is Guru, update or create teacher profile
            if ($request->role_id == 3) {
                $user->teacher()->updateOrCreate(
                    ['user_id' => $user->id],
                    ['nip' => $request->nip]
                );
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // The database foreign key constraints (onDelete('cascade')) will handle deleting related records.
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
