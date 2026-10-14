<?php

namespace App\Controllers;

class Infografis extends BaseController
{
    public function dashboard_kedatangan_kapal_rekapitulasi() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $header['head'] = "Rekapitulasi Keseluruhan Data Jawa Barat";
        $data['getData'] = $this->infografisKapalModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_kedatangan_kapal", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_kedatangan_kapal_per_wilker() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $head = "";
        $data_view = $this->request->getVar("data_view");
    
        if ($data_view == "hari_kemarin") {
          $head = "Data Infografis Kemarin di";
        } else if ($data_view == "bulan_ini_dan_sebelumnya") {
          $head = "Data Infografis Bulan Kemarin sampai Saat Ini di";
        } else {
          $head = "Rekapitulasi Data Infografis mulai 2023 sampai saat ini di";
        }
    
        $unit_kerja = $this->request->getVar("unit_kerja");
    
        $header['head'] = $head . ' (' . $unit_kerja . ')';
        $data['getData'] = $this->infografisKapalModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_kedatangan_kapal", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_kedatangan_kapal_bulan_ini_dan_sebelumnya() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $header['head'] = "Data Infografis Bulan Kemarin sampai Saat Ini di Jawa Barat";
        $data['getData'] = $this->infografisKapalModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_kedatangan_kapal", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_kedatangan_kapal_hari_kemarin() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $header['head'] = "Data Infografis Kemarin di Jawa Barat";
        $data['getData'] = $this->infografisKapalModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_kedatangan_kapal", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_keberangkatan_kapal_rekapitulasi() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $header['head'] = "Rekapitulasi Keseluruhan Data Jawa Barat";
        $data['getData'] = $this->infografisKapalModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_keberangkatan_kapal", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_keberangkatan_kapal_per_wilker() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $head = "";
        $data_view = $this->request->getVar("data_view");
    
        if ($data_view == "hari_kemarin") {
          $head = "Data Infografis Kemarin di";
        } else if ($data_view == "bulan_ini_dan_sebelumnya") {
          $head = "Data Infografis Bulan Kemarin sampai Saat Ini di";
        } else {
          $head = "Rekapitulasi Data Infografis mulai 2023 sampai saat ini di";
        }
    
        $unit_kerja = $this->request->getVar("unit_kerja");
    
        $header['head'] = $head . ' (' . $unit_kerja . ')';
        $data['getData'] = $this->infografisKapalModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_keberangkatan_kapal", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_keberangkatan_kapal_bulan_ini_dan_sebelumnya() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $header['head'] = "Data Infografis Bulan Kemarin sampai Saat Ini di Jawa Barat";
        $data['getData'] = $this->infografisKapalModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_keberangkatan_kapal", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_keberangkatan_kapal_hari_kemarin() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $header['head'] = "Data Infografis Kemarin di Jawa Barat";
        $data['getData'] = $this->infografisKapalModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_keberangkatan_kapal", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_kedatangan_pesawat_rekapitulasi() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $header['head'] = "Rekapitulasi Keseluruhan Data Jawa Barat";
        $data['getData'] = $this->infografisPesawatModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_kedatangan_pesawat", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_kedatangan_pesawat_per_wilker() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $head = "";
        $data_view = $this->request->getVar("data_view");
    
        if ($data_view == "hari_kemarin") {
          $head = "Data Infografis Kemarin di";
        } else if ($data_view == "bulan_ini_dan_sebelumnya") {
          $head = "Data Infografis Bulan Kemarin sampai Saat Ini di";
        } else {
          $head = "Rekapitulasi Data Infografis mulai 2023 sampai saat ini di";
        }
    
        $unit_kerja = $this->request->getVar("unit_kerja");
    
        $header['head'] = $head . ' (' . $unit_kerja . ')';
        $data['getData'] = $this->infografisPesawatModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_kedatangan_pesawat", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_kedatangan_pesawat_bulan_ini_dan_sebelumnya() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $header['head'] = "Data Infografis Bulan Kemarin sampai Saat Ini di Jawa Barat";
        $data['getData'] = $this->infografisPesawatModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_kedatangan_pesawat", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_kedatangan_pesawat_hari_kemarin() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $header['head'] = "Data Infografis Kemarin di Jawa Barat";
        $data['getData'] = $this->infografisPesawatModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_kedatangan_pesawat", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_keberangkatan_pesawat_rekapitulasi() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $header['head'] = "Rekapitulasi Keseluruhan Data Jawa Barat";
        $data['getData'] = $this->infografisPesawatModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_keberangkatan_pesawat", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_keberangkatan_pesawat_per_wilker() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $head = "";
        $data_view = $this->request->getVar("data_view");
    
        if ($data_view == "hari_kemarin") {
          $head = "Data Infografis Kemarin di";
        } else if ($data_view == "bulan_ini_dan_sebelumnya") {
          $head = "Data Infografis Bulan Kemarin sampai Saat Ini di";
        } else {
          $head = "Rekapitulasi Data Infografis mulai 2023 sampai saat ini di";
        }
    
        $unit_kerja = $this->request->getVar("unit_kerja");
    
        $header['head'] = $head . ' (' . $unit_kerja . ')';
        $data['getData'] = $this->infografisPesawatModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_keberangkatan_pesawat", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_keberangkatan_pesawat_bulan_ini_dan_sebelumnya() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $header['head'] = "Data Infografis Bulan Kemarin sampai Saat Ini di Jawa Barat";
        $data['getData'] = $this->infografisPesawatModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_keberangkatan_pesawat", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_keberangkatan_pesawat_hari_kemarin() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $header['head'] = "Data Infografis Kemarin di Jawa Barat";
        $data['getData'] = $this->infografisPesawatModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_keberangkatan_pesawat", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_dokumen_rekapitulasi() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $header['head'] = "Rekapitulasi Keseluruhan Data Jawa Barat";
        $data['getData'] = $this->infografisVaksinModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_dokumen", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_dokumen_per_wilker() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $head = "";
        $data_view = $this->request->getVar("data_view");
    
        if ($data_view == "hari_kemarin") {
          $head = "Data Infografis Kemarin di";
        } else if ($data_view == "bulan_ini_dan_sebelumnya") {
          $head = "Data Infografis Bulan Kemarin sampai Saat Ini di";
        } else {
          $head = "Rekapitulasi Data Infografis mulai 2023 sampai saat ini di";
        }
    
        $unit_kerja = $this->request->getVar("unit_kerja");
    
        $header['head'] = $head . ' (' . $unit_kerja . ')';
        $data['getData'] = $this->infografisVaksinModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_dokumen", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_dokumen_bulan_ini_dan_sebelumnya() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $header['head'] = "Data Infografis Bulan Kemarin sampai Saat Ini di Jawa Barat";
        $data['getData'] = $this->infografisVaksinModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_dokumen", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_dokumen_hari_kemarin() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $header['head'] = "Data Infografis Kemarin di Jawa Barat";
        $data['getData'] = $this->infografisVaksinModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_dokumen", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_sertifikat_rekapitulasi() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $header['head'] = "Rekapitulasi Keseluruhan Data Jawa Barat";
        $data['getData'] = $this->infografisVaksinModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_sertifikat", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_sertifikat_per_wilker() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $head = "";
        $data_view = $this->request->getVar("data_view");
    
        if ($data_view == "hari_kemarin") {
          $head = "Data Infografis Kemarin di";
        } else if ($data_view == "bulan_ini_dan_sebelumnya") {
          $head = "Data Infografis Bulan Kemarin sampai Saat Ini di";
        } else {
          $head = "Rekapitulasi Data Infografis mulai 2023 sampai saat ini di";
        }
    
        $unit_kerja = $this->request->getVar("unit_kerja");
    
        $header['head'] = $head . ' (' . $unit_kerja . ')';
        $data['getData'] = $this->infografisVaksinModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_sertifikat", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_sertifikat_bulan_ini_dan_sebelumnya() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $header['head'] = "Data Infografis Bulan Kemarin sampai Saat Ini di Jawa Barat";
        $data['getData'] = $this->infografisVaksinModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_sertifikat", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_sertifikat_hari_kemarin() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $header['head'] = "Data Infografis Kemarin di Jawa Barat";
        $data['getData'] = $this->infografisVaksinModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_sertifikat", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_vaksinasi_rekapitulasi() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $header['head'] = "Rekapitulasi Keseluruhan Data Jawa Barat";
        $data['getData'] = $this->infografisVaksinModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_vaksinasi", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_vaksinasi_per_wilker() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $head = "";
        $data_view = $this->request->getVar("data_view");
    
        if ($data_view == "hari_kemarin") {
          $head = "Data Infografis Kemarin di";
        } else if ($data_view == "bulan_ini_dan_sebelumnya") {
          $head = "Data Infografis Bulan Kemarin sampai Saat Ini di";
        } else {
          $head = "Rekapitulasi Data Infografis mulai 2023 sampai saat ini di";
        }
    
        $unit_kerja = $this->request->getVar("unit_kerja");
    
        $header['head'] = $head . ' (' . $unit_kerja . ')';
        $data['getData'] = $this->infografisVaksinModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_vaksinasi", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_vaksinasi_bulan_ini_dan_sebelumnya() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $header['head'] = "Data Infografis Bulan Kemarin sampai Saat Ini di Jawa Barat";
        $data['getData'] = $this->infografisVaksinModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_vaksinasi", $data),
          'head' => $header
        ];
      }
    }
    public function dashboard_vaksinasi_hari_kemarin() {
      if ($this->request->isAJAX()) {
        $data['getData'] = array();
        $header['head'] = "Data Infografis Kemarin di Jawa Barat";
        $data['getData'] = $this->infografisVaksinModel->first();
    
        $msg = [
          'data' => view("infografis/dashboard_vaksinasi", $data),
          'head' => $header
        ];
      }
    }
}