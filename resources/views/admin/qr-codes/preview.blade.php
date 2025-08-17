<div class="text-center">
    <h4>QR Code untuk {{ $user->name }}</h4>
    <p class="text-muted">{{ $user->email }} - {{ ucfirst(str_replace('_', ' ', $user->role->name)) }}</p>
    
    <div class="qr-code-container mb-3">
        @if($user->qr_code_path)
            <img src="{{ Storage::url($user->qr_code_path) }}" alt="QR Code" class="img-fluid" style="max-width: 300px;">
        @else
            <div class="alert alert-warning">
                QR Code image belum dibuat. Silakan generate ulang.
            </div>
        @endif
    </div>
    
    <div class="qr-code-info">
        <p><strong>QR Code:</strong> <code>{{ $user->qr_code }}</code></p>
        <p><strong>Dibuat:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
    </div>
    
    <div class="mt-3">
        <button type="button" class="btn btn-success" onclick="downloadQrCode({{ $user->id }})">
            <i class="fas fa-download"></i> Download
        </button>
        <button type="button" class="btn btn-warning" onclick="printQrCode({{ $user->id }})">
            <i class="fas fa-print"></i> Print
        </button>
    </div>
</div>
