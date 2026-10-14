<?php
namespace App\Controllers\Sk;

use App\Controllers\BaseController;

class Data extends BaseController
{
    public function index($folder = null, $kategori = null)
    {
        // default folder tahun
        if ($folder == null) {
            $folder = date('Y');
        }
    
        $data['title'] = "Surat Keputusan tahun " . $folder;
        $data['folder'] = $folder;
        $data['kategori'] = $kategori;
        if ($kategori) {
            // nota-dinas → nota_dinas
            $menuKategori = str_replace('-', '_', $kategori);
            $data['activeMenu'] = "sk_{$folder}_{$menuKategori}";
        } else {
            $data['activeMenu'] = "sk_" . $folder;
        }
    
        // 🔹 tentukan directory
        if ($kategori) {
            // contoh: writable/sk/2026/sk
            $directory = WRITEPATH . "sk/$folder/$kategori";
        } else {
            // fallback lama (writable/sk/2026)
            $directory = WRITEPATH . "sk/$folder";
        }
    
        if (is_dir($directory)) {
    
            $files = scandir($directory);
            $pdfFiles = [];
    
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === "pdf") {
    
                    // URL tetap ke method view()
                    $filePath = site_url("sk/data/view/$folder/" . ($kategori ? "$kategori/" : "") . $file);
    
                    $pdfFiles[] = [
                        'name' => $file,
                        'path' => $filePath
                    ];
                }
            }
    
            $data['files'] = $pdfFiles;
            return view('sk/data_view', $data);
    
        } else {
            session()->setFlashdata(
                'info',
                'Folder file SK ' . $folder . ($kategori ? " ($kategori)" : "") . ' tidak ditemukan!'
            );
            return redirect()->to('/surat_sk');
        }
    }

    
    public function view($folder, $kategori = null, $file = null)
    {
        // jika kategori ada, file ada di subfolder
        if ($file === null) {
            // artinya URL lama: view/{folder}/{file}
            $file = $kategori;
            $filePath = WRITEPATH . "sk/$folder/$file";
        } else {
            // URL baru: view/{folder}/{kategori}/{file}
            $filePath = WRITEPATH . "sk/$folder/$kategori/$file";
        }
    
        if (file_exists($filePath)) {
    
            return $this->response
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader('Content-Disposition', 'inline; filename="' . basename($filePath) . '"')
                ->setHeader('Content-Length', filesize($filePath))
                ->setBody(file_get_contents($filePath));
    
        } else {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'File tidak ditemukan.'
            ]);
        }
    }


}