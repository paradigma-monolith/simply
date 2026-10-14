<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-md-6">
                <h3 class="card-title">
                  <i class="fas fa-ship mr-2"></i>
                  Monitoring Sanitasi Kapal
                </h3>
              </div>
              <div class="col-md-6 text-right">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalSanitasiKapal">
                  <i class="fas fa-plus"></i> Tambah Data Sanitasi
                </button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <!-- Filter Section -->
            <div class="row mb-3">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Filter Tanggal:</label>
                  <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control" id="dateFilterSanitasi">
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Filter Unit Kerja:</label>
                  <select class="form-control form-control-sm" id="filterWilker">
                    <option value="">Semua Unit Kerja</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4 d-flex align-items-end">
                <button type="button" class="btn btn-success btn-sm mr-2" id="btnExportSanitasi">
                  <i class="fas fa-file-excel"></i> Export Excel
                </button>
                <button type="button" class="btn btn-info btn-sm" id="btnRefreshData">
                  <i class="fas fa-sync-alt"></i> Refresh
                </button>
              </div>
            </div>

            <!-- Data Table -->
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover" id="tableSanitasiKapal">
                <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th width="12%">Tanggal</th>
                    <th width="15%" class="d-none">Wilker</th>
                    <th width="15%">Unit Kerja</th>
                    <th width="20%">Nama Kapal</th>
                    <th width="15%">Bukti Geotagging</th>
                    <th width="15%">Laporan Tindakan</th>
                    <th width="15%">Output</th>
                    <th width="18%">Aksi</th>
                  </tr>
                </thead>
                <tbody id="dataSanitasiKapal">
                  <tr>
                    <td colspan="7" class="text-center">
                      <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                      <p class="mt-2 text-muted">Memuat data sanitasi kapal...</p>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Modal Form Sanitasi Kapal -->
<div class="modal fade" id="modalSanitasiKapal" tabindex="-1" role="dialog" aria-labelledby="modalSanitasiKapalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalSanitasiKapalLabel">
          <i class="fas fa-ship mr-2"></i>
          <span id="modalTitle">Tambah Data Sanitasi Kapal</span>
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formSanitasiKapal" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="row">
            <!-- Tanggal -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </div>
                  <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                </div>
                <small class="form-text text-muted">Tanggal akan terisi otomatis dengan hari ini</small>
              </div>
            </div>

            <!-- Wilker -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="wilker">User <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-user"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control" id="wilker" name="wilker" value="<?= session()->get('nama') ?? 'User Name'; ?>" readonly>
                  </div>
                  <small class="form-text text-muted">Diambil dari data user yang sedang login</small>
                </div>
              </div>
  
              <!-- Unit Kerja -->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="unit_kerja">Unit Kerja <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-building"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control" id="unit_kerja" name="unit_kerja" value="<?= session()->get('unit_kerja') ?? ''; ?>" readonly>
                  </div>
                  <small class="form-text text-muted">Diambil dari data user yang sedang login</small>
                </div>
              </div>
            </div>

          <div class="row">
            <!-- Nama Kapal -->
            <div class="col-md-12">
              <div class="form-group">
                <label for="namaKapal">Nama Kapal <span class="text-danger">*</span></label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="fas fa-ship"></i>
                    </span>
                  </div>
                  <input type="text" class="form-control" id="namaKapal" name="nama_kapal" placeholder="Masukkan nama kapal" required>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- Checkbox List -->
            <div class="col-md-12">
              <div class="form-group">
                <label>Output</label>
                <div class="row">
                  <div class="col-md-4">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" id="checkCOP" name="jenis_dokumen[]" value="COP">
                      <label class="custom-control-label" for="checkCOP">COP</label>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" id="checkPHQC" name="jenis_dokumen[]" value="PHQC">
                      <label class="custom-control-label" for="checkPHQC">PHQC</label>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" id="checkSSCC" name="jenis_dokumen[]" value="SSCC">
                      <label class="custom-control-label" for="checkSSCC">SSCC</label>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" id="checkSSCEC" name="jenis_dokumen[]" value="SSCEC">
                      <label class="custom-control-label" for="checkSSCEC">SSCEC</label>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" id="checkP3K" name="jenis_dokumen[]" value="P3K">
                      <label class="custom-control-label" for="checkP3K">P3K</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- Upload Bukti Geotagging -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="buktiGeotagging">Upload Bukti Geotagging <span class="text-danger">*</span></label>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="buktiGeotagging" name="bukti_geotagging" accept=".png,.jpg,.jpeg">
                  <label class="custom-file-label" for="buktiGeotagging">Pilih file gambar...</label>
                </div>
                <small class="form-text text-muted">Format: PNG, JPG, JPEG (Maksimal 5MB)</small>
                <div id="previewBukti" class="mt-2" style="display: none;">
                  <img id="imgPreviewBukti" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                </div>
              </div>
            </div>

            <!-- Upload Laporan Tindakan -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="laporanTindakan">Laporan Tindakan Pengendalian</label>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="laporanTindakan" name="laporan_tindakan" accept=".pdf,.docx">
                  <label class="custom-file-label" for="laporanTindakan">Pilih file dokumen...</label>
                </div>
                <small class="form-text text-muted">Format: PDF, DOCX (Opsional - Maksimal 10MB)</small>
                <div id="previewLaporan" class="mt-2" style="display: none;">
                  <div class="alert alert-info">
                    <i class="fas fa-file-alt mr-2"></i>
                    <span id="namaFileLaporan"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times"></i> Batal
          </button>
          <button type="submit" class="btn btn-primary" id="btnSimpan">
            <i class="fas fa-save"></i> Simpan Data
          </button>
        </div>
      </form>
    </div>
  </div>
</div>



<style>
/* Custom styling for monitoring sanitasi kapal */
.table th {
  font-weight: 600;
  font-size: 14px;
  text-align: center;
  vertical-align: middle;
}

.table td {
  vertical-align: middle;
  font-size: 13px;
}

.btn-action {
  margin: 1px;
  padding: 4px 8px;
  font-size: 12px;
}

.custom-file-label::after {
  content: "Browse";
}

.img-thumbnail {
  border: 2px solid #dee2e6;
  border-radius: 8px;
}

.modal-header {
  border-bottom: 2px solid rgba(255,255,255,0.2);
}

.form-group label {
  font-weight: 600;
  color: #495057;
}

.text-danger {
  font-weight: bold;
}

/* Loading animation */
.spinner-border {
  width: 2rem;
  height: 2rem;
}

/* Responsive table */
@media (max-width: 768px) {
  .table-responsive {
    font-size: 12px;
  }
  
  .btn-action {
    padding: 2px 6px;
    font-size: 11px;
  }
  
  .modal-dialog {
    margin: 10px;
  }
}

/* File upload styling */
.custom-file-input:focus ~ .custom-file-label {
  border-color: #007bff;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.alert-info {
  background-color: #d1ecf1;
  border-color: #bee5eb;
  color: #0c5460;
  padding: 8px 12px;
  margin-bottom: 0;
  border-radius: 4px;
}
</style>

<?= $this->endSection(); ?>