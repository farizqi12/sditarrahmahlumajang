<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Absensi - E-Learning</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('css/admin/absensi.css') }}">
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <x-sidebar></x-sidebar>

    <div class="content">
        <x-navbar></x-navbar>
        <x-notif></x-notif>
        <div class="card p-3 mt-4">
            <h5>Manajemen Lokasi Absensi</h5>
            <div class="row">
                <div class="col-md-6">
                    <input id="pac-input" class="form-control mb-2" type="text" placeholder="Cari Lokasi...">
                    <div id="map" style="height: 400px; width: 100%;"></div>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('admin.absensi.locations.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="locationName" class="form-label">Nama Lokasi</label>
                            <input type="text" class="form-control" id="locationName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="text" class="form-control" id="latitude" name="latitude" readonly required>
                        </div>
                        <div class="mb-3">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="text" class="form-control" id="longitude" name="longitude" readonly
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="radius" class="form-label">Radius (meter)</label>
                            <input type="number" class="form-control" id="radius" name="radius" value="100"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="roles" class="form-label">Peran yang Diizinkan</label>
                            <select class="form-select" id="roles" name="roles[]" multiple required>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Lokasi</button>
                    </form>
                </div>
            </div>
            <hr>
            <h5>Daftar Lokasi</h5>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nama Lokasi</th>
                            <th>Radius</th>
                            <th>Peran</th>
                            <th>QR Code</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($locations as $location)
                            <tr>
                                <td>{{ $location->name }}</td>
                                <td>{{ $location->radius_meter }} meter</td>
                                <td>
                                    @foreach ($location->roles as $role)
                                        <span class="badge bg-info">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @if ($location->qrcode_path)
                                        <a href="{{ route('admin.absensi.locations.qrcode', $location) }}" class="btn btn-primary btn-sm" target="_blank">Unduh QR Code (PDF)</a>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.absensi.locations.destroy', $location) }}"
                                        method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus lokasi ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada lokasi yang ditambahkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <hr>
            <h5>Data Absensi</h5>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendanceHistory as $attendance)
                            <tr>
                                <td>{{ $attendance->user->name }}</td>
                                <td>{{ $attendance->date->format('d M Y') }}</td>
                                <td><span class="badge bg-success">{{ ucfirst($attendance->status) }}</span></td>
                                <td>{{ $attendance->check_in }} - {{ $attendance->check_out ?? 'Belum Checkout' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data absensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('maps.google_maps_api_key') }}&libraries=places&callback=initMap"
        async defer></script> --}}
    <script src="https://cdn.jsdelivr.net/gh/somanchiu/Keyless-Google-Maps-API@v7.1/mapsJavaScriptAPI.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        let map, marker, circle;

        new TomSelect("#roles", {
            plugins: ['remove_button'],
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });

        function initMap() {
            const initialPosition = {
                lat: -8.1689,
                lng: 113.7169
            }; // Default Lumajang

            map = new google.maps.Map(document.getElementById('map'), {
                center: initialPosition,
                zoom: 15
            });

            marker = new google.maps.Marker({
                position: initialPosition,
                map: map,
                draggable: true
            });

            circle = new google.maps.Circle({
                map: map,
                radius: 100, // Initial radius
                fillColor: '#AA0000',
                fillOpacity: 0.3,
                strokeColor: '#AA0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
            });

            circle.bindTo('center', marker, 'position');

            google.maps.event.addListener(marker, 'dragend', function(event) {
                document.getElementById('latitude').value = event.latLng.lat();
                document.getElementById('longitude').value = event.latLng.lng();
            });

            document.getElementById('radius').addEventListener('input', function() {
                const radiusValue = parseInt(this.value, 10);
                if (!isNaN(radiusValue)) {
                    circle.setRadius(radiusValue);
                }
            });

            // Set initial form values
            document.getElementById('latitude').value = initialPosition.lat;
            document.getElementById('longitude').value = initialPosition.lng;

            // Create the search box and link it to the UI element.
            const input = document.getElementById('pac-input');
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', () => {
                searchBox.setBounds(map.getBounds());
            });

            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener('places_changed', () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }

                    marker.setPosition(place.geometry.location);
                    document.getElementById('latitude').value = place.geometry.location.lat();
                    document.getElementById('longitude').value = place.geometry.location.lng();

                    if (place.geometry.viewport) {
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        }
    </script>
</body>

</html>
