# Rate Limiter Documentation

## Overview

Sistem rate limiter telah diterapkan pada semua controller untuk mempertahankan performa server dan mencegah abuse. Rate limiter membatasi jumlah request yang dapat dilakukan oleh user dalam periode waktu tertentu.

## Middleware yang Tersedia

### 1. Basic Rate Limiter (`throttle`)
```php
$this->middleware('throttle:60,1')->only(['index']); // 60 request per menit
```

### 2. Advanced Rate Limiter (`throttle.advanced`)
```php
$this->middleware('throttle.advanced:login')->only(['login']); // Menggunakan konfigurasi
```

**Note:** Basic rate limiter menggunakan `CustomRateLimiter` class untuk menghindari konflik dengan Laravel's built-in `RateLimiter` facade.

## Konfigurasi Rate Limiter

File konfigurasi: `config/rate_limiter.php`

### Default Limits
- **Default**: 60 request per menit
- **Login**: 5 request per menit (untuk keamanan)
- **Logout**: 30 request per menit

### Attendance Limits
- **Check-in/Check-out**: 30 request per menit
- **Scan QR**: 20 request per menit
- **View**: 60 request per menit

### User Management Limits
- **CRUD Operations**: 30 request per menit
- **View**: 60 request per menit

### Course Management Limits
- **CRUD Operations**: 30 request per menit
- **Student Management**: 20 request per menit
- **View**: 60 request per menit

### Wallet/Financial Limits
- **Transactions**: 20 request per menit
- **View**: 60 request per menit

### Reports Limits
- **Generate Reports**: 30 request per menit

### Academic Year Limits
- **Operations**: 10 request per menit (karena jarang digunakan)

### QR Code Generation Limits
- **Generate QR**: 10 request per menit

## Implementasi pada Controller

### Admin Controllers

#### AbsensiController
```php
public function __construct()
{
    $this->middleware('throttle:30,1')->only(['checkIn', 'checkOut']);
    $this->middleware('throttle:60,1')->only(['index', 'storeLocation', 'destroyLocation']);
    $this->middleware('throttle:10,1')->only(['showQrCode']);
}
```

#### WalletController
```php
public function __construct()
{
    $this->middleware('throttle:20,1')->only(['store', 'acceptTransaction', 'rejectTransaction']);
    $this->middleware('throttle:60,1')->only(['index', 'show', 'pending']);
}
```

#### UserController
```php
public function __construct()
{
    $this->middleware('throttle:30,1')->only(['store', 'update', 'destroy']);
    $this->middleware('throttle:60,1')->only(['index']);
}
```

#### Login Controller
```php
public function __construct()
{
    $this->middleware('throttle:5,1')->only(['login']);
    $this->middleware('throttle:30,1')->only(['logout']);
}
```

### Kepala Sekolah Controllers

#### AbsensiControllerKepsek
```php
public function __construct()
{
    $this->middleware('throttle:30,1')->only(['checkIn', 'checkOut']);
    $this->middleware('throttle:60,1')->only(['index']);
    $this->middleware('throttle:20,1')->only(['scan']);
}
```

#### LaporanKeuanganController
```php
public function __construct()
{
    $this->middleware('throttle:30,1')->only(['index', 'getFinancialData']);
}
```

### Staff TU Controllers

#### AbsensiController
```php
public function __construct()
{
    $this->middleware('throttle:30,1')->only(['checkIn', 'checkOut']);
    $this->middleware('throttle:60,1')->only(['index']);
    $this->middleware('throttle:20,1')->only(['scan']);
}
```

## Response Format

Ketika rate limit terlampaui, sistem akan mengembalikan response JSON:

```json
{
    "error": "Too many requests",
    "message": "Terlalu banyak request. Silakan coba lagi dalam 1 menit.",
    "retry_after": 60,
    "limit_type": "login"
}
```

## Headers yang Ditambahkan

- `X-RateLimit-Limit`: Jumlah maksimum request yang diizinkan
- `X-RateLimit-Remaining`: Jumlah request yang tersisa
- `X-RateLimit-Reset`: Waktu reset dalam detik
- `Retry-After`: Waktu tunggu sebelum dapat request lagi

## Customisasi

### Menambah Limit Baru

1. Edit file `config/rate_limiter.php`
2. Tambahkan konfigurasi baru di array `limits`
3. Tambahkan pesan custom di array `messages`

### Menggunakan Advanced Rate Limiter

```php
// Di constructor controller
$this->middleware('throttle.advanced:login')->only(['login']);
$this->middleware('throttle.advanced:attendance_checkin')->only(['checkIn']);
```

## Monitoring

Rate limiter menggunakan Laravel's built-in cache system. Pastikan cache driver dikonfigurasi dengan benar di `config/cache.php`.

## Best Practices

1. **Login**: Gunakan limit yang ketat (5 request per menit)
2. **CRUD Operations**: Gunakan limit sedang (20-30 request per menit)
3. **View Operations**: Gunakan limit longgar (60 request per menit)
4. **Resource-Intensive Operations**: Gunakan limit ketat (10 request per menit)

## Troubleshooting

### Rate Limit Terlalu Ketat
- Edit konfigurasi di `config/rate_limiter.php`
- Sesuaikan nilai `attempts` dan `decay_minutes`

### Rate Limit Tidak Berfungsi
- Pastikan cache driver berfungsi
- Periksa konfigurasi middleware di `bootstrap/app.php`
- Pastikan constructor controller dipanggil dengan benar

### Testing Rate Limiter
```bash
# Test dengan curl
curl -X POST http://localhost/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password"}' \
  -v
```
