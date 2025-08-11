# Implementasi Rate Limiter untuk Performa Server

## 🚀 Overview

Rate limiter telah berhasil diterapkan pada semua controller dalam aplikasi untuk mempertahankan performa server dan mencegah abuse. Implementasi ini menggunakan Laravel's built-in rate limiting dengan konfigurasi yang dapat dikustomisasi.

## 📋 Controller yang Telah Dilengkapi Rate Limiter

### Admin Controllers
- ✅ **AbsensiController** - Rate limit untuk check-in/check-out dan QR generation
- ✅ **WalletController** - Rate limit untuk transaksi keuangan
- ✅ **UserController** - Rate limit untuk manajemen user
- ✅ **CoursesController** - Rate limit untuk manajemen kursus
- ✅ **AcademicYearController** - Rate limit untuk tahun ajaran
- ✅ **ReportsController** - Rate limit untuk generate laporan
- ✅ **DashboardController** - Rate limit untuk dashboard
- ✅ **Login** - Rate limit untuk login/logout

### Kepala Sekolah Controllers
- ✅ **AbsensiControllerKepsek** - Rate limit untuk absensi kepala sekolah
- ✅ **LaporanKeuanganController** - Rate limit untuk laporan keuangan

### Staff TU Controllers
- ✅ **AbsensiController** - Rate limit untuk absensi staff TU
- ✅ **DashboardController** - Rate limit untuk dashboard staff TU
- ✅ **SiswaController** - Rate limit untuk manajemen siswa

## 🔧 Middleware yang Dibuat

### 1. Basic Rate Limiter (`RateLimiter`)
- Middleware sederhana dengan parameter langsung
- Contoh: `throttle:60,1` (60 request per menit)

### 2. Advanced Rate Limiter (`AdvancedRateLimiter`)
- Middleware yang menggunakan konfigurasi dari file config
- Contoh: `throttle.advanced:login` (menggunakan konfigurasi login)

## ⚙️ Konfigurasi Rate Limiter

File: `config/rate_limiter.php`

### Limit yang Diterapkan

| Operation | Limit | Keterangan |
|-----------|-------|------------|
| Login | 5 request/menit | Keamanan tinggi |
| Logout | 30 request/menit | Operasi normal |
| Dashboard | 60 request/menit | View data |
| Check-in/Check-out | 30 request/menit | Operasi absensi |
| QR Scan | 20 request/menit | Scan QR code |
| User CRUD | 30 request/menit | Manajemen user |
| Course CRUD | 30 request/menit | Manajemen kursus |
| Wallet Transaction | 20 request/menit | Transaksi keuangan |
| Reports | 30 request/menit | Generate laporan |
| Academic Year | 10 request/menit | Operasi jarang |
| QR Generation | 10 request/menit | Generate QR |

## 🛡️ Keamanan

### Login Protection
- **5 request per menit** untuk mencegah brute force attack
- Response JSON dengan pesan yang informatif
- Headers untuk monitoring rate limit

### Resource Protection
- **10-20 request per menit** untuk operasi yang membutuhkan resource tinggi
- **30-60 request per menit** untuk operasi normal

## 📊 Monitoring

### Headers yang Ditambahkan
- `X-RateLimit-Limit`: Limit maksimum
- `X-RateLimit-Remaining`: Request tersisa
- `X-RateLimit-Reset`: Waktu reset
- `Retry-After`: Waktu tunggu

### Response Format
```json
{
    "error": "Too many requests",
    "message": "Terlalu banyak request. Silakan coba lagi dalam 1 menit.",
    "retry_after": 60,
    "limit_type": "login"
}
```

## 🔄 Cara Kerja

1. **Request Signature**: Setiap request memiliki signature unik berdasarkan user ID, IP, dan route
2. **Rate Checking**: Middleware mengecek apakah user telah melebihi limit
3. **Response**: Jika melebihi limit, return error 429 dengan pesan informatif
4. **Tracking**: Setiap request yang berhasil di-track untuk monitoring

## 📝 Dokumentasi Lengkap

Lihat file `docs/RATE_LIMITER.md` untuk dokumentasi lengkap termasuk:
- Cara customisasi limit
- Best practices
- Troubleshooting
- Testing

## 🚀 Manfaat

### Performa Server
- Mencegah overload server
- Mengurangi beban database
- Mengoptimalkan resource usage

### Keamanan
- Mencegah brute force attack
- Mengurangi spam request
- Melindungi endpoint sensitif

### User Experience
- Pesan error yang informatif
- Headers untuk monitoring
- Graceful degradation

## 🔧 Maintenance

### Menambah Rate Limiter Baru
1. Edit `config/rate_limiter.php`
2. Tambahkan konfigurasi baru
3. Terapkan di constructor controller

### Monitoring
- Gunakan Laravel's cache system
- Monitor rate limit violations
- Sesuaikan limit berdasarkan usage

## ✅ Status Implementasi

**100% Complete** - Semua controller telah dilengkapi dengan rate limiter yang sesuai dengan kebutuhan masing-masing operasi.

---

**Note**: Rate limiter ini menggunakan Laravel's cache system. Pastikan cache driver dikonfigurasi dengan benar di `config/cache.php`.
