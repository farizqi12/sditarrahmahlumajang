<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card - {{ $user->name }}</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        
        .id-card {
            width: 85.6mm;
            height: 54mm;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin: 0 auto;
            position: relative;
            overflow: hidden;
        }
        
        .id-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
        }
        
        .id-card-body {
            padding: 15px;
            display: flex;
            align-items: center;
            height: calc(100% - 60px);
        }
        
        .user-info {
            flex: 1;
            padding-right: 15px;
        }
        
        .user-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        
        .user-details {
            font-size: 12px;
            color: #666;
            line-height: 1.4;
        }
        
        .qr-code-section {
            text-align: center;
        }
        
        .qr-code {
            width: 80px;
            height: 80px;
            border: 2px solid #ddd;
            border-radius: 5px;
        }
        
        .qr-code-text {
            font-size: 10px;
            color: #999;
            margin-top: 5px;
            word-break: break-all;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .print-button:hover {
            background: #0056b3;
        }
        
        .multiple-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(85.6mm, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        @media print {
            .multiple-cards {
                grid-template-columns: repeat(2, 85.6mm);
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">
        <i class="fas fa-print"></i> Print ID Card
    </button>
    
    <div class="multiple-cards">
        <!-- Print multiple copies for cutting -->
        @for($i = 0; $i < 4; $i++)
        <div class="id-card">
            <div class="id-card-header">
                ID CARD SEKOLAH
            </div>
            <div class="id-card-body">
                <div class="user-info">
                    <div class="user-name">{{ $user->name }}</div>
                    <div class="user-details">
                        <strong>Email:</strong> {{ $user->email }}<br>
                        <strong>Role:</strong> {{ ucfirst(str_replace('_', ' ', $user->role->name)) }}<br>
                        <strong>ID:</strong> {{ $user->id }}<br>
                        <strong>QR:</strong> {{ substr($user->qr_code, 0, 20) }}...
                    </div>
                </div>
                <div class="qr-code-section">
                    @if($user->qr_code_path)
                        <img src="{{ Storage::url($user->qr_code_path) }}" alt="QR Code" class="qr-code">
                    @else
                        <div class="qr-code" style="background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999;">
                            QR<br>Code
                        </div>
                    @endif
                    <div class="qr-code-text">{{ $user->qr_code }}</div>
                </div>
            </div>
        </div>
        @endfor
    </div>
    
    <script>
        // Auto print when page loads
        window.onload = function() {
            // Uncomment the line below to auto-print
            // window.print();
        };
    </script>
</body>
</html>
