<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>
<!-- Main content -->
<section class="content">
  <iframe
    src="<?= $iframe['link']; ?>"
    title="CDC Website"
    style="width: 100%; height: 98vh; border: none;
    box-shadow: 10px 12px 4px 15px rgb(163 177 182), -10px 12px 4px 15px rgb(163 177 182), 10px 152px 6px 12px rgb(163 177 182), -10px 152px 6px 12px rgb(163 177 182);
    -webkit-box-shadow: 10px 12px 4px 15px rgb(163 177 182), -10px 12px 4px 15px rgb(163 177 182), 10px 152px 6px 12px rgb(163 177 182), -10px 152px 6px 12px rgb(163 177 182);
    -moz-box-shadow: 10px 12px 4px 15px rgb(163 177 182), -10px 12px 4px 15px rgb(163 177 182), 10px 152px 6px 12px rgb(163 177 182), -10px 152px 6px 12px rgb(163 177 182);
    "
    allowfullscreen>
  </iframe>
</section>
<?= $this->endSection(); ?>