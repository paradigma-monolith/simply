<?php
namespace App\Controllers;
use CodeIgniter\Controller;
class DbTool extends Controller
{
    public function index()
    {
        $db = \Config\Database::connect();
        $query = $db->query("DESCRIBE absen");
        foreach ($query->getResult() as $row) {
            echo $row->Field . " - " . $row->Type . "\n";
        }
    }
}
