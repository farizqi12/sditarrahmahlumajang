<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="DUMMY_CSRF_TOKEN">
    <title>Admin Users - E-Learning</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="{{ asset('css/admin/users.css') }}">
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
                        <tr>
                            <td>Dummy User 1</td>
                            <td>dummy1@example.com</td>
                            <td>Murid</td>
                            <td>
                                <button class="btn btn-primary btn-sm d-flex align-items-center gap-1"
                                    data-bs-toggle="modal" data-bs-target="#userModal"
                                    onclick="openEditModal({id: 1, name: 'Dummy User 1', email: 'dummy1@example.com', role_id: 4})">
                                    <i class="bi bi-pencil-square text-white"></i>
                                    <span class="d-none d-sm-inline">Edit</span>
                                </button>
                                <button class="btn btn-info btn-sm d-flex align-items-center gap-1"
                                    onclick="downloadQrCode(1)" title="Download QR Code">
                                    <i class="bi bi-qr-code text-white"></i>
                                    <span class="d-none d-sm-inline">QR</span>
                                </button>
                                <form action="#" method="POST"
                                    class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    <button class="btn btn-danger btn-sm d-flex align-items-center gap-1">
                                        <i class="bi bi-trash-fill text-white"></i>
                                        <span class="d-none d-sm-inline">Hapus</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td>Dummy User 2</td>
                            <td>dummy2@example.com</td>
                            <td>Guru</td>
                            <td>
                                <button class="btn btn-primary btn-sm d-flex align-items-center gap-1"
                                    data-bs-toggle="modal" data-bs-target="#userModal"
                                    onclick="openEditModal({id: 2, name: 'Dummy User 2', email: 'dummy2@example.com', role_id: 3})">
                                    <i class="bi bi-pencil-square text-white"></i>
                                    <span class="d-none d-sm-inline">Edit</span>
                                </button>
                                <button class="btn btn-warning btn-sm d-flex align-items-center gap-1"
                                    onclick="generateQrCode(2)" title="Generate QR Code">
                                    <i class="bi bi-plus-circle text-white"></i>
                                    <span class="d-none d-sm-inline">QR</span>
                                </button>
                                <form action="#" method="POST"
                                    class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    <button class="btn btn-danger btn-sm d-flex align-items-center gap-1">
                                        <i class="bi bi-trash-fill text-white"></i>
                                        <span class="d-none d-sm-inline">Hapus</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Tambah/Edit User --}}
    <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" class="modal-content" id="userForm">
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
                            <option value="3">Guru</option>
                            <option value="4">Murid</option>
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
            form.action = '#';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('userModalTitle').textContent = 'Tambah Pengguna';

            form.reset();
            toggleRoleFields();
        }

        function openEditModal(user) {
            const form = document.getElementById('userForm');
            form.action = `#`;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('userModalTitle').textContent = 'Edit Pengguna';

            document.getElementById('inputName').value = user.name;
            document.getElementById('inputEmail').value = user.email;
            document.getElementById('inputRole').value = user.role_id;
            document.getElementById('inputPassword').value = '';

            // Role-dependent fields
            toggleRoleFields();
        }

        function toggleRoleFields() {
            const role = parseInt(document.getElementById('inputRole').value);
            const nipField = document.getElementById('nipField');
            const nisField = document.getElementById('nisField');

            nipField.classList.add('d-none');
            nisField.classList.add('d-none');

            if (role === 3) { // Guru
                nipField.classList.remove('d-none');
            } else if (role === 4) { // Murid
                nisField.classList.remove('d-none');
            }
        }

        function downloadQrCode(userId) {
            alert('Download QR Code untuk user ' + userId);
        }

        function generateQrCode(userId) {
            alert('Generate QR Code untuk user ' + userId);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>