<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>

<!-- Custom CSS -->
<style>
    .card-pdf {
        cursor: pointer;
        transition: all 0.3s ease; /* Animasi halus */
    }
    .card-pdf:hover {
        box-shadow: 0 0 8px 2px rgb(22 179 172), 0 1px 12px 5px rgb(22 179 172);
        background: rgb(179 255 252 / 43%);
    }
    .card-footer {
        font-size: 14px; /* Ukuran teks footer */
        word-wrap: break-word; /* Memastikan teks panjang tidak keluar dari batas */
    }
    .card-pdf.hover-effect {
        box-shadow: 0 0 8px 2px rgb(22 179 172), 0 1px 12px 5px rgb(22 179 172);
        background: rgb(179 255 252 / 43%);
        transition: all 0.3s ease;
    }
</style>

<section class="content mb-3">
    <div class="container-fluid text-center">
        <?= !empty($files) ? '<p class="float-right">Total SK pada folder ' . $folder . ' sebanyak ' . count($files) . ' file.</p>' : ''; ?>
        <?php if(session()->get('role') == 'Admin'): ?>
        <p class="text-left">
        <i><b>Catatan</b>: <br />Penambahan file hanya dilakukan di server pada direktory <span class="text-primary">\writable\sk\{nama_folder}</span>. <span class="text-danger">Konsep penamaan folder dinamai berdasarkan tahun!</span> 
        <br />penamaan {nama_folder} folder hanya berisi angka saja, lalu buat sidebar tambahan dengan contoh target link "<span class="text-primary">/surat_sk/2026</span>"</i>
        </p>
        <?php endif; ?>
        <div class="form-inline justify-content-center">
            <div class="input-group">
                <input class="form-control" id="search-bar" type="search" placeholder="Search">
                <div class="input-group-append">
                    <button class="btn btn-info" type="button" id="search-button-sk">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <?php if (!empty($files)): ?>
                <!-- Elemen File PDF -->
                <?php foreach ($files as $file): ?>
                    <div class="col-sm-3 mb-4">
                        <div class="card card-pdf" data-folder="<?= esc($folder) ?>" data-file="<?= esc($file['name']) ?>" data-kategori="<?= esc($kategori ?? '') ?>" onclick="getModalPdfSK.call(this)" title="<?= esc($file['name']) ?>">
                            <div class="card-body text-center text-danger">
                                <i class="fas fa-5x fa-file-pdf"></i>
                            </div>
                            <div class="card-footer text-center" style="height: 80px; overflow: hidden;">
                                <?= esc($file['name']) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Tidak ada file PDF yang ditemukan.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="modal-xl" tabindex="-1" role="dialog" aria-labelledby="modalXlLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalXlLabel">Pratinjau PDF</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Konten iframe akan dimuat di sini -->
                <div id="pdf-container"></div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    function getModalPdfSK() {
    let folder   = $(this).data('folder');
    let kategori = $(this).data('kategori');
    let file     = $(this).data('file');

    let url = "<?= site_url('sk/data/view') ?>/" + folder;

    if (kategori) {
        url += "/" + kategori;
    }

    url += "/" + file;

    $.ajax({
        url: url,
        method: "GET",
        xhrFields: {
            responseType: 'blob' // ⬅️ penting: karena PDF
        },
        success: function(blob) {

            let pdfUrl = URL.createObjectURL(blob);

            let newWindow = window.open('', '_blank', 'width=900,height=650');

            const doc = newWindow.document;
            doc.open();
            doc.write(`
                <!DOCTYPE html>
                <html lang="id">
                <head>
                    <meta charset="UTF-8">
                    <title>Pratinjau PDF</title>
                    <style>
                        body {
                            margin: 0;
                            height: 100vh;
                        }
                        iframe {
                            width: 100%;
                            height: 100%;
                            border: none;
                        }
                    </style>
                </head>
                <body>
                    <iframe src="${pdfUrl}"></iframe>
                </body>
                </html>
            `);
            doc.close();
        },
        error: function() {
            alert("File PDF tidak ditemukan atau gagal dimuat.");
        }
    });
}

</script>
<script>
$(document).ready(function() {
    // Fungsi pencarian saat tombol keyboard ditekan
    $('#search-bar').on('keyup', function() {
        performSearch();
    });

    // Fungsi pencarian saat tombol search diklik
    $('#search-button-sk').on('click', function() {
        performSearch();

        // Tambahkan efek hover selama 2 detik
        $('.card-pdf:visible').addClass('hover-effect');
        setTimeout(function() {
            $('.card-pdf').removeClass('hover-effect');
        }, 300); // Efek hover hilang setelah 2 detik
    });

    // Fungsi utama untuk melakukan pencarian
    function performSearch() {
        let searchText = $('#search-bar').val().toLowerCase();
        let searchWords = searchText.split(' ').filter(word => word.trim() !== '');

        $('.card-pdf').each(function() {
            let fileName = $(this).data('file').toLowerCase();
            let footerText = $(this).find('.card-footer').text().toLowerCase();
            let targetText = `${fileName} ${footerText}`;

            let isMatch = searchWords.every(word => targetText.includes(word));

            if (isMatch) {
                $(this).closest('.col-sm-3').show();
            } else {
                $(this).closest('.col-sm-3').hide();
            }
        });
    }
});
</script>
<?= $this->endSection(); ?>