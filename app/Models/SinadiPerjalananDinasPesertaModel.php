<?php

namespace App\Models;

use CodeIgniter\Model;

class SinadiPerjalananDinasPesertaModel extends Model
{
  protected $table = 'sinadi_perjalanan_dinas_peserta';
  protected $primaryKey = 'id_peserta';
  protected $useTimestamps = true;
  protected $allowedFields = ['id_perjalanan', 'transportasi', 'id_sinadi_setting', 'tgl_pergi', 'tgl_pulang', 'nip_pegawai', 'nama_pegawai', 'jabatan_pegawai', 'golongan', 'no_surat_tugas', 'tgl_surat_tugas', 'no_sppd', 'tgl_sppd', 'kota', 'kota_asal', 'transport_hari', 'transport_nominal', 'transport_jumlah', 'harian_hari', 'harian_nominal', 'harian_jumlah', 'penginapan_hari', 'penginapan_nominal', 'penginapan_jumlah', 'jumlah_transfer', 'file', 'ttd_peserta'];
}
