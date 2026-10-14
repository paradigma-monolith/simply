<?php



namespace App\Models\Master;



use App\Models\Crud;



class JabatanModel extends Crud

{

  protected $table = 'jabatan';

  protected $primaryKey = 'id_jabatan';

  protected $useTimestamps = true;

  protected $allowedFields = ['jabatan'];
}
