<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Users - E-Learning</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="{{ asset('css/admin/users.css') }}">
    <style>
        .btn-success i {
            font-size: 1.1rem;
        }

        .btn-success:hover {
            background-color: #218838;
            box-shadow: 0 0.3rem 0.6rem rgba(0, 0, 0, 0.15);
        }

        @media (max-width: 575.98px) {
            .btn-success i {
                font-size: 1.2rem;
            }
        }

        .btn-sm i {
            font-size: 1rem;
        }

        @media (max-width: 575.98px) {
            .btn-sm {
                padding: 0.3rem 0.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <x-sidebar></x-sidebar>

    <div class="content">
        <x-navbar></x-navbar>
        <x-notif></x-notif>

        <div class="card p-3 mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                <h5 class="mb-0 fw-semibold d-flex align-items-center">
                    <i class="bi bi-people-fill me-2 text-primary"></i> Daftar Pengguna
                </h5>
                <button class="btn btn-success d-flex align-items-center gap-2 px-3 py-2 rounded-pill shadow-sm"
                    data-bs-toggle="modal" data-bs-target="#userModal" onclick="openAddModal()">
                    <i class="bi bi-person-plus-fill text-white"></i>
                    <span class="d-none d-sm-inline">Tambah</span>
                </button>
            </div>


            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
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
                                <td>{{ ucfirst(str_replace('_', ' ', $user->role->name)) }}</td>
                                <td>
                                    <!-- Tombol Edit -->
                                    <button class="btn btn-primary btn-sm d-flex align-items-center gap-1"
                                        data-bs-toggle="modal" data-bs-target="#userModal"
                                        onclick="openEditModal({{ $user->toJson() }})">
                                        <i class="bi bi-pencil-square text-white"></i>
                                        <span class="d-none d-sm-inline">Edit</span>
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm d-flex align-items-center gap-1">
                                            <i class="bi bi-trash-fill text-white"></i>
                                            <span class="d-none d-sm-inline">Hapus</span>
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data pengguna.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Tambah/Edit User --}}
    <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" class="modal-content" id="userForm">
                @csrf
                <input type="hidden" name="_method" value="POST" id="formMethod" />
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalTitle">Tambah Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" id="inputName" required />
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="inputEmail" required />
                    </div>

                    <div class="mb-2 position-relative">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" id="inputPassword" />
                            <button class="btn btn-outline-secondary toggle-password" type="button"
                                data-target="inputPassword">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-2 position-relative">
                        <label class="form-label">Konfirmasi Password</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" class="form-control"
                                id="inputConfirmPassword" />
                            <button class="btn btn-outline-secondary toggle-password" type="button"
                                data-target="inputConfirmPassword">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>


                    <div class="mb-2">
                        <label class="form-label">Role</label>
                        <select name="role_id" class="form-select" id="inputRole" required
                            onchange="toggleRoleFields()">
                            <option value="">-- Pilih Role --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2 d-none" id="nipField">
                        <label class="form-label">NIP (Guru)</label>
                        <input type="text" name="nip" class="form-control" id="inputNip" />
                    </div>

                    <div class="mb-2 d-none" id="nisField">
                        <label class="form-label">NIS (Siswa)</label>
                        <input type="text" name="nis" class="form-control" id="inputNis" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            const form = document.getElementById('userForm');
            form.action = `{{ route('admin.users.store') }}`;
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('userModalTitle').textContent = 'Tambah Pengguna';

            form.reset();
            toggleRoleFields();
        }

        function openEditModal(user) {
            const form = document.getElementById('userForm');
            form.action = `/admin/users/${user.id}`;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('userModalTitle').textContent = 'Edit Pengguna';

            document.getElementById('inputName').value = user.name;
            document.getElementById('inputEmail').value = user.email;
            document.getElementById('inputRole').value = user.role_id;
            document.getElementById('inputPassword').value = '';

            // Role-dependent fields
            toggleRoleFields();

            if (user.teacher) {
                document.getElementById('inputNip').value = user.teacher.nip;
            } else {
                document.getElementById('inputNip').value = '';
            }

            if (user.student) {
                document.getElementById('inputNis').value = user.student.nis;
            } else {
                document.getElementById('inputNis').value = '';
            }
        }

        function toggleRoleFields() {
            const role = parseInt(document.getElementById('inputRole').value);
            const nipField = document.getElementById('nipField');
            const nisField = document.getElementById('nisField');

            nipField.classList.add('d-none');
            nisField.classList.add('d-none');

            if (role === 3) nipField.classList.remove('d-none');
            if (role === 4) nisField.classList.remove('d-none');
            if (role === 2) nisField.classList.remove('d-none');
            if (role === 5) nisField.classList.remove('d-none');

        }
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.toggle-password');
            toggleButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const targetInput = document.getElementById(btn.dataset.target);
                    const icon = btn.querySelector('i');
                    if (targetInput.type === 'password') {
                        targetInput.type = 'text';
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    } else {
                        targetInput.type = 'password';
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    }
                });
            });

            // Validasi panjang password
            const form = document.getElementById('userForm');
            const passwordInput = document.getElementById('inputPassword');
            form.addEventListener('submit', function(event) {
                if (passwordInput.value && passwordInput.value.length < 8) {
                    event.preventDefault();
                    alert('Password harus minimal 8 karakter.');
                    passwordInput.classList.add('is-invalid');
                } else {
                    passwordInput.classList.remove('is-invalid');
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
