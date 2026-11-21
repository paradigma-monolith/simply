<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>
<?= view('crud/_sinadi_form_partial', [
  'isEdit'      => false,
  'isDalamKota' => false,
  'setting'     => $setting ?? [],
  'pegawai'     => $pegawai ?? [],
  'kota'        => $kota ?? [],
]); ?>
<?= $this->endSection(); ?>
