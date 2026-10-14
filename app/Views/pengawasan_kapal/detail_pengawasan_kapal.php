<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header row">

          </div>
          <div class="card-body">
            <h1 class="text-muted">PERTANYAAN BAGI KEDATANGAN KAPAL DALAM KARANTINA (KAPAL DATANG DARI LUAR NEGERI)</h1>
            <hr>
            <div class="row mb-3 mt-3">
              <div class="col-md-6">
                <label class="h4">Nama Kapal</label>
                <input type="text" id="nama_kapal" name="nama" class="form-control" value="<?php echo isset($pengawasanKapal['nama']) ? strtoupper($pengawasanKapal['nama']) : ''; ?>">
              </div>

              <div class="col-md-6">
                <label class="h4">Datang Dari </label>
                <input type="text" id="datang_dari" name="datang_dari" class="form-control" value="<?php echo isset($pengawasanKapal['datang_dari']) ? strtoupper($pengawasanKapal['datang_dari']) : ''; ?>">
              </div>
            </div>

        <div class="row mb-4 mt-3">
  <div class="col-md-3">
    <label class="h4">Negara Asal</label>
    <input type="text" class="form-control" name="origin_country" value="<?php echo isset($pengawasanKapal['origin_country']) ? strtoupper($pengawasanKapal['origin_country']) : ''; ?>">
  </div>
  <div class="col-md-3">
    <label class="h4">Gross Tonnage (GT)</label>
    <input type="number" class="form-control" name="gross_tonnage" value="<?php echo isset($pengawasanKapal['gross_tonnage']) ? $pengawasanKapal['gross_tonnage'] : ''; ?>">
  </div>
  <div class="col-md-3">
    <label class="h4">Ke Pelabuhan</label>
    <input type="text" id="pelabuhan" name="pelabuhan" class="form-control" value="<?php echo isset($pengawasanKapal['pelabuhan']) ? ucwords(strtolower($pengawasanKapal['pelabuhan'])) : ''; ?>">
  </div>
  <div class="col-md-3">
    <label class="h4">Jumlah Crew</label>
    <input type="number" id="jml_crew" name="jml_crew" class="form-control" value="<?php echo isset($pengawasanKapal['jml_crew']) ? $pengawasanKapal['jml_crew'] : ''; ?>">
  </div>
</div>



            <hr>

            <div class="row">
              <h3 class="text-muted">PERTANYAAN DI BAWAH INI WAJIB DI ISI OLEH KAPTEN KAPAL SEBELUM TIBA DI WILAYAH KERJA KKP BANDUNG, JAWABLAH PERTANYAAN SESUSI DENGAN KONDISI DAN KEADAAN SESUNGGUHNYA, PEMBERIAN JAWABAN YA ATAU TIDAK PADA KOLOM YANG TERSEDIA</h3>
            </div>

            <hr>

            <div class="row">
              <div class="col-md-9">
                <p class="h4">1. Negara yang dikunjungi dalam 14 hari terakhir</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="col-md-12">
                  <div class="form-control">
                    <?php echo isset($pengawasanKapal['negara']) ? strtoupper($pengawasanKapal['negara']) : ''; ?>
                  </div>
                </div>
              </div>
            </div>

            <hr>

            <div class="row">
              <div class="col-md-9">
                <p class="h4">2. Apakah ada orang yang meninggal di atas kapal selama dalam perjalanan yang bukan diakibatkan oleh kecelakaan?</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q2" id="q2-yes" value="20" onchange="calculateTotal()" <?php echo $pengawasanKapal['q2'] === '20' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-danger h4 btn-kapal" for="q2-yes">Ya</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q2" id="q2-no" value="0" onchange="calculateTotal()" <?php echo $pengawasanKapal['q2'] === '0' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-success h4 btn-kapal" for="q2-no">Tidak</label>
                </div>
              </div>
            </div>
            
            <hr>
            
            <div class="row">
              <div class="col-md-9">
                <p class="h4">3. Apakah dalam perjalanan ada crew dievakuasi karena sakit atau meninggal?</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q3" id="q3-yes" value="20" onchange="calculateTotal()" <?php echo $pengawasanKapal['q3'] === '20' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-danger h4 btn-kapal" for="q3-yes">Ya</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q3" id="q3-no" value="0" onchange="calculateTotal()" <?php echo $pengawasanKapal['q3'] === '0' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-success h4 btn-kapal" for="q3-no">Tidak</label>
                </div>
              </div>
            </div>
            
            <hr>
            
            <div class="row">
              <div class="col-md-9">
                <p class="h4">4. Apakah di atas kapal selama dalam perjalanan terdapat kasus penyakit yang diduga karena penyakit menular?</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q4" id="q4-yes" value="20" onchange="calculateTotal()" <?php echo $pengawasanKapal['q4'] === '20' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-danger h4 btn-kapal" for="q4-yes">Ya</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q4" id="q4-no" value="0" onchange="calculateTotal()" <?php echo $pengawasanKapal['q4'] === '0' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-success h4 btn-kapal" for="q4-no">Tidak</label>
                </div>
              </div>
            </div>
            
            <hr>
            
            <div class="row">
              <div class="col-md-9">
                <p class="h4">5. Apakah ada crew dengan suhu tubuh lebih dari sama dengan 37,3 C (demam, penurunan kesadaran, pembekakan kelenjar, tubuh yang berwarna kuning, batuk atau sesak nafas, pendaharan luar biasa, kelumpuhan, ruam di kulit yang bersifat akut atau kulit melepuh, muntah hebat, diare berat, kejang berulang, keracunan makanan)?</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q5" id="q5-yes" value="20" onchange="calculateTotal()" <?php echo $pengawasanKapal['q5'] === '20' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-danger h4 btn-kapal" for="q5-yes">Ya</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q5" id="q5-no" value="0" onchange="calculateTotal()" <?php echo $pengawasanKapal['q5'] === '0' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-success h4 btn-kapal" for="q5-no">Tidak</label>
                </div>
              </div>
            </div>
            
            <hr>
            
            <div class="row">
              <div class="col-md-9">
                <p class="h4">6. Apakah jumlah crew  yang sakit selama pelayaran lebih besar dari biasanya/perkiraan?</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q6" id="q6-yes" value="20" onchange="calculateTotal()" <?php echo $pengawasanKapal['q6'] === '20' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-danger h4 btn-kapal" for="q6-yes">Ya</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q6" id="q6-no" value="0" onchange="calculateTotal()" <?php echo $pengawasanKapal['q6'] === '0' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-success h4 btn-kapal" for="q6-no">Tidak</label>
                </div>
              </div>
            </div>
            
            <hr>
            
            <div class="row">
              <div class="col-md-9">
                <p class="h4">7. Apakah ada orang yang sakit  diatas kapal saat ini?</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q7" id="q7-yes" value="20" onchange="calculateTotal()" <?php echo $pengawasanKapal['q7'] === '20' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-danger h4 btn-kapal" for="q7-yes">Ya</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q7" id="q7-no" value="0" onchange="calculateTotal()" <?php echo $pengawasanKapal['q7'] === '0' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-success h4 btn-kapal" for="q7-no">Tidak</label>
                </div>
              </div>
            </div>
            
            <hr>
            
            <div class="row">
              <div class="col-md-9">
                <p class="h4">8. Jika ada crew yang sakit sudahkah dikonsulkan ke dokter?</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q8" id="q8-yes" value="20" onchange="calculateTotal()" <?php echo $pengawasanKapal['q8'] === '20' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-danger h4 btn-kapal" for="q8-yes">Ya</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q8" id="q8-no" value="0" onchange="calculateTotal()" <?php echo $pengawasanKapal['q8'] === '0' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-success h4 btn-kapal" for="q8-no">Tidak</label>
                </div>
              </div>
            </div>
            
            <hr>
            
            <div class="row">
              <div class="col-md-9">
                <p class="h4">9. Apakah anda mengetahui kondisi di atas kapal yang dapat menimbulkan infeksi atau penyebaran penyakit?</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q9" id="q9-yes" value="20" onchange="calculateTotal()" <?php echo $pengawasanKapal['q9'] === '20' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-danger h4 btn-kapal" for="q9-yes">Ya</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q9" id="q9-no" value="0" onchange="calculateTotal()" <?php echo $pengawasanKapal['q9'] === '0' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-success h4 btn-kapal" for="q9-no">Tidak</label>
                </div>
              </div>
            </div>
            
            <hr>
            
            <div class="row">
              <div class="col-md-9">
                <p class="h4">10.  Apakah pengolahan dan penyimpanan makanan dilakukan dengan baik dan hygienis?</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q10" id="q9-yes" value="0" onchange="calculateTotal()" <?php echo $pengawasanKapal['q10'] === '0' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-danger h4 btn-kapal" for="q10-yes">Ya</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q10" id="q9-no" value="1" onchange="calculateTotal()" <?php echo $pengawasanKapal['q10'] === '1' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-success h4 btn-kapal" for="q10-no">Tidak</label>
                </div>
              </div>
            </div>
            
            <hr>
            
            <div class="row">
              <div class="col-md-9">
                <p class="h4">11.  Apakah  bahan makanan yang busuk / expired dipisahkan?</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q11" id="q11-yes" value="0" onchange="calculateTotal()" <?php echo $pengawasanKapal['q11'] === '0' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-danger h4 btn-kapal" for="q11-yes">Ya</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q11" id="q11-no" value="1" onchange="calculateTotal()" <?php echo $pengawasanKapal['q11'] === '1' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-success h4 btn-kapal" for="q11-no">Tidak</label>
                </div>
              </div>
            </div>
            
            <hr>
            
            <div class="row">
              <div class="col-md-9">
                <p class="h4">12. Apakah suplai air bersih telah diperiksa dan bersertifikat?</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q12" id="q12-yes" value="0" onchange="calculateTotal()" <?php echo $pengawasanKapal['q12'] === '0' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-danger h4 btn-kapal" for="q12-yes">Ya</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q12" id="q12-no" value="1" onchange="calculateTotal()" <?php echo $pengawasanKapal['q12'] === '1' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-success h4 btn-kapal" for="q12-no">Tidak</label>
                </div>
              </div>
            </div>
            
            <hr>
            
            <div class="row">
              <div class="col-md-9">
                <p class="h4">13. Apakah air yang di gunakan  tidak  berbau, tidak berasa dan tidak berwarna?</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q13" id="q13-yes" value="0" onchange="calculateTotal()" <?php echo $pengawasanKapal['q13'] === '0' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-danger h4 btn-kapal" for="q13-yes">Ya</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q13" id="q13-no" value="1" onchange="calculateTotal()" <?php echo $pengawasanKapal['q13'] === '1' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-success h4 btn-kapal" for="q13-no">Tidak</label>
                </div>
              </div>
            </div>
            
            <hr>
            
            <div class="row">
              <div class="col-md-9">
                <p class="h4">14. Apakah tempat sampah selalu tertutup?</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q14" id="q14-yes" value="0" onchange="calculateTotal()" <?php echo $pengawasanKapal['q14'] === '0' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-danger h4 btn-kapal" for="q14-yes">Ya</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q14" id="q14-no" value="1" onchange="calculateTotal()" <?php echo $pengawasanKapal['q14'] === '1' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-success h4 btn-kapal" for="q14-no">Tidak</label>
                </div>
              </div>
            </div>
            
            <hr>
            
            <div class="row">
              <div class="col-md-9">
                <p class="h4">15. Apakah limbah B3 di kapal dikelola dengan baik?</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q15" id="q15-yes" value="0" onchange="calculateTotal()" <?php echo $pengawasanKapal['q15'] === '0' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-danger h4 btn-kapal" for="q15-yes">Ya</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q15" id="q15-no" value="5" onchange="calculateTotal()" <?php echo $pengawasanKapal['q15'] === '5' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-success h4 btn-kapal" for="q15-no">Tidak</label>
                </div>
              </div>
            </div>
            
            <hr>
            
            <div class="row">
              <div class="col-md-9">
                <p class="h4">16. Apakah terdapat tikus/kecoa/binatang pembawa penyakit lainya di atas kapal?</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q16" id="q16-yes" value="20" onchange="calculateTotal()" <?php echo $pengawasanKapal['q16'] === '20' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-danger h4 btn-kapal" for="q16-yes">Ya</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q16" id="q16-no" value="0" onchange="calculateTotal()" <?php echo $pengawasanKapal['q16'] === '0' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-success h4 btn-kapal" for="q16-no">Tidak</label>
                </div>
              </div>
            </div>
            
            <hr>
            
            <div class="row">
              <div class="col-md-9">
                <p class="h4">17. Apakah tindakan sanitasi (seperti karantina, isolasi, hapus hama atau dekontaminasi) telah dilakukan di atas kapal dalam 1 bulan terakhir?</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q17" id="q17-yes" value="5" onchange="calculateTotal()" <?php echo $pengawasanKapal['q17'] === '5' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-danger h4 btn-kapal" for="q17-yes">Ya</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q17" id="q17-no" value="0" onchange="calculateTotal()" <?php echo $pengawasanKapal['q17'] === '0' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-success h4 btn-kapal" for="q17-no">Tidak</label>
                </div>
              </div>
            </div>
            
            <hr>
            
            <div class="row">
              <div class="col-md-9">
                <p class="h4">18. Apakah ada penumpang gelap/crew yang tidak terdaftar di crew list?</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q18" id="q18-yes" value="10" onchange="calculateTotal()" <?php echo $pengawasanKapal['q18'] === '10' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-danger h4 btn-kapal" for="q18-yes">Ya</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q18" id="q18-no" value="0" onchange="calculateTotal()" <?php echo $pengawasanKapal['q18'] === '0' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-success h4 btn-kapal" for="q18-no">Tidak</label>
                </div>
              </div>
            </div>
            
            <hr>
            
            <div class="row">
              <div class="col-md-9">
                <p class="h4">19. Apakah ada hewan atau hewan piaraan yang sakit di atas kapal?</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q19" id="q19-yes" value="10" onchange="calculateTotal()" <?php echo $pengawasanKapal['q19'] === '10' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-danger h4 btn-kapal" for="q19-yes">Ya</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="btn-check-kapal" style="visibility: hidden; position: absolute" type="radio" name="Q19" id="q19-no" value="0" onchange="calculateTotal()" <?php echo $pengawasanKapal['q19'] === '0' ? 'checked' : ''; ?>>
                  <label class="btn btn-lg btn-outline-success h4 btn-kapal" for="q19-no">Tidak</label>
                </div>
              </div>
            </div>
            
            <hr>
            
            <div class="row">
              <h3 class="text-muted">KELENGKAPAN DOKUMEN KESEHATAN</h3>
            </div>
            
            <div class="row">
              <div class="col-md-9">
                <p class="h4">1. Maritime Declaration of Health sesuai dengan kondisi di kapal</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline custom-checkbox">
				  <input class="form-check-input" type="checkbox" name="x1" id="flexCheckboxDefault1" value="1" <?php echo $pengawasanKapal['x1'] === '1' ? 'checked' : ''; ?>>
				</div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-9">
                <p class="h4">2. Dokumen Sertifikat Sanitasi ada dan masih berlaku?</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline custom-checkbox">
				  <input class="form-check-input" type="checkbox" name="x2" id="flexCheckboxDefault2" value="1" <?php echo $pengawasanKapal['x2'] === '1' ? 'checked' : ''; ?>>
				</div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-9">
                <p class="h4">3. Sertifikat P3K / obat-obatan ada dan masih berlaku?</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline custom-checkbox">
				  <input class="form-check-input" type="checkbox" name="x3" id="flexCheckboxDefault3" value="1" <?php echo $pengawasanKapal['x3'] === '1' ? 'checked' : ''; ?>>
				</div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-9">
                <p class="h4">4. Terdapat Buku Kesehatan</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline custom-checkbox">
				  <input class="form-check-input" type="checkbox" name="x4" id="flexCheckboxDefault4" value="1" <?php echo $pengawasanKapal['x4'] === '1' ? 'checked' : ''; ?>>
				</div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-9">
                <p class="h4">5. Terdapat last port clerance dari pelabuhan sebelumnya</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline custom-checkbox">
				  <input class="form-check-input" type="checkbox" name="x5" id="flexCheckboxDefault5" value="1" <?php echo $pengawasanKapal['x5'] === '1' ? 'checked' : ''; ?>>
				</div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-9">
                <p class="h4">6. Apakah crew kapal mempunyai dokumen vaksinasi dan masih berlaku?</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline custom-checkbox">
				  <input class="form-check-input" type="checkbox" name="x6" id="flexCheckboxDefault6" value="1" <?php echo $pengawasanKapal['x6'] === '1' ? 'checked' : ''; ?>>
				</div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-9">
                <p class="h4">7. Terdapat crew list dengan hasil pengukuran suhu tubuh</p>
              </div>
              <div class="col-md-3 d-flex justify-content-center">
                <div class="form-check form-check-inline custom-checkbox">
				  <input class="form-check-input" type="checkbox" name="x7" id="flexCheckboxDefault7" value="1" <?php echo $pengawasanKapal['x7'] === '1' ? 'checked' : ''; ?>>
				</div>
              </div>
            </div>
            
            <hr>
            
            <?php
			// Set the default background color
			$backgroundColor = 'white';

			// Determine background color based on hasilStatus
			if (isset($pengawasanKapal['hasilStatus'])) {
				switch (strtoupper($pengawasanKapal['hasilStatus'])) {
					case 'ZONA KARANTINA':
						$backgroundColor = 'red';
						break;
					case 'KONFIRMASI':
						$backgroundColor = 'yellow';
						break;
					case 'SANDAR':
						$backgroundColor = 'green';
						break;
				}
			}
			?>
            
            <div id="header" style="padding: 20px; background-color: <?php echo $backgroundColor; ?>; color: white; text-align: center;">
			  <div id="hasil1Div" style="display: flex; align-items: center; justify-content: center;">
				<h3 style="margin-right: 20px;">SKORING : <span id="skoring"><?php echo isset($pengawasanKapal['skoring']) ? strtoupper($pengawasanKapal['skoring']) : ''; ?></span></h3>
				<span style="border-left: 1px solid white; height: 1.5em; margin: 0 10px;"></span>
				<h3 style="margin-right: 20px; margin-left: 20px;">FAKTOR RESIKO : <span id="kresiko"><?php echo isset($pengawasanKapal['kresiko']) ? strtoupper($pengawasanKapal['kresiko']) : ''; ?></span></h3>
				<span style="border-left: 1px solid white; height: 1.5em; margin: 0 10px;"></span>
				
				<?php 
				// Cek status verifikasi
				$statusVerifikasi = isset($pengawasanKapal['status_verifikasi']) ? $pengawasanKapal['status_verifikasi'] : 'pending';
				
				if ($statusVerifikasi == 'pending') {
					// Tampilkan tombol verifikasi jika belum diverifikasi
				?>
					<button type="button" class="btn btn-warning btn-lg" id="btnVerifikasiDetail" 
							data-id="<?= $pengawasanKapal['id']; ?>" 
							data-skoring="<?= isset($pengawasanKapal['skoring']) ? $pengawasanKapal['skoring'] : 0; ?>">
						<i class="fas fa-check-circle"></i> Belum Verifikasi
					</button>
				<?php 
				} else {
					// Tampilkan status yang sudah diverifikasi
					$hasilStatus = $pengawasanKapal['hasilStatus'];
					if (empty($hasilStatus) && isset($pengawasanKapal['skoring'])) {
						$skoring = intval($pengawasanKapal['skoring']);
						if ($skoring >= 20) {
							$hasilStatus = 'ZONA KARANTINA';
						} elseif ($skoring >= 10 && $skoring <= 19) {
							$hasilStatus = 'KONFIRMASI';
						} else {
							$hasilStatus = 'SANDAR';
						}
					}
					
					// Styling berdasarkan status
					$statusClass = 'badge-secondary';
					if ($hasilStatus == 'ZONA KARANTINA') {
						$statusClass = 'badge-danger';
					} elseif ($hasilStatus == 'KONFIRMASI') {
						$statusClass = 'badge-warning';
					} elseif ($hasilStatus == 'SANDAR') {
						$statusClass = 'badge-success';
					}
				?>
					<h3 style="margin-left: 20px;">HASIL STATUS : <span class="badge badge-lg <?= $statusClass; ?>"><?= $hasilStatus; ?></span></h3>
					
					<?php
					// Gabungkan informasi verifikasi jika ada catatan atau verifikator
					$hasVerificationInfo = (isset($pengawasanKapal['catatan_verifikasi']) && !empty($pengawasanKapal['catatan_verifikasi'])) ||
										   (isset($pengawasanKapal['verified_by']) && !empty($pengawasanKapal['verified_by']));
					
					if ($hasVerificationInfo) {
						$tooltipContent = '';
						
						// Tambahkan informasi verifikator jika ada
						if (isset($pengawasanKapal['verified_by']) && !empty($pengawasanKapal['verified_by'])) {
							$tooltipContent .= 'Diverifikasi oleh: ' . $pengawasanKapal['verified_by'];
							
							// Tambahkan tanggal verifikasi jika ada
							if (isset($pengawasanKapal['verified_at']) && !empty($pengawasanKapal['verified_at'])) {
								$tooltipContent .= ' (' . date('d/m/Y H:i', strtotime($pengawasanKapal['verified_at'])) . ')';
							}
						}
						
						// Tambahkan catatan jika ada
						if (isset($pengawasanKapal['catatan_verifikasi']) && !empty($pengawasanKapal['catatan_verifikasi'])) {
							if (!empty($tooltipContent)) {
								$tooltipContent .= ' | ';
							}
							$tooltipContent .= 'Catatan: ' . $pengawasanKapal['catatan_verifikasi'];
						}
					?>
						<i class="fas fa-info-circle ml-2" style="cursor: pointer;" 
						   data-toggle="tooltip" 
						   data-placement="bottom" 
						   data-container="body"
						   title="<?= htmlspecialchars($tooltipContent, ENT_QUOTES, 'UTF-8'); ?>"></i>
					<?php } ?>
				<?php } ?>
			  </div>
			</div>
			
			<script>
				function calculateTotal() {
					const questionGroups = [
						'Q1', 'Q2', 'Q3', 'Q4', 'Q5', 'Q6', 'Q7', 'Q8', 'Q9', 'Q10',
						'Q11', 'Q12', 'Q13', 'Q14', 'Q15', 'Q16', 'Q17', 'Q18', 'Q19'
					];

					let total = 0;

					questionGroups.forEach(group => {
						const radios = document.querySelectorAll(`input[name="${group}"]:checked`);
						radios.forEach(radio => {
							total += parseInt(radio.value);
						});
					});

					// Update skoring di header
					document.getElementById('skoring').textContent = total;

					// Update faktor risiko di header
					const resiko = document.getElementById('kresiko');
					if (total >= 20) {
						resiko.textContent = 'TINGGI';
					} else if (total >= 10 && total <= 19) {
						resiko.textContent = 'SEDANG';
					} else {
						resiko.textContent = 'RENDAH';
					}

					// Update data-skoring pada tombol verifikasi jika ada
					const btnVerifikasi = document.getElementById('btnVerifikasiDetail');
					if (btnVerifikasi) {
						btnVerifikasi.setAttribute('data-skoring', total);
					}
				}

				// Call calculateTotal() function when the page loads
				calculateTotal();
			</script>
			
		<div class="row">
    <?php if (!empty($pengawasanKapal['surat_pernyataan_nahkoda'])): ?>
        <div class="col-md-12 mt-3 text-center">
            <a class="btn btn-info text-light" href="https://selfassessment.bkkbandung.net/quarantine/download/<?= htmlspecialchars($pengawasanKapal['surat_pernyataan_nahkoda'], ENT_QUOTES, 'UTF-8') ?>" target="_blank">
                Download Surat Pernyataan Nahkoda
            </a><br/>
        </div>
    <?php endif; ?>
</div>
			
			<hr>
			
			<div class="row">
			  <div class="col-md-12 mt-3">
				<p class="h5" style="text-align: justify;">
				  Saya Kapten Kapal: <?php echo isset($pengawasanKapal['nama']) ? strtoupper($pengawasanKapal['nama']) : ''; ?></span>,
				  Menyatakan bahwa isian di atas telah sesuai dengan kondisi yang sebenarnya di kapal <?php echo isset($pengawasanKapal['nama']) ? strtoupper($pengawasanKapal['nama']) : ''; ?></span>,
				  jika ada ketidaksesuaian saya bertanggungjawab sesuai peraturan yang berlaku di Indonesia.
				</p>
			  </div>
			</div>
			
			<script>
    			document.getElementById('nama').addEventListener('input', function() {
    				document.getElementById('captainName').innerText = this.value;
    			});
    
    			document.getElementById('nama').addEventListener('input', function() {
    				document.getElementById('shipName').innerText = this.value;
    			});
    		</script>
    		
    		<div class="row">
        	  <div class="col-md-12">
        		<div class="card">
        		  <div class="card-body">
        			<div class="form-check">
        			  <input class="form-check-input" type="checkbox" value="1" name="setuju" id="setuju" onclick="info()" <?php echo $pengawasanKapal['setuju_vaksin'] === '1' ? 'checked' : ''; ?>>
        			  <label class="form-check-label h4" for="flexCheckDefault" style="font-weight: bold">
        			    <span>&nbsp;&nbsp;</span>Pernyataan sesuai dengan keadaan dan kondisi yang sesungguhnya
        			    <hr>
        			  </label>
        			</div>
        		  </div>
        		</div>
        	  </div>
        	</div>

            <!--<a href="https://selfassessment.bkkbandung.net/quarantine/download/<?= $pengawasanKapal['surat_pernyataan_nahkoda'] ?>" target="_blank"><?= $pengawasanKapal['surat_pernyataan_nahkoda'] ?></a>-->
          </div>
        </div>
      </div>
    </div>
  </div>

</section>

<!-- Modal Verifikasi Status -->
<div class="modal fade" id="modalVerifikasiDetail" tabindex="-1" role="dialog" aria-labelledby="modalVerifikasiDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalVerifikasiDetailLabel">
                    <i class="fas fa-check-circle"></i> Verifikasi Status Kapal
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formVerifikasiDetail">
                    <input type="hidden" id="id_pengawasan_detail" name="id_pengawasan" value="">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Skoring Saat Ini:</label>
                                <p id="skoring_display_detail" class="badge badge-lg badge-info"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Status Otomatis Berdasarkan Skoring:</label>
                                <p id="status_otomatis_display_detail" class="badge badge-lg"></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="status_manual_detail" class="font-weight-bold">
                            <i class="fas fa-hand-paper"></i> Pilih Status Manual:
                        </label>
                        <select class="form-control" id="status_manual_detail" name="status_manual" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="ZONA KARANTINA">ZONA KARANTINA</option>
                            <option value="SANDAR">SANDAR</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="catatan_verifikasi_detail" class="font-weight-bold">
                            <i class="fas fa-sticky-note"></i> Catatan Verifikasi:
                        </label>
                        <textarea class="form-control" id="catatan_verifikasi_detail" name="catatan_verifikasi"
                                  rows="4" placeholder="Masukkan catatan atau alasan pemilihan status manual..."></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>Informasi:</strong> Status yang dipilih secara manual akan menggantikan status otomatis berdasarkan skoring.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" class="btn btn-success" id="btnSimpanVerifikasiDetail">
                    <i class="fas fa-save"></i> Simpan Verifikasi
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Inisialisasi tooltip
    function initTooltips() {
        $('[data-toggle="tooltip"]').tooltip();
    }
    
    // Panggil inisialisasi tooltip saat halaman dimuat
    initTooltips();
    
    // Event handler untuk tombol verifikasi di detail
    $(document).on('click', '#btnVerifikasiDetail', function() {
        var id = $(this).data('id');
        var skoring = $(this).data('skoring');
        
        // Set ID pengawasan
        $('#id_pengawasan_detail').val(id);
        
        // Tampilkan skoring
        $('#skoring_display_detail').text(skoring);
        
        // Hitung dan tampilkan status otomatis
        var statusOtomatis = '';
        var badgeClass = '';
        if (skoring >= 20) {
            statusOtomatis = 'ZONA KARANTINA';
            badgeClass = 'badge-danger';
        } else if (skoring >= 10 && skoring <= 19) {
            statusOtomatis = 'KONFIRMASI';
            badgeClass = 'badge-warning';
        } else {
            statusOtomatis = 'SANDAR';
            badgeClass = 'badge-success';
        }
        
        $('#status_otomatis_display_detail').text(statusOtomatis).removeClass().addClass('badge badge-lg ' + badgeClass);
        
        // Set default status manual sama dengan otomatis
        $('#status_manual_detail').val(statusOtomatis);
        
        // Reset catatan
        $('#catatan_verifikasi_detail').val('');
        
        // Tampilkan modal
        $('#modalVerifikasiDetail').modal('show');
    });
    
    // Event handler untuk simpan verifikasi di detail
    $('#btnSimpanVerifikasiDetail').click(function() {
        var formData = {
            id_pengawasan: $('#id_pengawasan_detail').val(),
            status_manual: $('#status_manual_detail').val(),
            catatan_verifikasi: $('#catatan_verifikasi_detail').val()
        };
        
        if (!formData.status_manual) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Silakan pilih status manual terlebih dahulu!'
            });
            return;
        }
        
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
        
        $.ajax({
            url: '/pengawasan_kapal/verify_status',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#modalVerifikasiDetail').modal('hide');
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Status kapal berhasil diverifikasi!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        // Reload halaman untuk menampilkan status terbaru
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message || 'Terjadi kesalahan saat menyimpan verifikasi!'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan pada server!'
                });
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Verifikasi');
            }
        });
    });
});
</script>

<style>
  /* Style untuk tombol "Ya" */
  input.btn-check-kapal:checked + label.btn-kapal.btn-outline-danger {
    background-color: #dc3444;
    color: white;
  }

  /* Style untuk tombol "Tidak" */
  input.btn-check-kapal:checked + label.btn-kapal.btn-outline-success {
    background-color: #24a344;
    color: white;
  }

  /* Tambahkan pointer untuk label */
  .form-check-inline label {
    cursor: pointer;
  }

  /* Style untuk tooltip agar tidak terlalu memanjang */
  .tooltip {
    max-width: 250px !important;
    width: auto !important;
  }
  
  .tooltip-inner {
    max-width: 250px !important;
    width: auto !important;
    white-space: normal !important;
    word-wrap: break-word !important;
    word-break: break-word !important;
    overflow-wrap: break-word !important;
    text-align: left !important;
    padding: 8px 12px !important;
    line-height: 1.4 !important;
    font-size: 12px !important;
  }

  /* Khusus untuk Bootstrap tooltip */
  .bs-tooltip-bottom .tooltip-inner,
  .bs-tooltip-top .tooltip-inner,
  .bs-tooltip-left .tooltip-inner,
  .bs-tooltip-right .tooltip-inner {
    max-width: 250px !important;
    width: auto !important;
    white-space: normal !important;
    word-wrap: break-word !important;
    word-break: break-word !important;
    overflow-wrap: break-word !important;
  }
</style>

<?= $this->endSection(); ?>