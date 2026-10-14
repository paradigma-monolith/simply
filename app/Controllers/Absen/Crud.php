<?php

namespace App\Controllers\Absen;

use App\Controllers\BaseController;
use Config\Services;

class Crud extends BaseController
{
  public function addJamAbsen() 
  {
    if ($this->request->isAJAX()) {
        $msg = [];
        
        $by = $this->userModel->where('username', session()->get('username'))->first();
        
        $data = [
            'hari' => $this->request->getVar('hari'),
            'aturan_jam_masuk' => $this->request->getVar('aturan_jam_masuk') == "" ? null : $this->request->getVar('aturan_jam_masuk'),
            'aturan_jam_pulang' => $this->request->getVar('aturan_jam_pulang') == "" ? null : $this->request->getVar('aturan_jam_pulang'),
            'toleransi_keterlambatan' => $this->request->getVar('toleransi_keterlambatan') == "" ? null : $this->request->getVar('toleransi_keterlambatan'),
            'created_by' => '2',
            'updated_by' => $by['id_user']
        ];
        if($this->absenJamModel->save($data)) {
            $insertedId = $this->absenJamModel->getInsertID();
            $savedData = $this->absenJamModel->find($insertedId);
            $msg = [
                'getData' => $savedData,
                'data' => 'sukses'
            ];
        }
        
        echo json_encode($msg);
    }    
  }
  
  private function getAgendaForUser($userId)
  {
    $getData = $this->agendaUserModel
      ->where('tgl_notif_awal <=', date('Y-m-d'))
      ->where('tgl_notif_akhir >=', date('Y-m-d'))
      ->findAll();
    
    $setData = array();
    
    foreach ($getData as $data) {
      $peruntukan = unserialize($data['peruntukan']);
      if (in_array($userId, $peruntukan)) {
        $setData[] = $data;
      }
    }
    
    return $setData;
  }
  
  private function formatAgendaForAlert($agendaData)
  {
    if (empty($agendaData)) {
      return '';
    }
    
    $totalAgenda = count($agendaData);
    $agendaHtml = '<div class="agenda-container">';
    $agendaHtml .= '<div class="agenda-header">';
    $agendaHtml .= '<span class="agenda-header-icon">📅</span>';
    $agendaHtml .= '<h6 class="agenda-header-title">Agenda Hari Ini (' . $totalAgenda . ')</h6>';
    $agendaHtml .= '</div>';
    
    foreach ($agendaData as $index => $agenda) {
      $delay = ($index * 0.1) . 's';
      $agendaHtml .= '<div class="agenda-item" style="animation-delay: ' . $delay . ';">';
      $agendaHtml .= '<div class="agenda-title">' . htmlspecialchars($agenda['topik']) . '</div>';
      
      // Format waktu yang lebih compact dan informatif
      $waktuAwal = date('H:i', strtotime($agenda['waktu_pelaksana_awal']));
      $waktuAkhir = date('H:i', strtotime($agenda['waktu_pelaksana_akhir']));
      $tanggalAwal = date('d/m', strtotime($agenda['waktu_pelaksana_awal']));
      $tanggalAkhir = date('d/m', strtotime($agenda['waktu_pelaksana_akhir']));
      
      $agendaHtml .= '<div class="agenda-time">';
      $agendaHtml .= '<span class="agenda-time-icon">🕐</span>';
      
      // Cek apakah agenda berlangsung dalam hari yang sama
      if ($tanggalAwal === $tanggalAkhir) {
        $agendaHtml .= $tanggalAwal . ' • ' . $waktuAwal . ' - ' . $waktuAkhir;
      } else {
        $agendaHtml .= $tanggalAwal . ' ' . $waktuAwal . ' - ' . $tanggalAkhir . ' ' . $waktuAkhir;
      }
      
      $agendaHtml .= '</div>';
      
      // Konversi URL dalam deskripsi menjadi tombol
      $deskripsi = trim($agenda['deskripsi']);
      if (!empty($deskripsi)) {
        $urlRegex = '/((?:https?:\/\/|www\.)(?:[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b(?:[-a-zA-Z0-9()@:%_\+.~#?&\/\/=]*)))|([a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}(?:\/[-a-zA-Z0-9()@:%_\+.~#?&\/\/=]*)?)/';
        $deskripsi = preg_replace_callback($urlRegex, function($matches) {
          $url = $matches[0];
          $href = $url;
          if (!preg_match('/^https?:\/\//', $href)) {
            $href = 'http://' . $href;
          }
          return '<button type="button" class="link-button" data-url="' . htmlspecialchars($href) . '">🔗 Link</button>';
        }, $deskripsi);
        
        $agendaHtml .= '<div class="agenda-description">' . $deskripsi . '</div>';
      }
      $agendaHtml .= '</div>';
    }
    
    $agendaHtml .= '</div>';
    
    return $agendaHtml;
  }
  
  public function clockIn()
  {
    if ($this->request->isAJAX()) {
      $day = date('N');
      $getJam = $this->absenJamModel->where('hari', $day)->orderBy('created_at', 'DESC')->limit(1)->first();
        
      $startTime = microtime(true); // Waktu mulai
      $cache = Services::cache();
      $msg = [];

      $nip = $this->request->getVar("nip");
      $imageData = $this->request->getVar("image");
      $alamat = $this->request->getVar("alamat");
      $latitude_masuk = $this->request->getVar("latitude");
      $longitude_masuk = $this->request->getVar("longitude");
      $dayDate = date("Y-m-d");
      $jam_masuk = date("Y-m-d H:i:s");
      $jam_pulang = null;
      
      if (
        $alamat == "Informasi Lokasi Anda Akan Muncul Disini!" ||
        $alamat == "Gagal mendapatkan alamat." ||
        $alamat == "User denied the request for Geolocation." ||
        $alamat == "Location information is unavailable." ||
        $alamat == "The request to get user location timed out." ||
        $alamat == "An unknown error occurred." ||
        $alamat == "Geolocation is not supported by this browser." ||
        $alamat == "Geolocation is not supported by this browser."
      ) {
        $msg = [
          'data' => 'error',
          'pesan' => 'pastikan lokasi di perangkat anda aktif!',
        ];
      } else if (in_array($imageData, $this->getBlackImage())) {
        $msg = [
          'data' => 'error',
          'pesan' => 'akses terhadap kamera mati, tolong periksa kembali izin perangkat anda atau coba lagi!',
        ];
      } else {
        $cekData = $this->dataPegawaiModel->where('nip', $nip)->first();

        if ($cekData) {
          $id_data_pegawai = $cekData['id_data_pegawai'];
          
          // Ambil agenda untuk user
          $agendaData = $this->getAgendaForUser($cekData['id_user']);
          $agendaHtml = $this->formatAgendaForAlert($agendaData);

          // Get Motivational Quote
          $motivasiHtml = $this->getRandomMotivasi('Masuk');
          
          // Gabungkan: Motivasi dulu, baru Agenda (dengan pemisah garis tipis jika keduanya ada)
          $separator = (!empty($motivasiHtml) && !empty($agendaHtml)) ? '<hr style="margin: 15px 0; border-top: 1px solid rgba(0,0,0,0.1);">' : '';
          $combinedHtml = $motivasiHtml . $separator . $agendaHtml;
          
          $logoHtml = '<div style="text-align: center; margin-top: -10px; margin-bottom: 15px;"><img src="' . base_url('dist/motivasi/logo.png') . '" style="height: 45px; object-fit: contain;" alt="Logo"></div>';

          $dayDate = date("Y-m-d");
          $cekAbsen = $this->absenModel->select('id_absen, id_data_pegawai, alamat_masuk, jam_masuk, rekam_absen')->where(['id_data_pegawai' => $id_data_pegawai, 'DATE(jam_masuk)' => $dayDate])->first();
          if ($cekAbsen) {
            $updateData = [
              'id_absen' => $cekAbsen['id_absen'],
              'image_masuk_2' => $imageData
            ];
            
            // =========================================================================================================================================================================
            if ($imageData) {
                // Pisahkan header data base64 dan data sebenarnya
                list($type, $base64String) = explode(';', $imageData);
                list(, $base64Data) = explode(',', $base64String);
            
                // Dekode data base64 menjadi string biner
                $decodedData = base64_decode($base64Data);
            
                // Tentukan jenis file berdasarkan header
                if (strpos($type, 'jpeg') !== false) {
                    $extension = 'jpg';
                } elseif (strpos($type, 'png') !== false) {
                    $extension = 'png';
                } elseif (strpos($type, 'gif') !== false) {
                    $extension = 'gif';
                } elseif (strpos($type, 'bmp') !== false) {
                    $extension = 'bmp';
                } elseif (strpos($type, 'webp') !== false) {
                    $extension = 'webp';
                } elseif (strpos($type, 'tiff') !== false) {
                    $extension = 'tiff';
                } else {
                    $extension = 'jpg'; // Default extension jika tidak terdeteksi
                }
            
                // Nama file baru
                $newName = uniqid() . '.' . $extension;
            
                // Path penyimpanan file
                $filePath = FCPATH . '/dist/foto_absen/' . $newName;
            
                // Simpan file ke direktori
                if (file_put_contents($filePath, $decodedData)) {
                    $newData = [
                        'tombol' => "Rekam Masuk",
                        'foto' => $newName,
                        'longitude' => $this->request->getVar("longitude"),
                        'latitude' => $this->request->getVar("latitude"),
                        'alamat_terdekat' => $this->request->getVar("alamat"),
                        'jam_absen' => $jam_masuk
                    ];
                    
                    if($cekAbsen['rekam_absen'] != "") {
                        // Asumsikan $cekAbsen['rekam_absen'] berisi data JSON yang sudah ada
                        $existingData = json_decode($cekAbsen['rekam_absen'], true);
                        
                        // Gabungkan data baru dengan data yang sudah ada
                        // Jika existingData adalah array, kita tambahkan newData ke array tersebut
                        if (is_array($existingData)) {
                            $existingData[] = $newData;
                        } else {
                            // Jika data yang ada tidak berbentuk array (misalnya null atau tidak valid),
                            // buat array baru dengan data baru sebagai elemen pertama
                            $existingData = [$newData];
                        }
                    } else {
                        $existingData = [$newData];
                    }
                    
                    // Encode kembali array gabungan menjadi string JSON
                    $combinedJsonData = json_encode($existingData);
                    
                    $updateData += [
                        'rekam_absen' => $combinedJsonData
                    ];
                }
            }
            // =========================================================================================================================================================================
            
            $updateData += [
                'status_masuk' => $this->request->getVar('status')
            ];
            $this->absenModel->save($updateData);
            
            $msg = [
              'data' => 'sukses',
              'pesan' => $logoHtml . '<strong>' . name_capitalize($cekData['nama']) . '</strong> sudah menandai masuk dengan waktu submit <u>' . $cekAbsen['jam_masuk'] . '</u>' . $combinedHtml,
            ];
          } else {
            if ($this->request->getVar("posisi_alamat") !== null) {
                $posisi_alamat_masuk = $this->request->getVar("posisi_alamat");
            }

            // Baris untuk input data
            $data = [
              'id_data_pegawai' => $id_data_pegawai,
              'alamat_masuk' => $alamat,
              'latitude_masuk' => $latitude_masuk,
              'longitude_masuk' => $longitude_masuk,
              'image_masuk_1' => $imageData,
              'jam_masuk' => $jam_masuk,
              'jam_pulang' => $jam_pulang,
              'aturan_jam_masuk' => $getJam['aturan_jam_masuk'],
              'aturan_jam_pulang' => $getJam['aturan_jam_pulang'],
              'toleransi_keterlambatan' => $getJam['toleransi_keterlambatan'],
            ];
            
            $data += [
              'posisi_alamat_masuk' => $posisi_alamat_masuk
            ];
            
            
            // =========================================================================================================================================================================
            if ($imageData) {
                // Pisahkan header data base64 dan data sebenarnya
                list($type, $base64String) = explode(';', $imageData);
                list(, $base64Data) = explode(',', $base64String);
            
                // Dekode data base64 menjadi string biner
                $decodedData = base64_decode($base64Data);
            
                // Tentukan jenis file berdasarkan header
                if (strpos($type, 'jpeg') !== false) {
                    $extension = 'jpg';
                } elseif (strpos($type, 'png') !== false) {
                    $extension = 'png';
                } elseif (strpos($type, 'gif') !== false) {
                    $extension = 'gif';
                } elseif (strpos($type, 'bmp') !== false) {
                    $extension = 'bmp';
                } elseif (strpos($type, 'webp') !== false) {
                    $extension = 'webp';
                } elseif (strpos($type, 'tiff') !== false) {
                    $extension = 'tiff';
                } else {
                    $extension = 'jpg'; // Default extension jika tidak terdeteksi
                }
            
                // Nama file baru
                $newName = uniqid() . '.' . $extension;
            
                // Path penyimpanan file
                $filePath = FCPATH . '/dist/foto_absen/' . $newName;
            
                // Simpan file ke direktori
                if (file_put_contents($filePath, $decodedData)) {
                    $newData =[
                        [
                            'tombol' => "Rekam Masuk",
                            'foto' => $newName,
                            'longitude' => $this->request->getVar("longitude"),
                            'latitude' => $this->request->getVar("latitude"),
                            'alamat_terdekat' => $this->request->getVar("alamat"),
                            'jam_absen' => $jam_masuk
                        ]
                    ];
                    
                    // Encode kembali array gabungan menjadi string JSON
                    $combinedJsonData = json_encode($newData);
                    
                    $data += [
                        'rekam_absen' => $combinedJsonData
                    ];
                }
            }
            // =========================================================================================================================================================================
            
            
            $data += [
                'status_masuk' => $this->request->getVar('status')
            ];
  
            $this->absenModel->save($data);
            
            $msg = [
              'data' => 'sukses',
              'pesan' => $logoHtml . '<strong>' . name_capitalize($cekData['nama']) . '</strong> terdeteksi waktu submit <u>' . $jam_masuk . '</u>' . $combinedHtml,
            ];
          }
        }
      }
      // Buat token persisten
      $persistentToken = bin2hex(random_bytes(16));
      $cacheKey = 'user_nip_token_' . $persistentToken;
      $cache->save($cacheKey, $nip, 2592000); // TTL 30 hari

      // Simpan token di cookie
      $response = $this->response;
      $response->setCookie('persistent_token', $persistentToken, 2592000);
    }

        $endTime = microtime(true); // Waktu selesai
        $processingTime = $endTime - $startTime;
        $msg['waktu'] = ('Processing time: ' . $processingTime . ' seconds');
    // datetime_indo
    echo json_encode($msg);
  }
  public function clockOut()
  {
    if ($this->request->isAJAX()) {
      $msg = [];

      $nip = $this->request->getVar("nip");
      $imageData = $this->request->getVar("image");
      $alamat = $this->request->getVar("alamat");
      $latitude_pulang = $this->request->getVar("latitude");
      $longitude_pulang = $this->request->getVar("longitude");
      $dayDate = date("Y-m-d");
      $jam_pulang = date("Y-m-d H:i:s");

      if (
        $alamat == "Informasi Lokasi Anda Akan Muncul Disini!" ||
        $alamat == "Gagal mendapatkan alamat." ||
        $alamat == "User denied the request for Geolocation." ||
        $alamat == "Location information is unavailable." ||
        $alamat == "The request to get user location timed out." ||
        $alamat == "An unknown error occurred." ||
        $alamat == "Geolocation is not supported by this browser." ||
        $alamat == "Geolocation is not supported by this browser."
      ) {
        $msg = [
          'data' => 'error',
          'pesan' => 'pastikan lokasi di perangkat anda aktif!',
        ];
      }else if (in_array($imageData, $this->getBlackImage())) {
        $msg = [
          'data' => 'error',
          'pesan' => 'akses terhadap kamera mati, tolong periksa kembali izin perangkat anda atau coba lagi!',
        ];
      } else {
        $cekData = $this->dataPegawaiModel->where('nip', $nip)->first();

        if ($cekData) {
          $id_data_pegawai = $cekData['id_data_pegawai'];
          
          // Ambil agenda untuk user
          $agendaData = $this->getAgendaForUser($cekData['id_user']);
          $agendaHtml = $this->formatAgendaForAlert($agendaData);

          // Get Motivational Quote
          $motivasiHtml = $this->getRandomMotivasi('Pulang');
          
          // Gabungkan: Motivasi dulu, baru Agenda (dengan pemisah garis tipis jika keduanya ada)
          $separator = (!empty($motivasiHtml) && !empty($agendaHtml)) ? '<hr style="margin: 15px 0; border-top: 1px solid rgba(0,0,0,0.1);">' : '';
          $combinedHtml = $motivasiHtml . $separator . $agendaHtml;
          
          $logoHtml = '<div style="text-align: center; margin-top: -10px; margin-bottom: 15px;"><img src="' . base_url('dist/motivasi/logo.png') . '" style="height: 45px; object-fit: contain;" alt="Logo"></div>';

          $dayDate = date("Y-m-d");
          $cekAbsen = $this->absenModel->select('id_absen, id_data_pegawai, jam_masuk, jam_pulang, rekam_absen')->where(['id_data_pegawai' => $id_data_pegawai, 'DATE(jam_masuk)' => $dayDate])->first();
          if ($cekAbsen) {
            if ($this->request->getVar("posisi_alamat") !== null) {
                $posisi_alamat_pulang = $this->request->getVar("posisi_alamat");
            }
            $updateData = [
              'id_absen' => $cekAbsen['id_absen'],
              'alamat_pulang' => $alamat,
              'latitude_pulang' => $latitude_pulang,
              'longitude_pulang' => $longitude_pulang,
              'jam_pulang' => $jam_pulang,
            ];
            $updateData += [
              'posisi_alamat_pulang' => $posisi_alamat_pulang
            ];

            if ($cekAbsen['jam_pulang'] == null) {
              $updateData += [
                'image_pulang_1' => $imageData,
              ];
              
              // Hitung jam kerja
              $jamKerja = hitungJamKerja($cekAbsen['jam_masuk'], $jam_pulang);
              
              $msg = [
                'data' => 'sukses',
                'pesan' => $logoHtml . '<strong>' . name_capitalize($cekData['nama']) . '</strong> sudah menandai pulang dengan waktu submit <u>' . $jam_pulang . '</u><br><strong>Total jam kerja: ' . $jamKerja . '</strong>' . $combinedHtml,
              ];
            } else {
              $updateData += [
                'image_pulang_2' => $imageData,
              ];
              
              // Hitung jam kerja
              $jamKerja = hitungJamKerja($cekAbsen['jam_masuk'], $jam_pulang);
              
              $msg = [
                'data' => 'sukses',
                'pesan' => $logoHtml . '<strong>' . name_capitalize($cekData['nama']) . '</strong> memperbaharui data pulang dengan dengan waktu submit <u>' . $jam_pulang . '</u><br><strong>Total jam kerja: ' . $jamKerja . '</strong>' . $combinedHtml,
              ];
            }
            
            // =========================================================================================================================================================================
            if ($imageData) {
                // Pisahkan header data base64 dan data sebenarnya
                list($type, $base64String) = explode(';', $imageData);
                list(, $base64Data) = explode(',', $base64String);
            
                // Dekode data base64 menjadi string biner
                $decodedData = base64_decode($base64Data);
            
                // Tentukan jenis file berdasarkan header
                if (strpos($type, 'jpeg') !== false) {
                    $extension = 'jpg';
                } elseif (strpos($type, 'png') !== false) {
                    $extension = 'png';
                } elseif (strpos($type, 'gif') !== false) {
                    $extension = 'gif';
                } elseif (strpos($type, 'bmp') !== false) {
                    $extension = 'bmp';
                } elseif (strpos($type, 'webp') !== false) {
                    $extension = 'webp';
                } elseif (strpos($type, 'tiff') !== false) {
                    $extension = 'tiff';
                } else {
                    $extension = 'jpg'; // Default extension jika tidak terdeteksi
                }
            
                // Nama file baru
                $newName = uniqid() . '.' . $extension;
            
                // Path penyimpanan file
                $filePath = FCPATH . '/dist/foto_absen/' . $newName;
            
                // Simpan file ke direktori
                if (file_put_contents($filePath, $decodedData)) {
                    $newData = [
                        'tombol' => "Rekam Pulang",
                        'foto' => $newName,
                        'longitude' => $this->request->getVar("longitude"),
                        'latitude' => $this->request->getVar("latitude"),
                        'alamat_terdekat' => $this->request->getVar("alamat"),
                        'jam_absen' => $jam_pulang
                    ];
                    
                    if($cekAbsen['rekam_absen'] != "") {
                        // Asumsikan $cekAbsen['rekam_absen'] berisi data JSON yang sudah ada
                        $existingData = json_decode($cekAbsen['rekam_absen'], true);
                        
                        // Gabungkan data baru dengan data yang sudah ada
                        // Jika existingData adalah array, kita tambahkan newData ke array tersebut
                        if (is_array($existingData)) {
                            $existingData[] = $newData;
                        } else {
                            // Jika data yang ada tidak berbentuk array (misalnya null atau tidak valid),
                            // buat array baru dengan data baru sebagai elemen pertama
                            $existingData = [$newData];
                        }
                    }  else {
                        $existingData = [$newData];
                    }
                    
                    // Encode kembali array gabungan menjadi string JSON
                    $combinedJsonData = json_encode($existingData);
                    
                    $updateData += [
                        'rekam_absen' => $combinedJsonData
                    ];
                }
            }
            // =========================================================================================================================================================================
            
            $updateData += [
                'status_pulang' => $this->request->getVar('status')
            ];
  
            $this->absenModel->save($updateData);
          }
        }
      }
    }

    // datetime_indo
    echo json_encode($msg);
  }
  public function saveZonaAbsen()
  {
    $keterangan_tempat = $this->request->getVar("keterangan_tempat");
    $alamat = $this->request->getVar("alamat");
    $latitude = $this->request->getVar("latitude");
    $longitude = $this->request->getVar("longitude");

    $cekAlamat = $this->absenZonaModel->where('alamat', $alamat)->first();

    if ($cekAlamat) {
      session()->setFlashdata('info', 'Alamat sudah terdaftar dengan status "' . $cekAlamat['status'] . '" <br/>Pastikan hubungi admin untuk tindakan lebih lanjut dengan bukti/alasan');
      return redirect()->to('/absen/zona_absen');
    }

    $by = $this->userModel->where('username', session()->get('username'))->first();

    $data = [
      'keterangan_tempat' => $keterangan_tempat,
      'alamat' => $alamat,
      'latitude' => $latitude,
      'longitude' => $longitude,
      'created_by' => $by['id_user'],
      'updated_by' => $by['id_user'],
    ];

    if ($this->absenZonaModel->save($data)) {
      $newID = $this->absenZonaModel->getInsertId();
      $timeline = [
        'icon'        => 'fas fa-map-marker-alt bg-danger',
        'id_table'    => $newID,
        'table'       => 'absen_zona',
        'id_user'     => $by['id_user'],
        'aktivitas'   => 'Mengajukan Request Zona Absensi',
        'keterangan'  => "Telah mengajukan lokasi dengan alamat '" . $alamat . "' untuk bagian tempat '" . $keterangan_tempat . "'",
      ];
      $this->linimasaModel->save($timeline);
      session()->setFlashdata('pesan', 'Data berhasil diperbaharui.');
      return redirect()->to('/absen/zona_absen');
    } else {
      session()->setFlashdata('error', 'Data gagal diperbaharui.');
      return redirect()->to('/absen/zona_absen');
    }
  }
  public function terkonfirmasiZona($id = null)
  {
    if ($id != null) {
      $by = $this->userModel->where('username', session()->get('username'))->first();
      $cek = $this->absenZonaModel->where('id_absen_zona', $id)->first();

      $data = [
        'id_absen_zona' => $id,
        'status' => 'Terkonfirmasi',
        'updated_by' => $by['id_user']
      ];
      if ($this->absenZonaModel->save($data)) {
        $timeline = [
          'icon'        => 'fas fa-map-marked-alt bg-success',
          'id_table'    => $id,
          'table'       => 'absen_zona',
          'id_user'     => $by['id_user'],
          'aktivitas'   => 'Mengkonfirmasi Zona Absen',
          'keterangan'  => "Telah mengkonfirmasi alamat <i>" . $cek['alamat'] . "</i> untuk bagian tempat '" . $cek['keterangan_tempat'] . "'",
        ];
        $this->linimasaModel->save($timeline);
        session()->setFlashdata('pesan', 'Data berhasil diperbaharui.');
        return redirect()->to('/absen/zona_absen');
      } else {
        session()->setFlashdata('error', 'Data gagal diperbaharui.');
        return redirect()->to('/absen/zona_absen');
      }
    }
  }
  public function tolakZona($id = null)
  {
    if ($id != null) {
      $by = $this->userModel->where('username', session()->get('username'))->first();
      $cek = $this->absenZonaModel->where('id_absen_zona', $id)->first();

      $data = [
        'id_absen_zona' => $id,
        'status' => 'Tolak',
        'updated_by' => $by['id_user']
      ];
      if ($this->absenZonaModel->save($data)) {
        $timeline = [
          'icon'        => 'fas fa-map bg-danger',
          'id_table'    => $id,
          'table'       => 'absen_zona',
          'id_user'     => $by['id_user'],
          'aktivitas'   => 'Menolak Zona Absen',
          'keterangan'  => "Telah menolak alamat <i>" . $cek['alamat'] . "</i> untuk bagian tempat '" . $cek['keterangan_tempat'] . "'",
        ];
        $this->linimasaModel->save($timeline);
        session()->setFlashdata('pesan', 'Data berhasil diperbaharui.');
        return redirect()->to('/absen/zona_absen');
      } else {
        session()->setFlashdata('error', 'Data gagal diperbaharui.');
        return redirect()->to('/absen/zona_absen');
      }
    }
  }
  public function deleteZona($id = null)
  {
    if ($id != null) {
      $by = $this->userModel->where('username', session()->get('username'))->first();

      if ($this->absenZonaModel->delete($id)) {
        session()->setFlashdata('pesan', 'Data berhasil dihapus.');
      } else {
        session()->setFlashdata('error', 'Data gagal dihapus.');
      }
    }
  }

  public function deleteAbsen($id = null)
  {
    if ($this->request->isAJAX()) {
      if ($id != null) {
        $absen = $this->absenModel->find($id);
        if ($absen) {
          if ($this->absenModel->delete($id)) {
            echo json_encode(['data' => 'sukses']);
            return;
          }
        }
      }
      echo json_encode(['data' => 'error']);
    }
  }
  public function validasiMasuk($status, $id) {
    if ($status == "1") {
      $status = 'Validasi';
      $icon = 'fas fa-check-circle bg-success';
    } else {
      $status = 'Invalidasi';
      $icon = 'fas fa-times-circle bg-danger';
    }
    $validasi = $this->request->getVar("keterangan_validasi_masuk");
     
    $data = [
      'id_absen' => $id,
      'keterangan_validasi_masuk' => $validasi,
      'validation_masuk' => $status
    ];
    
    $msg = [];
    
    
    if ($this->absenModel->save($data)) {
        
        if ($status == "Invalidasi") {
            $dataPegawai = $this->dataPegawaiModel->join('absen', 'id_data_pegawai')->where('id_absen', $id)->first();
            $by = $this->userModel->where('username', session()->get('username'))->first();
            
            $timeline = [
              'icon'        => $icon,
              'id_table'    => $id,
              'table'       => 'absen',
              'id_user'     => $by['id_user'],
              'aktivitas'   => 'Melakukan '. $status . ' kepada '. name_capitalize($dataPegawai['nama']),
              'keterangan'  => "<strong>" . session()->get('nama') . "</strong> telah melakukan '". $status ."' jam masuk dengan alasan <u>" . $validasi . '</u> waktu absen ('. $dataPegawai['jam_masuk'] .')',
            ];
            $this->linimasaModel->save($timeline);
            
            $data = [
              'nama_pengirim'   => $by['id_user'],
              'nama_penerima'   => $dataPegawai['id_user'],
              'pesan'           => "<strong>" . session()->get('nama') . "</strong> telah melakukan '". $status ."' jam masuk dengan alasan <u>" . $validasi . '</u> waktu absen ('. $dataPegawai['jam_masuk'] .')',
            ];
            $this->notifikasiModel->save($data);
        }
        
        $msg = ['data' => 'sukses'];
    }
    
    echo json_encode($msg);
  }
  public function validasiPulang($status, $id) {
    if ($status == "1") {
      $status = 'Validasi';
      $icon = 'fas fa-check-circle bg-success';
    } else {
      $status = 'Invalidasi';
      $icon = 'fas fa-times-circle bg-danger';
    }
    $validasi = $this->request->getVar("keterangan_validasi_pulang");
     
    $data = [
      'id_absen' => $id,
      'keterangan_validasi_pulang' => $validasi,
      'validation_pulang' => $status
    ];
    
    $msg = [];
    
    if ($this->absenModel->save($data)) {
        
        if ($status == "Invalidasi") {
            $dataPegawai = $this->dataPegawaiModel->join('absen', 'id_data_pegawai')->where('id_absen', $id)->first();
            $by = $this->userModel->where('username', session()->get('username'))->first();
            
            $timeline = [
              'icon'        => $icon,
              'id_table'    => $id,
              'table'       => 'absen',
              'id_user'     => $by['id_user'],
              'aktivitas'   => 'Melakukan '. $status . ' kepada '. name_capitalize($dataPegawai['nama']),
              'keterangan'  => "<strong>" . session()->get('nama') . "</strong> telah melakukan '". $status ."' jam pulang dengan alasan <u>" . $validasi . '</u> waktu absen ('. $dataPegawai['jam_pulang'] .')',
            ];
            $this->linimasaModel->save($timeline);
            
            $data = [
              'nama_pengirim'   => $by['id_user'],
              'nama_penerima'   => $dataPegawai['id_user'],
              'pesan'           => "<strong>" . session()->get('nama') . "</strong> telah melakukan '". $status ."' jam pulang dengan alasan <u>" . $validasi . '</u> waktu absen ('. $dataPegawai['jam_pulang'] .')',
            ];
            $this->notifikasiModel->save($data);
        }
        
        $msg = ['data' => 'sukses'];
    }
    
    echo json_encode($msg);
  }
  public function addKeterangan()
  {
    $by = $this->userModel->where('username', session()->get('username'))->first();
    $tanggal = $this->request->getVar("tanggal_masuk");
    $id_data_pegawai = $this->request->getVar("id_data_pegawai");
    $keterangan = $this->request->getVar("keterangan");

    $cek = $this->absenModel->where(['id_data_pegawai' => $id_data_pegawai, 'DATE(jam_masuk)' => $tanggal])->first();
    $jam_masuk = $tanggal . ' 07:00:00';

    $timestamp = strtotime($tanggal);

    if (date('N', $timestamp) == 5) {
      $jam_pulang = $tanggal . ' 16:30:00';
    } else {
      $jam_pulang = $tanggal . ' 16:00:00';
    }

    $data = [];
    if ($cek) {
      $data = [
        'id_absen' => $cek['id_absen'],
        'jam_masuk' => $jam_masuk,
        'jam_pulang' => $jam_pulang,
        'keterangan' => $keterangan,
      ];
    } else {
      $data = [
        'id_data_pegawai' => $id_data_pegawai,
        'jam_masuk' => $jam_masuk,
        'jam_pulang' => $jam_pulang,
        'keterangan' => $keterangan,
      ];
    }

    if ($this->absenModel->save($data)) {
      if ($cek)
        $newID = $cek['id_absen'];
      else
        $newID = $this->absenZonaModel->getInsertId();

      $timeline = [
        'icon'        => 'fas fa-marker bg-warning',
        'id_table'    => $newID,
        'table'       => 'absen',
        'id_user'     => $by['id_user'],
        'aktivitas'   => 'mengubah setatus keterangan menjadi "' . ($keterangan) . '"!',
      ];
      $this->linimasaModel->save($timeline);
      session()->setFlashdata('pesan', 'Data berhasil diperbaharui.');
      return redirect()->to('/absen/absen_harian');
    } else {
      session()->setFlashdata('error', 'Data gagal diperbaharui.');
      return redirect()->to('/absen/absen_harian');
    }
  }
  public function insertAkses()
  {
    if ($this->request->isAjax()) {
      $akses = $this->userModel
        ->join('absen_akses', 'absen_akses.id_user=user.id_user')
        ->where("user.username", session()->get('username'))
        ->first();
      if ($akses && $akses['status'] == 'Akses Diterima') {

        $by = $this->userModel->where('username', session()->get('username'))->first();
        $msg = [];
        $user = $this->request->getVar("id");
        $cek = $this->absenAksesModel->where('id_user', $user)->first();
        $icon = '';
        $userName = $this->userModel->where('id_user', $user)->first();
        $aktivitas = '';

        $data = [];
        if ($cek) {
          $data += [
            'id_absen_akses' => $cek['id_absen_akses'],
          ];
          $icon = 'fas fa-user-edit bg-warning';
          $aktivitas = 'memperbaharui akses penuh ABSEN kepada user <strong>' . name_capitalize($userName['nama']) . '</strong> dengan keterangan <i>(Akses Diterima)</i>';
        } else {
          $icon = 'fas fa-user-plus bg-success';
          $aktivitas = 'menambahkan akses baru penuh ABSEN kepada user <strong>' . name_capitalize($userName['nama']) . '</strong>';
        }

        $data += [
          'id_user' => $user,
          'status' => 'Akses Diterima',
          'created_by' => $by['id_user'],
          'updated_by' => $by['id_user'],
        ];
        if ($this->absenAksesModel->save($data)) {
          $newId = $this->absenAksesModel->getInsertID();
          $timeline = [
            'icon'        => $icon,
            'id_table'    => $newId,
            'table'       => 'absen_akses',
            'id_user'     => $by['id_user'],
            'aktivitas'   => $aktivitas,
            'keterangan'  => "",
          ];
          $this->linimasaModel->save($timeline);
          $msg = [
            'data' => 'sukses'
          ];
        } else {
          $msg = [
            'data' => 'error'
          ];
        }
      } else {
        $msg = [
          'data' => 'error'
        ];
      }
      echo json_encode($msg);
    }
  }
  public function removeAkses()
  {
    if ($this->request->isAjax()) {
      $akses = $this->userModel
        ->join('absen_akses', 'absen_akses.id_user=user.id_user')
        ->where("user.username", session()->get('username'))
        ->first();
      if ($akses && $akses['status'] == 'Akses Diterima') {
        $by = $this->userModel->where('username', session()->get('username'))->first();
        $msg = [];
        $user = $this->request->getVar("id");
        $cek = $this->absenAksesModel->where('id_user', $user)->first();
        $icon = '';
        $userName = $this->userModel->where('id_user', $user)->first();
        $aktivitas = '';

        $data = [];
        if ($cek) {
          $data += [
            'id_absen_akses' => $cek['id_absen_akses'],
          ];
          $icon = 'fas fa-user-alt-slash bg-danger';
          $aktivitas = 'memperbaharui akses penuh ABSEN kepada user <strong>' . name_capitalize($userName['nama']) . '</strong> dengan keterangan <i>(Akses Diblokir)</i>';
        } else {
          $icon = 'fas fa-user-times bg-danger';
          $aktivitas = 'menambahkan akses baru penuh ABSEN kepada user <strong>' . name_capitalize($userName['nama']) . '</strong> dengan keterangan <i>(Akses Diblokir)</i>';
        }

        $data += [
          'id_user' => $user,
          'status' => 'Akses Diblokir',
          'created_by' => $by['id_user'],
          'updated_by' => $by['id_user'],
        ];
        if ($this->absenAksesModel->save($data)) {
          $newId = $this->absenAksesModel->getInsertID();
          $timeline = [
            'icon'        => $icon,
            'id_table'    => $newId,
            'table'       => 'absen_akses',
            'id_user'     => $by['id_user'],
            'aktivitas'   => $aktivitas,
            'keterangan'  => "",
          ];
          $this->linimasaModel->save($timeline);
          $msg = [
            'data' => 'sukses'
          ];
        } else {
          $msg = [
            'data' => 'error'
          ];
        }
      } else {
        $msg = [
          'data' => 'error'
        ];
      }
      echo json_encode($msg);
    }
  }
  public function updateTombolAbsen()
  {
      if ($this->request->isAjax()) {
          $absenSettingModel = new \App\Models\AbsenSettingModel();
          $setting = $absenSettingModel->first();
          
          $field = $this->request->getVar('field');
          $value = $this->request->getVar('value');
          
          if (in_array($field, ['show_wfo', 'show_wfa', 'show_dl'])) {
              // Validasi: Minimal 1 tombol harus aktif
              if ($value == 0 && $setting) {
                  $wfo = $field == 'show_wfo' ? 0 : $setting['show_wfo'];
                  $wfa = $field == 'show_wfa' ? 0 : $setting['show_wfa'];
                  $dl  = $field == 'show_dl'  ? 0 : $setting['show_dl'];
                  
                  if ($wfo == 0 && $wfa == 0 && $dl == 0) {
                      echo json_encode(['data' => 'error', 'message' => 'Minimal harus ada 1 tombol absen yang aktif!']);
                      return;
                  }
              }
              
              if ($setting) {
                  $absenSettingModel->update($setting['id'], [$field => $value]);
              } else {
                  $insertData = ['show_wfo' => 1, 'show_wfa' => 1, 'show_dl' => 1];
                  $insertData[$field] = $value;
                  $absenSettingModel->insert($insertData);
              }
              echo json_encode(['data' => 'sukses']);
          } else {
              echo json_encode(['data' => 'error']);
          }
      }
    }

  public function updateTargetPolygon() {
      if($this->request->isAjax()) {
          $id_absen_target = $this->request->getVar('id_absen_target');
          $polygon = $this->request->getVar('polygon');
          $tolerance_meters = $this->request->getVar('tolerance_meters');
          
          $data = [
              'id_absen_target' => $id_absen_target,
              'polygon' => $polygon,
              'tolerance_meters' => $tolerance_meters
          ];
          
          if($this->absenTargetModel->save($data)) {
              echo json_encode(['data' => true]);
          } else {
              echo json_encode(['data' => false]);
          }
      }
  }

  public function updateAksesWFA()
  {
    if ($this->request->isAjax()) {
      try {
        $akses = $this->userModel
          ->join('absen_akses', 'absen_akses.id_user=user.id_user')
          ->where("user.username", session()->get('username'))
          ->first();
        if ($akses && $akses['status'] == 'Akses Diterima') {

          $by = $this->userModel->where('username', session()->get('username'))->first();
          $id_user_target = $this->request->getVar("id");
          $status_wfa = $this->request->getVar("status");
          
          if (is_array($id_user_target)) {
              $sukses = true;
              foreach ($id_user_target as $id) {
                  $cek = $this->absenAksesModel->where('id_user', $id)->first();
                  $data = [];
                  if ($cek) {
                      $data = [
                          'id_absen_akses' => $cek['id_absen_akses'],
                          'akses_wfa' => $status_wfa,
                          'updated_by' => $by['id_user'],
                      ];
                      $this->absenAksesModel->update($cek['id_absen_akses'], $data);
                  } else {
                      $data = [
                          'id_user' => $id,
                          'status' => 'Akses Diblokir',
                          'akses_wfa' => $status_wfa,
                          'akses_edit_del' => 0,
                          'created_by' => $by['id_user'],
                          'updated_by' => $by['id_user'],
                      ];
                      $this->absenAksesModel->insert($data);
                  }
              }
              $msg = ['data' => 'sukses'];
          } else {
              $cek = $this->absenAksesModel->where('id_user', $id_user_target)->first();
              
              $data = [];
              if ($cek) {
                  $data = [
                      'id_absen_akses' => $cek['id_absen_akses'],
                      'akses_wfa' => $status_wfa,
                      'updated_by' => $by['id_user'],
                  ];
                  if ($this->absenAksesModel->update($cek['id_absen_akses'], $data)) {
                       $msg = ['data' => 'sukses'];
                  } else {
                       $msg = ['data' => 'error'];
                  }
              } else {
                  $data = [
                      'id_user' => $id_user_target,
                      'status' => 'Akses Diblokir',
                      'akses_wfa' => $status_wfa,
                      'akses_edit_del' => 0,
                      'created_by' => $by['id_user'],
                      'updated_by' => $by['id_user'],
                  ];
                  if ($this->absenAksesModel->insert($data)) {
                       $msg = ['data' => 'sukses'];
                  } else {
                       $msg = ['data' => 'error'];
                  }
              }
          }
        } else {
            $msg = ['data' => 'error'];
        }
      } catch (\Throwable $e) {
            log_message('error', 'Error in updateAksesEditDel: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            $msg = ['data' => 'error', 'message' => $e->getMessage()];
        }
      echo json_encode($msg);
    }
  }
  public function updateAksesEditDel()
  {
    if ($this->request->isAjax()) {
      try {
        $akses = $this->userModel
          ->join('absen_akses', 'absen_akses.id_user=user.id_user')
          ->where("user.username", session()->get('username'))
          ->first();
        if ($akses && $akses['status'] == 'Akses Diterima') {

          $by = $this->userModel->where('username', session()->get('username'))->first();
          $id_user_target = $this->request->getVar("id");
          $status_edit_del = $this->request->getVar("status");
          
          if (is_array($id_user_target)) {
              $sukses = true;
              foreach ($id_user_target as $id) {
                  $cek = $this->absenAksesModel->where('id_user', $id)->first();
                  $data = [];
                  if ($cek) {
                      $data = [
                          'id_absen_akses' => $cek['id_absen_akses'],
                          'akses_edit_del' => $status_edit_del,
                          'updated_by' => $by['id_user'],
                      ];
                      $this->absenAksesModel->update($cek['id_absen_akses'], $data);
                  } else {
                      $data = [
                          'id_user' => $id,
                          'status' => 'Akses Diblokir',
                          'akses_wfa' => 0,
                          'akses_edit_del' => $status_edit_del,
                          'created_by' => $by['id_user'],
                          'updated_by' => $by['id_user'],
                      ];
                      $this->absenAksesModel->insert($data);
                  }
              }
              $msg = ['data' => 'sukses'];
          } else {
              $cek = $this->absenAksesModel->where('id_user', $id_user_target)->first();
              
              $data = [];
              if ($cek) {
                  $data = [
                      'id_absen_akses' => $cek['id_absen_akses'],
                      'akses_edit_del' => $status_edit_del,
                      'updated_by' => $by['id_user'],
                  ];
                  if ($this->absenAksesModel->update($cek['id_absen_akses'], $data)) {
                       $msg = ['data' => 'sukses'];
                  } else {
                       $msg = ['data' => 'error'];
                  }
              } else {
                  $data = [
                      'id_user' => $id_user_target,
                      'status' => 'Akses Diblokir',
                      'akses_wfa' => 0,
                      'akses_edit_del' => $status_edit_del,
                      'created_by' => $by['id_user'],
                      'updated_by' => $by['id_user'],
                  ];
                  if ($this->absenAksesModel->insert($data)) {
                       $msg = ['data' => 'sukses'];
                  } else {
                       $msg = ['data' => 'error'];
                  }
              }
          }
        } else {
            $msg = ['data' => 'error'];
        }
      } catch (\Exception $e) {
          $msg = ['data' => 'error', 'message' => $e->getMessage()];
      }
      echo json_encode($msg);
    }
  }

  public function updateDataAbsenAll()
  {
    $id_absen = $this->request->getVar("id_absen");
    $data = [
      'status_masuk' => $this->request->getVar("status_masuk"),
      'jam_masuk' => $this->request->getVar("jam_masuk") ?: null,
      'alamat_masuk' => $this->request->getVar("alamat_masuk"),
      'longitude_masuk' => $this->request->getVar("longitude_masuk"),
      'latitude_masuk' => $this->request->getVar("latitude_masuk"),
      'status_pulang' => $this->request->getVar("status_pulang"),
      'jam_pulang' => $this->request->getVar("jam_pulang") ?: null,
      'alamat_pulang' => $this->request->getVar("alamat_pulang"),
      'longitude_pulang' => $this->request->getVar("longitude_pulang"),
      'latitude_pulang' => $this->request->getVar("latitude_pulang"),
      'keterangan' => $this->request->getVar("keterangan"),
    ];

    $foto_masuk = $this->request->getFile("foto_masuk");
    if ($foto_masuk && $foto_masuk->isValid() && !$foto_masuk->hasMoved() && in_array($foto_masuk->getExtension(), ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp', 'svg', 'ico'])) {
      $data['image_masuk_1'] = generate_base64($foto_masuk->getTempName());
    }

    $foto_pulang = $this->request->getFile("foto_pulang");
    if ($foto_pulang && $foto_pulang->isValid() && !$foto_pulang->hasMoved() && in_array($foto_pulang->getExtension(), ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp', 'svg', 'ico'])) {
      $data['image_pulang_1'] = generate_base64($foto_pulang->getTempName());
    }

    if ($this->absenModel->update($id_absen, $data)) {
      session()->setFlashdata('pesan', 'Data berhasil diupdate!');
    } else {
      session()->setFlashdata('error', 'Data gagal diupdate!');
    }
    return redirect()->back();
  }

  public function addDataAbsen()
  {
    $id_data_pegawai = $this->request->getVar("id_data_pegawai");
    $keterangan = $this->request->getVar("keterangan");
    $jam = $this->request->getVar("jam"); //""
    $foto = $this->request->getFile("foto");
    $id_absen_post = $this->request->getVar("id_absen_post");

    $base64 = false;
    $waktu = false;
    if ($foto) {
      if ($foto->isValid() && !$foto->hasMoved() && in_array($foto->getExtension(), ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp', 'svg', 'ico'])) {
        $fileContents = generate_base64($foto->getTempName());
        $base64 = true;
      }
    }

    if ($jam != "") {
      $waktu = true;
    }

    if ($waktu == true || $base64 == true) {
      $data = [];
      $date = \DateTime::createFromFormat('Y-m-d H:i', $jam);
      // echo $date->format('Y-m-d H:i:s');
      // echo $date->format('Y-m-d');

      if ($id_absen_post != "") {
        $cekData = $this->absenModel->find($id_absen_post);
      } else {
        $cekData = $this->absenModel->where(['id_data_pegawai' => $id_data_pegawai, 'DATE(jam_masuk)' => $date->format('Y-m-d')])->first();
      }

      if ($cekData) {
        $data += [
          'id_absen' => $cekData['id_absen']
        ];
      }

      if ($keterangan == "masuk") {
        $data += [
          'id_data_pegawai' => $id_data_pegawai,
          'jam_masuk' => $date->format('Y-m-d H:i:s'),
        ];
        if ($this->request->getVar("alamat") != "")
            $data += [
                'alamat_masuk' => $this->request->getVar("alamat")
            ];
        if ($this->request->getVar("longitude") != "")
            $data += [
                'longitude_masuk' => $this->request->getVar("longitude")
            ];
        if ($this->request->getVar("latitude") != "")
            $data += [
                'latitude_masuk' => $this->request->getVar("latitude")
            ];
        if ($base64 == true) {
          $data += [
            'image_masuk_1' => $fileContents,
          ];
        }
      } else {
        if($cekData) {
            $data += [
              'id_data_pegawai' => $id_data_pegawai,
              'jam_pulang' => $date->format('Y-m-d H:i:s'),
            ];
            
            if ($this->request->getVar("alamat") != "")
                $data += [
                    'alamat_pulang' => $this->request->getVar("alamat")
                ];
            if ($this->request->getVar("longitude") != "")
                $data += [
                    'longitude_pulang' => $this->request->getVar("longitude")
                ];
            if ($this->request->getVar("latitude") != "")
                $data += [
                    'latitude_pulang' => $this->request->getVar("latitude")
                ];
            if ($base64 == true) {
              $data += [
                'image_pulang_1' => $fileContents,
              ];
            }
        } else {
            session()->setFlashdata('error', 'Tidak memasukkan Jam Masuk!');
            return redirect()->to('/absen/absen_harian');
        }
      }
      if ($this->absenModel->save($data)) {
        session()->setFlashdata('pesan', 'Data berhasil diupdate!');
      } else {
        session()->setFlashdata('error', 'Data gagal diupdate!');
      }
    } else {
      session()->setFlashdata('error', 'Tidak ada data yang diupdate!');
    }
    return redirect()->back();
  }
  public function setNewAbsen() {
    if($this->request->isAJAX()) {
      $rekam = $this->request->getVar('rekam');
      $id_absen = $this->request->getVar('id_absen');
      $alamat = $this->request->getVar('alamat');
      $longitude = $this->request->getVar('longitude');
      $latitude = $this->request->getVar('latitude');
      $jam = $this->request->getVar('jam');
      $image = $this->request->getVar('image');
      
      $var = "";
      if($rekam == "Rekam Masuk") {
          $var = "masuk";
      } else {
          $var = "pulang";
      }
      
      $msg = [];
      $data = [ 
        "id_absen" => $id_absen,
        "alamat_".$var => $alamat,
        "longitude_".$var => $longitude,
        "latitude_".$var => $latitude,
        "jam_".$var => $jam,
        "image_".$var."_1" => generate_base64(substr($image, 1)),
      ];
      
      if($this->absenModel->save($data)) {
          $msg = ['data' => 'sukses'];
      }
          
      echo json_encode($msg);
    }
  }
  public function updateTargetAkses() {
    if($this->request->isAjax()) {
      $id_data_pegawai = $this->request->getVar('id_data_pegawai');
      $by = $this->userModel->where('username', session()->get('username'))->first();
      
      $data = [];
      
      $cek = $this->absenTargetAksesModel
        ->where('data_pegawai.id_data_pegawai', $id_data_pegawai)
        ->join('data_pegawai', 'absen_target_akses.id_data_pegawai = data_pegawai.id_data_pegawai',"right")
        ->first();
      
      if ($cek['id_absen_target_akses'] != null) {
          $data += [
            'id_absen_target_akses' => $cek['id_absen_target_akses']
          ];
      } else {
          $data += [
            'created_by' => $by['id_user']
          ];
      }
      $lokasi = $this->request->getVar('lokasi');
      $data += [
        'id_data_pegawai' => $id_data_pegawai,
        'lokasi' => json_encode($lokasi),
        'updated_by' => $by['id_user']
      ];
      
      $msg = [];
      if($this->absenTargetAksesModel->save($data)) {
          $newID = "";
          if ($cek['id_absen_target_akses'] != null) {
            $newID = $cek['id_absen_target_akses'];
          } else {
            $newID = $this->absenTargetAksesModel->getInsertID();
          }
          
          $keterangan = "Pegawai bernama " . name_capitalize($cek['nama'] . " telah ditetapkan penempatannya di ");
          if (json_encode($lokasi) != "null") {
              for($i=0; $i<count($lokasi); $i++) {
                  $cekLokasi = $this->unitKerjaModel->where('id_unit_kerja', $lokasi[$i])->first();
                  
                  if ($cekLokasi) {
                      $keterangan .= "<i>" . $cekLokasi['unit_kerja'] . "</i>";
                      
                      if (($i+1) >= count($lokasi)) {
                          $keterangan .= " ";
                      } else {
                          $keterangan .= ", ";
                      }
                  }
              }
          } else {
            $keterangan = "Pegawai bernama " . name_capitalize($cek['nama']) . " tidak ditempatkan dimanapun untuk melakukan absen ";
          }
          
          $keterangan .= "oleh " . name_capitalize($by['nama']); 
        
          $timeline = [
            'icon'        => 'fas fa-map-marker-alt bg-warning',
            'id_table'    => $newID,
            'table'       => 'absen_target_akses',
            'id_user'     => $by['id_user'],
            'aktivitas'   => 'Menetapkan Lokasi Absen',
            'keterangan'  => $keterangan,
          ];
          $this->linimasaModel->save($timeline);
          
        $msg = [
          'data' => "sukses"
        ];
      }
      
      echo json_encode($msg);
    }
  }
  private function formatPesanPengukuranKebugaran($dataPengukuranKebugaran)
  {
    if (!$dataPengukuranKebugaran) {
      return '';
    }
    
    // Format tanggal
    $tanggalPengukuran = date('d/m/Y', strtotime($dataPengukuranKebugaran['tanggal_pengukuran']));
    
    // Tentukan kategori dan warna untuk tekanan darah
    $sistolik = $dataPengukuranKebugaran['tekanan_darah_sistolik'];
    $diastolik = $dataPengukuranKebugaran['tekanan_darah_diastolik'];
    if ($sistolik < 120 && $diastolik < 80) {
        $tekananDarahKategori = 'Normal';
        $tekananDarahWarna = '#28a745';
    } elseif ($sistolik < 140 && $diastolik < 90) {
        $tekananDarahKategori = 'Prehipertensi';
        $tekananDarahWarna = '#ffc107';
    } else {
        $tekananDarahKategori = 'Hipertensi';
        $tekananDarahWarna = '#dc3545';
    }
    
    // Tentukan kategori dan warna untuk gula darah
    $gulaDarah = $dataPengukuranKebugaran['gula_darah_sewaktu'];
    if ($gulaDarah < 140) {
        $gulaDarahKategori = 'Normal';
        $gulaDarahWarna = '#28a745';
    } elseif ($gulaDarah < 200) {
        $gulaDarahKategori = 'Prediabetes';
        $gulaDarahWarna = '#ffc107';
    } else {
        $gulaDarahKategori = 'Diabetes';
        $gulaDarahWarna = '#dc3545';
    }
    
    // Tentukan kategori dan warna untuk kolesterol
    $kolesterol = $dataPengukuranKebugaran['kolesterol_total'];
    if ($kolesterol < 200) {
        $kolesterolKategori = 'Normal';
        $kolesterolWarna = '#28a745';
    } elseif ($kolesterol < 240) {
        $kolesterolKategori = 'Batas Tinggi';
        $kolesterolWarna = '#ffc107';
    } else {
        $kolesterolKategori = 'Tinggi';
        $kolesterolWarna = '#dc3545';
    }
    
    // Warna untuk lingkar pinggang
    $lingkarPinggangWarna = ($dataPengukuranKebugaran['kategori_lingkar_pinggang'] == 'Normal') ? '#28a745' : '#dc3545';
    
    // Warna untuk IMT
    $imtKategori = $dataPengukuranKebugaran['kategori_imt'];
    if ($imtKategori == 'Normal') {
        $imtWarna = '#28a745';
    } elseif ($imtKategori == 'Underweight') {
        $imtWarna = '#17a2b8';
    } elseif ($imtKategori == 'Overweight') {
        $imtWarna = '#ffc107';
    } else {
        $imtWarna = '#dc3545';
    }
    
    // Format BB/TB untuk IMT
    $beratBadan = $dataPengukuranKebugaran['berat_badan'];
    $tinggiBadan = $dataPengukuranKebugaran['tinggi_badan'];
    $imt = $dataPengukuranKebugaran['imt'];
    
    return '<div style="margin: 15px 0; padding: 12px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #007bff;">' .
        '<div style="display: flex; align-items: center; margin-bottom: 10px;">' .
        '<span style="font-size: 16px; margin-right: 8px;">🏥</span>' .
        '<strong style="color: #495057; font-size: 14px;">Data Kesehatan Terakhir</strong>' .
        '<span style="color: #6c757d; font-size: 12px; margin-left: 8px;">(' . $tanggalPengukuran . ')</span>' .
        '</div>' .
        
        '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 8px; margin-top: 10px;">' .
        
        // Tekanan Darah
        '<div style="background: white; padding: 8px; border-radius: 6px; border: 1px solid #e9ecef; text-align: center;">' .
        '<div style="font-size: 10px; color: #6c757d; margin-bottom: 2px;">TEKANAN DARAH</div>' .
        '<div style="font-weight: bold; font-size: 13px; color: #495057; margin-bottom: 2px;">' . $sistolik . '/' . $diastolik . ' mmHg</div>' .
        '<span style="background: ' . $tekananDarahWarna . '; color: white; padding: 2px 6px; border-radius: 10px; font-size: 9px;">' . $tekananDarahKategori . '</span>' .
        '</div>' .
        
        // Gula Darah
        '<div style="background: white; padding: 8px; border-radius: 6px; border: 1px solid #e9ecef; text-align: center;">' .
        '<div style="font-size: 10px; color: #6c757d; margin-bottom: 2px;">GULA DARAH</div>' .
        '<div style="font-weight: bold; font-size: 13px; color: #495057; margin-bottom: 2px;">' . $gulaDarah . ' mg/dL</div>' .
        '<span style="background: ' . $gulaDarahWarna . '; color: white; padding: 2px 6px; border-radius: 10px; font-size: 9px;">' . $gulaDarahKategori . '</span>' .
        '</div>' .
        
        // Kolesterol
        '<div style="background: white; padding: 8px; border-radius: 6px; border: 1px solid #e9ecef; text-align: center;">' .
        '<div style="font-size: 10px; color: #6c757d; margin-bottom: 2px;">KOLESTEROL</div>' .
        '<div style="font-weight: bold; font-size: 13px; color: #495057; margin-bottom: 2px;">' . $kolesterol . ' mg/dL</div>' .
        '<span style="background: ' . $kolesterolWarna . '; color: white; padding: 2px 6px; border-radius: 10px; font-size: 9px;">' . $kolesterolKategori . '</span>' .
        '</div>' .
        
        // Lingkar Pinggang
        '<div style="background: white; padding: 8px; border-radius: 6px; border: 1px solid #e9ecef; text-align: center;">' .
        '<div style="font-size: 10px; color: #6c757d; margin-bottom: 2px;">LINGKAR PINGGANG</div>' .
        '<div style="font-weight: bold; font-size: 13px; color: #495057; margin-bottom: 2px;">' . $dataPengukuranKebugaran['lingkar_pinggang'] . ' cm</div>' .
        '<span style="background: ' . $lingkarPinggangWarna . '; color: white; padding: 2px 6px; border-radius: 10px; font-size: 9px;">' . $dataPengukuranKebugaran['kategori_lingkar_pinggang'] . '</span>' .
        '</div>' .
        
        // Berat/Tinggi Badan
        '<div style="background: white; padding: 8px; border-radius: 6px; border: 1px solid #e9ecef; text-align: center;">' .
        '<div style="font-size: 10px; color: #6c757d; margin-bottom: 2px;">BERAT/TINGGI</div>' .
        '<div style="font-weight: bold; font-size: 13px; color: #495057; margin-bottom: 2px;">' . $beratBadan . ' kg / ' . $tinggiBadan . ' cm</div>' .
        '<span style="background: #6c757d; color: white; padding: 2px 6px; border-radius: 10px; font-size: 9px;">Antropometri</span>' .
        '</div>' .
        
        // IMT
        '<div style="background: white; padding: 8px; border-radius: 6px; border: 1px solid #e9ecef; text-align: center;">' .
        '<div style="font-size: 10px; color: #6c757d; margin-bottom: 2px;">INDEKS MASSA TUBUH</div>' .
        '<div style="font-weight: bold; font-size: 13px; color: #495057; margin-bottom: 2px;">' . $imt . ' kg/m²</div>' .
        '<span style="background: ' . $imtWarna . '; color: white; padding: 2px 6px; border-radius: 10px; font-size: 9px;">' . $imtKategori . '</span>' .
        '</div>' .
        
        '</div>' .
        
        // Pesan rekomendasi dokter
        '<div style="margin-top: 12px; padding: 8px; background: #e3f2fd; border-radius: 6px; text-align: center;">' .
        '<span style="font-size: 11px; color: #1976d2; font-style: italic;">💡 Untuk rekomendasi dokter dapat dilihat di Simply</span>' .
        '</div>' .
        
        '</div>';
  }

  public function getBlackImage() {
      return ["data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/4gHYSUNDX1BST0ZJTEUAAQEAAAHIAAAAAAQwAABtbnRyUkdCIFhZWiAH4AABAAEAAAAAAABhY3NwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAQAA9tYAAQAAAADTLQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAlkZXNjAAAA8AAAACRyWFlaAAABFAAAABRnWFlaAAABKAAAABRiWFlaAAABPAAAABR3dHB0AAABUAAAABRyVFJDAAABZAAAAChnVFJDAAABZAAAAChiVFJDAAABZAAAAChjcHJ0AAABjAAAADxtbHVjAAAAAAAAAAEAAAAMZW5VUwAAACAAAAAcAEcAbwBvAGcAbABlACAASQBuAGMALgAgADIAMAAxADb/2wBDAAMCAgICAgMCAgIDAwMDBAYEBAQEBAgGBgUGCQgKCgkICQkKDA8MCgsOCwkJDRENDg8QEBEQCgwSExIQEw8QEBD/2wBDAQMDAwQDBAgEBAgQCwkLEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBD/wAARCAB4AKADASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAn/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFAEBAAAAAAAAAAAAAAAAAAAAAP/EABQRAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhEDEQA/AJVAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA//9k=", "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/4gHYSUNDX1BST0ZJTEUAAQEAAAHIAAAAAAQwAABtbnRyUkdCIFhZWiAH4AABAAEAAAAAAABhY3NwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAQAA9tYAAQAAAADTLQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAlkZXNjAAAA8AAAACRyWFlaAAABFAAAABRnWFlaAAABKAAAABRiWFlaAAABPAAAABR3dHB0AAABUAAAABRyVFJDAAABZAAAAChnVFJDAAABZAAAAChiVFJDAAABZAAAAChjcHJ0AAABjAAAADxtbHVjAAAAAAAAAAEAAAAMZW5VUwAAACAAAAAcAEcAbwBvAGcAbABlACAaSQBuAGMALgAgADIAMAAxADb/2wBDAAMCAgICAgMCAgIDAwMDBAYEBAQEBAgGBgUGCQgKCgkICQkKDA8MCgsOCwkJDRENDg8QEBEQCgwSExIQEw8QEBD/2wBDAQMDAwQDBAgEBAgQCwkLEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBD/wAARCAB4AKADASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAn/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFAEBAAAAAAAAAAAAAAAAAAAAAP/EABQRAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhEDEQA/AJVAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA//9k=", "data:image/jpg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/4gHYSUNDX1BST0ZJTEUAAQEAAAHIAAAAAAQwAABtbnRyUkdCIFhZWiAH4AABAAEAAAAAAABhY3NwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAQAA9tYAAQAAAADTLQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAlkZXNjAAAA8AAAACRyWFlaAAABFAAAABRnWFlaAAABKAAAABRiWFlaAAABPAAAABR3dHB0AAABUAAAABRyVFJDAAABZAAAAChnVFJDAAABZAAAAChiVFJDAAABZAAAAChjcHJ0AAABjAAAADxtbHVjAAAAAAAAAAEAAAAMZW5VUwAAACAAAAAcAEcAbwBvAGcAbABlACAaSQBuAGMALgAgADIAMAAxADb/2wBDAAMCAgICAgMCAgIDAwMDBAYEBAQEBAgGBgUGCQgKCgkICQkKDA8MCgsOCwkJDRENDg8QEBEQCgwSExIQEw8QEBD/2wBDAQMDAwQDBAgEBAgQCwkLEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBD/wAARCAB4AKADASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAn/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFAEBAAAAAAAAAAAAAAAAAAAAAP/EABQRAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhEDEQA/AJVAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA//9k="];
  }

  public function simpanTandaTangan()
  {
      try {
          if ($this->request->isAJAX()) {
              $nip = $this->request->getPost('nip');
              $status = $this->request->getPost('status');
              $jenis = $this->request->getPost('jenis');
              $signature = $this->request->getPost('signature');
              $day = date('Y-m-d');

              if (!$nip || !$signature) {
                  return $this->response->setJSON(['status' => 'error', 'message' => 'NIP atau Tanda Tangan kosong']);
              }

              $dataPegawai = $this->dataPegawaiModel->where('nip', $nip)->first();
              if (!$dataPegawai) {
                  return $this->response->setJSON(['status' => 'error', 'message' => 'NIP tidak ditemukan']);
              }

              // Untuk pulang, cari berdasarkan jam_pulang jika jam_masuk kosong
              $absen = $this->absenModel->where('id_data_pegawai', $dataPegawai['id_data_pegawai'])
                  ->groupStart()
                      ->where('DATE(jam_masuk)', $day)
                      ->orWhere('DATE(jam_pulang)', $day)
                  ->groupEnd()
                  ->orderBy('id_absen', 'DESC')
                  ->first();

              if ($absen) {
                  $dataUpdate = [];
                  if ($jenis === 'masuk') {
                      $dataUpdate['ttd_masuk'] = $signature;
                  } else if ($jenis === 'pulang') {
                      $dataUpdate['ttd_pulang'] = $signature;
                  }

                  if (!empty($dataUpdate)) {
                      $save = $this->absenModel->save(['id_absen' => $absen['id_absen']] + $dataUpdate);
                      if ($save) {
                          return $this->response->setJSON(['status' => 'success']);
                      } else {
                          return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan ke database']);
                      }
                  }
              }
              return $this->response->setJSON(['status' => 'error', 'message' => 'Data absen tidak ditemukan untuk hari ini']);
          }
          return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid Request']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
  }

  public function getRandomMotivasi($jenisAbsen = 'Masuk')
  {
      $tanggalIni = date('Y-m-d');

      $model = new \App\Models\AbsenMotivasiModel();
      
      // 1. Cek apakah ada motivasi khusus untuk tanggal hari ini
      $motivasi = $model->where('status', 'Aktif')
                        ->where('hari', $tanggalIni)
                        ->groupStart()
                            ->where('jenis', $jenisAbsen)
                            ->orWhere('jenis', 'Semua')
                        ->groupEnd()
                        ->orderBy('RAND()')
                        ->first();
                        
      // 2. Jika tidak ada yang khusus tanggal ini, ambil dari yang 'Semua Hari'
      if (!$motivasi) {
          $motivasi = $model->where('status', 'Aktif')
                            ->where('hari', 'Semua Hari')
                            ->groupStart()
                                ->where('jenis', $jenisAbsen)
                                ->orWhere('jenis', 'Semua')
                            ->groupEnd()
                            ->orderBy('RAND()')
                            ->first();
      }

      if ($motivasi) {
          $pesan = nl2br(htmlspecialchars($motivasi['pesan']));
          $signature = htmlspecialchars($motivasi['signature']);
          
          $defaultImg = ($jenisAbsen == 'Pulang') ? 'Bununingpulang.jpg' : 'Bununingmasuk.jpg';
          // Gunakan relative path sederhana tanpa base_url agar lebih tahan banting terhadap konfigurasi server
          $imageUrl = !empty($motivasi['image']) ? base_url('dist/motivasi/' . $motivasi['image']) : base_url('dist/motivasi/' . $defaultImg);
          
          $signatureHtml = $signature ? "
              <div style='margin-top: 15px; display: flex; align-items: center; justify-content: flex-end;'>
                  <div style='height: 1px; width: 30px; background: #6c757d; margin-right: 10px; opacity: 0.5;'></div>
                  <span style='font-style: italic; color: #495057; font-size: 13px; font-weight: 600; font-family: \"Georgia\", serif;'>{$signature}</span>
              </div>" : "";
          
          return "
              <style>
                  /* Menyesuaikan ukuran elemen SweetAlert2 agar tidak terlalu memakan tempat vertikal */
                  .swal2-popup { width: 450px !important; padding-bottom: 10px !important; }
                  .swal2-title { font-size: 1.25rem !important; margin-top: 0px !important; padding-top: 0 !important; }
                  .swal2-icon { transform: scale(0.55); margin-top: 0px !important; margin-bottom: -15px !important; }
                  .swal2-html-container { font-size: 0.9rem !important; }
              </style>
              <div style='margin-top: 15px; margin-bottom: 5px; position: relative;'>
                  <!-- Dekorasi Quote Besar Transparan di Belakang -->
                  <div style='position: absolute; top: -10px; left: 10px; font-size: 60px; color: rgba(23, 162, 184, 0.1); font-family: serif; line-height: 1; z-index: 0;'>
                      &ldquo;
                  </div>
                  
                  <!-- Card Utama -->
                  <div style='position: relative; z-index: 1; padding: 20px; background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%); border-radius: 12px; border: 1px solid rgba(23, 162, 184, 0.2); box-shadow: 0 8px 20px rgba(0,0,0,0.04); text-align: left; overflow: hidden;'>
                      
                      <!-- Aksen Garis Atas -->
                      <div style='position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #17a2b8 0%, #4facfe 100%);'></div>
                      
                      <!-- Gambar -->
                      <div style='margin-bottom: 15px;'>
                          <img src='{$imageUrl}' style='width: 100%; border-radius: 8px; object-fit: cover;' alt='Motivasi'>
                      </div>
                      
                      <!-- Teks Motivasi -->
                      <div style='font-size: 15px; color: #34495e; font-weight: 500; line-height: 1.6; letter-spacing: 0.3px; font-family: \"Poppins\", \"Segoe UI\", \"Helvetica Neue\", sans-serif; text-align: center; font-style: italic; padding: 0 10px;'>
                          \"{$pesan}\"
                      </div>
                      
                      {$signatureHtml}
                  </div>
              </div>";
      }
      return "";
  }

  public function getMotivasi()
  {
      $model = new \App\Models\AbsenMotivasiModel();
      $data = $model->orderBy('id_motivasi', 'DESC')->findAll();
      return $this->response->setJSON(['data' => $data]);
  }

  public function saveMotivasi()
  {
      $model = new \App\Models\AbsenMotivasiModel();
      $id = $this->request->getPost('id_motivasi');
      $data = [
          'hari' => $this->request->getPost('hari'),
          'jenis' => $this->request->getPost('jenis'),
          'pesan' => $this->request->getPost('pesan'),
          'signature' => $this->request->getPost('signature'),
          'status' => $this->request->getPost('status')
      ];

      $file = $this->request->getFile('image');
      if ($file && $file->isValid() && !$file->hasMoved()) {
          $newName = $file->getRandomName();
          // Create directory if not exists
          if (!is_dir(FCPATH . 'dist/motivasi')) {
              mkdir(FCPATH . 'dist/motivasi', 0777, true);
          }
          $file->move(FCPATH . 'dist/motivasi', $newName);
          $data['image'] = $newName;
          
          if ($id) {
              $oldData = $model->find($id);
              if ($oldData && !empty($oldData['image']) && file_exists(FCPATH . 'dist/motivasi/' . $oldData['image'])) {
                  unlink(FCPATH . 'dist/motivasi/' . $oldData['image']);
              }
          }
      }

      if ($id) {
          $model->update($id, $data);
      } else {
          $model->insert($data);
      }
      return $this->response->setJSON(['status' => 'success']);
  }

  public function deleteMotivasi()
  {
      $model = new \App\Models\AbsenMotivasiModel();
      $id = $this->request->getPost('id_motivasi');
      if ($id) {
          $oldData = $model->find($id);
          if ($oldData && !empty($oldData['image']) && file_exists(FCPATH . 'dist/motivasi/' . $oldData['image'])) {
              unlink(FCPATH . 'dist/motivasi/' . $oldData['image']);
          }
          $model->delete($id);
          return $this->response->setJSON(['status' => 'success']);
      }
      return $this->response->setJSON(['status' => 'error']);
  }
}
