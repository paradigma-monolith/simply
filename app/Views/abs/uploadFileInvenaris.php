<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Import Data</title>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<style>
  /* Reset default styles */
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  /* Body styles */
  body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    padding: 20px;
  }

  /* Form styles */
  .form-relaxtime-import-pagu {
    max-width: 400px;
    margin: 0 auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  /* Form input styles */
  .form-relaxtime-import-pagu input[type="text"],
  .form-relaxtime-import-pagu input[type="file"],
  .form-relaxtime-import-pagu input[type="submit"] {
    display: block;
    width: 100%;
    margin-bottom: 15px;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 3px;
  }

  .form-relaxtime-import-pagu input[type="submit"] {
    background-color: #007bff;
    color: #fff;
    border: none;
    cursor: pointer;
  }

  .form-relaxtime-import-pagu input[type="submit"]:hover {
    background-color: #0056b3;
  }

  .btn-download:hover {
    background-color: #218838 !important;
  }

  .container {
    display: flex;
    justify-content: space-between;
    overflow: auto;
    background-color: #fff;
  }

  /* Loading Overlay */
  #loading-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8);
    z-index: 9999;
    justify-content: center;
    align-items: center;
    flex-direction: column;
  }

  .spinner {
    border: 5px solid #f3f3f3;
    border-top: 5px solid #007bff;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
    margin-bottom: 15px;
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }

  /* Style the input file */
  input[type="file"] {
    margin-bottom: 10px;
  }

  /* Style the table */
  table {
    width: 100%;
    border-collapse: collapse;
  }

  /* Style the table headers */
  th {
    background-color: #f2f2f2;
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
  }

  /* Style the table rows */
  tr {
    border: 1px solid #ddd;
  }

  /* Style the table data cells */
  td {
    border: 1px solid #ddd;
    padding: 8px;
  }

  /* Style the container */
  .container {
    margin: 20px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
  }

  /* Style the pre element */
  pre {
    background-color: #f9f9f9;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    overflow-x: auto;
  }
</style>
</head>

<body>
  <!-- Loading Overlay -->
  <div id="loading-overlay">
    <div class="spinner"></div>
    <h3 style="color: #007bff; font-family: Arial, sans-serif;">Sedang Mengimport Data...</h3>
    <p style="color: #666; font-family: Arial, sans-serif; margin-top: 5px;">Mohon tunggu, ini mungkin membutuhkan waktu beberapa menit.</p>
  </div>

  <div>

    <form id="importForm" action="<?= base_url('abs/upload-file') ?>" class="form-relaxtime-import-pagu" method="post" autocomplete="off" enctype="multipart/form-data">
      <input type="file" accept=".xlsx, .xls, .csv" name="file" required>
      <div style="display: flex; gap: 10px; margin-bottom: 15px;">
        <input type="submit" value="Import Data" style="margin-bottom: 0;">
        <a href="<?= base_url('abs/download-template') ?>" class="btn-download" style="display: block; width: 100%; padding: 10px; font-size: 16px; border-radius: 3px; background-color: #28a745; color: #fff; text-align: center; text-decoration: none;">Download Template</a>
      </div>
    </form>
  </div>

  <script>
    document.getElementById('importForm').addEventListener('submit', function(e) {
      document.getElementById('loading-overlay').style.display = 'flex';
    });

    <?php if (session()->getFlashdata('success')) : ?>
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        html: '<?= session()->getFlashdata('success') ?>'
      });
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        html: '<?= session()->getFlashdata('error') ?>'
      });
    <?php endif; ?>
  </script>
</body>

</html>