<?php

namespace App\Models;

use CodeIgniter\Model;

class Crud extends Model
{
  function joinFirst($select = '', $with = '', $value = '', $where = '', $orderBy = '', $order = '', $group = '', $limit = '')
  {
    if ($select != '') $this->select($select);
    if ($with != '' && $value != '') $this->join($with, $value);
    if ($where != '') $this->where($where);
    return $this;
  }
  function joinAll($select = '', $with = '', $value = '', $where = '', $orderBy = '', $order = '', $group = '', $limit = '')
  {
    if ($select != '') $this->select($select);
    if ($with != '' && $value != '') $this->join($with, $value);
    if ($where != '') $this->where($where);
    if ($orderBy != '' && $order != '') $this->orderBy($orderBy, $order);

    return $this;
  }

  public function joinData()
  {
    $table = $this->table;

    $builder = $this->db->table($table);
    // Assuming you have already initialized $builder and $table
    $builder->select('data_pegawai.*, 
                    user.*, 
                    unit_kerja.*, 
                    golongan.*, 
                    jabatan.*,
                    status_pegawai.*,
                    role_access.*, 
                    tim_kerja.nama_tim_kerja AS tim_kerja,
                    created.nama AS created, 
                    updated.nama AS updated, 
                    ' . $table . '.created_at AS created_at, 
                    ' . $table . '.updated_at AS updated_at');

    // Assuming $table contains the main table name
    $builder->join('user', 'user.id_user = ' . $table . '.id_user');
    $builder->join('unit_kerja', 'unit_kerja.id_unit_kerja = ' . $table . '.id_unit_kerja');
    $builder->join('golongan', 'golongan.id_golongan = ' . $table . '.id_golongan');
    $builder->join('jabatan', 'jabatan.id_jabatan = ' . $table . '.id_jabatan');
    $builder->join('status_pegawai', 'status_pegawai.id_status_pegawai = ' . $table . '.id_status_pegawai');
    $builder->join('tim_kerja', 'tim_kerja.id_tim_kerja = ' . $table . '.id_tim_kerja', 'left');
    $builder->join('role_access', 'role_access.id_role_access = user.id_role_access');
    $builder->join('user AS created', 'created.id_user = ' . $table . '.created_by');
    $builder->join('user AS updated', 'updated.id_user = ' . $table . '.updated_by');


    $query = $builder->get();
    return $query->getResultArray();
    // return $query->getRowArray(); // Menggunakan getRowArray() untuk mengambil satu data saja.
  }
}
