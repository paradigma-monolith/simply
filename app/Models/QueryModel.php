<?php

namespace App\Models;

use CodeIgniter\Model;

class QueryModel extends Model
{
  // Fungsi untuk mengambil semua data pengguna beserta jumlah pesanannya
  public function getAllInfografis($tgl1, $tgl2)
  {
    $query = "
        WITH RECURSIVE all_units AS ( 
            -- Mendapatkan daftar unik unit_kerja dari semua tabel
            SELECT DISTINCT unit_kerja FROM infografis_kapal
            UNION
            SELECT DISTINCT unit_kerja FROM infografis_pesawat
            UNION
            SELECT DISTINCT unit_kerja FROM infografis_vaksin
        ),
        all_dates AS (
            SELECT DATE('$tgl1') AS tanggal
            UNION ALL
            SELECT DATE_ADD(tanggal, INTERVAL 1 DAY)
            FROM all_dates
            WHERE tanggal < '$tgl2'
        ),
        date_unit_combinations AS (
            -- Menggabungkan semua unit_kerja dengan semua tanggal
            SELECT 
                u.unit_kerja,
                d.tanggal
            FROM all_units u
            CROSS JOIN all_dates d
        ),
        kapal_data AS (
            -- Data dari tabel infografis_kapal
            SELECT 
                unit_kerja,
                DATE(created_at) AS tanggal,
                kapal_datang_negeri AS kapal_datang_negeri_kapal,
                kapal_berangkat_negeri AS kapal_berangkat_negeri_kapal,
                abk_datang_negeri AS abk_datang_negeri_kapal,
                abk_berangkat_negeri AS abk_berangkat_negeri_kapal,
                penumpang_datang_negeri AS penumpang_datang_negeri_kapal,
                penumpang_berangkat_negeri AS penumpang_berangkat_negeri_kapal,
                kapal_datang_luar AS kapal_datang_luar_kapal,
                kapal_berangkat_luar AS kapal_berangkat_luar_kapal,
                abk_datang_luar AS abk_datang_luar_kapal,
                abk_berangkat_luar AS abk_berangkat_luar_kapal,
                penumpang_datang_luar AS penumpang_datang_luar_kapal,
                penumpang_berangkat_luar AS penumpang_berangkat_luar_kapal
            FROM infografis_kapal
            WHERE DATE(created_at) BETWEEN '$tgl1' AND '$tgl2'
        ),
        pesawat_data AS (
            -- Data dari tabel infografis_pesawat
            SELECT 
                unit_kerja,
                DATE(created_at) AS tanggal,
                pesawat_datang_negeri AS pesawat_datang_negeri_pesawat,
                pesawat_berangkat_negeri AS pesawat_berangkat_negeri_pesawat,
                crew_datang_negeri AS crew_datang_negeri_pesawat,
                crew_berangkat_negeri AS crew_berangkat_negeri_pesawat,
                penumpang_datang_negeri AS penumpang_datang_negeri_pesawat,
                penumpang_berangkat_negeri AS penumpang_berangkat_negeri_pesawat,
                pesawat_datang_luar AS pesawat_datang_luar_pesawat,
                pesawat_berangkat_luar AS pesawat_berangkat_luar_pesawat,
                crew_datang_luar AS crew_datang_luar_pesawat,
                crew_berangkat_luar AS crew_berangkat_luar_pesawat,
                penumpang_datang_luar AS penumpang_datang_luar_pesawat,
                penumpang_berangkat_luar AS penumpang_berangkat_luar_pesawat
            FROM infografis_pesawat
            WHERE DATE(created_at) BETWEEN '$tgl1' AND '$tgl2'
        ),
        vaksin_data AS (
            -- Data dari tabel infografis_vaksin
            SELECT 
                unit_kerja,
                DATE(created_at) AS tanggal,
                phqc,
                cop,
                sscec,
                sscc,
                p3k,
                icv,
                sklt,
                siaos,
                siaj,
                kir,
                vaksin_mm,
                vaksin_yf,
                vaksin_covid,
                vaksin_influenza,
                vaksin_tifoid,
                bukes,
                kontraindikasi,
                e_icv
            FROM infografis_vaksin
            WHERE DATE(created_at) BETWEEN '$tgl1' AND '$tgl2'
        ),
        combined_data AS (
            -- Menggabungkan semua data
            SELECT 
                du.unit_kerja,
                du.tanggal,
                k.kapal_datang_negeri_kapal,
                k.kapal_berangkat_negeri_kapal,
                k.abk_datang_negeri_kapal,
                k.abk_berangkat_negeri_kapal,
                k.penumpang_datang_negeri_kapal,
                k.penumpang_berangkat_negeri_kapal,
                k.kapal_datang_luar_kapal,
                k.kapal_berangkat_luar_kapal,
                k.abk_datang_luar_kapal,
                k.abk_berangkat_luar_kapal,
                k.penumpang_datang_luar_kapal,
                k.penumpang_berangkat_luar_kapal,
                p.pesawat_datang_negeri_pesawat,
                p.pesawat_berangkat_negeri_pesawat,
                p.crew_datang_negeri_pesawat,
                p.crew_berangkat_negeri_pesawat,
                p.penumpang_datang_negeri_pesawat,
                p.penumpang_berangkat_negeri_pesawat,
                p.pesawat_datang_luar_pesawat,
                p.pesawat_berangkat_luar_pesawat,
                p.crew_datang_luar_pesawat,
                p.crew_berangkat_luar_pesawat,
                p.penumpang_datang_luar_pesawat,
                p.penumpang_berangkat_luar_pesawat,
                v.phqc,
                v.cop,
                v.sscec,
                v.sscc,
                v.p3k,
                v.icv,
                v.sklt,
                v.siaos,
                v.siaj,
                v.kir,
                v.vaksin_mm,
                v.vaksin_yf,
                v.vaksin_covid,
                v.vaksin_influenza,
                v.vaksin_tifoid,
                v.bukes,
                v.kontraindikasi,
                v.e_icv
            FROM date_unit_combinations du
            LEFT JOIN kapal_data k ON du.unit_kerja = k.unit_kerja AND du.tanggal = k.tanggal
            LEFT JOIN pesawat_data p ON du.unit_kerja = p.unit_kerja AND du.tanggal = p.tanggal
            LEFT JOIN vaksin_data v ON du.unit_kerja = v.unit_kerja AND du.tanggal = v.tanggal
        )
        -- Final output
        SELECT * FROM combined_data
        ORDER BY tanggal, unit_kerja
        ";

    return $this->db->query($query)->getResultArray();
  }
}
