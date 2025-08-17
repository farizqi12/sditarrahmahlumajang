<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi QR Code</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        
        .qr-input-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 2rem;
        }
        
        .btn-action {
            padding: 1rem 2rem;
            border-radius: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        
        .btn-checkin {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            color: white;
        }
        
        .btn-checkout {
            background: linear-gradient(135deg, #dc3545, #fd7e14);
            border: none;
            color: white;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
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
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            font-size: 1.1rem;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
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
                <p class="mb-0">Scan QR Code dari ID Card untuk absen</p>
            </div>
            
            <div class="scanner-body">
                <!-- QR Code Input Section -->
                <div class="qr-input-section">
                    <h4 class="text-center mb-3">
                        <i class="fas fa-barcode"></i> Masukkan QR Code
                    </h4>
                    
                    <div class="mb-3">
                        <label for="qrCode" class="form-label">QR Code dari ID Card:</label>
                        <input type="text" class="form-control" id="qrCode" placeholder="Scan atau ketik QR Code..." autofocus>
                    </div>
                    
                    <div class="mb-3">
                        <label for="deviceId" class="form-label">ID Perangkat (Opsional):</label>
                        <input type="text" class="form-control" id="deviceId" placeholder="Contoh: DEVICE_001">
                    </div>
                    
                    <div class="action-buttons">
                        <button type="button" class="btn btn-action btn-checkin" onclick="processAttendance('checkin')">
                            <i class="fas fa-sign-in-alt"></i> Check In
                        </button>
                        <button type="button" class="btn btn-action btn-checkout" onclick="processAttendance('checkout')">
                            <i class="fas fa-sign-out-alt"></i> Check Out
                        </button>
                    </div>
                </div>
                
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
                                <div class="stat-number" id="totalToday">0</div>
                                <div class="stat-label">Total Absen</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-number" id="checkedIn">0</div>
                                <div class="stat-label">Check In</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-number" id="checkedOut">0</div>
                                <div class="stat-label">Check Out</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-number" id="present">0</div>
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
        // Set CSRF token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Load statistics on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadStats();
        });

        // Process attendance
        function processAttendance(action) {
            const qrCode = document.getElementById('qrCode').value.trim();
            const deviceId = document.getElementById('deviceId').value.trim();
            
            if (!qrCode) {
                showResult('error', 'QR Code harus diisi!');
                return;
            }
            
            // Show loading
            document.getElementById('loading').style.display = 'block';
            document.getElementById('resultSection').style.display = 'none';
            
            // Make AJAX request
            fetch('/scanner/scan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    qr_code: qrCode,
                    device_id: deviceId,
                    action: action
                })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('loading').style.display = 'none';
                
                if (data.success) {
                    showResult('success', data.message, data.data);
                    document.getElementById('qrCode').value = '';
                    loadStats(); // Refresh statistics
                } else {
                    showResult('error', data.message);
                }
            })
            .catch(error => {
                document.getElementById('loading').style.display = 'none';
                showResult('error', 'Terjadi kesalahan: ' + error.message);
            });
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

        // Load statistics
        function loadStats() {
            fetch('/scanner/stats')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('totalToday').textContent = data.data.total_today;
                    document.getElementById('checkedIn').textContent = data.data.checked_in;
                    document.getElementById('checkedOut').textContent = data.data.checked_out;
                    document.getElementById('present').textContent = data.data.present;
                }
            })
            .catch(error => {
                console.error('Error loading stats:', error);
            });
        }

        // Auto focus on QR code input
        document.getElementById('qrCode').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                processAttendance('checkin');
            }
        });

        // Auto refresh stats every 30 seconds
        setInterval(loadStats, 30000);
    </script>
</body>
</html>
