<!-- Button trigger modal -->
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#InfografisExcel">
  Launch demo modal
</button> -->

<!-- Modal -->
<div class="modal fade" id="InfografisExcel" tabindex="-1" role="dialog" aria-labelledby="InfografisExcelTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="InfografisExcelTitle">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="/infografis/excel" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label>Rentang tanggal dan waktu:</label>

            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="far fa-clock"></i></span>
              </div>
              <input type="text" name="date" class="form-control float-right" id="DateInfografisExcel">
            </div>
            <!-- /.input group -->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal" id="btn-notifikasi-read">
    <div class="modal-dialog">
      <div class="modal-content">
    
        <!-- Bagian Header Modal -->
        <div class="modal-header">
          <h5 class="modal-title">Notif</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
    
        <!-- Bagian Body Modal -->
        <div class="modal-body">
          <div class="post">
            <div class="user-block">
              <!-- <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image"> -->
              <img class="img-circle img-bordered-sm" src="/dist/img/logo_kkp.png" alt="user image">
              <span class="username">
                <a href="#" class="notif-pengirim"></a>
              </span>
              <span class="description">Dibagikan - <span class="notif-waktu"></span> yang lalu</span>
            </div>
            <!-- /.user-block -->
            <p class="notif-pesan">
            </p>
          </div>
        </div>
    
      </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModalCutiSisaTahunan" tabindex="-1" aria-labelledby="exampleModalCutiSisaTahunanLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCutiSisaTahunanLabel">Pengaturan Sisa Cuti Tahun Lalu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="/cuti/update_sisa_cuti">
          <div class="form-group">
            <label class="has-float-label">
              <input type="hidden" name="id_data_pegawai" class="form-control">
              <input type="text" name="nama" class="form-control" readonly>
              <span>Nama Pegawai</span>
            </label>
          </div>
          <div class="form-group">
            <lable class="has-float-label">
              <input type="number" min="0" name="sisa_cuti_tahunan" class="form-control">
              <span>Sisa Cuti Tahunan</span>
            </lable>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success cuti-btn-submit-pengaturan-sisa-tahunan">Simpan perubahan</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="edit-relaxtime-realisasi-modal">
  <div class="modal-dialog edit-relaxtime-realisasi-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Realisasi Anggaran</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h6 id="relaxtime-kro-modal-edit"></h6>
        <table class="table table-sm" style="font-size: 8pt;">
          <tbody>
            <tr class="table-secondary">
              <td id="relaxtime-komponen-modal-edit"></td>
              <td id="relaxtime-komponen-deskripsi-modal-edit"></td>
            </tr>
            <tr class="table-warning">
              <td id="relaxtime-sub-komponen-modal-edit"></td>
              <td id="relaxtime-sub-komponen-deskripsi-modal-edit"></td>
            </tr>
            <tr>
              <td colspan="2" id="relaxtime-uraian-modal-edit"></td>
            </tr>
          </tbody>
        </table>
        <p id="relaxtime-detail-uraian-modal-edit"></p>
        <form class="relaxtime-realisasi-formulir-edit" action="/relaxtime/realisasiUpdate" method="post">
          <input readonly class="form-control relaxtime-anggaran-realisasi-id" type="hidden" name="id_realisasi" placeholder="0">
          <input class="form-control relaxtime-anggaran-realisasi" type="text" name="realisasi" placeholder="0" value="" autocomplete="off">
          <small class="form-text text-muted sisa-anggaran-pagu-relaxtime">We'll never share your email with anyone else.</small>
          <div class="input-group mt-2" data-target-input="nearest">
            <input type="text" name="tanggal" class="form-control datetimepicker-input date-today" data-toggle="datetimepicker" autocomplete="off" placeholder="<?= date("Y-m-d"); ?>" />
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default btn-close-modal" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary relaxtime-realisasi-button-formulir-edit">Simpan Perubahan</button>

      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="relaxtime-realisasi-modal">
  <div class="modal-dialog relaxtime-realisasi-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Realisasi Anggaran</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h6 id="relaxtime-kro-modal"></h6>
        <table class="table table-sm" style="font-size: 8pt;">
          <tbody>
            <tr class="table-secondary">
              <td id="relaxtime-komponen-modal"></td>
              <td id="relaxtime-komponen-deskripsi-modal"></td>
            </tr>
            <tr class="table-warning">
              <td id="relaxtime-sub-komponen-modal"></td>
              <td id="relaxtime-sub-komponen-deskripsi-modal"></td>
            </tr>
            <tr>
              <td colspan="2" id="relaxtime-uraian-modal"></td>
            </tr>
          </tbody>
        </table>
        <p id="relaxtime-detail-uraian-modal"></p>
        <form class="relaxtime-realisasi-formulir" action="/relaxtime/realisasi" method="post">
          <input readonly class="form-control relaxtime-anggaran-realisasi-id" type="hidden" name="id_realisasi" placeholder="0">
          <input class="form-control relaxtime-anggaran-realisasi" type="text" name="realisasi" placeholder="0" value="" autocomplete="off">
          <small class="form-text text-muted sisa-anggaran-pagu-relaxtime">We'll never share your email with anyone else.</small>
          <div class="input-group mt-2" data-target-input="nearest">
            <input type="text" name="tanggal" class="form-control datetimepicker-input date-today" data-toggle="datetimepicker" autocomplete="off" placeholder="<?= date("Y-m-d"); ?>" />
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default btn-close-modal" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary relaxtime-realisasi-button-formulir">Simpan Perubahan</button>

      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="relaxtime-edit-realisasi-modal">
  <div class="modal-dialog relaxtime-edit-realisasi-modal modal-lg">
    <div class="modal-content">
      <form class="relaxtime-update-pagu" action="/relaxtime/update-pagu" method="post">
        <div class="modal-header">
          <h4 class="modal-title">Edit Data Pagu</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h5 class="deskripsi-kode-relaxtime"></h5>
          <div class="form-group">
            <label class="has-float-label">
              <input required type="hidden" name="segment" class="form-control" placeholder="segment">
            </label>
          </div>
          <div class="form-group">
            <label class="has-float-label">
              <input required type="text" name="id" class="form-control" placeholder="Id">
              <span>Id</span>
            </label>
          </div>
          <div class="form-group">
            <label class="has-float-label">
              <input required type="text" name="digit_kode" class="form-control" placeholder="digit kode">
              <span>Digit Kode</span>
            </label>
          </div>
          <div class="form-group">
            <label class="has-float-label">
              <input required type="text" name="digit_uraian" class="form-control" placeholder="digit uraian">
              <span>Digit Uraian</span>
            </label>
          </div>
          <div class="form-group">
            <label class="has-float-label">
              <input required type="text" name="kode" class="form-control" placeholder="kode">
              <span>Kode</span>
            </label>
          </div>
          <div class="form-group">
            <label class="has-float-label">
              <input required type="text" name="uraian" class="form-control" placeholder="uraian">
              <span>Uraian</span>
            </label>
          </div>
          <div class="form-group">
            <label class="has-float-label">
              <input required type="text" name="vol" class="form-control" placeholder="vol">
              <span>Vol</span>
            </label>
          </div>
          <div class="form-group">
            <label class="has-float-label">
              <input required type="text" name="sat" class="form-control" placeholder="sat">
              <span>Sat</span>
            </label>
          </div>
          <div class="form-group">
            <label class="has-float-label">
              <input required type="text" name="harga" class="form-control" placeholder="harga">
              <span>Harga</span>
            </label>
          </div>
          <div class="form-group">
            <label class="has-float-label">
              <input required type="text" name="jumlah" class="form-control" placeholder="jumlah">
              <span>Jumlah</span>
            </label>
          </div>
          <div class="form-group">
            <label class="has-float-label">
              <input type="text" name="tahun" class="form-control" placeholder="tahun">
              <span>Tahun</span>
            </label>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default btn-close-modal" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-success relaxtime-pagu-button-formulir-edit"><i class="fas fa-share"></i> Simpan</button>

        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>


<!-- Modal -->
<div class="modal fade" id="simandjaModalEdit" tabindex="-1" aria-labelledby="simandjaModalEditLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="simandjaModalEditLabel">Edit Data</h5>
        <span class="float-right">
          <?= $title; ?>
        </span>
      </div>
      <div class="modal-body">
        <form action="/crud/simandja/update" method="post" enctype="multipart/form-data">
          <input type="hidden" name="id" value="">
          <input type="hidden" name="kategori" value="" required>
          <div class="form-group d-none">
            <label class="">
              Substansi
            </label>
            <select class="form-control select2" type="text" name="substansi" style="width: 100%;">
              <option value="">--Substansi--</option>
              <?php if (isset($substansi)) : ?>
                <?php foreach ($substansi as $data) : ?>
                  <option value="<?= $data['nama_substansi']; ?>"><?= $data['nama_substansi']; ?> </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>
          
          <div class="form-group">
            <label class="">
              Tim Kerja
            </label>
            <select class="form-control select2" type="text" name="tim_kerja" required="" style="width: 100%;">
              <option value="">--Tim Kerja--</option>
              <?php if (isset($timKerja)) : ?>
                <?php foreach ($timKerja as $data) : ?>
                  <option value="<?= $data['nama_tim_kerja']; ?>"><?= $data['nama_tim_kerja']; ?> </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>
          
          <div class="form-group">
            <lable class="has-float-label">
              <input type="date" min="0" name="tanggal" class="form-control" required>
              <span>Tanggal</span>
            </lable>
          </div>
          <div class="form-group">
            <lable class="has-float-label">
              <input type="text" min="0" name="deskripsi" class="form-control" required>
              <span>Deskripsi</span>
            </lable>
          </div>
          <div class="custom-file">
            <input type="file" class="custom-file-input" name="fileSimandja">
            <label class="custom-file-label" for="customFile">Pilih file</label>
          </div>
          <hr>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success float-right">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="simandjaModalTambah" tabindex="-1" aria-labelledby="simandjaModalTambahLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="simandjaModalTambahLabel">Tambah Data</h5>
        <span class="float-right">
          <?= $title; ?>
        </span>
      </div>
      <div class="modal-body">
        <form action="/crud/simandja" method="post" enctype="multipart/form-data">
          <input type="hidden" name="id" value="">
          <input type="hidden" name="kategori" value="" required>
          <div class="form-group d-none">
            <label class="">
              Substansi
            </label>
            <select class="form-control select2" type="text" name="substansi" style="width: 100%;">
              <option value="">--Substansi--</option>
              <?php if (isset($substansi)) : ?>
                <?php foreach ($substansi as $data) : ?>
                  <option value="<?= $data['nama_substansi']; ?>"><?= $data['nama_substansi']; ?> </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>
          
          <div class="form-group">
            <label class="">
              Tim Kerja
            </label>
            <select class="form-control select2" type="text" name="tim_kerja" required="" style="width: 100%;">
              <option value="">--Tim Kerja--</option>
              <?php if (isset($timKerja)) : ?>
                <?php foreach ($timKerja as $data) : ?>
                  <option value="<?= $data['nama_tim_kerja']; ?>"><?= $data['nama_tim_kerja']; ?> </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>
          
          <div class="form-group">
            <lable class="has-float-label">
              <input type="date" min="0" name="tanggal" class="form-control" required>
              <span>Tanggal</span>
            </lable>
          </div>
          <div class="form-group">
            <lable class="has-float-label">
              <input type="text" min="0" name="deskripsi" class="form-control" required>
              <span>Deskripsi</span>
            </lable>
          </div>
          <div class="custom-file">
            <input type="file" class="custom-file-input" name="fileSimandja" required>
            <label class="custom-file-label" for="customFile">Pilih file</label>
          </div>
          <hr>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success float-right">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="relaxtimeTargetBulananTahunModal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tahun</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <select name="tahun" class="form-control">
          <?php if (isset($tahun)) : ?>
            <?php for ($i = date('Y') + 1; $i >= 2023; $i--) : ?>
              <option value="<?= $i; ?>" <?= $i == $tahun ? 'selected' : ''; ?>><?= $i; ?></option>
            <?php endfor; ?>
          <?php endif; ?>
        </select>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-primary" onclick="directLinkUpdateTargetBulanan()">Lanjutkan...</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-profil-saya-foto-profil" tabindex="-1" aria-labelledby="modal-profil-saya-foto-profilLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        Perbaharui Foto Profile
      </div>

      <form id="perbaharuiPhoto" method="post" action="/auth/update_photo" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="custom-file mb-3">
            <input type="file" class="custom-file-input" name="image" required>
            <label class="custom-file-label" for="validatedCustomFile">Pilih photo anda...</label>
          </div><small id="emailHelp" class="form-text text-muted">Lebih bagus menggunakan rasio foto 1:1.</small>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-success btn-simpan"><i class="fas fa-share"></i> Simpan</button>
        </div>
      </form>

    </div>
  </div>
</div>

<div class="modal fade" id="tutorialGantiPasswordModal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tutorial Ganti Password</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="uklwDirectWithDataPegawai">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Data Pegawai</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="uklwDirectPemkes">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Pemeriksaan</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- Modal Sistem PRL -->
<div class="modal fade" id="exampleModalTppSecA" tabindex="-1" aria-labelledby="exampleModalTppSecALabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalTppSecALabel">Penilaian Skor Profil Pangan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img src="/dist/img/prl-tpp-sec-a.png" alt="Deskripsi gambar" style="max-width: 100%; height: auto;">
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="exampleModalTppSecB" tabindex="-1" aria-labelledby="exampleModalTppSecBLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalTppSecBLabel">Penilaian Skor Profil Pangan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img src="/dist/img/prl-tpp-sec-b.png" alt="Deskripsi gambar" style="max-width: 100%; height: auto;">
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="exampleModalTppSecC" tabindex="-1" aria-labelledby="exampleModalTppSecCLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalTppSecCLabel">Penilaian Skor Ukuran Bisnis</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img src="/dist/img/prl-tpp-sec-c.png" alt="Deskripsi gambar" style="max-width: 100%; height: auto;">
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="absImageGalery">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Daftar Galery</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- =========================================================================== -->

        <!-- =========================================================================== -->
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="loadLabelABSModal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">History Label</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- =========================================================================== -->

        <!-- =========================================================================== -->
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>


<div class="modal fade" id="absScanQRCode" data-backdrop="static">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">QR Code (Code SIMAN)</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formulirInputQRABS" action="/abs/search/dataABS" method="post">
        <div class="modal-body">
          <!-- =========================================================================== -->
          <?php if ($activeMenu == 'abs_verifikasi' && session()->get('role') == 'Admin') : ?>
            <select name="qrCode" class="select2" required style="width: 100%;">
              <option value="">--Pilih Code--</option>
              <?php foreach ($qrCodeSiman as $data) : ?>
                <option value="<?= $data['kode_qr_code_pusat']; ?>"><?= $data['kode_qr_code_pusat']; ?></option>
              <?php endforeach; ?>
            </select>
          <?php else : ?>
            <?php if($activeMenu == 'abs_verifikasi'): ?>
                <select name="qrCode" class="select2" required style="width: 100%;">
                  <option value="">--Pilih Code--</option>
                  <?php foreach ($qrCodeSiman as $data) : ?>
                    <option value="<?= $data['kode_qr_code_pusat']; ?>"><?= $data['kode_qr_code_pusat']; ?></option>
                  <?php endforeach; ?>
                </select>
            <?php else: ?>
                <input type="text" name="qrCode" class="form-control" value="" required>
            <?php endif; ?>
          <?php endif; ?>
          <!-- =========================================================================== -->
        </div>
        <div class="modal-footer">
          <button class="btn-primary float-right"><i class="fas fa-search"></i> Mencari</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-xl">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header"></div>
      <div class="modal-body">

      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-lg">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header"></div>
      <div class="modal-body">

      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-md">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header"></div>
      <div class="modal-body">

      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-sm">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header"></div>
      <div class="modal-body">

      </div>
    </div>
  </div>
</div>

<!-- Modal Export Laporan SINADI -->
<div class="modal fade" id="modal-export-sinadi" tabindex="-1" role="dialog" aria-labelledby="modalExportSinadiLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalExportSinadiLabel">Export Laporan SINADI</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="/sinadi/export_laporan" method="post" target="_blank">
        <div class="modal-body">
          <div class="form-group">
            <label>Rentang Tanggal:</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
              </div>
              <input type="text" name="date_range" class="form-control float-right" id="DateExportSinadi">
            </div>
          </div>
          <div class="form-group">
            <label>Sumber Anggaran:</label>
            <select class="form-control" name="sumber_anggaran">
              <option value="">Semua</option>
              <option value="APBN">APBN</option>
              <option value="RPL">RPL</option>
            </select>
          </div>
          <div class="form-group">
            <label>Jenis Perjalanan Dinas:</label>
            <select class="form-control" name="jenis_perjalanan">
              <option value="">Semua</option>
              <option value="dalam kota">Dalam Kota</option>
              <option value="luar kota">Luar Kota</option>
            </select>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Export</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(function() {
    //Date range picker for Export SINADI
    $('#DateExportSinadi').daterangepicker({
      timePicker: false,
      locale: {
        format: 'YYYY-MM-DD'
      }
    })
  })
</script>

<script>
  // $('#daterange').daterangepicker({
  //   startDate: '03/05/2005',
  // });
  $(function() {
    //Date range picker
    $('#DateInfografisExcel').daterangepicker({
      timePicker: true,
      timePicker24Hour: true,
      timePickerIncrement: 30,
      locale: {
        format: 'YYYY-MM-DD HH:mm'
      }
    })
    $('#dateRange').daterangepicker();
  })
</script>