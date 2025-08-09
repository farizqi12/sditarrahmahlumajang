use Error;
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Absensi Kepala Sekolah - E-Learning</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="{{ asset('css/admin/absensi.css') }}">
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('maps.google_maps_api_key') }}&libraries=places&callback=initMap"
        async defer></script>

</head> 
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <x-sidebar></x-sidebar>

    <div class="content">
        <x-navbar></x-navbar>
        <x-notif></x-notif>

        <div class="container-fluid">
            <div class="card p-4 mt-4">
                <h5 class="card-title fw-semibold mb-4">Absensi Kehadiran</h5>

                <div class="row g-4">
                    <!-- Kolom Kiri: Aksi Absensi & Peta -->
                    <div class="col-lg-5">
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-3 text-muted">Status Hari Ini</h6>
                                @if ($attendanceToday && $attendanceToday->check_in)
                                    <div class="alert alert-success">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        Anda sudah check-in pada
                                        <strong>{{ Carbon\Carbon::parse($attendanceToday->check_in)->format('H:i:s') }}</strong>.
                                        @if ($attendanceToday->check_out)
                                            <br>Dan sudah check-out pada
                                            <strong>{{ Carbon\Carbon::parse($attendanceToday->check_out)->format('H:i:s') }}</strong>.
                                        @endif
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        Anda belum melakukan absensi hari ini.
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label for="location_id" class="form-label">Pilih Lokasi Absen</label>
                                    <select id="location_id" class="form-select">
                                        <option value="">-- Pilih Lokasi --</option>
                                        @foreach ($locations as $loc)
                                            <option value="{{ $loc->id }}" data-lat="{{ $loc->latitude }}"
                                                data-lng="{{ $loc->longitude }}" data-radius="{{ $loc->radius_meter }}">
                                                {{ $loc->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="distanceStatus" class="text-center my-3 small p-2 rounded"></div>

                                <div class="d-grid gap-2">
                                    @if (!$attendanceToday || !$attendanceToday->check_in)
                                        <button id="checkInBtn" class="btn btn-primary" disabled>Check-In</button>
                                    @elseif (!$attendanceToday->check_out)
                                        <button id="checkOutBtn" class="btn btn-danger" disabled>Check-Out</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div id="map" style="height: 400px; width: 100%; border-radius: .5rem;" class="shadow-sm">
                        </div>
                    </div>

                    <!-- Kolom Kanan: Riwayat Absensi -->
                    <div class="col-lg-7">
                        <h6 class="text-muted mb-3">Riwayat Absensi Anda</h6>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Check-In</th>
                                        <th>Check-Out</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($attendanceHistory as $att)
                                        <tr>
                                            <td>{{ $att->date->format('d M Y') }}</td>
                                            <td>{{ $att->check_in ? Carbon\Carbon::parse($att->check_in)->format('H:i:s') : '-' }}
                                            </td>
                                            <td>{{ $att->check_out ? Carbon\Carbon::parse($att->check_out)->format('H:i:s') : '-' }}
                                            </td>
                                            <td><span class="badge bg-success">{{ ucfirst($att->status) }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada riwayat absensi.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $attendanceHistory->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let map, userMarker, locationMarker, locationCircle;
        let userCoords = null;

        function initMap() {
            const initialPosition = {
                lat: -8.1689,
                lng: 113.7169
            }; // Default Lumajang
            map = new google.maps.Map(document.getElementById('map'), {
                center: initialPosition,
                zoom: 15,
                mapTypeControl: false,
                streetViewControl: false
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const locationSelect = document.getElementById('location_id');
            const checkInBtn = document.getElementById('checkInBtn');
            const checkOutBtn = document.getElementById('checkOutBtn');
            const distanceStatus = document.getElementById('distanceStatus');

            function haversineDistance(lat1, lon1, lat2, lon2) {
                const R = 6371e3; // metres
                const \u03c61 = lat1 * Math.PI / 180;
                const \u03c62 = lat2 * Math.PI / 180;
                const \u0394\u03c6 = (lat2 - lat1) * Math.PI / 180;
                const \u0394\u03bb = (lon2 - lon1) * Math.PI / 180;

                const a = Math.sin(\u0394\u03c6 / 2) * Math.sin(\u0394\u03c6 / 2) +
                    Math.cos(\u03c61) * Math.cos(\u03c62) *
                    Math.sin(\u0394\u03bb / 2) * Math.sin(\u0394\u03bb / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

                return R * c; // in metres
            }

            function updateUserMarker(position) {
                if (!userMarker) {
                    userMarker = new google.maps.Marker({
                        position: position,
                        map: map,
                        title: "Lokasi Anda",
                        icon: {
                            url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
                        }
                    });
                } else {
                    userMarker.setPosition(position);
                }
            }

            function updateLocationMarkerAndCircle(position, radius) {
                if (!locationMarker) {
                    locationMarker = new google.maps.Marker({
                        position: position,
                        map: map,
                        title: "Lokasi Absen"
                    });
                } else {
                    locationMarker.setPosition(position);
                }

                if (!locationCircle) {
                    locationCircle = new google.maps.Circle({
                        map: map,
                        radius: radius,
                        fillColor: '#AA0000',
                        fillOpacity: 0.2,
                        strokeColor: '#AA0000',
                        strokeOpacity: 0.6,
                        strokeWeight: 1,
                    });
                } else {
                    locationCircle.setRadius(radius);
                }
                locationCircle.setCenter(position);
            }

            function updateDistance() {
                if (!userCoords || locationSelect.value === '') {
                    distanceStatus.textContent = 'Pilih lokasi untuk melihat jarak.';
                    distanceStatus.className = 'text-center my-3 small p-2 rounded bg-light text-dark';
                    return;
                }

                const selectedOption = locationSelect.options[locationSelect.selectedIndex];
                const locLat = parseFloat(selectedOption.dataset.lat);
                const locLng = parseFloat(selectedOption.dataset.lng);
                const radius = parseFloat(selectedOption.dataset.radius);

                const distance = haversineDistance(userCoords.latitude, userCoords.longitude, locLat, locLng);

                distanceStatus.innerHTML = `Jarak Anda dari lokasi: <strong>${distance.toFixed(0)} meter</strong>.`;

                if (distance <= radius) {
                    distanceStatus.className =
                        'text-center my-3 small p-2 rounded bg-success-subtle text-success-emphasis';
                    if (checkInBtn) checkInBtn.disabled = false;
                    if (checkOutBtn) checkOutBtn.disabled = false;
                } else {
                    distanceStatus.className =
                        'text-center my-3 small p-2 rounded bg-danger-subtle text-danger-emphasis';
                    if (checkInBtn) checkInBtn.disabled = true;
                    if (checkOutBtn) checkOutBtn.disabled = true;
                }

                const locationPosition = {
                    lat: locLat,
                    lng: locLng
                };
                updateLocationMarkerAndCircle(locationPosition, radius);

                const bounds = new google.maps.LatLngBounds();
                bounds.extend(userMarker.getPosition());
                bounds.extend(locationMarker.getPosition());
                map.fitBounds(bounds);
            }

            function getLocation() {
                if (navigator.geolocation) {
                    distanceStatus.textContent = 'Mendapatkan lokasi Anda...';
                    navigator.geolocation.getCurrentPosition(position => {
                        userCoords = position.coords;
                        const userPosition = {
                            lat: userCoords.latitude,
                            lng: userCoords.longitude
                        };
                        updateUserMarker(userPosition);
                        updateDistance();
                    }, () => {
                        distanceStatus.textContent =
                            'Gagal mendapatkan lokasi. Izinkan akses lokasi di browser Anda.';
                        distanceStatus.className =
                            'text-center my-3 small p-2 rounded bg-danger-subtle text-danger-emphasis';
                    });
                } else {
                    distanceStatus.textContent = 'Geolocation tidak didukung oleh browser ini.';
                }
            }

            async function performCheck(action) {
                if (!userCoords || locationSelect.value === '') {
                    alert('Silakan pilih lokasi dan izinkan akses GPS.');
                    return;
                }

                const btn = action === 'in' ? checkInBtn : checkOutBtn;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';

                const url = action === 'in' ? '{{ route('kepala_sekolah.absensi.checkin') }}' :
                    '{{ route('kepala_sekolah.absensi.checkout') }}';

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            latitude: userCoords.latitude,
                            longitude: userCoords.longitude,
                            location_id: locationSelect.value
                        })
                    });

                    const result = await response.json();

                    if (response.ok) {
                        alert(result.message);
                        window.location.reload();
                    } else {
                        throw new Error(result.message || 'Terjadi kesalahan');
                    }
                } catch (error) {
                    alert('Error: ' + error.message);
                    btn.disabled = false;
                    btn.textContent = action === 'in' ? 'Check-In' : 'Check-Out';
                }
            }

            locationSelect.addEventListener('change', updateDistance);
            if (checkInBtn) checkInBtn.addEventListener('click', () => performCheck('in'));
            if (checkOutBtn) checkOutBtn.addEventListener('click', () => performCheck('out'));

            // Get initial location on page load
            getLocation();
        });

        // Inisialisasi peta setelah DOM dimuat, meskipun Google Maps API mungkin belum siap.
        // Google Maps API akan memanggil initMap() secara global setelah selesai dimuat.
        window.initMap = initMap;
    </script>
</body>

</html>
