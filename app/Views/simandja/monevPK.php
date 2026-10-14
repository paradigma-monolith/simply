<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<?php
$array = unserialize($akses['akses']);
if (session()->get('role') == 'Admin' || in_array($by['id_user'], $array)): ?>
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">
            <a data-toggle="collapse" href="#collapseExample" class="btn btn-sm btn-primary" aria-expanded="false" aria-controls="collapseExample">
                <i class="fas fa-eye"></i> Akses Khusus
            </a>
          </h5>
        </div>
        <div id="collapseExample" class="collapse">
          <div class="card-body">
            <div class="row">
              <div class="col-12 col-md-5 mb-4">
                <div class="form-group">
                  <label class="font-weight-bold"><i class="fas fa-users text-primary mr-1"></i> User Akses</label>
                  <select onchange="changeAccessSemulmen(this)" name="akses[]" style="width: 100%;" class="select2" multiple="multiple" data-placeholder="Pilih pengguna yang memiliki akses...">
                    <?php
                    foreach($getUser as $data): ?>
                        <option value="<?= $data['id_user']; ?>" <?= in_array($data['id_user'], $array) ? 'selected':''; ?>><?= name_capitalize($data['nama']); ?></option>
                    <?php endforeach; ?>
                  </select>
                  <small class="form-text text-muted mt-2">Pengguna yang dipilih akan memiliki akses ke tab Akses Khusus ini.</small>
                </div>
              </div>
              <div class="col-12">
                <hr class="mt-0 mb-4">
                <div class="row">
                  <div class="col-sm-4 mb-4">
                    <div class="card card-outline card-primary shadow-sm h-100">
                      <div class="card-header bg-light">
                        <h3 class="card-title font-weight-bold text-primary m-0"><i class="fas fa-plus-circle mr-1"></i> Formulir Penambahan Data</h3>
                      </div>
                      <div class="card-body">
                        <?= $this->include('/simandja/tambahDataTahun'); ?>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-8 mb-4">
                    <div class="card card-outline card-info shadow-sm h-100">
                      <div class="card-header bg-light">
                        <h3 class="card-title font-weight-bold text-info m-0"><i class="fas fa-list mr-1"></i> Daftar Dokumen</h3>
                      </div>
                      <div class="card-body p-0">
                        <div class="table-responsive">
                          <table class="table table-hover table-striped mb-0" style="width: 100%" id="tableAksesKhusus">
                            <thead class="bg-light">
                              <tr>
                                <th class="text-center align-middle" width="5%">No</th>
                                <th class="align-middle">Keterangan Dokumen</th>
                                <th class="text-center align-middle" width="10%">Tahun</th>
                                <th class="text-center align-middle" width="15%">Status</th>
                                <?php if(session()->get('role') == 'Admin' || in_array($by['id_user'], $array)): ?>
                                <th class="text-center align-middle" width="15%">Aksi</th>
                                <?php endif; ?>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $nomor = 1;
                              // Mengurutkan array $getData dari belakang
                              $getDataReversed = array_reverse($getData);
                              foreach ($getDataReversed as $data): ?>
                                <tr>
                                  <td class="text-center align-middle"><?= $nomor++; ?></td>
                                  <td class="align-middle">
                                    <a href="<?= $data['link']; ?>" class="text-dark font-weight-bold" target="_blank">
                                      <i class="fas fa-external-link-alt text-primary mr-1"></i> <?= $data['keterangan']; ?>
                                    </a>
                                  </td>
                                  <td class="text-center align-middle"><span class="badge badge-info"><?= $data['tahun']; ?></span></td>
                                  <td class="text-center align-middle">
                                      <button type="button" class="btn btn-xs <?= $data['status'] == 'non-aktif' ? "btn-outline-danger" : 'btn-success'; ?> btn-block rounded-pill btn-status-toggle" data-id="<?= $data['id_simandja_tahun_anggaran']; ?>" data-url="/simandja/simandjaMonevPK/status/<?= $data['id_simandja_tahun_anggaran']; ?>">
                                        <?= $data['status'] == 'aktif' ? '<i class="fas fa-check-circle mr-1"></i> Aktif' : '<i class="fas fa-times-circle mr-1"></i> Non-Aktif'; ?>
                                      </button>
                                  </td>
                                <?php if(session()->get('role') == 'Admin' || in_array($by['id_user'], $array)): ?>
                                <td class="text-center align-middle">
                                  <div class="btn-group">
                                    <button class="btn btn-sm btn-warning editDataTahun" data-id="<?= $data['id_simandja_tahun_anggaran']; ?>" data-toggle="tooltip" data-placement="bottom" title="Edit Dokumen"><i class="fas fa-edit"></i></button>
                                    <form action="/simandja/dlt_simulmen/<?= $data['id_simandja_tahun_anggaran']; ?>" method="post" class="d-inline formHapusData">
                                      <?= csrf_field(); ?>
                                      <input type="hidden" name="_method" value="DELETE">
                                      <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Dokumen"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                  </div>
                                </td>
                                <?php endif; ?>
                                </tr>
                              <?php endforeach; ?>
                              <?php if(empty($getDataReversed)): ?>
                                <tr>
                                  <td colspan="<?= (session()->get('role') == 'Admin' || in_array($by['id_user'], $array)) ? '5' : '4' ?>" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 text-light"></i>
                                    <p class="mb-0">Belum ada data dokumen yang ditambahkan.</p>
                                  </td>
                                </tr>
                              <?php endif; ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>

<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <?php
        $link = "";
        $tahun = "";
        foreach ($getData as $key => $data):
            if($data['status'] == 'aktif') {
                $link = $data['link'];
            }
        if($tahun != $data['tahun']) {
            $tahun = $data['tahun'];
            echo "<hr />";
            echo "<h4>Data Monev PK Tahun $tahun</h4>";
        }
        ?>
        <a target="_blank" href="<?= $data['link']; ?>" class="<?= $data['status'] == 'non-aktif' ? 'disabled' : ''; ?> btn btn-sm btn-info m-1">
          <i class="fas fa-link"></i> <?= $data['keterangan']; ?>
        </a>
        <?php
        endforeach; ?>
      </div>

      <div class="card-footer">
        <iframe class="lazyIframe" src="" data-src="<?= $link; ?>" title="Simulmen Juli" style="width:100%; height:1200px;" loading="lazy"></iframe>
      </div>

</section>

<script>
function changeAccessSemulmen(selectElement) {
    let data = $(selectElement).val();
    console.log(data)
    
    var formData = {
        akses: data
    };
    
    $.ajax({
        url: '/simandja/simandjaRevisi/setAkses',
        data: formData,
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
          $("#overlay").show();
        },
        success: function(response) {
          if (response.data) {
            Toast.fire({
              icon: 'success',
              title: 'Akses berhasil diperbaharui'
            });
          } else {
            Toast.fire({
              icon: 'warning',
              title: 'Akses gagal diperbaharui'
            });
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          Swal.fire({text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError});
        },
        complete: function() {
          $("#overlay").hide();
        }
    });
}
</script>

<script>
  $(document).on("click", ".editDataTahun", function() {
    var $modal = $("#modal-md");
    var activeMenu = "<?= $activeMenu ?>";
    var id = $(this).data("id");

    $.ajax({
      url: `/simandja/editDataTahun/${id}/${activeMenu}`,
      method: 'get',
      dataType: 'json',
      beforeSend: function() {
        $("#overlay").show();
      },
      success: function(response) {
        if (response.data) {
          $modal.find(".modal-body").html(response.data);
          $modal.modal('show');
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Swal.fire({
          'text': xhr.status + "\n" + xhr.responseText + "\n" + thrownError
        })
      },
      complete: function() {
        $("#overlay").hide();
      }
    });
  });
</script>
<script>
  $(document).ready(function() {
    var totalIframes = $('.lazyIframe').length;
    var loadedIframes = 0;

    function loadIframe(iframe) {
      var lazySrcIframe = $(iframe).data('src');
      $(iframe).attr('src', lazySrcIframe);

      loadedIframes++;

      if (loadedIframes === totalIframes) {
        console.log('Semua iframe telah dimuat.');
      }
    }

    var observer = new IntersectionObserver(function(entries, observer) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          loadIframe(entry.target);
          observer.unobserve(entry.target);
        }
      });
    });

    $('.lazyIframe').each(function() {
      observer.observe(this);
    });

    // Handle status toggle button click
    $('.btn-status-toggle').on('click', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        var id = $(this).data('id');
        
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $("#overlay").show();
            },
            success: function(response) {
                if(response.status === 'success') {
                    if(response.new_status === 'aktif') {
                        // Switch to the newly activated document's tab
                        sessionStorage.setItem('activeSimandjaId', id);
                    } else {
                        // Document became non-aktif, so we switch back to Akses Khusus tab
                        sessionStorage.setItem('openAksesKhusus', 'true');
                    }
                    location.reload();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                Swal.fire({
                    text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError,
                    icon: 'error'
                });
                $("#overlay").hide();
            }
        });
    });

    // Check if we need to auto-load a specific document after reload
    var activeId = sessionStorage.getItem('activeSimandjaId');
    var openAksesKhusus = sessionStorage.getItem('openAksesKhusus');
    
    if(activeId) {
        sessionStorage.removeItem('activeSimandjaId');
        
        setTimeout(function() {
            var $activeDoc = $('.load-doc.active');
            if($activeDoc.length) {
                $activeDoc.closest('.dropdown').find('.dropdown-toggle').addClass('active');
            }
        }, 100);
    } else if(openAksesKhusus) {
        sessionStorage.removeItem('openAksesKhusus');
        
        setTimeout(function() {
            // Remove active class from all tabs and panes
            $('#custom-tabs-three-tab .nav-link').removeClass('active');
            $('#custom-tabs-three-tabContent .tab-pane').removeClass('active show');
            
            // Specifically remove active from load-doc links to clear any highlighted dropdown items
            $('.load-doc').removeClass('active');
            
            // Also hide the "File Aktif" alert if there is no active file anymore
            $('.alert-info').hide();
            
            // Clear iframe source
            $('.lazyIframe').attr('src', '');
            
            // Activate Akses Khusus tab using Bootstrap's tab method
            $('#custom-tabs-three-akses-tab').tab('show');
        }, 100);
    }

    // Initialize DataTable with 5 entries per page
    $('#tableAksesKhusus').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "pageLength": 5,
      "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
      "language": {
        "lengthMenu": "Tampilkan _MENU_ data per halaman",
        "zeroRecords": "Tidak ada data yang ditemukan",
        "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
        "infoEmpty": "Tidak ada data yang tersedia",
        "infoFiltered": "(difilter dari _MAX_ total data)",
        "search": "Cari:",
        "paginate": {
            "first": "Pertama",
            "last": "Terakhir",
            "next": "Selanjutnya",
            "previous": "Sebelumnya"
        }
      }
    });
  });
</script>


<?= $this->endSection(); ?>