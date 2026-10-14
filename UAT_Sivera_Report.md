# Laporan User Acceptance Testing (UAT)
## Modul Surveilans Vektor & Reservoar (SIVERA)

**Tanggal:** 07 Oktober 2026
**Sistem:** Aplikasi Simply (Modul PRL SIVERA)
**Lingkungan:** Localhost (AMPPS)

---

### 1. Tujuan Pengujian
Menguji dan memvalidasi penambahan fitur baru dan perbaikan bug pada modul SIVERA agar sesuai dengan kebutuhan pengguna, termasuk form Jentik, Kecoa, Tikus, Lalat, dan penambahan tab Dashboard.

### 2. Skenario & Hasil Pengujian

#### 2.1. Form Jentik Aedes sp.
- **Skenario:** Menambahkan opsi "Lain-Lain" pada Jenis Kontainer Positif dengan input teks dinamis.
- **Langkah Pengujian:** 
  1. Buka form tambah/edit Jentik.
  2. Centang checkbox "Lain-Lain".
  3. Isi nama kontainer pada kolom teks yang muncul.
  4. Simpan data.
- **Hasil yang Diharapkan:** Kolom teks hanya muncul dan aktif jika checkbox "Lain-Lain" dicentang. Data kontainer kustom tersimpan dan dapat dimuat ulang saat form edit dibuka.
- **Status:** **[ PASS ]**

#### 2.2. Form Kecoa
- **Skenario:** Menghapus inputan spesies *Supella longipalpa* dan *Neostylopiga rhombifolia*, menggantinya dengan input "Lain-Lain".
- **Langkah Pengujian:**
  1. Buka form tambah/edit Kecoa.
  2. Pastikan dua spesies lama tidak ada.
  3. Centang "Lain-Lain", isi nama spesies kustom dan jumlah tangkapan.
  4. Simpan data.
- **Hasil yang Diharapkan:** Spesies kustom tersimpan ke dalam format JSON di database dan terakumulasi dalam perhitungan total. Saat diedit, data kustom muncul kembali secara otomatis.
- **Status:** **[ PASS ]**

#### 2.3. Form Tikus
- **Skenario:** Menyesuaikan form input untuk Tikus (menghapus "Jumlah Perangkap Hilang" yang lama dan memastikan input "Jenis Umpan", "Jumlah Perangkap Dipasang", dan "Jumlah Tikus Tertangkap" sejajar).
- **Langkah Pengujian:**
  1. Buka form tambah/edit Tikus.
  2. Periksa layout kolom form.
- **Hasil yang Diharapkan:** Form tersusun rapi tanpa kolom "Jumlah Perangkap Hilang" di tabel utama, dan form input lebih terorganisir.
- **Status:** **[ PASS ]**

#### 2.4. Form Lalat
- **Skenario:** Mengubah form agar mendukung input multi-lokasi sekaligus dan menambahkan pelabelan status MS/TMS otomatis.
- **Langkah Pengujian:**
  1. Buka form tambah Lalat.
  2. Klik tombol "+ Tambah Lokasi".
  3. Isi titik lokasi tambahan beserta nilai T1-T10.
  4. Simpan data.
  5. Lihat hasil di tabel Lalat.
- **Hasil yang Diharapkan:** Form dapat menggandakan input lokasi secara dinamis. Perhitungan rata-rata setiap lokasi berjalan independen. Sistem melabeli (MS/TMS) berdasarkan Standar Acuan Kepadatan Lalat (<2 MS, 2-5 TMS Sedang, 6-20 TMS Tinggi, >20 TMS Sangat Tinggi).
- **Status:** **[ PASS ]**

#### 2.5. Tab Dashboard & Navigasi
- **Skenario:** Menambahkan tab Dashboard dengan 4 grafik (Kecoa, Lalat, Jentik, Tikus) dan memperbaiki bug navigasi tab.
- **Langkah Pengujian:**
  1. Buka halaman SIVERA, pastikan tab Dashboard tampil pertama.
  2. Pindah ke tab Jentik, lalu kembali ke tab Dashboard.
- **Hasil yang Diharapkan:** Grafik (Chart.js v2.9.4) tampil proporsional tanpa error. Perpindahan tab berjalan lancar tanpa memunculkan konten tab sebelumnya (konflik ID `dashboard` telah diselesaikan menjadi `sivera-dashboard`).
- **Status:** **[ PASS ]**

---

### 3. Kesimpulan
Semua penyesuaian fungsionalitas form (Jentik, Kecoa, Tikus, Lalat) dan penambahan Dashboard telah berhasil diimplementasikan tanpa perlu mengubah skema database (`ALTER TABLE`). Bug navigasi antar tab juga telah diperbaiki secara tuntas. Modul SIVERA dinyatakan **SIAP** dan fungsionalitas berjalan sesuai spesifikasi.