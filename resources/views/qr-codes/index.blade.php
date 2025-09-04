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
                    <div class="alert alert-success">
                        Ini adalah pesan sukses dummy.
                    </div>

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
                                <tr>
                                    <td>
                                        <input type="checkbox" class="user-checkbox" value="1">
                                    </td>
                                    <td>Dummy User 1</td>
                                    <td>dummy1@example.com</td>
                                    <td>
                                        <span class="badge badge-success">Murid</span>
                                    </td>
                                    <td>
                                        <code>DUMMY-QR-CODE-1</code>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">Tersedia</span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" onclick="previewQrCode(1)">
                                            <i class="fas fa-eye"></i> Preview
                                        </button>
                                        <button type="button" class="btn btn-sm btn-success" onclick="downloadQrCode(1)">
                                            <i class="fas fa-download"></i> Download
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="printQrCode(1)">
                                            <i class="fas fa-print"></i> Print
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="user-checkbox" value="2">
                                    </td>
                                    <td>Dummy User 2</td>
                                    <td>dummy2@example.com</td>
                                    <td>
                                        <span class="badge badge-primary">Guru</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">Belum dibuat</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-warning">Belum dibuat</span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="generateQrCode(2)">
                                            <i class="fas fa-plus"></i> Generate
                                        </button>
                                    </td>
                                </tr>
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
        alert('Generate QR Code untuk user ' + userId);
    }

    function downloadQrCode(userId) {
        alert('Download QR Code untuk user ' + userId);
    }

    function previewQrCode(userId) {
        $('#previewContent').html('<p>Ini adalah preview QR Code untuk user ' + userId + '</p>');
        $('#previewModal').modal('show');
    }

    function printQrCode(userId) {
        alert('Print QR Code untuk user ' + userId);
    }

    function bulkGenerate() {
        alert('Bulk generate QR Code');
    }

    function toggleSelectAll() {
        const isChecked = $('#selectAll').is(':checked');
        $('.user-checkbox').prop('checked', isChecked);
    }
</script>
@endpush