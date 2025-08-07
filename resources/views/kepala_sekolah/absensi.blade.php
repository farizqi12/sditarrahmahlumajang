
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Absensi Kepala Sekolah - E-Learning</title>
    <!-- Google Fonts Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD"
        crossorigin="anonymous"
    />
    <link rel="stylesheet" href="{{ asset('css/admin/absensi.css') }}">
</head>

<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <!-- Sidebar -->
    <x-sidebar></x-sidebar>

    <!-- Main Content -->
    <div class="content">
        <x-navbar></x-navbar>
        <x-notif></x-notif>
        
        <div class="container-fluid">
            <div class="card p-4 mt-4">
                <h5 class="card-title">Absensi Kehadiran</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Status Absensi Hari Ini</h6>
                                @if($attendance && $attendance->check_in && !$attendance->check_out)
                                    <p class="card-text">Anda sudah check-in pada: <strong>{{ $attendance->check_in->format('H:i:s') }}</strong></p>
                                    <p class="card-text">Lokasi: <strong>{{ $attendance->location->name }}</strong></p>
                                @elseif($attendance && $attendance->check_out)
                                    <p class="card-text">Anda sudah check-out hari ini.</p>
                                @else
                                    <p class="card-text">Anda belum melakukan absensi hari ini.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                @if(!$attendance || ($attendance && $attendance->check_out))
                                    {{-- Form Check-in --}}
                                    <form action="{{ route('kepala_sekolah.absensi.checkin') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="location_id" class="form-label">Pilih Lokasi</label>
                                            <select name="location_id" id="location_id" class="form-select" required>
                                                <option value="">-- Pilih Lokasi --</option>
                                                @foreach($locations as $location)
                                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Check In</button>
                                    </form>
                                @elseif($attendance && !$attendance->check_out)
                                    {{-- Tombol Check-out --}}
                                    <form action="{{ route('kepala_sekolah.absensi.checkout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Check Out</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
   </body>

</html>
