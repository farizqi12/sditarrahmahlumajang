<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['role', 'studentProfile', 'teacherProfile'])->latest()->paginate(10);
        // Menggunakan 'admin.users.index' untuk konsistensi resource controller
        return view('admin.users', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Asumsi dari RoleSeeder: 1:admin, 2:kepala_sekolah, 3:guru, 4:murid, 5:staff_tu
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'nis' => 'nullable|string|max:255|unique:students,nis|required_if:role_id,4',
            'nip' => 'nullable|string|max:255|unique:teachers,nip|required_if:role_id,3',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        if ($request->role_id == 4 && $request->filled('nis')) { // Role murid
            $user->studentProfile()->create(['nis' => $request->nis, 'class' => 'Unassigned']);
        } elseif ($request->role_id == 3 && $request->filled('nip')) { // Role guru
            $user->teacherProfile()->create(['nip' => $request->nip]);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['role', 'studentProfile', 'teacherProfile']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load(['studentProfile', 'teacherProfile']);
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Asumsi dari RoleSeeder: 1:admin, 2:kepala_sekolah, 3:guru, 4:murid, 5:staff_tu
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'nis' => ['nullable', 'string', 'max:255', Rule::unique('students', 'nis')->ignore($user->studentProfile->id ?? null), 'required_if:role_id,4'],
            'nip' => ['nullable', 'string', 'max:255', Rule::unique('teachers', 'nip')->ignore($user->teacherProfile->id ?? null), 'required_if:role_id,3'],
        ]);

        $userData = $request->only('name', 'email', 'role_id');
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Hapus profil lama jika role berubah
        if ($request->role_id != 4 && $user->studentProfile) {
            $user->studentProfile->delete();
        }
        if ($request->role_id != 3 && $user->teacherProfile) {
            $user->teacherProfile->delete();
        }

        // Buat atau update profil baru
        if ($request->role_id == 4 && $request->filled('nis')) { // Role murid
            $user->studentProfile()->updateOrCreate(['user_id' => $user->id], ['nis' => $request->nis]);
        } elseif ($request->role_id == 3 && $request->filled('nip')) { // Role guru
            $user->teacherProfile()->updateOrCreate(['user_id' => $user->id], ['nip' => $request->nip]);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Relasi di database diatur dengan onDelete('cascade'),
        // jadi profil siswa/guru akan terhapus otomatis.
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
