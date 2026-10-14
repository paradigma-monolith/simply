# Laporan Uji Coba (User Acceptance Testing)
## Aplikasi SIMPLY - Modul EKIP (Evaluasi Kinerja, Perilaku, dan Penilaian 360)

**Tanggal Uji Coba:** 02 Juli 2026  
**Modul:** Penilaian 360 & Pengaturan E-KIP  
**Status Keseluruhan:** **LULUS (PASS)**  

---

### 1. Tujuan Uji Coba
Uji coba ini bertujuan untuk memastikan bahwa penambahan fitur baru dan penyesuaian logika perhitungan pada Modul EKIP (Evaluasi Kinerja, Perilaku, dan Penilaian 360) berjalan sesuai dengan kebutuhan dan spesifikasi yang diharapkan.

---

### 2. Skenario & Hasil Uji Coba

#### A. Penyesuaian Perhitungan Nilai (Dari Total ke Rata-rata)
* **Deskripsi:** Mengubah logika kalkulasi skor Penilaian 360 dari akumulasi (sum) menjadi nilai rata-rata (average) dengan dua angka di belakang koma.
* **Langkah Pengujian:**
  1. Mengisi nilai pada form Penilaian 360.
  2. Mengecek kolom hasil di halaman form.
  3. Mengecek nilai pada halaman "Evaluasi Hasil Penilaian 360" (untuk Penilai dan Super Admin).
* **Ekspektasi:** Nilai yang tampil adalah hasil bagi dari total skor dengan jumlah indikator yang dinilai (desimal 2 angka).
* **Hasil:** ✔️ **PASS**. Nilai berhasil disimpan dan ditampilkan dalam bentuk desimal (misal: 4.25).

#### B. Pengaturan Akses Tab Kinerja & Perilaku
* **Deskripsi:** Menambahkan kontrol (toggle) untuk mengaktifkan/menonaktifkan Tab Kinerja dan Perilaku melalui halaman Pengaturan E-KIP.
* **Langkah Pengujian:**
  1. Masuk ke halaman Pengaturan E-KIP, matikan toggle untuk Tab Kinerja dan Perilaku.
  2. Akses halaman `/ekip/kinerja` atau `/ekip/perilaku`.
* **Ekspektasi:** Pengguna otomatis dialihkan (redirect) ke `/ekip/penilaian_360`, dan tab Kinerja & Perilaku di antarmuka menjadi abu-abu (disabled) dan tidak dapat diklik.
* **Hasil:** ✔️ **PASS**. Redirect dan pembatasan klik (disabled class & aria-disabled) berfungsi dengan baik.

#### C. Fitur Justifikasi Penilaian 360
* **Deskripsi:** Menyediakan fitur bagi penilai untuk memberikan catatan/justifikasi dan mengunggah dokumen bukti pada form Penilaian 360.
* **Langkah Pengujian:**
  1. Klik tombol Justifikasi pada rekan tim kerja.
  2. Isi deskripsi dan unggah file (PDF/JPG/PNG), lalu simpan.
  3. Periksa perubahan warna tombol justifikasi menjadi solid (biru).
  4. Periksa daftar justifikasi di halaman Evaluasi Hasil 360.
* **Ekspektasi:** Data tersimpan ke database, file terunggah ke direktori server, dan indikator warna tombol berubah secara *real-time*. Data juga dapat dilihat oleh Admin melalui modal pop-up di halaman Hasil.
* **Hasil:** ✔️ **PASS**. Fitur berjalan normal dan tombol responsif menyesuaikan keberadaan data.

#### D. Fitur Kosongkan Justifikasi
* **Deskripsi:** Memungkinkan penilai untuk menghapus justifikasi dan file yang sudah diunggah jika terjadi kesalahan.
* **Langkah Pengujian:**
  1. Buka kembali modal Justifikasi yang sudah terisi.
  2. Klik tombol merah "Kosongkan" dan konfirmasi pada pop-up SweetAlert.
* **Ekspektasi:** File bukti di server terhapus, data di database menjadi `NULL`, dan warna tombol kembali transparan (outline).
* **Hasil:** ✔️ **PASS**. Penghapusan berjalan dengan baik.

#### E. Otomatisasi Input Nilai (Isi Semua & Kosongkan Semua)
* **Deskripsi:** Tombol *bulk action* untuk mempercepat proses penilaian. "Isi Semua Nilai (4)" untuk mengisi angka 4 ke seluruh indikator dan rekan tim. "Kosongkan Semua" untuk membersihkan seluruh nilai.
* **Langkah Pengujian:**
  1. Klik tombol "Isi Semua Nilai (4)", konfirmasi.
  2. Klik tombol "Kosongkan Semua", konfirmasi.
* **Ekspektasi:** Semua input pada tabel terisi angka 4 (atau kosong), kolom rata-rata disesuaikan secara real-time, dan data tersimpan/terhapus di server menggunakan AJAX bulk request. Tabel berkedip hijau/merah sebagai indikator sukses.
* **Hasil:** ✔️ **PASS**. Bulk action bekerja sempurna tanpa perlu memuat ulang halaman.

---

### 3. Kesimpulan
Seluruh fitur baru yang mencakup kalkulasi nilai rata-rata, restriksi tab penilaian, manajemen justifikasi (input, lihat, hapus), serta fitur otomatisasi input secara masal telah diuji coba. Tidak ditemukan *bug* atau *error* dalam fungsionalitas utama. Sistem siap digunakan pada tahap *Production*.

---
*Laporan dibuat otomatis oleh sistem.*