<?php

namespace App\Controllers\Relaxtime;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Import extends BaseController
{
  // public function realisasi()
  // {
  //   if ($this->request->isAJAX()) {
  //     if ($this->request->getFile('file')) {
  //       $file = $this->request->getFile('file');
  //       $tahun = $this->request->getVar("tahun");
  //       $bulan = $this->request->getVar("bulan");
  //       $fileExtension = $file->getClientExtension();

  //       $allowedExtensions = ['xls', 'csv', 'xlsx'];

  //       if (in_array($fileExtension, $allowedExtensions)) {
  //         $fileName = $file->getRandomName();
  //         $file->move(ROOTPATH . 'writable/uploads', $fileName);

  //         $fileType = IOFactory::identify(ROOTPATH . 'writable/uploads/' . $fileName);
  //         $reader = IOFactory::createReader($fileType);

  //         $spreadsheet = $reader->load(ROOTPATH . 'writable/uploads/' . $fileName);

  //         unlink(ROOTPATH . 'writable/uploads/' . $fileName);

  //         $data = $spreadsheet->getActiveSheet()->toArray();
  //         $by = $this->userModel->where("username", session()->get("username"))->first();

  //         $dataRKT = [];
  //         $kodeDigitKRO = '';
  //         $uraianDigitKRO = '';
  //         $uraianKomponen = '';
  //         $newIDRKT = '';
  //         $newIDKRO = '';
  //         $newIDRO = '';
  //         $newIDKomponen = '';
  //         $newIDUraian = '';
  //         $dataAkhir = [];
  //         $dataAwal = [];
  //         $cekDataInput = false;
  //         $cekas = 0;
  //         foreach ($data as $column) {
  //           if ($column[1] != "" && $column[1] != null) {
  //             $cekArray = explode(".", $column[1]);
  //             if (count($cekArray) == 2) {
  //               $kodeDigitKRO = $cekArray[1];
  //               $uraianDigitKRO = $column[8];
  //             } else {
  //               if ($column[3] != '' && $column[3] != null) {
  //                 $dataRKT = [
  //                   'kode_rkt' => $column[1],
  //                   'deskripsi_rkt' => $column[3],
  //                 ];
  //                 $cekData = $this->relaxtimeRKTModel
  //                   ->where($dataRKT)
  //                   ->first();
  //                 if ($cekData) {
  //                   $newIDRKT = $cekData['id_relaxtime_rkt'];
  //                 } else {
  //                   $dataRKT += [
  //                     'created_by' => $by['id_user'],
  //                     'updated_by' => $by['id_user'],
  //                   ];
  //                   if ($this->relaxtimeRKTModel->save($dataRKT)) {
  //                     $newIDRKT = $this->relaxtimeRKTModel->getInsertID();
  //                   }
  //                 }
  //               }
  //             }
  //           }
  //           if ($column[2] != "" && $column[2] != null) {
  //             $cekArray = explode(".", $column[2]);
  //             if (count($cekArray) == 2) {
  //               $dataRO = [
  //                 'id_relaxtime_kro' => $newIDKRO,
  //                 'kode_relaxtime_ro' => $column[2],
  //                 'deskripsi_ro' => $column[10],
  //                 'tahun' => $tahun,
  //               ];
  //               $cekData = $this->relaxtimeROModel
  //                 ->where($dataRO)
  //                 ->first();
  //               if ($cekData) {
  //                 $newIDRO = $cekData['id_relaxtime_ro'];
  //               } else {
  //                 $dataRO += [
  //                   'created_by' => $by['id_user'],
  //                   'updated_by' => $by['id_user'],
  //                 ];
  //                 if ($this->relaxtimeROModel->save($dataRO)) {
  //                   $newIDRO = $this->relaxtimeROModel->getInsertID();
  //                 }
  //               }
  //             } else {
  //               $dataKRO = [
  //                 'id_relaxtime_rkt' => $newIDRKT,
  //                 'digit_kro' => $kodeDigitKRO,
  //                 'detail_digit_kro' => $uraianDigitKRO,
  //                 'deskripsi_kro' => $column[6],
  //                 'kode_kro' => $column[2],
  //               ];
  //               $cekData = $this->relaxtimeKROModel
  //                 ->where($dataKRO)
  //                 ->first();
  //               if ($cekData) {
  //                 $newIDKRO = $cekData['id_relaxtime_kro'];
  //               } else {
  //                 $dataKRO += [
  //                   'created_by' => $by['id_user'],
  //                   'updated_by' => $by['id_user'],
  //                 ];
  //                 if ($this->relaxtimeKROModel->save($dataKRO)) {
  //                   $newIDKRO = $this->relaxtimeKROModel->getInsertID();
  //                 }
  //               }
  //             }
  //           }

  //           if ($column[4] != "" && $column[4] != null) {
  //             $uraianKomponen = $column[9];
  //           }
  //           if ($column[5] != "" && $column[5] != null) {
  //             $cekArray = explode(".", $column[5]);
  //             if (count($cekArray) == 2) {
  //               $dataKomponen = [
  //                 'id_relaxtime_ro' => $newIDRO,
  //                 'kode_komponen' => $cekArray[0],
  //                 'kode_sub_komponen' => $cekArray[1],
  //                 'deskripsi_komponen' => $uraianKomponen,
  //                 'deskripsi_sub_komponen' => $column[11],
  //               ];
  //               $cekData = $this->relaxtimeKomponenModel->where($dataKomponen)->first();

  //               if ($cekData) {
  //                 $newIDKomponen = $cekData['id_relaxtime_komponen'];
  //               } else {
  //                 $dataKomponen += [
  //                   'created_by' => $by['id_user'],
  //                   'updated_by' => $by['id_user'],
  //                 ];
  //                 if ($this->relaxtimeKomponenModel->save($dataKomponen)) {
  //                   $newIDKomponen = $this->relaxtimeKomponenModel->getInsertID();
  //                 }
  //               }
  //             }
  //           }

  //           if ($column[7] != "" && $column[7] != null) {
  //             $dataUraian = [
  //               'kode_uraian' => $column[7],
  //               'deskripsi_uraian' => $column[12],
  //             ];
  //             $cekData = $this->relaxtimeUraianModel
  //               ->where($dataUraian)
  //               ->first();
  //             if ($cekData) {
  //               $newIDUraian = $cekData['id_relaxtime_uraian'];
  //             } else {
  //               $dataUraian += [
  //                 'created_by' => $by['id_user'],
  //                 'updated_by' => $by['id_user'],
  //               ];
  //               if ($this->relaxtimeUraianModel->save($dataUraian)) {
  //                 $newIDUraian = $this->relaxtimeUraianModel->getInsertID();
  //               }
  //             }
  //           }

  //           if ($column[13] != "" && $column[13] != null) {
  //             $nilaiDariExcel = $column[16];
  //             $nilaiDariExcel = str_replace(',', '', $nilaiDariExcel);
  //             $nilaiDouble = (float) $nilaiDariExcel;


  //             $nilaiRealisasiDariExcel = $column[23];
  //             $nilaiRealisasiDariExcel = str_replace(',', '', $nilaiRealisasiDariExcel);
  //             $nilaiRealisasiDouble = (float) $nilaiRealisasiDariExcel;

  //             $dataDetailUraian = [
  //               'id_relaxtime_uraian' => $newIDUraian,
  //               'id_relaxtime_komponen' => $newIDKomponen,
  //               'deskripsi_detail_uraian' => $column[13],
  //               'tahun' => $tahun,
  //             ];

  //             $cekData = $this->relaxtimeDetailUraianModel->where($dataDetailUraian)->first();
  //             $newID = 0;
  //             if ($cekData) {
  //               if ($cekData['jumlah_detail_uraian'] != $nilaiDouble) {
  //                 $dataDetailUraian = [
  //                   'id_relaxtime_detail_uraian' => $cekData['id_relaxtime_detail_uraian'],
  //                   'jumlah_detail_uraian' => $nilaiDouble,
  //                   'tahun' => $tahun,
  //                 ];
  //                 $this->relaxtimeDetailUraianModel->save($dataDetailUraian);
  //               }
  //               $newID = $cekData['id_relaxtime_detail_uraian'];
  //             } else {
  //               if ($this->relaxtimeDetailUraianModel->save($dataDetailUraian)) {
  //                 $newID = $this->relaxtimeDetailUraianModel->getInsertID();
  //               }
  //             }
  //             if ($nilaiRealisasiDouble != '0' && $nilaiRealisasiDouble != 0) {
  //               $data = [];
  //               $data = [
  //                 'id_relaxtime_detail_uraian' => $newID,
  //                 'bulan' => $bulan,
  //                 'tahun' => $tahun,
  //               ];
  //               $cekData = $this->relaxtimeRealisasiModel->where($data)->first();
  //               if ($cekData) {
  //                 $data = [
  //                   'id_relaxtime_realisasi' => $cekData['id_relaxtime_realisasi'],
  //                   'id_relaxtime_detail_uraian' => $newID,
  //                   'realisasi' => $nilaiRealisasiDouble,
  //                   'bulan' => $bulan,
  //                   'tahun' => $tahun,
  //                   'updated_by' => $by['id_user'],
  //                 ];
  //               } else {
  //                 $data += [
  //                   'realisasi' => $nilaiRealisasiDouble,
  //                   'created_by' => $by['id_user'],
  //                   'updated_by' => $by['id_user'],
  //                 ];
  //               }
  //               $this->relaxtimeRealisasiModel->save($data);
  //             }
  //           }
  //         }
  //         $msg = [
  //           'icon' => "success",
  //           'title' => "Berhasil",
  //           'pesan' => "Data berhasil diperbaharui!",
  //         ];
  //       } else {
  //         $msg = [
  //           'icon' => "warning",
  //           'title' => "Warning",
  //           'pesan' => "Pastikan file bertipe format .xls, .csv, atau .xlsx!"
  //         ];
  //       }
  //     } else {
  //       $msg = [
  //         'icon' => "error",
  //         'title' => "Error",
  //         'pesan' => "Sistem tidak mendeteksi adanya file excel!"
  //       ];
  //     }

  //     echo json_encode($msg);
  //   }
  // }
  public function realisasi()
  {
    if ($this->request->isAJAX()) {
      if ($this->request->getFile('file')) {
        $file = $this->request->getFile('file');
        $tahun = $this->request->getVar("tahun");
        $bulan = $this->request->getVar("bulan");
        $fileExtension = $file->getClientExtension();

        $allowedExtensions = ['xls', 'csv', 'xlsx'];

        if (in_array($fileExtension, $allowedExtensions)) {
          $fileName = $file->getRandomName();
          $file->move(ROOTPATH . 'writable/uploads', $fileName);

          $fileType = IOFactory::identify(ROOTPATH . 'writable/uploads/' . $fileName);
          $reader = IOFactory::createReader($fileType);

          $spreadsheet = $reader->load(ROOTPATH . 'writable/uploads/' . $fileName);

          unlink(ROOTPATH . 'writable/uploads/' . $fileName);

          $data = $spreadsheet->getActiveSheet()->toArray();
          $by = $this->userModel->where("username", session()->get("username"))->first();

          $dataRKT = [];
          $kodeDigitKRO = '';
          $uraianDigitKRO = '';
          $uraianKomponen = '';
          $newIDRKT = '';
          $newIDKRO = '';
          $newIDRO = '';
          $newIDKomponen = '';
          $newIDUraian = '';
          $dataAkhir = [];
          $dataAwal = [];
          $cekDataInput = false;
          $cekas = 0;
          $colom = 0;
          foreach ($data as $column) {
            $colom++;
            if ($column[1] != "" && $column[1] != null) {
              $cekArray = explode(".", $column[1]);
              if (count($cekArray) == 2) {
                $kodeDigitKRO = $cekArray[1];
                $uraianDigitKRO = $column[8];
              } else {
                if ($column[3] != '' && $column[3] != null) {
                  $dataRKT = [
                    'kode_rkt' => $column[1],
                  ];
                  $cekData = $this->relaxtimeRKTModel
                    ->where($dataRKT)
                    ->first();
                  if ($cekData) {
                    $newIDRKT = $cekData['id_relaxtime_rkt'];
                  } else {
                    $dataRKT += [
                      'deskripsi_rkt' => $column[3],
                      'created_by' => $by['id_user'],
                      'updated_by' => $by['id_user'],
                    ];
                    if ($this->relaxtimeRKTModel->save($dataRKT)) {
                      $newIDRKT = $this->relaxtimeRKTModel->getInsertID();
                    }
                  }
                }
              }
            }
            if ($column[2] != "" && $column[2] != null) {
              $cekArray = explode(".", $column[2]);
              if (count($cekArray) == 2) {
                $dataRO = [
                  'id_relaxtime_kro' => $newIDKRO,
                  'kode_relaxtime_ro' => $column[2],
                  'tahun' => $tahun,
                ];
                $cekData = $this->relaxtimeROModel
                  ->where($dataRO)
                  ->first();
                if ($cekData) {
                  $newIDRO = $cekData['id_relaxtime_ro'];
                } else {
                  $dataRO += [
                    'deskripsi_ro' => $column[10],
                    'created_by' => $by['id_user'],
                    'updated_by' => $by['id_user'],
                  ];
                  if ($this->relaxtimeROModel->save($dataRO)) {
                    $newIDRO = $this->relaxtimeROModel->getInsertID();
                  }
                }
              } else {
                $dataKRO = [
                  'id_relaxtime_rkt' => $newIDRKT,
                  'digit_kro' => $kodeDigitKRO,
                  'kode_kro' => $column[2],
                ];
                $cekData = $this->relaxtimeKROModel
                  ->where($dataKRO)
                  ->first();
                if ($cekData) {
                  $newIDKRO = $cekData['id_relaxtime_kro'];
                } else {
                  $dataKRO += [
                    'detail_digit_kro' => $uraianDigitKRO,
                    'deskripsi_kro' => $column[6],
                    'created_by' => $by['id_user'],
                    'updated_by' => $by['id_user'],
                  ];
                  if ($this->relaxtimeKROModel->save($dataKRO)) {
                    $newIDKRO = $this->relaxtimeKROModel->getInsertID();
                  }
                }
              }
            }
            if ($column[4] != "" && $column[4] != null) {
              $uraianKomponen = $column[9];
            }
            if ($column[5] != "" && $column[5] != null) {
              $cekArray = explode(".", $column[5]);
              if (count($cekArray) == 2) {
                $dataKomponen = [
                  'id_relaxtime_ro' => $newIDRO,
                  'kode_komponen' => $cekArray[0],
                  'kode_sub_komponen' => $cekArray[1],
                ];
                $cekData = $this->relaxtimeKomponenModel->where($dataKomponen)->first();
                if ($cekData) {
                  $newIDKomponen = $cekData['id_relaxtime_komponen'];
                } else {
                  $dataKomponen += [
                    'deskripsi_komponen' => $uraianKomponen,
                    'deskripsi_sub_komponen' => $column[11],
                    'created_by' => $by['id_user'],
                    'updated_by' => $by['id_user'],
                  ];
                  if ($this->relaxtimeKomponenModel->save($dataKomponen)) {
                    $newIDKomponen = $this->relaxtimeKomponenModel->getInsertID();
                  }
                }
              }
            }

            if ($column[7] != "" && $column[7] != null) {
              $dataUraian = [
                'kode_uraian' => $column[7],
              ];
              $cekData = $this->relaxtimeUraianModel
                ->where($dataUraian)
                ->first();
              if ($cekData) {
                $newIDUraian = $cekData['id_relaxtime_uraian'];
              } else {
                $dataUraian += [
                  'deskripsi_uraian' => $column[12],
                  'created_by' => $by['id_user'],
                  'updated_by' => $by['id_user'],
                ];
                if ($this->relaxtimeUraianModel->save($dataUraian)) {
                  $newIDUraian = $this->relaxtimeUraianModel->getInsertID();
                }
              }
            }

            if ($column[13] != "" && $column[13] != null) {
              $nilaiDariExcel = $column[16];
              $nilaiDariExcel = str_replace(',', '', $nilaiDariExcel);
              $nilaiDouble = (float) $nilaiDariExcel;


              $nilaiRealisasiDariExcel = $column[23];
              $nilaiRealisasiDariExcel = str_replace(',', '', $nilaiRealisasiDariExcel);
              $nilaiRealisasiDouble = (float) $nilaiRealisasiDariExcel;

              $dataDetailUraian = [
                'id_relaxtime_uraian' => $newIDUraian,
                'id_relaxtime_komponen' => $newIDKomponen,
                'deskripsi_detail_uraian' => $column[13],
                'tahun' => $tahun,
              ];

              $cekData = $this->relaxtimeDetailUraianModel->where($dataDetailUraian)->first();
              $newID = 0;
              if ($cekData) {
                if ($cekData['jumlah_detail_uraian'] != $nilaiDouble) {
                  $dataDetailUraian = [
                    'id_relaxtime_detail_uraian' => $cekData['id_relaxtime_detail_uraian'],
                    'jumlah_detail_uraian' => $nilaiDouble,
                    'tahun' => $tahun,
                  ];
                  $this->relaxtimeDetailUraianModel->save($dataDetailUraian);
                }
                $newID = $cekData['id_relaxtime_detail_uraian'];
              } else {
                if ($this->relaxtimeDetailUraianModel->save($dataDetailUraian)) {
                  $newID = $this->relaxtimeDetailUraianModel->getInsertID();
                }
              }
              if ($nilaiRealisasiDouble != '0' && $nilaiRealisasiDouble != 0) {
                $data = [];
                $data = [
                  'id_relaxtime_detail_uraian' => $newID,
                  'bulan' => $bulan,
                  'tahun' => $tahun,
                ];
                $cekData = $this->relaxtimeRealisasiModel->where($data)->first();
                if ($cekData) {
                  $data = [
                    'id_relaxtime_realisasi' => $cekData['id_relaxtime_realisasi'],
                    'id_relaxtime_detail_uraian' => $newID,
                    'realisasi' => $nilaiRealisasiDouble,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'updated_by' => $by['id_user'],
                  ];
                } else {
                  $data += [
                    'realisasi' => $nilaiRealisasiDouble,
                    'created_by' => $by['id_user'],
                    'updated_by' => $by['id_user'],
                  ];
                }
                $this->relaxtimeRealisasiModel->save($data);
              }
            }
          }
          // dd("berhasil");
          $msg = [
            'icon' => "success",
            'title' => "Berhasil",
            'pesan' => "Data berhasil diperbaharui!",
          ];
        } else {
          $msg = [
            'icon' => "warning",
            'title' => "Warning",
            'pesan' => "Pastikan file bertipe format .xls, .csv, atau .xlsx!"
          ];
        }
      } else {
        $msg = [
          'icon' => "error",
          'title' => "Error",
          'pesan' => "Sistem tidak mendeteksi adanya file excel!"
        ];
      }
      // dd();
      echo json_encode($msg);
    }
  }
  public function pagu()
  {
    if ($this->request->isAJAX()) {
      $msg = [];

      $tahun = $this->request->getVar("tahun");
      $cek1 = $this->relaxtimeROModel->where('tahun', $tahun)->findAll();
      $cek2 = $this->relaxtimeDetailUraianModel->where('tahun', $tahun)->findAll();
      if ($cek1 || $cek2) {
        $msg = [
          'icon' => "warning",
          'title' => "Warning",
          'pesan' => "Data dengan tahun " . $tahun . " sudah ada dalam sistem!"
        ];
      } else {

        if ($this->request->getFile('file')) {
          $file = $this->request->getFile('file');
          $fileExtension = $file->getClientExtension();

          $allowedExtensions = ['xls', 'csv', 'xlsx'];

          if (in_array($fileExtension, $allowedExtensions)) {
            $fileName = $file->getRandomName();
            $file->move(ROOTPATH . 'writable/uploads', $fileName);

            $fileType = IOFactory::identify(ROOTPATH . 'writable/uploads/' . $fileName);
            $reader = IOFactory::createReader($fileType);

            $spreadsheet = $reader->load(ROOTPATH . 'writable/uploads/' . $fileName);

            unlink(ROOTPATH . 'writable/uploads/' . $fileName);

            $data = $spreadsheet->getActiveSheet()->toArray();

            // dd($data);
            $by = $this->userModel->where("username", session()->get("username"))->first();

            $dataRKT = [];
            $kodeDigitKRO = '';
            $uraianDigitKRO = '';
            $uraianKomponen = '';
            $newIDRKT = '';
            $newIDKRO = '';
            $newIDRO = '';
            $newIDKomponen = '';
            $newIDUraian = '';
            foreach ($data as $column) {
              if ($column[1] != "" && $column[1] != null) {
                $cekArray = explode(".", $column[1]);
                if (count($cekArray) == 2) {
                  $kodeDigitKRO = $cekArray[1];
                  $uraianDigitKRO = $column[8];
                } else {
                  if ($column[3] != '' && $column[3] != null) {
                    $dataRKT = [
                      'kode_rkt' => $column[1],
                    ];
                    $cekData = $this->relaxtimeRKTModel
                      ->where($dataRKT)
                      ->first();
                    if ($cekData) {
                      $newIDRKT = $cekData['id_relaxtime_rkt'];
                    } else {
                      $dataRKT += [
                        'deskripsi_rkt' => $column[3],
                        'created_by' => $by['id_user'],
                        'updated_by' => $by['id_user'],
                      ];
                      if ($this->relaxtimeRKTModel->save($dataRKT)) {
                        $newIDRKT = $this->relaxtimeRKTModel->getInsertID();
                      }
                    }
                  }
                }
              }
              if ($column[2] != "" && $column[2] != null) {
                $cekArray = explode(".", $column[2]);
                if (count($cekArray) == 2) {
                  $dataRO = [
                    'id_relaxtime_kro' => $newIDKRO,
                    'kode_relaxtime_ro' => $column[2],
                    'tahun' => $tahun,
                  ];
                  $cekData = $this->relaxtimeROModel
                    ->where($dataRO)
                    ->first();
                  if ($cekData) {
                    $newIDRO = $cekData['id_relaxtime_ro'];
                  } else {
                    $dataRO += [
                      'deskripsi_ro' => $column[10],
                      'vol_ro' => 0,
                      'sat_ro' => "-",
                      'created_by' => $by['id_user'],
                      'updated_by' => $by['id_user'],
                    ];
                    if ($this->relaxtimeROModel->save($dataRO)) {
                      $newIDRO = $this->relaxtimeROModel->getInsertID();
                    }
                  }
                } else {
                  $dataKRO = [
                    'id_relaxtime_rkt' => $newIDRKT,
                    'digit_kro' => $kodeDigitKRO,
                    'kode_kro' => $column[2],
                  ];
                  $cekData = $this->relaxtimeKROModel
                    ->where($dataKRO)
                    ->first();
                  if ($cekData) {
                    $newIDKRO = $cekData['id_relaxtime_kro'];
                  } else {
                    $dataKRO += [
                      'detail_digit_kro' => $uraianDigitKRO,
                      'deskripsi_kro' => $column[6],
                      'created_by' => $by['id_user'],
                      'updated_by' => $by['id_user'],
                    ];
                    if ($this->relaxtimeKROModel->save($dataKRO)) {
                      $newIDKRO = $this->relaxtimeKROModel->getInsertID();
                    }
                  }
                }
              }

              if ($column[4] != "" && $column[4] != null) {
                $uraianKomponen = $column[9];
              }
              if ($column[5] != "" && $column[5] != null) {
                $cekArray = explode(".", $column[5]);
                if (count($cekArray) == 2) {
                  $dataKomponen = [
                    'id_relaxtime_ro' => $newIDRO,
                    'kode_komponen' => $cekArray[0],
                    'kode_sub_komponen' => $cekArray[1],
                  ];
                  $cekData = $this->relaxtimeKomponenModel
                    ->where($dataKomponen)
                    ->first();
                  if ($cekData) {
                    $newIDKomponen = $cekData['id_relaxtime_komponen'];
                  } else {
                    $dataKomponen += [
                      'deskripsi_komponen' => $uraianKomponen,
                      'deskripsi_sub_komponen' => $column[11],
                      'created_by' => $by['id_user'],
                      'updated_by' => $by['id_user'],
                    ];
                    if ($this->relaxtimeKomponenModel->save($dataKomponen)) {
                      $newIDKomponen = $this->relaxtimeKomponenModel->getInsertID();
                    }
                  }
                }
              }

              if ($column[7] != "" && $column[7] != null) {
                $dataUraian = [
                  'kode_uraian' => $column[7],
                ];
                $cekData = $this->relaxtimeUraianModel
                  ->where($dataUraian)
                  ->first();
                if ($cekData) {
                  $newIDUraian = $cekData['id_relaxtime_uraian'];
                } else {
                  $dataUraian += [
                    'deskripsi_uraian' => $column[12],
                    'created_by' => $by['id_user'],
                    'updated_by' => $by['id_user'],
                  ];
                  if ($this->relaxtimeUraianModel->save($dataUraian)) {
                    $newIDUraian = $this->relaxtimeUraianModel->getInsertID();
                  }
                }
              }


              if ($column[13] != "" && $column[13] != null) {
                $nilaiDariExcel = $column[16];
                $nilaiDariExcel = str_replace(',', '', $nilaiDariExcel);
                $nilaiDouble = (float) $nilaiDariExcel;

                $dataDetailUraian = [
                  'id_relaxtime_uraian' => $newIDUraian,
                  'id_relaxtime_komponen' => $newIDKomponen,
                  'deskripsi_detail_uraian' => $column[13],
                  'vol_detail_uraian' => 0,
                  'sat_detail_uraian' => '-',
                  'harga_detail_uraian' => 0,
                  'jumlah_detail_uraian' => $nilaiDouble,
                  'tahun' => $tahun,
                  'created_by' => $by['id_user'],
                  'updated_by' => $by['id_user'],
                ];
                if ($this->relaxtimeDetailUraianModel->save($dataDetailUraian)) {
                  // Berhasil
                }
              }
            }
            $msg = [
              'icon' => "success",
              'title' => "Berhasil",
              'pesan' => "Data berhasil diperbaharui!"
            ];
          } else {
            $msg = [
              'icon' => "warning",
              'title' => "Warning",
              'pesan' => "Pastikan file bertipe format .xls, .csv, atau .xlsx!"
            ];
          }
        } else {
          $msg = [
            'icon' => "error",
            'title' => "Error",
            'pesan' => "Sistem tidak mendeteksi adanya file excel!"
          ];
        }
      }


      echo json_encode($msg);
    }
  }
  public function paguBackup()
  {
    if ($this->request->isAJAX()) {
      $msg = [];

      $tahun = $this->request->getVar("tahun");
      $cek1 = $this->relaxtimeROModel->where('tahun', $tahun)->findAll();
      $cek2 = $this->relaxtimeDetailUraianModel->where('tahun', $tahun)->findAll();
      if ($cek1 || $cek2) {
        $msg = [
          'icon' => "warning",
          'title' => "Warning",
          'pesan' => "Data dengan tahun " . $tahun . " sudah ada dalam sistem!"
        ];
      } else {

        if ($this->request->getFile('file')) {
          $file = $this->request->getFile('file');
          $fileExtension = $file->getClientExtension();

          $allowedExtensions = ['xls', 'csv', 'xlsx'];

          if (in_array($fileExtension, $allowedExtensions)) {
            $fileName = $file->getRandomName();
            $file->move(ROOTPATH . 'writable/uploads', $fileName);

            $fileType = IOFactory::identify(ROOTPATH . 'writable/uploads/' . $fileName);
            $reader = IOFactory::createReader($fileType);

            $spreadsheet = $reader->load(ROOTPATH . 'writable/uploads/' . $fileName);

            unlink(ROOTPATH . 'writable/uploads/' . $fileName);

            $data = $spreadsheet->getActiveSheet()->toArray();

            // dd($data);
            $by = $this->userModel->where("username", session()->get("username"))->first();
            $created_by = $by['id_user'];
            $updated_by = $by['id_user'];
            $id_relaxtime_rkt = null;
            $id_relaxtime_kro = null;
            $id_relaxtime_komponen = null;
            $id_relaxtime_uraian = null;
            $id_relaxtime_ro = null;
            $digit_kro = null;
            $detail_digit_kro = "";
            $kode_komponen = null;
            $deskripsi_komponen = "";
            $getUpdate = [];
            foreach ($data as $row) {
              $colomA = $row[0];
              $colomB = $row[1];
              $colomC = $row[2];
              $colomD = $row[3];
              $colomE = $row[4];
              $colomF = $row[5];

              $isArrayColomA = explode(".", $colomA);
              if ($colomA == "" && $colomC == "" && $colomD == "" && $colomE == "") {
                // Tidak usah melakukan apa-apa
              } elseif (count($isArrayColomA) == 3 && $colomC == "" && $colomD == "" && $colomE == "") {
                // RKT
                $data = [
                  'kode_rkt'      => $colomA,
                  'deskripsi_rkt' => $colomB,
                  'created_by'    => $created_by,
                  'updated_by'    => $updated_by,
                ];

                // Mencari data yang sudah ada berdasarkan beberapa kolom
                $existingData = $this->relaxtimeRKTModel->where([
                  'kode_rkt'      => $colomA,
                  'deskripsi_rkt' => $colomB,
                  // Tambahkan kolom-kolom lain yang perlu dijadikan kriteria unik
                ])->first();

                // Jika data sudah ada, ambil ID-nya
                if ($existingData) {
                  $id_relaxtime_rkt = $existingData['id_relaxtime_rkt'];
                } else {
                  // Jika tidak ada, lakukan penyimpanan baru
                  if ($this->relaxtimeRKTModel->save($data)) {
                    $id_relaxtime_rkt = $this->relaxtimeRKTModel->getInsertID();
                  }
                }
                // if ($this->relaxtimeRKTModel->save($data))
                //   $id_relaxtime_rkt = $this->relaxtimeRKTModel->getInsertID();
              } elseif (strlen($colomA) == 4 && ctype_digit($colomA) && $colomC == "" && $colomD == "" && $colomE == "") {
                // KRO - Digit

                $digit_kro = $colomA;
                $detail_digit_kro = $colomB;
              } elseif (count($isArrayColomA) == 2 && $colomE == "") {
                // KRO - 2 Array
                $data = [
                  'id_relaxtime_rkt'   => $id_relaxtime_rkt,
                  'digit_kro'          => $digit_kro,
                  'detail_digit_kro'   => $detail_digit_kro,
                  'kode_kro'           => $isArrayColomA[1],
                  'deskripsi_kro'      => $colomB,
                  'created_by'         => $created_by,
                  'updated_by'         => $updated_by,
                ];

                // Mencari data yang sudah ada berdasarkan beberapa kolom
                $existingData = $this->relaxtimeKROModel->where([
                  'id_relaxtime_rkt'   => $id_relaxtime_rkt,
                  'digit_kro'          => $digit_kro,
                  'detail_digit_kro'   => $detail_digit_kro,
                  'kode_kro'           => $isArrayColomA[1],
                  'deskripsi_kro'      => $colomB,
                  // Tambahkan kolom-kolom lain yang perlu dijadikan kriteria unik
                ])->first();

                // Jika data sudah ada, ambil ID-nya
                if ($existingData) {
                  $id_relaxtime_kro = $existingData['id_relaxtime_kro'];
                } else {
                  // Jika tidak ada, lakukan penyimpanan baru
                  if ($this->relaxtimeKROModel->save($data)) {
                    $id_relaxtime_kro = $this->relaxtimeKROModel->getInsertID();
                  }
                }
                // if ($this->relaxtimeKROModel->save($data))
                //   $id_relaxtime_kro = $this->relaxtimeKROModel->getInsertID();
              } elseif (count($isArrayColomA) == 3 && $colomE == "") {
                // RO - 3 Array
                $data = [
                  'id_relaxtime_kro'    => $id_relaxtime_kro,
                  'kode_relaxtime_ro'   => $isArrayColomA[2],
                  'deskripsi_ro'        => $colomB,
                  'vol_ro'              => $colomC,
                  'sat_ro'              => $colomD,
                  'tahun'               => $tahun,
                  'created_by'          => $created_by,
                  'updated_by'          => $updated_by,
                ];

                // Mencari data yang sudah ada berdasarkan beberapa kolom
                $existingData = $this->relaxtimeROModel->where([
                  'id_relaxtime_kro'    => $id_relaxtime_kro,
                  'kode_relaxtime_ro'   => $isArrayColomA[2],
                  'deskripsi_ro'        => $colomB,
                  'vol_ro'              => $colomC,
                  'sat_ro'              => $colomD,
                  'tahun'               => $tahun,
                  // Tambahkan kolom-kolom lain yang perlu dijadikan kriteria unik
                ])->first();

                // Jika data sudah ada, ambil ID-nya
                if ($existingData) {
                  $id_relaxtime_ro = $existingData['id_relaxtime_ro'];
                } else {
                  // Jika tidak ada, lakukan penyimpanan baru
                  if ($this->relaxtimeROModel->save($data)) {
                    $id_relaxtime_ro = $this->relaxtimeROModel->getInsertID();
                  }
                }
                // if ($this->relaxtimeROModel->save($data))
                //   $id_relaxtime_ro = $this->relaxtimeROModel->getInsertID();
              } elseif (strlen($colomA) == 1 && $colomC == "" && $colomD == "" && $colomE == "") {
                // Sub-Komponen
                $data = [
                  'id_relaxtime_ro'         => $id_relaxtime_ro,
                  'kode_komponen'           => $kode_komponen,
                  'kode_sub_komponen'       => $colomA,
                  'deskripsi_komponen'      => $deskripsi_komponen,
                  'deskripsi_sub_komponen'  => $colomB,
                  'created_by'              => $created_by,
                  'updated_by'              => $updated_by,
                ];

                // Mencari data yang sudah ada berdasarkan beberapa kolom
                $existingData = $this->relaxtimeKomponenModel->where([
                  'id_relaxtime_ro'         => $id_relaxtime_ro,
                  'kode_komponen'           => $kode_komponen,
                  'kode_sub_komponen'       => $colomA,
                  'deskripsi_komponen'      => $deskripsi_komponen,
                  'deskripsi_sub_komponen'  => $colomB,
                  // Tambahkan kolom-kolom lain yang perlu dijadikan kriteria unik
                ])->first();

                // Jika data sudah ada, ambil ID-nya
                if ($existingData) {
                  $id_relaxtime_komponen = $existingData['id_relaxtime_komponen'];
                } else {
                  // Jika tidak ada, lakukan penyimpanan baru
                  if ($this->relaxtimeKomponenModel->save($data)) {
                    $id_relaxtime_komponen = $this->relaxtimeKomponenModel->getInsertID();
                  }
                }
                // if ($this->relaxtimeKomponenModel->save($data))
                //   $id_relaxtime_komponen = $this->relaxtimeKomponenModel->getInsertID();
              } elseif (ctype_digit($colomA) && $colomC == "" && $colomD == "" && $colomE == "") {
                // uraian - Baiknya simpan pada variable
                $data = [
                  'kode_uraian'       => $colomA,
                  'deskripsi_uraian'  => $colomB,
                  'created_by'        => $created_by,
                  'updated_by'        => $updated_by,
                ];

                // Mencari data yang sudah ada berdasarkan beberapa kolom
                $existingData = $this->relaxtimeUraianModel->where([
                  'kode_uraian'       => $colomA,
                  'deskripsi_uraian'  => $colomB,
                  // Tambahkan kolom-kolom lain yang perlu dijadikan kriteria unik
                ])->first();

                // Jika data sudah ada, ambil ID-nya
                if ($existingData) {
                  $id_relaxtime_uraian = $existingData['id_relaxtime_uraian'];
                } else {
                  // Jika tidak ada, lakukan penyimpanan baru
                  if ($this->relaxtimeUraianModel->save($data)) {
                    $id_relaxtime_uraian = $this->relaxtimeUraianModel->getInsertID();
                  }
                }
                // if ($this->relaxtimeUraianModel->save($data))
                //   $id_relaxtime_uraian = $this->relaxtimeUraianModel->getInsertID();
              } elseif (ctype_digit($colomA) && $colomD == "" && $colomE == "") {
                // Komponen
                $kode_komponen = $colomA;
                $deskripsi_komponen = $colomB;
              }
              if ($colomA == "" && $colomB != "" && $colomC != "" && $colomD != "" && $colomE != "") {
                // Data Uraian
                $data = [
                  'id_relaxtime_uraian'     => $id_relaxtime_uraian,
                  'id_relaxtime_komponen'   => $id_relaxtime_komponen,
                  'deskripsi_detail_uraian' => $colomB,
                  'vol_detail_uraian'       => $colomC,
                  'sat_detail_uraian'       => $colomD,
                  'harga_detail_uraian'     => $colomE,
                  'jumlah_detail_uraian'    => $colomF,
                  'tahun'                   => $tahun,
                  'created_by'              => $created_by,
                  'updated_by'              => $updated_by,
                ];
                if ($this->relaxtimeDetailUraianModel->save($data)) {
                }
              }

              // echo "<tr>";
              // echo "<td>" . $row[0] . "</td>";
              // echo "<td>" . $row[1] . "</td>";
              // echo "<td>" . $row[2] . "</td>";
              // echo "<td>" . $row[3] . "</td>";
              // echo "<td>" . $row[4] . "</td>";
              // echo "<td>" . $row[5] . "</td>";
              // echo "</tr>";
            }
            $msg = [
              'icon' => "success",
              'title' => "Berhasil",
              'pesan' => "Data berhasil diperbaharui!"
            ];
          } else {
            $msg = [
              'icon' => "warning",
              'title' => "Warning",
              'pesan' => "Pastikan file bertipe format .xls, .csv, atau .xlsx!"
            ];
          }
        } else {
          $msg = [
            'icon' => "error",
            'title' => "Error",
            'pesan' => "Sistem tidak mendeteksi adanya file excel!"
          ];
        }
      }


      echo json_encode($msg);
    }
  }
}
