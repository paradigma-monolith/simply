<?php
$tplList = is_array($templateFiles ?? null) ? $templateFiles : [];
$tplGrouped = [];
foreach ($tplList as $tf) {
    $tplGrouped[$tf['folder']][] = $tf;
}
$iconMap = [
    'pdf'  => 'fas fa-file-pdf text-danger',
    'xls'  => 'fas fa-file-excel text-success',
    'xlsx' => 'fas fa-file-excel text-success',
    'csv'  => 'fas fa-file-csv text-success',
    'doc'  => 'fas fa-file-word text-primary',
    'docx' => 'fas fa-file-word text-primary',
    'ppt'  => 'fas fa-file-powerpoint text-warning',
    'pptx' => 'fas fa-file-powerpoint text-warning',
    'zip'  => 'fas fa-file-archive text-secondary',
    'rar'  => 'fas fa-file-archive text-secondary',
    'jpg'  => 'fas fa-file-image text-info',
    'jpeg' => 'fas fa-file-image text-info',
    'png'  => 'fas fa-file-image text-info',
];
?>

<div class="modal fade" id="modalTemplateTracingTb" tabindex="-1" role="dialog" aria-labelledby="modalTemplateTracingTbLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-teal text-white py-2">
        <h5 class="modal-title" id="modalTemplateTracingTbLabel">
          <i class="fas fa-file-download mr-2"></i>Download Template Dokumen Tracing TB
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php if (!empty($tplList)) : ?>
          <div class="d-flex flex-wrap justify-content-between align-items-center mb-3" style="gap: 8px;">
            <div class="input-group input-group-sm" style="max-width: 320px;">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
              </div>
              <input type="text" id="searchTemplateTracingTb" class="form-control" placeholder="Cari nama template...">
            </div>
            <?php if (count($tplList) >= 2 && class_exists('\ZipArchive')) : ?>
              <a href="/sinadi/tracing-tb/download-template-zip" class="btn btn-sm btn-success">
                <i class="fas fa-file-archive mr-1"></i> Download Semua (<?= count($tplList); ?> File .ZIP)
              </a>
            <?php endif; ?>
          </div>

          <?php foreach ($tplGrouped as $folderName => $items) : ?>
            <?php if (count($tplGrouped) > 1 || $folderName !== 'Umum') : ?>
              <div class="small font-weight-bold text-uppercase text-muted mt-2 mb-1">
                <i class="fas fa-folder-open text-warning mr-1"></i> <?= esc($folderName); ?>
              </div>
            <?php endif; ?>
            <div class="list-group mb-2">
              <?php foreach ($items as $item) :
                $iconCls = $iconMap[$item['ext']] ?? 'fas fa-file-alt text-secondary';
              ?>
                <div class="list-group-item py-2 px-3 d-flex justify-content-between align-items-center tpl-tracing-item" data-search="<?= esc(strtolower($item['name'] . ' ' . $item['folder'])); ?>">
                  <div class="d-flex align-items-center pr-2" style="min-width: 0; gap: 10px;">
                    <i class="<?= $iconCls; ?> fa-lg flex-shrink-0"></i>
                    <div style="min-width: 0;">
                      <div class="font-weight-bold text-dark text-truncate" title="<?= esc($item['name']); ?>"><?= esc($item['name']); ?></div>
                      <small class="text-muted text-uppercase"><?= esc($item['ext'] ?: 'FILE'); ?> &bull; <?= esc($item['size_label']); ?></small>
                    </div>
                  </div>
                  <a href="<?= esc($item['download_url']); ?>" class="btn btn-sm btn-outline-primary text-nowrap flex-shrink-0">
                    <i class="fas fa-download mr-1"></i> Download
                  </a>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
          <div class="text-center py-4 text-muted">
            <i class="fas fa-folder-open fa-3x mb-3 text-secondary"></i>
            <h6 class="font-weight-bold text-dark">Belum Ada File Template Tersedia</h6>
            <p class="small mb-0">
              Letakkan file template dokumen (<code>.xlsx</code>, <code>.docx</code>, <code>.pdf</code>, dll.) ke folder:<br>
              <code>template doc tracing tb</code><br>
              Daftar file akan tampil otomatis di sini untuk diunduh saat pengajuan.
            </p>
          </div>
        <?php endif; ?>
      </div>
      <div class="modal-footer py-2">
        <small class="text-muted mr-auto">Total template: <strong><?= count($tplList); ?></strong> file</small>
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
  $(function() {
    $('#searchTemplateTracingTb').on('input keyup', function() {
      var q = String($(this).val() || '').toLowerCase().trim();
      $('.tpl-tracing-item').each(function() {
        var hay = String($(this).data('search') || '');
        $(this).toggleClass('d-none', q !== '' && hay.indexOf(q) === -1)
          .toggleClass('d-flex', q === '' || hay.indexOf(q) !== -1);
      });
    });
  });
</script>
