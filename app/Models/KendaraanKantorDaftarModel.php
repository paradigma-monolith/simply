<?php

namespace App\Models;

use CodeIgniter\Model;

class KendaraanKantorDaftarModel extends Model
{
  protected $table = 'kendaraan_kantor_daftar';
  protected $primaryKey = 'id_kendaraan_kantor_daftar';
  protected $useTimestamps = true;
  protected $allowedFields = [
    "nama_kendaraan",
    "plat_nomor",
    "gambar",
    "bisa_dipinjam",
    "created_by",
    "updated_by",
  ];

  private ?bool $hasBorrowableColumn = null;

  /**
   * Memeriksa ketersediaan kolom `bisa_dipinjam` agar query tetap aman sebelum migrasi dijalankan.
   */
  public function supportsBorrowableFilter(): bool
  {
    if ($this->hasBorrowableColumn === null) {
      $this->hasBorrowableColumn = $this->db->fieldExists('bisa_dipinjam', $this->table);
    }

    return $this->hasBorrowableColumn;
  }

  /**
   * Mengambil daftar unit kendaraan yang diizinkan untuk modul Peminjaman Kendaraan.
   *
   * @return array<int, array<string, mixed>>
   */
  public function getBorrowable(): array
  {
    $builder = $this->builder();
    if ($this->supportsBorrowableFilter()) {
      $builder->where('bisa_dipinjam', 1);
    }

    return $builder->orderBy('nama_kendaraan', 'ASC')->get()->getResultArray();
  }

  /**
   * Memeriksa apakah ID kendaraan tersedia dan diizinkan untuk dipinjam.
   */
  public function isBorrowable(int $id): bool
  {
    if ($id <= 0) {
      return false;
    }

    $row = $this->find($id);
    if (!$row) {
      return false;
    }

    if (!$this->supportsBorrowableFilter()) {
      return true;
    }

    return (int) ($row['bisa_dipinjam'] ?? 0) === 1;
  }

  /**
   * Menyertakan kolom `bisa_dipinjam` ke payload simpan bila kolom sudah tersedia di database.
   *
   * @param array<string, mixed> $data
   * @param mixed $rawValue
   * @return array<string, mixed>
   */
  public function withBorrowableFlag(array $data, $rawValue, int $default): array
  {
    if ($this->supportsBorrowableFilter()) {
      $data['bisa_dipinjam'] = $rawValue === null ? $default : ((int) $rawValue === 1 ? 1 : 0);
    }

    return $data;
  }
}
