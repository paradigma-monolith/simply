<table id="example11" class="table table-bordered table-striped table-hover" style="width: 100%;">
  <thead>
    <tr>
      <th>No</th>
      <th>Id</th>
      <th>Aksi</th>
      <th>Status Surat</th>
      <th>Tanggal Pelaksanaan</th>
      <th>Mata Anggaran</th>
      <th>Sumber Anggaran</th>
      <th>Nama Kegiatan</th>
      <th>Tempat Perjalanan</th>
      <th>Jumlah Orang</th>
      <th>Nominal</th>
      <th>Akses Data</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1;
    foreach ($perjalanan as $data) : ?>
      <tr>
        <td><?= $no++; ?></td>
        <td><?= $data['id_perjalanan']; ?></td>
        <!--Aksi-->
        
        <?php if ($data['status_surat'] == "Draft"): ?>
          <td>
            <a href="/sinadi/validasi_data/<?= $data['id_perjalanan']; ?>" class="btn mb-1 btn-xs btn-primary tombol-ttd-fitur-baru">
              <i class="fas fa-pen-square"></i>
            </a>
            <?php if (session()->get('username') == $data['username'] || $akses) : ?>
              <!-- Tombol Dokumen -->

              <!-- Tombol Ubah Dokumen -->
              <?php if ($akses || $data['status_surat'] != "Disetujui"): ?>
                <a
                  href="/sinadi/<?= $activeMenu == 'dinasDalamKota' ? 'formulirPerjalananDinas' : 'formulirPerjalananDinasAntarKota'; ?>/<?= $data['id_perjalanan']; ?>"
                  class="btn mb-1 btn-xs bg-gradient-warning"
                  data-toggle="tooltip"
                  data-placement="bottom"
                  title="Ubah Dokumen"
                  for="Edit Data">
                  <i class="fas fa-edit"></i>
                </a>
              <?php endif; ?>

                <?php if ($akses) : ?>
                  <!-- Tombol Hapus Dokumen -->
                  <form
                    action="/sinadi/dlt_sinadi/<?= $data['id_perjalanan']; ?>"
                    method="post"
                    class="d-inline formHapusData">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button
                      type="submit"
                      class="btn mb-1 btn-xs bg-gradient-danger"
                      data-toggle="tooltip"
                      data-placement="bottom"
                      title="Hapus Dokumen">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </form>
                <?php endif; ?>
            <?php else : ?>
              <!--<button-->
              <!--  class="btn mb-1 btn-xs bg-gradient-info tombol-td"-->
              <!--  data-toggle="tooltip"-->
              <!--  data-placement="right"-->
              <!--  title="Pratinjau"-->
              <!--  for="Lihat Data">-->
              <!--  <i class="fas fa-eye"></i>-->
              <!--</button>-->
            <?php endif; ?>
          </td>
        <?php elseif ($data['status_surat'] == "Diajukan"): ?>
          <td>
            <button class="btn mb-1 btn-xs btn-info disabled"><i class="fas fa-spinner fa-pulse"></i></button>
            <a href="/sinadi/validasi_data/view/<?= $data['id_perjalanan']; ?>"
                class="btn mb-1 btn-xs bg-gradient-info"
                data-toggle="tooltip"
                data-placement="right"
                title="Pratinjau"
                for="Lihat Data">
                <i class="fas fa-eye"></i>
            </a>


          </td>
        <?php elseif ($data['status_surat'] == "Dalam Verifikasi"): ?>
          <td>
            <button class="btn mb-1 btn-xs btn-info disabled"><i class="fas fa-spinner fa-pulse"></i></button>
            <a href="/sinadi/validasi_data/view/<?= $data['id_perjalanan']; ?>"
                class="btn mb-1 btn-xs bg-gradient-info"
                data-toggle="tooltip"
                data-placement="right"
                title="Pratinjau"
                for="Lihat Data">
                <i class="fas fa-eye"></i>
            </a>


          </td>
        <?php elseif ($data['status_surat'] == "Perlu Revisi"): ?>
          <td>
            <a href="/sinadi/validasi_data/<?= $data['id_perjalanan']; ?>" class="btn mb-1 btn-xs btn-primary tombol-ttd-fitur-baru">
              <i class="fas fa-pen-square"></i>
            </a>
            <?php if (session()->get('username') == $data['username'] || $akses) : ?>
              <!--<button-->
              <!--  class="btn mb-1 btn-xs bg-gradient-info tombol-td"-->
              <!--  data-toggle="tooltip"-->
              <!--  data-placement="right"-->
              <!--  title="Pratinjau"-->
              <!--  for="Lihat Data">-->
              <!--  <i class="fas fa-eye"></i>-->
              <!--</button>-->
              <?php if ($akses || $data['status_surat'] != "Disetujui"): ?>
                <a
                  href="/sinadi/<?= $activeMenu == 'dinasDalamKota' ? 'formulirPerjalananDinas' : 'formulirPerjalananDinasAntarKota'; ?>/<?= $data['id_perjalanan']; ?>"
                  class="btn mb-1 btn-xs bg-gradient-warning"
                  data-toggle="tooltip"
                  data-placement="bottom"
                  title="Ubah Dokumen"
                  for="Edit Data">
                  <i class="fas fa-edit"></i>
                </a>
              <?php endif; ?>

              <form
                action="/sinadi/dlt_sinadi/<?= $data['id_perjalanan']; ?>"
                method="post"
                class="d-inline formHapusData">
                <?= csrf_field(); ?>
                <input type="hidden" name="_method" value="DELETE">
                <button
                  type="submit"
                  class="btn mb-1 btn-xs bg-gradient-danger"
                  data-toggle="tooltip"
                  data-placement="bottom"
                  title="Hapus Dokumen">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </form>
            <?php else : ?>
              <!--<button-->
              <!--  class="btn mb-1 btn-xs bg-gradient-info tombol-td"-->
              <!--  data-toggle="tooltip"-->
              <!--  data-placement="right"-->
              <!--  title="Pratinjau"-->
              <!--  for="Lihat Data">-->
              <!--  <i class="fas fa-eye"></i>-->
              <!--</button>-->
            <?php endif; ?>
            <a href="/sinadi/validasi_data/view/<?= $data['id_perjalanan']; ?>"
                class="btn mb-1 btn-xs bg-gradient-info"
                data-toggle="tooltip"
                data-placement="right"
                title="Pratinjau"
                for="Lihat Data">
                <i class="fas fa-eye"></i>
            </a>

          </td>
        <?php elseif ($data['status_surat'] == "Disetujui"): ?>
          <td>
            <?php if (session()->get('username') == $data['username'] || $akses) : ?>
              <!-- Tombol Pratinjau -->
              <!--<button-->
              <!--  class="btn mb-1 btn-xs bg-gradient-info tombol-td"-->
              <!--  data-toggle="tooltip"-->
              <!--  data-placement="bottom"-->
              <!--  title="Pratinjau"-->
              <!--  for="Lihat Data">-->
              <!--  <i class="fas fa-eye"></i>-->
              <!--</button>-->

              <!-- Tombol Download File -->
              <button
                class="btn mb-1 btn-xs btn-danger"
                onclick="<?= (date('Y', strtotime($data['tgl_pelaksanaan_akhir'])) >= 2025) ? 'downloadFileSinadiNew' : 'downloadFileSinadi'; ?>('<?= $data['id_perjalanan']; ?>','<?= $data['nama']; ?>','<?= $data['bukti_pembayaran']; ?>')"
                data-toggle="tooltip"
                data-placement="bottom"
                title="Download File">
                <i class="fas fa-file-pdf"></i>
              </button>



              <!-- Tombol Ubah Dokumen -->
              <?php if ($akses || $data['status_surat'] != "Disetujui"): ?>
                <a
                  href="/sinadi/<?= $activeMenu == 'dinasDalamKota' ? 'formulirPerjalananDinas' : 'formulirPerjalananDinasAntarKota'; ?>/<?= $data['id_perjalanan']; ?>"
                  class="btn mb-1 btn-xs bg-gradient-warning"
                  data-toggle="tooltip"
                  data-placement="bottom"
                  title="Ubah Dokumen"
                  for="Edit Data">
                  <i class="fas fa-edit"></i>
                </a>
              <?php endif; ?>

              <!-- Tombol Hapus Dokumen -->
              <?php if ($akses || ($data['status_surat'] != "Disetujui" && $data['status_surat'] != "Ditolak")): ?>
                <form
                  action="/sinadi/dlt_sinadi/<?= $data['id_perjalanan']; ?>"
                  method="post"
                  class="d-inline formHapusData">
                  <?= csrf_field(); ?>
                  <input type="hidden" name="_method" value="DELETE">
                  <button
                    type="submit"
                    class="btn mb-1 btn-xs bg-gradient-danger"
                    data-toggle="tooltip"
                    data-placement="bottom"
                    title="Hapus Dokumen">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </form>
              <?php endif; ?>
            <?php else : ?>
              <!-- Tombol Pratinjau -->
              <!--<button-->
              <!--  class="btn mb-1 btn-xs bg-gradient-info tombol-td"-->
              <!--  data-toggle="tooltip"-->
              <!--  data-placement="right"-->
              <!--  title="Pratinjau"-->
              <!--  for="Lihat Data">-->
              <!--  <i class="fas fa-eye"></i>-->
              <!--</button>-->

              <!-- Tombol Download File -->
              <button
                class="btn mb-1 btn-xs btn-danger"
                onclick="<?= (date('Y', strtotime($data['tgl_pelaksanaan_akhir'])) >= 2025) ? 'downloadFileSinadiNew' : 'downloadFileSinadi'; ?>('<?= $data['id_perjalanan']; ?>','<?= $data['nama']; ?>','<?= $data['bukti_pembayaran']; ?>')"
                data-toggle="tooltip"
                data-placement="right"
                title="Download File">
                <i class="fas fa-file-pdf"></i>
              </button>


            <?php endif; ?>
            <a href="/sinadi/validasi_data/view/<?= $data['id_perjalanan']; ?>"
                class="btn mb-1 btn-xs bg-gradient-info"
                data-toggle="tooltip"
                data-placement="right"
                title="Pratinjau"
                for="Lihat Data">
                <i class="fas fa-eye"></i>
            </a>

          </td>
        <?php elseif ($data['status_surat'] == "Ditolak"): ?>
          <td><button class="btn mb-1 btn-secondary disabled"><i class="fas fa-ban"></i></button></td>
        <?php endif; ?>
        <!--Aksi-->
        
        <td class="sinadi-label-disini">
          <?php if ($data['status_surat'] == "Draft"): ?>
            <span class="badge badge-secondary"><?= $data['status_surat']; ?></span>
          <?php elseif ($data['status_surat'] == "Diajukan"): ?>
            <span class="badge badge-primary"><?= $data['status_surat']; ?></span>
          <?php elseif ($data['status_surat'] == "Dalam Verifikasi"): ?>
            <span class="badge badge-info"><?= $data['status_surat']; ?></span>
          <?php elseif ($data['status_surat'] == "Perlu Revisi"): ?>
            <span class="badge badge-warning"><?= $data['status_surat']; ?></span>
          <?php elseif ($data['status_surat'] == "Disetujui"): ?>
            <span class="badge badge-success"><?= $data['status_surat']; ?></span>
          <?php elseif ($data['status_surat'] == "Ditolak"): ?>
            <span class="badge badge-danger"><?= $data['status_surat']; ?></span>
          <?php endif; ?>
        </td>
        
        <?php if ($data['tgl_pelaksanaan'] == $data['tgl_pelaksanaan_akhir']) : ?>
          <td><?= $data['tgl_pelaksanaan']; ?></td>
        <?php else : ?>
          <td><?= $data['tgl_pelaksanaan']; ?> s/d <?= $data['tgl_pelaksanaan_akhir']; ?></td>
        <?php endif; ?>
        <td><?= $data['mata_anggaran']; ?></td>
        <td><?= isset($data['sumber_anggaran']) ? $data['sumber_anggaran'] : 'APBN'; ?></td>
        <td data-toggle="tooltip" data-placement="top" title="<?= $data['nama_kegiatan']; ?>"><?= strlen($data['nama_kegiatan']) > 36 ? substr($data['nama_kegiatan'], 0, 36) . '...' : $data['nama_kegiatan']; ?></td>
        <td data-toggle="tooltip" data-placement="top" title="<?= $data['tempat_perjalanan']; ?>"><?= strlen($data['tempat_perjalanan']) > 36 ? substr($data['tempat_perjalanan'], 0, 36) . '...' : $data['tempat_perjalanan']; ?></td>
        <td><?= $data['jumlah_peserta']; ?> Orang</td>
        <td><?= number_format($data['total_perjalanan'], '2', ',', '.'); ?></td>
        <td>
          <?= name_capitalize($data['nama']); ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <th>No</th>
      <th>Id</th>
      <th>Aksi</th>
      <th>Status Surat</th>
      <th>Tanggal Pelaksanaan</th>
      <th>Mata Anggaran</th>
      <th>Sumber Anggaran</th>
      <th>Nama Kegiatan</th>
      <th>Tempat Perjalanan</th>
      <th>Jumlah Orang</th>
      <th>Nominal</th>
      <th>Akses Data</th>
    </tr>
  </tfoot>
</table>


<script>
  var table = $("#example11").DataTable({
    paging: true, // Nonaktifkan paginasi
    info: false, // Nonaktifkan info tabel
    columnDefs: [{
      targets: [0, 1, 3, 4, 5, 6, 7], // Kolom yang akan memiliki fitur ekspansi
      className: "details-control",
      orderable: true,
    }, ],
  });
  table.columns(1).visible(false);

  // Event handler untuk mengontrol ekspansi baris
  $("#example11 tbody").on("click", "td.details-control", function() {
    var tr = $(this).closest("tr");
    var row = table.row(tr);

    if (row.child.isShown()) {
      // Baris sudah terbuka, tutup
      row.child.hide();
      tr.removeClass("shown");
    } else {
      // Baris ditutup, buka dan tampilkan konten detail
      var id = row.data()[1];

      // Menampilkan SweetAlert2 sebagai indikator loading
      Swal.fire({
        title: "Sedang mengambil data...",
        onBeforeOpen: () => {
          Swal.showLoading();
        },
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false
      });

      getDataSinadi(id)
        .then((response) => {

          Swal.close();
          // Lanjutkan dengan tindakan lain setelah berhasil mendapatkan data
          // Tampilkan konten detail di dalam baris
          row.child(response.data).show(); // Menggunakan 'response' sebagai konten detail
          tr.addClass("shown");
          console.log("Element dari baris yang dibuka:", row.node());
          // Swal.close();
        })
        .catch((error) => {
          console.log("Data berhasil diambil:", response.cek);
          Swal.fire("Terjadi kesalahan", error, "error");
        });
    }
  });
</script>
<script>
  $(document).ready(function () {
    setTimeout(function () {
      const steps = [];

      const tombolTtd = document.querySelector('.tombol-ttd-fitur-baru');
      if (tombolTtd) {
        steps.push({
          element: tombolTtd,
          intro: 'Terdapat fitur baru tanda-tangan digital disini, Anda dapat melakukan tanda tangan setelah membuat data perjalanan baru pada formulir yang telah dibuat dengan status "Draft"/"Perlu Revisi"!',
          position: 'auto', // Otomatis sesuaikan posisi tooltip
          tooltipClass: 'custom-tooltip'
        });
      }

      const sinadiLabel = document.querySelector('.sinadi-label-disini');
      if (sinadiLabel) {
        steps.push({
          element: sinadiLabel,
          intro: 'Anda dapat melihat status surat sekarang disini!',
          position: 'auto'
        });
      }

      if (steps.length > 0) {
        introJs().setOptions({
          steps: steps,
          doneLabel: 'Ok',
          scrollToElement: true,         // Auto-scroll ke elemen
          tooltipPosition: 'auto',       // Tooltip akan menyesuaikan posisi terbaik
          buttons: ['next', 'prev'],     // Navigasi prev/next
          highlightClass: 'intro-highlight'
        }).onbeforechange(function (element) {
          // Scroll halus ke elemen dengan dukungan horizontal dan vertikal
          if (element) {
            element.scrollIntoView({
              behavior: 'smooth',
              block: 'center',    // Vertikal
              inline: 'center'    // Horizontal
            });
          }
        }).start();
      }
    }, 200);
  });
</script>
<script>
$(".fileSinadi").on('click', function() {

  var file = $(this).data('file');

  if (file !== "#") {
    var fullUrl = '<?= base_url(); ?>/' + file;
    // var iframeSrc = 'https://drive.google.com/viewerng/viewer?embedded=true&url=' + encodeURIComponent(fullUrl);

    $("#modal-xl").find('.modal-body').html(
      '<iframe src="' + fullUrl + '" frameborder="1" style="width: 100%; height: 80vh;"></iframe>'
    );
    $("#modal-xl").modal('show');
  }
});
</script>