<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manajemen User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
</head>
<body>
    <x-navbar />
    <x-sidebar />

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Daftar User</h4>
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="bi bi-plus"></i> Tambah User
            </button>
        </div>

        <x-notif />

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $user->role->name)) ?? '-' }}</td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal-{{ $user->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form method="POST" action="{{ route('users.destroy', $user->id) }}" class="d-inline" onsubmit="return confirm('Yakin ingin hapus user ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editUserModal-{{ $user->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('users.update', $user->id) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header"><h5>Edit User</h5></div>
                                    <div class="modal-body">
                                        <input name="name" value="{{ old('name', $user->name) }}" class="form-control mb-2" placeholder="Nama" required>
                                        <input name="email" value="{{ old('email', $user->email) }}" class="form-control mb-2" placeholder="Email" required>
                                        <input name="password" type="password" class="form-control mb-2" placeholder="Password (kosongkan jika tidak diganti)">
                                        <input name="password_confirmation" type="password" class="form-control mb-2" placeholder="Konfirmasi Password">

                                        <select name="role_id" class="form-select mb-2" onchange="toggleFields(this.value, '{{ $user->id }}')">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <input type="text" name="nis" id="edit-nis-{{ $user->id }}" class="form-control mb-2 d-none" placeholder="NIS (Siswa)" value="{{ old('nis', optional($user->student)->nis) }}">
                                        <input type="text" name="nip" id="edit-nip-{{ $user->id }}" class="form-control mb-2 d-none" placeholder="NIP (Guru)" value="{{ old('nip', optional($user->teacher)->nip) }}">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada data user.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $users->links() }}
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header"><h5>Tambah User</h5></div>
                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <input name="name" value="{{ old('name') }}" class="form-control mb-2" placeholder="Nama" required>
                        <input name="email" value="{{ old('email') }}" class="form-control mb-2" placeholder="Email" required>
                        <input name="password" type="password" class="form-control mb-2" placeholder="Password" required>
                        <input name="password_confirmation" type="password" class="form-control mb-2" placeholder="Konfirmasi Password" required>

                        <select name="role_id" class="form-select mb-2" onchange="toggleFields(this.value, 'add')">
                            <option selected disabled>Pilih Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                </option>
                            @endforeach
                        </select>

                        <input type="text" name="nis" id="add-nis" class="form-control mb-2 d-none" placeholder="NIS (Siswa)" value="{{ old('nis') }}">
                        <input type="text" name="nip" id="add-nip" class="form-control mb-2 d-none" placeholder="NIP (Guru)" value="{{ old('nip') }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleFields(roleId, context) {
            const nisField = document.getElementById(`${context}-nis`);
            const nipField = document.getElementById(`${context}-nip`);

            if (nisField) nisField.classList.add('d-none');
            if (nipField) nipField.classList.add('d-none');

            if (roleId == 4 && nisField) nisField.classList.remove('d-none'); // Role Murid
            if (roleId == 3 && nipField) nipField.classList.remove('d-none'); // Role Guru
        }

        document.addEventListener('DOMContentLoaded', function () {
            @foreach ($users as $user)
                toggleFields({{ $user->role_id }}, 'edit-{{ $user->id }}');
            @endforeach

            // If validation fails, keep the modal open and show correct fields
            @if($errors->any())
                const addUserModal = new bootstrap.Modal(document.getElementById('addUserModal'));
                addUserModal.show();
                const selectedRole = document.querySelector('#addUserModal select[name="role_id"]').value;
                if (selectedRole) {
                    toggleFields(selectedRole, 'add');
                }
            @endif
        });
    </script>
    <script src="{{ asset('js/admin/dashboard.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
