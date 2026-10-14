# Floating Button Saran & Masukan - Dokumentasi

## Gambaran Umum

Floating button saran dan masukan adalah fitur baru yang memungkinkan pengguna untuk memberikan feedback pengembangan aplikasi SIMPLY dengan mudah dan cepat. Button ini muncul di semua halaman aplikasi sebagai tombol mengambang di pojok kanan bawah.

## Fitur Utama

### 1. **Floating Button**
- Posisi tetap di pojok kanan bawah layar
- Animasi pulse untuk menarik perhatian
- Tooltip informatif saat hover
- Responsive design untuk semua ukuran layar

### 2. **Form Feedback Komprehensif**
- **Kategori**: UI/UX, Fitur, Bug, Performa, Keamanan, Lainnya
- **Prioritas**: Rendah, Sedang, Tinggi, Kritis
- **Judul**: Maksimal 100 karakter dengan counter
- **Deskripsi**: Detail feedback dengan auto-resize textarea
- **Modul**: Spesifikasi halaman/modul terkait
- **Opsi Anonim**: Pengguna dapat memilih kirim sebagai anonim

### 3. **Validasi & UX**
- Validasi form real-time
- Loading state saat submit
- Notifikasi sukses/error menggunakan SweetAlert2
- Auto-reset form setelah submit berhasil

## Implementasi Teknis

### File yang Terlibat:

1. **View Component**: `app/Views/home/floating_feedback_button.php`
   - Komponen UI floating button dan modal form
   - CSS styling untuk animasi dan responsivitas
   - JavaScript untuk handling form dan AJAX

2. **Model**: `app/Models/FeedbackModel.php`
   - Model untuk interaksi database feedback
   - Validasi data dan business logic
   - Helper methods untuk statistik dan laporan

3. **Controller**: `app/Controllers/Ajax.php`
   - Method `feedback()` untuk handling AJAX request
   - Validasi server-side
   - Logging aktivitas ke linimasa

4. **Database**: Tabel `feedback`
   - Struktur tabel untuk menyimpan data feedback
   - Index untuk optimasi query
   - Foreign key relationship dengan user

### Integrasi:

Button terintegrasi di layout utama `adminLTE-3.2.0.php` sehingga muncul di semua halaman aplikasi.

## Cara Penggunaan

### Untuk Pengguna:

1. **Mengakses Form**
   - Klik floating button di pojok kanan bawah
   - Modal form akan terbuka

2. **Mengisi Form**
   - Pilih kategori feedback (wajib)
   - Tentukan prioritas (default: Sedang)
   - Tulis judul singkat dan jelas (wajib)
   - Berikan deskripsi detail (wajib)
   - Sebutkan modul/halaman terkait (opsional)
   - Centang "Kirim sebagai anonim" jika diperlukan

3. **Submit Feedback**
   - Klik "Kirim Saran"
   - Tunggu konfirmasi berhasil
   - Form akan reset otomatis

### Untuk Administrator:

1. **Melihat Statistik**
   - Dashboard admin menampilkan statistik feedback
   - Jumlah total, baru, diproses, selesai

2. **Mengelola Feedback**
   - List semua feedback dengan filter status
   - Detail view untuk setiap feedback
   - Update status feedback (Baru → Diproses → Selesai)

## Database Schema

```sql
CREATE TABLE `feedback` (
  `id_feedback` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kategori` enum('UI/UX','Fitur','Bug','Performa','Keamanan','Lainnya') NOT NULL,
  `prioritas` enum('Rendah','Sedang','Tinggi','Kritis') NOT NULL DEFAULT 'Sedang',
  `judul` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `modul` varchar(100) DEFAULT NULL,
  `anonymous` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('Baru','Diproses','Selesai','Ditolak') NOT NULL DEFAULT 'Baru',
  `tanggapan` text DEFAULT NULL,
  `created_by` int(11) UNSIGNED NOT NULL,
  `updated_by` int(11) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_feedback`),
  KEY `idx_status_created` (`status`, `created_at`),
  KEY `idx_kategori` (`kategori`),
  KEY `idx_prioritas` (`prioritas`),
  KEY `idx_created_by` (`created_by`)
);
```

## Keamanan

1. **CSRF Protection**: Form menggunakan CSRF token CodeIgniter
2. **Input Validation**: Validasi server-side dan client-side
3. **SQL Injection Prevention**: Menggunakan prepared statements
4. **XSS Protection**: Output escaping otomatis
5. **Rate Limiting**: Bisa ditambahkan untuk mencegah spam

## Monitoring & Analytics

1. **Activity Logging**: Setiap feedback dicatat di linimasa
2. **Statistics**: Dashboard untuk melihat tren feedback
3. **Categorization**: Grouping berdasarkan kategori dan prioritas
4. **Response Time**: Tracking waktu respon admin

## Customization

### Styling:
- Warna dan animasi bisa disesuaikan di CSS
- Position button dapat diubah sesuai kebutuhan
- Modal styling mengikuti theme AdminLTE

### Functionality:
- Kategori dan prioritas dapat ditambah/dikurangi
- Validation rules dapat disesuaikan
- Email notification bisa ditambahkan

## Troubleshooting

### Common Issues:

1. **Button tidak muncul**
   - Pastikan layout include floating_feedback_button.php
   - Check CSS conflicts
   - Verify JavaScript errors di console

2. **Form tidak submit**
   - Check AJAX URL routing
   - Verify CSRF token
   - Check server-side validation errors

3. **Database errors**
   - Pastikan tabel feedback sudah dibuat
   - Check database connection
   - Verify user permissions

## Future Enhancements

1. **Email Notifications**: Notif email ke admin saat ada feedback baru
2. **File Attachments**: Upload screenshot untuk bug reports
3. **Voting System**: User bisa vote feedback yang ada
4. **Public Roadmap**: Tampilan public untuk status feedback
5. **Integration**: Integrasi dengan sistem ticketing external

## Support

Untuk pertanyaan atau masalah terkait floating button feedback, silakan hubungi:
- IT Support Balai Kekarantinaan Kesehatan Kelas I Bandung
- Email: support@simply.app
- Internal: Melalui sistem feedback ini sendiri 😊
