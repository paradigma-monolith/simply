<?php

namespace App\Models;

use CodeIgniter\Model;

class AgendaUserModel extends Model
{
  protected $table = 'agenda_user';
  protected $primaryKey = 'id_agenda_user';
  protected $useTimestamps = true;
  protected $allowedFields = ['topik', 'deskripsi', 'peruntukan', 'waktu_pelaksana_awal', 'waktu_pelaksana_akhir', 'tgl_notif_awal', 'tgl_notif_akhir', 'warna_agenda', 'created_by', 'updated_by'];
}
