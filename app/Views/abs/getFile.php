<div class="table-responsive">
  <table class="table table-bordered table-hover table-sm mb-0">
    <thead class="bg-light">
      <tr>
        <th width="6%" class="text-center">No</th>
        <th>Nama File PDF</th>
        <th width="14%" class="text-center">Ukuran Kertas</th>
        <th width="16%" class="text-center">Estimasi Halaman</th>
        <th width="24%">Diproses Oleh</th>
        <th width="12%" class="text-center">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php $rows = $getFile ?? []; ?>
      <?php if (empty($rows)) : ?>
        <tr>
          <td colspan="6" class="text-center text-muted py-4">Belum ada batch file QR Code yang dibuat.</td>
        </tr>
      <?php else : ?>
        <?php $nomor = 1; ?>
        <?php foreach ($rows as $data) : ?>
          <tr>
            <td class="text-center font-weight-bold align-middle"><?= $nomor++; ?></td>
            <td class="align-middle font-weight-bold">
              <i class="fas fa-file-pdf text-danger mr-1"></i> <?= esc((string) ($data['file'] ?? '')); ?>
            </td>
            <td class="text-center align-middle">
              <span class="badge badge-light border px-2 py-1"><?= esc((string) ($data['kertas'] ?? '')); ?></span>
            </td>
            <td class="text-center align-middle"><?= (int) ($data['lembar'] ?? 0); ?> Halaman</td>
            <td class="align-middle">
              <div class="font-weight-bold" style="font-size:0.78rem;"><?= esc((string) ($data['nama'] ?? '-')); ?></div>
              <small class="text-muted"><?= esc((string) ($data['created_at'] ?? '')); ?></small>
            </td>
            <td class="text-center align-middle text-nowrap">
              <a href="/abs/download/<?= esc((string) ($data['file'] ?? ''), 'attr'); ?>"
                 target="_blank"
                 class="btn btn-xs btn-info mr-1"
                 title="Unduh / Generate PDF">
                <i class="fas fa-file-download"></i>
              </a>
              <form action="/abs/dlt_file_abs/<?= (int) ($data['id_abs_export_label'] ?? 0); ?>" method="post" class="d-inline formHapusData">
                <?= csrf_field(); ?>
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-xs btn-outline-danger" title="Hapus Batch File">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
