<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <!-- /.card-header -->
        <div class="card">
          <div class="card-header row">
            <div class="col-6">
              <!-- <h3>Kedatangan Kapal</h3> -->
            </div>
            <div class="col-6 text-right">
              <a href="#" class="btn bg-gradient-primary" onclick="getPasien()"><i class="fas fa-plus"></i> Tambah Data</a>
            </div>
          </div>
          <div class="card-body">
            <table id="tableWithoutExporting" class="table table-sm table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama Pasien</th>
                  <th>Kriteria</th>
                  <th>Obat</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $nomor = 1;
                foreach ($getData as $data): ?>
                  <tr>
                    <td><?= $nomor++; ?></td>
                    <td><?= $data['nama']; ?></td>
                    <td><?= $data['kriteria']; ?></td>
                    <td>
                      <ul class="list-group list-group-unbordered mb-3">
                        <?php
                        $array = unserialize($data['obat']);
                        ?>
                        <?php foreach ($array as $getObat): ?>
                          <li class="list-group-item bg-transparent" style="backdrop-filter: blur(10px);">
                            <b><?= $getObat['obat']; ?></b>
                            <a class="float-right">
                              | Jml: <?= $getObat['obat_jumlah']; ?>
                              | Sehari: <?= $getObat['obat_sehari']; ?>
                              | Ket: <?= $getObat['obat_keterangan']; ?> |
                            </a>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                    </td>
                    <td class="project-actions text-right">
                      <a class="btn btn-info btn-sm disabled" style="cursor: not-allowed;" href="#">
                        <i class="fas fa-print"></i>
                      </a>
                      <!-- <a class="btn btn-warning btn-sm" href="/uklw/editPengambilanObat/<? //= $data['id_uklw_pengambilan_obat']; 
                                                                                              ?>">
                        <i class="fas fa-pencil-alt"> </i>
                      </a> -->
                      <form action="/uklw/delete/uklw_pengambilan_obat/<?= $data['id_uklw_pengambilan_obat']; ?>" method="post" class="d-inline formHapusData">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Hapus Dokumen"><i class="fas fa-trash-alt"></i> </button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  const patients = [];
  // let timeIntervalUklwFarmasi = 0;

  $(document).ready(function() {
    Swal.fire({
      title: "Mengecek data pasien!",
      timer: null,
      timerProgressBar: true,
      didOpen: () => {
        Swal.showLoading();
      },
      allowOutsideClick: false,
    });

    $.ajax({
      url: '/uklw/cekFarmasi',
      method: 'POST',
      dataType: 'json',
      success: function(response) {
        console.log(response);
        patients.push(...response);
        showPatientUklw();
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Swal.fire({
          title: "Terjadi kesalahan",
          icon: "error",
          // text: "Gagal mengambil data pasien.",
          text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError,
        });
      }
    });
  });

  function showPatientUklw() {
    console.log(patients);

    const patientListHtml = patients.map(patient => {
      var statusWarna = "";
      if (patient.status_pemberian_obat == "Sudah Diberikan") {
        statusWarna = "table-success";
      }
      return `
            <li class="list-group-item ${statusWarna}">
                <a href="/uklw/getCatatanPemeriksaan/pasien/${patient.id_uklw_catatan_pemeriksaan}" class="text-danger list-group-item patient-link" data-name="${patient.nama_pasien}">
                    ${patient.nama_pasien}
                </a>
            </li>
        `;
    }).join('');

    // Menampilkan SweetAlert dengan input pencarian
    if (patientListHtml) {
      Swal.fire({
        title: "<strong>Daftar Pasien</strong>",
        icon: "info",
        iconHtml: '<i class="fas fa-user-md" style="font-size: 2em;"></i>',
        html: `
            <input type="text" id="search-input" class="form-control" placeholder="Cari pasien..." style="width: 90%; margin: auto; margin-bottom: 10px;">
            <ul class="list-group" id="patient-list" style="width: 90%; margin: auto;">
                ${patientListHtml}
            </ul>
        `,
        showCloseButton: true,
        showCancelButton: false,
        allowOutsideClick: false,
        showConfirmButton: false,
      });
    } else {
      Swal.fire({
        title: "<strong>Tidak ada data pemeriksaan dokter</strong>",
        icon: "info",
        iconHtml: '<i class="fas fa-user-md" style="font-size: 2em;"></i>',
        showCloseButton: true,
        showCancelButton: false,
        allowOutsideClick: false,
        showConfirmButton: false,
      });
    }

    // Menambahkan event listener untuk input pencarian
    document.getElementById('search-input').addEventListener('input', function() {
      const searchValue = this.value.toLowerCase();
      const listItems = document.querySelectorAll('#patient-list li');

      listItems.forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(searchValue) ? '' : 'none';
      });
    });
  }
</script>

<script>
  const patientsNews = [];
  // let timeIntervalUklwFarmasi = 0;

  function getPasien() {
    Swal.fire({
      title: "Mencari data pasien!",
      timer: null,
      timerProgressBar: true,
      didOpen: () => {
        Swal.showLoading();
      },
      allowOutsideClick: false,
    });

    $.ajax({
      url: '/uklw/getDataPasien',
      method: 'POST',
      dataType: 'json',
      success: function(response) {
        console.log(response);
        patientsNews.push(...response);
        showPatientUklwNews();
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Swal.fire({
          title: "Terjadi kesalahan",
          icon: "error",
          // text: "Gagal mengambil data pasien.",
          text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError,
        });
      }
    });
  };

  function showPatientUklwNews() {
    console.log(patientsNews);

    const patientListHtmlNews = patientsNews.map(patient => {
      return `
            <li class="list-group-item">
                <a href="/uklw/setPengambilanObat/pasien/${patient.id_uklw_pasien}" class="text-danger list-group-item patient-link" data-name="${patient.nama}">
                    ${patient.nama} <br />(${patient.identitas}: ${patient.nomor_identitas})
                </a>
            </li>
        `;
    }).join('');

    // Menampilkan SweetAlert dengan input pencarian
    if (patientListHtmlNews) {
      Swal.fire({
        title: "<strong>Daftar Pasien</strong>",
        icon: "info",
        iconHtml: '<i class="fas fa-user-md" style="font-size: 2em;"></i>',
        html: `
            <input type="text" id="search-input-new" class="form-control" placeholder="Cari pasien..." style="width: 90%; margin: auto; margin-bottom: 10px;">
            <ul class="list-group" id="patient-list-news" style="width: 90%; margin: auto;">
                ${patientListHtmlNews}
            </ul>
        `,
        showCloseButton: true,
        showCancelButton: false,
        allowOutsideClick: false,
        showConfirmButton: false,
      });
    } else {
      Swal.fire({
        title: "<strong>Belum terdapat data pasien</strong>",
        icon: "info",
        iconHtml: '<i class="fas fa-user-md" style="font-size: 2em;"></i>',
        showCloseButton: true,
        showCancelButton: false,
        allowOutsideClick: false,
        showConfirmButton: false,
      });
    }

    // Menambahkan event listener untuk input pencarian
    document.getElementById('search-input-new').addEventListener('input', function() {
      const searchValue = this.value.toLowerCase();
      const listItems = document.querySelectorAll('#patient-list-news li');

      listItems.forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(searchValue) ? '' : 'none';
      });
    });
  }
</script>

<?= $this->endSection(); ?>