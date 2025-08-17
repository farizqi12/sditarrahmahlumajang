# SISTEM ABSENSI QR CODE - DOKUMENTASI LENGKAP

## **KONSEP BARU SISTEM ABSENSI**

### **Perubahan Konsep:**
- **SEBELUM**: Setiap lokasi memiliki QR code, user scan QR code lokasi
- **SESUDAH**: Setiap user memiliki QR code unik di ID card, sistem/server yang melakukan scanning

### **Alur Kerja Baru:**
1. **Admin** membuat QR code unik untuk setiap user
2. QR code dicetak di ID card masing-masing user
3. User datang ke sekolah dengan ID card
4. **Staff/Admin** scan QR code dari ID card user menggunakan sistem
5. Sistem mencatat kehadiran user secara otomatis

---

## **FITUR UTAMA**

### **1. Manajemen QR Code (Admin)**
- Generate QR code unik untuk setiap user
- Bulk generate QR code untuk multiple user
- Preview QR code sebelum cetak
- Download QR code sebagai gambar
- Print ID card dengan QR code

### **2. QR Code Scanner Interface**
- Interface scanning yang user-friendly
- Support manual input QR code
- Real-time feedback saat scanning
- Statistik kehadiran hari ini
- Support check-in dan check-out

### **3. Sistem Absensi**
- Tracking user yang melakukan scan
- Device ID tracking
- Timestamp yang akurat
- Status kehadiran (hadir, sakit, izin, alpa)

---

## **STRUKTUR DATABASE BARU**

### **Tabel Users (Updated)**
```sql
users (
    id,
    name,
    email,
    qr_code,           -- NEW: QR code unik user
    qr_code_path,      -- NEW: Path ke file QR code image
    password,
    role_id,
    created_at,
    updated_at
)
```

### **Tabel Attendances (Updated)**
```sql
attendances (
    id,
    user_id,           -- User yang di-scan
    scanned_by,        -- NEW: User yang melakukan scan
    device_id,         -- NEW: ID perangkat scanner
    date,
    check_in,
    check_out,
    status,
    notes,             -- NEW: Catatan tambahan
    created_at,
    updated_at
)
```

---

## **ENDPOINTS API**

### **QR Code Scanner**
```
GET  /scanner                    - Interface scanner
POST /scanner/scan              - Process QR code scan
GET  /scanner/stats             - Get attendance statistics
```

### **QR Code Management (Admin)**
```
GET  /admin/qr-codes                    - List semua user dan QR code
POST /admin/qr-codes/generate           - Generate QR code untuk user
POST /admin/qr-codes/bulk-generate      - Bulk generate QR code
GET  /admin/qr-codes/download           - Download QR code image
GET  /admin/qr-codes/{user}/preview     - Preview QR code
GET  /admin/qr-codes/{user}/print       - Print ID card
```

---

## **CARA PENGGUNAAN**

### **1. Setup QR Code (Admin)**
1. Login sebagai admin
2. Akses menu "Manajemen QR Code"
3. Pilih user yang belum memiliki QR code
4. Klik "Generate" untuk membuat QR code
5. Download atau print QR code untuk ID card

### **2. Scanning Absensi (Staff/Admin)**
1. Akses `/scanner` di browser
2. Pilih mode "Check In" atau "Check Out"
3. Scan QR code dari ID card user
4. Sistem akan memproses dan menampilkan hasil
5. Statistik akan update secara real-time

### **3. Monitoring Absensi**
- Lihat statistik real-time di interface scanner
- Akses laporan absensi di admin panel
- Export data absensi untuk analisis

---

## **KEAMANAN**

### **QR Code Security**
- QR code unik per user dengan format: `USER_{ID}_{TIMESTAMP}_{HASH}`
- Tidak dapat di-duplicate atau di-reuse
- Tracking user yang melakukan scan untuk audit trail

### **Access Control**
- Hanya user yang login yang dapat mengakses scanner
- Admin dapat mengelola QR code
- Role-based access control untuk semua fitur

---

## **INSTALASI & SETUP**

### **1. Install Dependencies**
```bash
composer require simplesoftwareio/simple-qrcode
```

### **2. Run Migrations**
```bash
php artisan migrate
```

### **3. Generate QR Codes**
```bash
php artisan db:seed --class=QrCodeSeeder
```

### **4. Setup Storage**
```bash
php artisan storage:link
```

---

## **CONTOH QR CODE FORMAT**

### **Format QR Code:**
```
USER_1_1705123456_A1B2C3D4
```

**Penjelasan:**
- `USER_` - Prefix untuk identifikasi
- `1` - User ID
- `1705123456` - Timestamp saat dibuat
- `A1B2C3D4` - Hash dari email user

---

## **ID CARD TEMPLATE**

### **Ukuran Standar:**
- Width: 85.6mm (kartu kredit)
- Height: 54mm
- Format: PNG/PDF untuk print

### **Informasi di ID Card:**
- Nama sekolah
- Nama user
- Email
- Role
- QR code
- User ID

---

## **TROUBLESHOOTING**

### **QR Code tidak terbaca:**
1. Pastikan QR code sudah di-generate
2. Cek kualitas print QR code
3. Pastikan scanner dalam kondisi baik
4. Coba input manual QR code

### **Error saat scanning:**
1. Cek koneksi internet
2. Pastikan user sudah login
3. Cek permission user
4. Restart aplikasi jika perlu

---

## **PERFORMANCE**

### **Optimization:**
- QR code image cached di storage
- Database indexing untuk query cepat
- Rate limiting untuk mencegah abuse
- Real-time stats dengan AJAX

### **Scalability:**
- Support multiple scanner devices
- Concurrent scanning support
- Database optimization untuk data besar

---

## **MAINTENANCE**

### **Regular Tasks:**
1. Backup database secara berkala
2. Clean up QR code images lama
3. Monitor storage usage
4. Update QR code jika diperlukan

### **Monitoring:**
- Log semua scanning activity
- Track failed scans
- Monitor system performance
- Alert untuk error yang sering terjadi

---

## **KESIMPULAN**

Sistem absensi QR code baru ini memberikan:
- **Efisiensi**: Tidak perlu setup QR code di setiap lokasi
- **Fleksibilitas**: User dapat absen di mana saja dengan ID card
- **Keamanan**: QR code unik per user dengan audit trail
- **Kemudahan**: Interface yang user-friendly untuk scanning
- **Monitoring**: Real-time statistics dan reporting

Sistem ini cocok untuk sekolah yang ingin modernisasi sistem absensi dengan tetap menjaga keamanan dan akurasi data.
