<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>
<?= view('crud/_sinadi_form_partial', [
  'isEdit'      => true,
  'isDalamKota' => true,
  'setting'     => $setting ?? [],
  'pegawai'     => $pegawai ?? [],
  'kota'        => $kota ?? [],
  'perjalanan'  => $perjalanan ?? [],
  'peserta'     => $peserta ?? [],
]); ?>
<?= $this->endSection(); ?>
