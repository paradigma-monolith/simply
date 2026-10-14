<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">

  <div class="card">
    <div class="card-header">
      <h3 class="card-title text-right">
      </h3>
      <div class="card-tools">
        <button class="btn btn-primary btn-add-obat-uklw">
          <i class="fas fa-plus"> </i>
          Tambah Data Obat
        </button>
      </div>
    </div>
    <div class="card-body px-0">
      <table id="tableWithoutExporting" class="table table-striped projects">
        <thead>
            <tr>
              <th>No</th>
              <th>Golongan</th>
              <th>Nama Obat</th>
              <th>Kategori</th>
              <th>Satuan</th>
              <th>Penyimpanan</th>
              <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
          <?php 
          $nomor = 1;
          foreach ($obat as $data) : ?>
            <tr>
              <td><?= $nomor++ ?></td>
              <td><?= $data['golongan']; ?></td>
              <td><?= $data['nama']; ?></td>
              <td><?= $data['kategori']; ?></td>
              <td><?= $data['satuan']; ?></td>
              <td><?= $data['penyimpanan']; ?></td>
              <td>
                  <button 
                  class="btn btn-sm btn-warning btn-edit-obat-uklw"
                  data-id="<?= $data['id_uklw_obat']; ?>"
                  data-golongan="<?= $data['golongan']; ?>"
                  data-nama="<?= $data['nama']; ?>"
                  data-kategori="<?= $data['kategori']; ?>"
                  data-satuan="<?= $data['satuan']; ?>"
                  data-penyimpanan="<?= $data['penyimpanan']; ?>"
                  >
                    <i class="fas fa-edit"></i>
                  </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</section>

<section class="formulir-uklw-obat d-none">
    <div class="container">
        <h2 class="text-center mb-4">Formulir Data Obat</h2>
        <form action="/uklw/update_obat" method="POST">
            <input type="hidden" class="form-control" name="id">
            <!-- Golongan -->
            <div class="form-group row">
                <label for="golongan" class="col-sm-2 col-form-label">Golongan</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="golongan" placeholder="Masukkan Golongan Obat" required>
                </div>
            </div>

            <!-- Nama -->
            <div class="form-group row">
                <label for="nama" class="col-sm-2 col-form-label">Nama Obat</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama Obat" required>
                </div>
            </div>

            <!-- Kategori -->
            
            <div class="form-group row">
                <label for="nama" class="col-sm-2 col-form-label">Kategori Obat</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="kategori" placeholder="Masukkan Kategori Obat" required>
                </div>
            </div>

            <!-- Satuan -->
            <div class="form-group row">
                <label for="satuan" class="col-sm-2 col-form-label">Satuan</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="satuan" placeholder="Masukkan Satuan (Contoh: Tablet, Botol)" required>
                </div>
            </div>

            <!-- Penyimpanan -->
            <div class="form-group row">
                <label for="penyimpanan" class="col-sm-2 col-form-label">Penyimpanan</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="penyimpanan" rows="3" placeholder="Masukkan Informasi Penyimpanan Obat" required></textarea>
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="form-group row">
                <div class="col-sm-10 offset-sm-2 text-right">
                    <button type="submit" class="btn btn-success">Simpan Data</button>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
    $(document).on("click", ".btn-add-obat-uklw", function(){
        let formulir = $(".formulir-uklw-obat").html();
        
        $("#modal-lg").find(".modal-body").html(formulir);
        $("#modal-lg").modal("show");
    });
    $(document).on("click", ".btn-edit-obat-uklw", function(){
        let formulir = $(".formulir-uklw-obat").html();
        let input = $("#modal-lg").find(".modal-body");
        let data = $(this);
        
        $("#modal-lg").find(".modal-body").html(formulir);
        
        input.find('input[name="id"]').val(data.data("id"))
        input.find('input[name="golongan"]').val(data.data("golongan"))
        input.find('input[name="nama"]').val(data.data("nama"))
        input.find('input[name="kategori"]').val(data.data("kategori"))
        input.find('input[name="satuan"]').val(data.data("satuan"))
        input.find('textarea[name="penyimpanan"]').val(data.data("penyimpanan"))
        
        $("#modal-lg").modal("show");
    });
</script>

<?= $this->endSection(); ?>