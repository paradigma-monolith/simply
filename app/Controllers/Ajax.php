<?php

namespace App\Controllers;

class Ajax extends BaseController
{
  public function getUser()
  {
    if ($this->request->isAJAX()) {
        $data['dataSideRightUser'] = $this->userModel
          ->select("data_pegawai.*, user.nama, user.image, golongan.*, user.email, user.id_user")
          ->join("data_pegawai", "user.id_user=data_pegawai.id_user", "LEFT")
          ->join("golongan", 'data_pegawai.id_golongan=golongan.id_golongan', "LEFT")
          ->where('data_pegawai.status_bekerja', 'Aktif')
          ->orWhere('data_pegawai.id_user IS NULL', null, false)
          ->orderBy("golongan.golongan", "desc")
          ->findAll();

      $view = view('home/getUser', $data);

      $msg = [
        'data' => $view
      ];
      echo json_encode($msg);
    }
  }
  public function openBoxEmail()
  {
    if ($this->request->isAJAX()) {
      $data['email'] = $this->request->getVar("email");

      $view = view("home/sendEmail", $data);
      $msg = [
        'data' => $view
      ];
      echo json_encode($msg);
    }
  }
  public function autoUploadFile()
  {
    if ($this->request->isAJAX()) {
      $by = $this->userModel->select('id_user')->where('username', session()->get('username'))->first();
      // Ambil file yang diunggah
      $file = $this->request->getFile('autoUploadFile');

      // Periksa apakah file valid
      if ($file->isValid() && !$file->hasMoved()) {
        // Ambil nama file asli
        $originalName = $file->getName();

        // Ubah nama file menjadi bilangan unik/acak
        $newName = uniqid() . '_' . $file->getRandomName();

        // Simpan file ke direktori '/file-email'
        $file->move(ROOTPATH . 'writable/file-email', $newName);

        // Periksa apakah file telah tersimpan
        if (file_exists(ROOTPATH . 'writable/file-email/' . $newName)) {
          $dataEmail = [
            'file_nama_original' => $originalName,
            'file_nama_baru' => $newName,
            'status' => 'Pending',
            'created_by' => $by['id_user'],
            'updated_by' => $by['id_user']
          ];
          if ($this->emailLampiranModel->save($dataEmail)) {
            $msg = [
              'id' => $this->emailLampiranModel->getInsertID(),
              'original_name' => $originalName,
              'new_name' => $newName,
              'status' => 'success'
            ];
          } else {
            $msg = [
              'original_name' => $originalName,
              'new_name' => $newName,
              'status' => 'error'
            ];
          }
        } else {
          $msg = [
            'original_name' => $originalName,
            'new_name' => '',
            'status' => 'error'
          ];
        }
      } else {
        $msg = [
          'original_name' => '',
          'new_name' => '',
          'status' => 'error'
        ];
      }

      echo json_encode($msg);
    }
  }
  public function autoRemoveFile()
  {
    // Ambil file yang diunggah
    $file = $this->request->getVar('newName');
    $id = $this->request->getVar('id');

    // Direktori tempat file disimpan
    $directory = ROOTPATH . 'writable/file-email/';

    if (file_exists($directory . $file)) {
      unlink($directory . $file);
      $this->emailLampiranModel->delete($id);
    }

    echo json_encode("success");
  }
  public function testSendMail() {
    // Lakukan proses pengiriman email
    $email = \Config\Services::email();

    // Set penerima dan pengirim email
    $email->setTo('hadian.zainal@gmail.com');
    $email->setFrom('kkpbandung@gmail.com', 'KKP Bandung | Simply Aplikasi');

     // Mengambil data latitude dan longitude dari request
    $latitude = $this->request->getVar('lat');
    $longitude = $this->request->getVar('lon');

    // Menyusun pesan dengan koordinat dan link ke Google Maps
    $isi = "Latitude: " . $latitude . " | Longitude: " . $longitude . "\n";
    $isi .= "Link ke Google Maps: https://www.google.com/maps/@{$latitude},{$longitude},15z";

    // Set subject dan pesan email
    $email->setSubject("Test Send Latitude Longitude");
    $email->setMessage($isi);

    // Kirim email tanpa konfirmasi
    $email->send();
  }

  public function sendMessagesToEmail()
  {
    if ($this->request->isAJAX()) {
      $by = $this->userModel->select('id_user')->where('username', session()->get('username'))->first();

      $penerima = 0;
      $user = $this->userModel->where("email", $this->request->getVar("email"))->first(); // Mengambil data pengguna dengan alamat email yang sesuai
      if ($user) {
        $penerima = $user['id_user'];
      }

      $subjek = $this->request->getVar("subjek");
      $isi = $this->request->getVar("isi") . $this->request->getVar("isiBlank") . $this->request->getVar("isiAdd");
      $data = [
        'pengirim' => $by['id_user'],
        'penerima' => $penerima,
        'email_tujuan' => $this->request->getVar("email"),
        'subjek' => $this->request->getVar("subjek"),
        'isi' => $isi,
        'created_by' => $by['id_user'],
        'updated_by' => $by['id_user'],
      ];
      if ($this->emailDataModel->save($data)) {
        $newId = $this->emailDataModel->getInsertID();
        if (null !== $this->request->getVar("id"))
          for ($i = 0; $i < count($this->request->getVar("id")); $i++) {
            $data = [
              'id_email_lampiran' => $this->request->getVar("id")[$i],
              'id_email_data' => $newId,
              'status' => 'Terkirim'
            ];
            $this->emailLampiranModel->save($data);
          }
        // Lakukan proses pengiriman ke email disini
        $email = service('email');
        $email->setTo($this->request->getVar("email"));
        $email->setFrom('kkpbandung@gmail.com', 'KKP Bandung | Simply Aplikasi');

        // Direktori tempat file disimpan
        $directory = ROOTPATH . 'writable/file-email/';

        if (null !== $this->request->getVar("new_name")) {
          for ($i = 0; $i < count($this->request->getVar("new_name")); $i++) {
            $attachmentPath = $directory . $this->request->getVar("new_name")[$i];

            // Mengatur nama file lampiran (ganti 'new_filename' dengan nama yang Anda inginkan)
            $newFileName = $this->request->getVar("original_name")[$i];

            // Menambahkan lampiran dengan nama file yang diinginkan
            $email->attach($attachmentPath, 'attachment', $newFileName);
          }
        }

        $email->setSubject($subjek);
        $email->setMessage($isi);

        if ($email->send()) {
          $msg = [
            'status' => 'success'
          ];
          $dataUpdate = [
            'id_email_data' => $newId,
            'status' => "Terkirim"
          ];
          $this->emailDataModel->save($dataUpdate);
          $timeline = [
            'icon'        => 'fas fa-share bg-success',
            'id_table'    => $newId,
            'table'       => 'email_data',
            'id_user'     => $by['id_user'],
            'aktivitas'   => 'mengirim pesan kepada ' . ($user ? $user['nama'] : $this->request->getVar("email")),
            'keterangan'  => "",
          ];
          $this->linimasaModel->save($timeline);
        } else {
          $msg = [
            'status' => 'error'
          ];
        }
      } else {
        $msg = [
          'status' => 'error databases' // tidak tersimpan di database
        ];
      }

      echo json_encode($msg);
    }
  }
  
  public function getNotifikasi()
  {
    if ($this->request->isAJAX()) {
      $by = $this->userModel->where('username', session()->get('username'))->first();

      $data['belumDibaca'] = $this->notifikasiModel
        ->select('user_penerima.nama AS nama_pa, user_pengirim.nama AS nama_pm, notifikasi.*')
        ->join('user AS user_penerima', 'user_penerima.id_user = notifikasi.nama_penerima')
        ->join('user AS user_pengirim', 'user_pengirim.id_user = notifikasi.nama_pengirim')
        ->where('nama_penerima', $by['id_user'])
        ->where('status', 'Belum Dibaca') // Menambahkan kondisi untuk status
        ->orderBy('notifikasi.created_at', 'desc')
        ->findAll();
      $number = '';
      if (count($data['belumDibaca']) > 0) {
        $number = count($data['belumDibaca']);
      }

      $data['notifikasi'] = $this->notifikasiModel
        ->select('user_penerima.nama AS nama_pa, user_pengirim.nama AS nama_pm, notifikasi.*')
        ->join('user AS user_penerima', 'user_penerima.id_user = notifikasi.nama_penerima')
        ->join('user AS user_pengirim', 'user_pengirim.id_user = notifikasi.nama_pengirim')
        ->where('nama_penerima', $by['id_user'])
        ->orderBy('notifikasi.created_at', 'desc')
        ->findAll();

      $msg = [
        'number' => $number,
        'data' => view('home/getNotifikasi', $data),
      ];
      echo json_encode($msg);
    }
  }
  public function readNotif($id)
  {
    $data = ['id_notifikasi' => $id, 'status' => 'Sudah Dibaca'];
    $this->notifikasiModel->save($data);
    $msg = [
      'data' => 'sukses'
    ];
    echo json_encode($msg);
  }
  
  public function agenda()
  {
    $by = $this->userModel->where('username', session()->get('username'))->first();

    $topik = $this->request->getVar("topik");
    $deskripsi = $this->request->getVar("deskripsi");
    $peruntukan = $this->request->getVar("peruntukan");
    if ($peruntukan != '') {
      array_unshift($peruntukan, $by['id_user']);
      $peruntukan = serialize($peruntukan);
    } else {
      $peruntukan = [
        $by['id_user']
      ];
      $peruntukan = serialize($peruntukan);
    }

    $waktu_pelaksana_awal = substr($this->request->getVar("waktu_pelaksanaan"), 0, 19);
    $waktu_pelaksana_akhir = substr($this->request->getVar("waktu_pelaksanaan"), 22, 19);
    $tgl_notif_awal = substr($this->request->getVar("tgl_notif"), 0, 10);
    $tgl_notif_akhir = substr($this->request->getVar("tgl_notif"), 13, 10);
    $created_by = $by['id_user'];
    $updated_by = $by['id_user'];
    $data = [];
    if ($this->request->getVar("id_agenda_user")) {
      $data += [
        'id_agenda_user' => $this->request->getVar("id_agenda_user")
      ];
    }

    $notif = $this->request->getVar("notif");
    if ($notif == null) {
      $tgl_notif_awal = null;
      $tgl_notif_akhir = null;
    }
    $data += [
      'topik' => $topik,
      'deskripsi' => $deskripsi,
      'peruntukan' => $peruntukan,
      'waktu_pelaksana_awal' => $waktu_pelaksana_awal,
      'waktu_pelaksana_akhir' => $waktu_pelaksana_akhir,
      'tgl_notif_awal' => $tgl_notif_awal,
      'tgl_notif_akhir' => $tgl_notif_akhir,
      'warna_agenda' => $this->request->getVar("warna_agenda"),
    ];
    $cek = null;

    if (!$this->request->getVar("id_agenda_user"))
      $cek = $this->agendaUserModel->where($data)->first();
    $msg = [];

    if ($cek) {
      $msg = [
        'pesanAgenda' => $cek
      ];
    } else {
      $data += [
        'created_by' => $created_by,
        'updated_by' => $updated_by,
      ];
      if ($this->agendaUserModel->save($data)) {
        $msg = [
          'dataAgenda' => $data
        ];
      }
    }

    echo json_encode($msg);
  }
  public function agenda_saya()
  {
    $by = $this->userModel->where('username', session()->get('username'))->first();
    $data['getData'] = $this->agendaUserModel->where('created_by', $by['id_user'])->findAll();
    $msg = [
      'data' => view('/home/agenda_saya', $data)
    ];
    echo json_encode($msg);
  }
  public function get_agenda_saya($id)
  {
    $data['user'] = $this->userModel->findAll();

    $data['getData'] = $this->agendaUserModel->where('id_agenda_user', $id)->first();
    $msg = [
      'data' => view('/home/form_agenda_saya', $data)
    ];
    echo json_encode($msg);
  }
  public function delete_agenda($id)
  {
    $this->agendaUserModel->delete($id);
  }
  public function getCallendarAgenda()
  {
    $user = $this->userModel->where('username', session()->get('username'))->first();
    if (!$user) {
      return $this->response->setJSON(['data' => '<div class="alert alert-danger">User tidak ditemukan</div>']);
    }
    
    $data['id_user'] = $user['id_user'];
    // Ambil hanya agenda yang relevan untuk user ini
    $allAgenda = $this->agendaUserModel->findAll();
    $filteredAgenda = [];
    
    foreach ($allAgenda as $agenda) {
      try {
        $peruntukan = @unserialize($agenda['peruntukan']);
        if ($peruntukan === false && $agenda['peruntukan'] !== 'b:0;') {
          continue;
        }
        if (!is_array($peruntukan)) {
          continue;
        }
        if (in_array($user['id_user'], $peruntukan)) {
          $filteredAgenda[] = $agenda;
        }
      } catch (Exception $e) {
        continue;
      }
    }
    
    $data['getData'] = $filteredAgenda;

    $msg = ['data' => view('home/agendaCalendar', $data)];

    echo json_encode($msg);
  }
  public function getAgenda()
  {
    $getData = $this->agendaUserModel
    ->where('tgl_notif_awal <=', date('Y-m-d'))
    ->where('tgl_notif_akhir >=', date('Y-m-d'))
    ->findAll();
    $setData = array(); // Inisialisasi array kosong

    $by = $this->userModel->where('username', session()->get('username'))->first();

    foreach ($getData as $data) {
      $peruntukan = unserialize($data['peruntukan']);
      if (in_array($by['id_user'], $peruntukan)) {
        // Tambahkan data ke dalam array $setData
        $setData[] = $data;
      }
    }
    if ($setData == array()) {
      $setData = null;
    }
    $msg = ['data' =>  $setData];

    echo json_encode($msg);
  }
  public function newFitur()
  {
    if ($this->request->isAJAX()) {
      $msg = [
        'data' => view('users/newFitur')
      ];

      echo json_encode($msg);
    }
  }
  
  public function feedback()
  {
    if ($this->request->isAJAX()) {
      $validation = \Config\Services::validation();
      
      $rules = [
        'kategori' => 'required|in_list[UI/UX,Fitur,Bug,Performa,Keamanan,Lainnya]',
        'prioritas' => 'required|in_list[Rendah,Sedang,Tinggi,Kritis]',
        'judul' => 'required|max_length[100]',
        'deskripsi' => 'required|max_length[1000]',
        'modul' => 'permit_empty|max_length[100]'
      ];
      
      $messages = [
        'kategori' => [
          'required' => 'Kategori harus dipilih',
          'in_list' => 'Kategori tidak valid'
        ],
        'prioritas' => [
          'required' => 'Prioritas harus dipilih', 
          'in_list' => 'Prioritas tidak valid'
        ],
        'judul' => [
          'required' => 'Judul harus diisi',
          'max_length' => 'Judul maksimal 100 karakter'
        ],
        'deskripsi' => [
          'required' => 'Deskripsi harus diisi',
          'max_length' => 'Deskripsi maksimal 1000 karakter'
        ],
        'modul' => [
          'max_length' => 'Modul maksimal 100 karakter'
        ]
      ];
      
      $validation->setRules($rules, $messages);
      
      if (!$validation->withRequest($this->request)->run()) {
        $msg = [
          'success' => false,
          'message' => 'Data tidak valid',
          'errors' => $validation->getErrors()
        ];
        echo json_encode($msg);
        return;
      }
      
      try {
        // Get current user
        $currentUser = $this->userModel->where('username', session()->get('username'))->first();
        
        $data = [
          'kategori' => $this->request->getPost('kategori'),
          'prioritas' => $this->request->getPost('prioritas'),
          'judul' => $this->request->getPost('judul'),
          'deskripsi' => $this->request->getPost('deskripsi'),
          'modul' => $this->request->getPost('modul') ?: null,
          'anonymous' => $this->request->getPost('anonymous') ? 1 : 0,
          'status' => 'Baru',
          'created_by' => $currentUser['id_user']
        ];
        
        $result = $this->feedbackModel->insert($data);
        
        if ($result) {
          // Log activity
          $this->linimasaModel->insert([
            'aktivitas' => 'mengirim saran dan masukan pengembangan aplikasi',
            'keterangan' => 'Kategori: ' . $data['kategori'] . ' - ' . $data['judul'],
            'icon' => 'fas fa-lightbulb bg-warning',
            'created_by' => $currentUser['id_user']
          ]);
          
          $msg = [
            'success' => true,
            'message' => 'Saran dan masukan berhasil dikirim. Terima kasih atas kontribusi Anda!'
          ];
        } else {
          $msg = [
            'success' => false,
            'message' => 'Gagal menyimpan data. Silakan coba lagi.'
          ];
        }
        
      } catch (\Exception $e) {
        log_message('error', 'Error saving feedback: ' . $e->getMessage());
        $msg = [
          'success' => false,
          'message' => 'Terjadi kesalahan sistem. Silakan coba lagi nanti.'
        ];
      }
      
      echo json_encode($msg);
    }
  }
  
  public function feedbackDetail($id)
  {
    if ($this->request->isAJAX()) {
      try {
        // Get current user
        $currentUser = $this->userModel->where('username', session()->get('username'))->first();
        
        // Get feedback detail - only allow user to see their own feedback
        $feedback = $this->feedbackModel
          ->where('id_feedback', $id)
          ->where('created_by', $currentUser['id_user'])
          ->first();
        
        if (!$feedback) {
          return $this->response->setJSON([
            'success' => false,
            'message' => 'Feedback tidak ditemukan atau Anda tidak memiliki akses'
          ]);
        }
        
        $data['feedback'] = $feedback;
        $html = view('home/feedback_detail_user', $data);
        
        return $this->response->setJSON([
          'success' => true,
          'data' => $html
        ]);
        
      } catch (\Exception $e) {
        log_message('error', 'Error getting feedback detail: ' . $e->getMessage());
        return $this->response->setJSON([
          'success' => false,
          'message' => 'Terjadi kesalahan sistem'
        ]);
      }
    }
  }
}

