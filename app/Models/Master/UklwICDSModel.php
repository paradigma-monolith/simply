<?php

namespace App\Models;

use CodeIgniter\Model;

class UklwICDSModel extends Model
{
  protected $table = 'uklw_icds';
  protected $primaryKey = 'code';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'name_en',
    'name_id'
  ];

  public function searchData($searchTerm)
  {
    $builder = $this->db->table($this->table);
    $builder->select('code, name_en, name_id');
    $builder->like('code', $searchTerm);
    $builder->orLike('name_en', $searchTerm);
    $builder->orLike('name_id', $searchTerm);

    $result = $builder->get()->getResultArray();

    // $formattedData = [];

    // foreach ($result as $data) {
    //   // Format sesuai kebutuhan
    //   $formattedData[] = [
    //     'id' => $data['code'],
    //     'text' => $data['code'] . ' | ' . $data['name_id'] . ' / ' . $data['name_en'],
    //   ];
    // }

    return $result;
  }
}
