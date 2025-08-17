@extends('layouts.admin')

@section('title', 'Manajemen QR Code')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-qrcode"></i> Manajemen QR Code User
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" onclick="bulkGenerate()">
                            <i class="fas fa-plus"></i> Generate Semua QR Code
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                    </th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>QR Code</th>
                                    <th>Status</th>
                                    <th width="200">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="user-checkbox" value="{{ $user->id }}">
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge badge-{{ $user->role->name === 'admin' ? 'danger' : ($user->role->name === 'guru' ? 'primary' : 'success') }}">
                                            {{ ucfirst(str_replace('_', ' ', $user->role->name)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->qr_code)
                                            <code>{{ $user->qr_code }}</code>
                                        @else
                                            <span class="text-muted">Belum dibuat</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->qr_code)
                                            <span class="badge badge-success">Tersedia</span>
                                        @else
                                            <span class="badge badge-warning">Belum dibuat</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->qr_code)
                                            <button type="button" class="btn btn-sm btn-info" onclick="previewQrCode({{ $user->id }})">
                                                <i class="fas fa-eye"></i> Preview
                                            </button>
                                            <button type="button" class="btn btn-sm btn-success" onclick="downloadQrCode({{ $user->id }})">
                                                <i class="fas fa-download"></i> Download
                                            </button>
                                            <button type="button" class="btn btn-sm btn-warning" onclick="printQrCode({{ $user->id }})">
                                                <i class="fas fa-print"></i> Print
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-primary" onclick="generateQrCode({{ $user->id }})">
                                                <i class="fas fa-plus"></i> Generate
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview QR Code</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function generateQrCode(userId) {
        if (confirm('Apakah Anda yakin ingin membuat QR Code untuk user ini?')) {
            $.ajax({
                url: '{{ route("admin.qr-codes.generate") }}',
                method: 'POST',
                data: {
                    user_id: userId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat membuat QR Code');
                }
            });
        }
    }

    function downloadQrCode(userId) {
        window.open('{{ url("admin/qr-codes/download") }}?user_id=' + userId, '_blank');
    }

    function previewQrCode(userId) {
        $.get('{{ url("admin/qr-codes") }}/' + userId + '/preview', function(data) {
            $('#previewContent').html(data);
            $('#previewModal').modal('show');
        });
    }

    function printQrCode(userId) {
        window.open('{{ url("admin/qr-codes") }}/' + userId + '/print', '_blank');
    }

    function bulkGenerate() {
        const selectedUsers = $('.user-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedUsers.length === 0) {
            alert('Pilih user yang akan dibuatkan QR Code');
            return;
        }

        if (confirm('Apakah Anda yakin ingin membuat QR Code untuk ' + selectedUsers.length + ' user yang dipilih?')) {
            $.ajax({
                url: '{{ route("admin.qr-codes.bulk-generate") }}',
                method: 'POST',
                data: {
                    user_ids: selectedUsers,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat membuat QR Code');
                }
            });
        }
    }

    function toggleSelectAll() {
        const isChecked = $('#selectAll').is(':checked');
        $('.user-checkbox').prop('checked', isChecked);
    }
</script>
@endpush
