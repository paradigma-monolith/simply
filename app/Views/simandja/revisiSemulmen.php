<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<?php
$array = unserialize($akses['akses']);
$hasAccess = (session()->get('role') == 'Admin' || in_array($by['id_user'], $array));
?>

<section class="content">
  <div class="container-fluid">
    <div class="card card-primary card-outline card-tabs">
      <div class="card-header p-0 pt-1 border-bottom-0">
        <?php
        $link = "";
        $tahunData = [];
        $activeFile = null;
        
        // Kelompokkan data berdasarkan tahun
        foreach ($getData as $key => $data):
            if($data['status'] == 'aktif') {
                $link = $data['link'];
                $activeFile = $data;
            }
            $tahunData[$data['tahun']][] = $data;
        endforeach;
        ?>

        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
        <?php if ($hasAccess): ?>
          <li class="nav-item">
            <a class="nav-link" id="custom-tabs-three-akses-tab" data-toggle="pill" href="#custom-tabs-three-akses" role="tab" aria-controls="custom-tabs-three-akses" aria-selected="false">Akses Khusus</a>
          </li>
        <?php endif; ?>

        <?php foreach ($tahunData as $tahun => $dataList): 
            $isActiveYear = ($activeFile && $activeFile['tahun'] == $tahun);
        ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?= $isActiveYear ? 'active' : '' ?>" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            Tahun <?= $tahun ?>
          </a>
          <div class="dropdown-menu">
            <?php foreach ($dataList as $data): 
                  $isActiveFile = ($activeFile && $activeFile['id_simandja_tahun_anggaran'] == $data['id_simandja_tahun_anggaran']);
             ?>
               <a class="dropdown-item load-doc <?= $data['status'] == 'non-aktif' ? 'disabled' : ''; ?> <?= $isActiveFile ? 'active' : '' ?>" href="<?= $data['link']; ?>" data-ket="<?= $data['keterangan']; ?>" data-tahun="<?= $data['tahun']; ?>">
                  <?= $data['keterangan']; ?> <?= $isActiveFile ? '<span class="badge badge-light ml-2">Aktif</span>' : '' ?>
               </a>
             <?php endforeach; ?>
          </div>
        </li>
        <?php endforeach; ?>
        </ul>
      </div>
      <div class="card-body">
        <?php if ($activeFile): ?>
        <div class="alert alert-info alert-dismissible py-2 px-3 mb-2" style="font-size: 0.9em;">
          <button type="button" class="close py-2" data-dismiss="alert" aria-hidden="true">×</button>
          <i class="icon fas fa-info mr-1"></i> <strong>File Aktif: <?= $activeFile['keterangan']; ?> (Tahun <?= $activeFile['tahun']; ?>)</strong> - Dokumen aktif saat ini.
        </div>
        <?php endif; ?>

        <div class="tab-content" id="custom-tabs-three-tabContent">
          <?php if ($hasAccess): ?>
          <div class="tab-pane fade" id="custom-tabs-three-akses" role="tabpanel" aria-labelledby="custom-tabs-three-akses-tab">
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
                                <?php if($hasAccess): ?>
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
                                      <button type="button" class="btn btn-xs <?= $data['status'] == 'non-aktif' ? "btn-outline-danger" : 'btn-success'; ?> btn-block rounded-pill btn-status-toggle" data-id="<?= $data['id_simandja_tahun_anggaran']; ?>" data-url="/simandja/simandjarevisi/status/<?= $data['id_simandja_tahun_anggaran']; ?>">
                                        <?= $data['status'] == 'aktif' ? '<i class="fas fa-check-circle mr-1"></i> Aktif' : '<i class="fas fa-times-circle mr-1"></i> Non-Aktif'; ?>
                                      </button>
                                  </td>
                                <?php if($hasAccess): ?>
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
                                  <td colspan="<?= $hasAccess ? '5' : '4' ?>" class="text-center py-4 text-muted">
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
          <?php endif; ?>
        </div>
      </div>
      <!-- /.card -->
    </div>
    
    <div class="card">
        <div class="card-body p-0">
             <iframe class="lazyIframe" src="" data-src="<?= $link; ?>" title="Simulmen Juli" style="width:100%; height:1200px; border:0;" loading="lazy"></iframe>
        </div>
    </div>
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
      if (lazySrcIframe) {
          $(iframe).attr('src', lazySrcIframe);
      }

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

    // Handle document click to load in iframe
    $('.load-doc').on('click', function(e) {
        e.preventDefault();
        var link = $(this).attr('href');
        var ket = $(this).data('ket');
        var tahun = $(this).data('tahun');
        
        if(link && link !== '#') {
            $('.lazyIframe').attr('src', link);
            window.open(link, '_blank'); // Buka link spreadsheet di tab baru
            
            // Update Active File Info
            var alertHtml = `
            <div class="alert alert-info alert-dismissible py-2 px-3 mb-2" style="font-size: 0.9em;">
              <button type="button" class="close py-2" data-dismiss="alert" aria-hidden="true">×</button>
              <i class="icon fas fa-info mr-1"></i> <strong>File Aktif: ${ket} (Tahun ${tahun})</strong> - Dokumen yang sedang Anda lihat.
            </div>`;
            
            // Replace or append alert
            if($('.alert-info').length) {
                $('.alert-info').replaceWith(alertHtml);
            } else {
                $('.card-body').first().prepend(alertHtml);
            }

            // Deactivate all tabs and panes (including Akses Khusus)
            $('.nav-link').removeClass('active');
            $('.tab-pane').removeClass('active show');

            // Update active state in dropdown
            $('.load-doc').removeClass('active');
            $(this).addClass('active');

            // Update active tab based on year
            $(this).closest('.dropdown').find('.dropdown-toggle').addClass('active');
        }
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