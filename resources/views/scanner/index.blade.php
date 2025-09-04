<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi QR Code</title>
    <meta name="csrf-token" content="DUMMY_CSRF_TOKEN">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .scanner-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 800px;
            margin: 2rem auto;
        }
        
        .scanner-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .scanner-body {
            padding: 2rem;
        }
        
        #preview {
            width: 100%;
            border-radius: 15px;
            margin-bottom: 2rem;
            background-color: #f0f0f0;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #6c757d;
        }
        
        .result-section {
            background: #e9ecef;
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 2rem;
            display: none;
        }
        
        .stats-section {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
        }
        
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .loading {
            display: none;
            text-align: center;
            padding: 2rem;
        }
        
        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="scanner-container">
            <div class="scanner-header">
                <h1><i class="fas fa-qrcode"></i> Sistem Absensi QR Code</h1>
                <p class="mb-0">Arahkan QR Code ke kamera untuk melakukan absensi</p>
            </div>
            
            <div class="scanner-body">
                <div id="preview">Kamera tidak aktif (dummy)</div>
                
                <button class="btn btn-primary w-100" onclick="simulateScan()">Simulasikan Scan</button>

                <!-- Loading Section -->
                <div class="loading" id="loading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memproses absensi...</p>
                </div>
                
                <!-- Result Section -->
                <div class="result-section" id="resultSection">
                    <div id="resultContent"></div>
                </div>
                
                <!-- Statistics Section -->
                <div class="stats-section">
                    <h4 class="text-center mb-3">
                        <i class="fas fa-chart-bar"></i> Statistik Hari Ini
                    </h4>
                    <div class="row" id="statsContent">
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-number" id="totalToday">10</div>
                                <div class="stat-label">Total Absen</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-number" id="checkedIn">5</div>
                                <div class="stat-label">Check In</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-number" id="checkedOut">3</div>
                                <div class="stat-label">Check Out</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-number" id="present">2</div>
                                <div class="stat-label">Hadir</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function simulateScan() {
            processAttendance('DUMMY-QR-CODE');
        }

        // Process attendance
        function processAttendance(qrCode) {
            // Show loading
            document.getElementById('loading').style.display = 'block';
            document.getElementById('resultSection').style.display = 'none';
            
            // Simulate API call
            setTimeout(() => {
                document.getElementById('loading').style.display = 'none';
                const dummyData = {
                    user_name: 'Dummy User',
                    date: '01 Jan 2025',
                    scanned_by: 'Admin',
                    check_in_time: '08:00',
                    check_out_time: null
                };
                showResult('success', 'Absensi berhasil', dummyData);
            }, 1000);
        }

        // Show result
        function showResult(type, message, data = null) {
            const resultSection = document.getElementById('resultSection');
            const resultContent = document.getElementById('resultContent');
            
            let alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            let icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
            
            let html = `
                <div class="alert ${alertClass}">
                    <h5><i class="${icon}"></i> ${message}</h5>
            `;
            
            if (data) {
                html += `
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Nama:</strong> ${data.user_name}<br>
                            <strong>Tanggal:</strong> ${data.date}<br>
                            <strong>Scan oleh:</strong> ${data.scanned_by}
                        </div>
                        <div class="col-md-6">
                            <strong>Check In:</strong> ${data.check_in_time || '-'}<br>
                            <strong>Check Out:</strong> ${data.check_out_time || '-'}
                        </div>
                    </div>
                `;
            }
            
            html += '</div>';
            
            resultContent.innerHTML = html;
            resultSection.style.display = 'block';
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                resultSection.style.display = 'none';
            }, 5000);
        }
    </script>
</body>
</html>