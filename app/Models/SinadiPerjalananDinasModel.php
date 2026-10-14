<?php

namespace App\Models;

use CodeIgniter\Model;

class SinadiPerjalananDinasModel extends Model
{
  protected $table = 'sinadi_perjalanan_dinas';
  protected $primaryKey = 'id_perjalanan';
  protected $useTimestamps = true;
  protected $allowedFields = ['kategori', 'nama_kegiatan', 'tgl_pelaksanaan', 'tgl_pelaksanaan_akhir', 'tempat_perjalanan', 'mata_anggaran', 'sumber_anggaran', 'hasil_kegiatan', 'total_transport', 'total_harian', 'total_penginapan', 'total_perjalanan', 'pic', 'pic_nip', 'file', 'berkas', 'bukti_pembayaran', 'status_surat', 'catatan_kegiatan', 'verifikator_1', 'verifikator_2', 'verifikator_3', 'verifikator_4', 'verifikator_5', 'created_by', 'updated_by', 'jenis_perjalanan_dinas', 'id_monitoring_sanitasi'];
}
