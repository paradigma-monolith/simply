<?php

namespace App\Controllers;

use App\Libraries\TokenManagerSatuSehat;

class ApiControllerSatuSehat extends BaseController
{
  public function index()
  {
    // Metode ini bisa digunakan untuk pengujian
    return $this->getAllProducts();
  }

  public function getAllProducts()
  {
    $tokenManagerSatuSehat = new TokenManagerSatuSehat();
    try {
      $token = $tokenManagerSatuSehat->getToken();

      // Mengatur parameter untuk mendapatkan semua data obat
      $page = 1; // Mulai dari halaman 1
      $size = 100; // Jumlah item per halaman (sesuaikan dengan batas maksimum API)
      $allProducts = [];

      // Tentukan ProductType yang ingin Anda cari
      $productType = 'farmasi'; // Ganti dengan nilai yang sesuai jika perlu
      $farmalkesType = ''; // Ganti dengan nilai yang sesuai jika perlu

      while (true) {
        // Panggil API untuk mendapatkan produk
        $response = $this->callApiForProducts($token['access_token'], $page, $size, $productType, $farmalkesType);
        // return $this->response->setJSON($response);
        dd($response);
        if (empty($response['items'])) {
          break; // Jika tidak ada data, keluar dari loop
        }

        // Tambahkan data produk ke array
        $allProducts = array_merge($allProducts, $response['items']);

        // Jika jumlah data kurang dari size, berarti sudah tidak ada lagi data
        if (count($response['items']) < $size) {
          break;
        }

        $page++; // Pindah ke halaman berikutnya
      }

      return $this->response->setJSON($allProducts);
    } catch (\Exception $e) {
      return $this->response->setJSON(['error' => $e->getMessage()]);
    }
  }

  private function callApiForProducts($accessToken, $page, $size, $productType, $farmalkesType)
  {
    $url = "https://api-satusehat-stg.dto.kemkes.go.id/kfa-v2/products/all";

    // Menambahkan parameter untuk paginasi dan ProductType ke URL
    $params = [
      'page' => $page,
      'size' => $size,
      'product_type' => $productType, // Menambahkan ProductType // Menambahkan ProductType
    ];

    $farmalkesType  != "" ? $params += ['farmalkes_type' => $farmalkesType] : "";

    // Menambahkan parameter ke URL
    if (!empty($params)) {
      $url .= '?' . http_build_query($params); // Menambahkan query string
    }

    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => [
        "Authorization: Bearer {$accessToken}",
        "Content-Type: application/json"
      ],
    ]);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Mendapatkan kode status HTTP
    curl_close($curl);

    if ($httpCode !== 200) {
      throw new \Exception("API Error: HTTP status code $httpCode, Response: $response");
    }

    return json_decode($response, true);
  }
}
