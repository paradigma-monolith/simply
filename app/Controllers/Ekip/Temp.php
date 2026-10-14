<?php
namespace App\Controllers\Ekip;
use App\Controllers\BaseController;
class Temp extends BaseController {
    public function index() {
        $db = \Config\Database::connect();
        $db->query('CREATE TABLE IF NOT EXISTS ekip_rekomendasi_kebijakan (id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, dinilai_id INT(11), penilai_id INT(11), tahun INT(4), bulan INT(2), minggu_ke INT(1), rencana_hasil_kerja TEXT, iki TEXT, target VARCHAR(255), created_at DATETIME, updated_at DATETIME)');
        echo "Table created";
    }
}