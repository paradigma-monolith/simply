<?php



namespace App\Models\Master;



use App\Models\Crud;



class GolonganModel extends Crud

{

  protected $table = 'golongan';

  protected $primaryKey = 'id_golongan';

  protected $useTimestamps = true;

  protected $allowedFields = ['golongan'];
}
