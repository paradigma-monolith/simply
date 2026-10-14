<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- /.col-md-6 -->
      <!-- ================================================================= -->
      <div class="col-xl-3 col-lg-6">
        <!-- small box -->
        <div class="small-box-custom bg-primary">
          <div class="inner-custom">
            <a href="#" class="small-box-header">PHQC</a>
            <h3 id="total_phqc">-</h3>

            <div class="row">
              <p>Terakhir kali diinput <br />(<span id="tgl_phqc"></span>)</small><br />
                <strong id="phqc">-</strong> PHQC
              </p>
            </div>
          </div>
          <div class="icon-custom">
            <!-- <i class="fas fa-plane-arrival mt-5"></i> -->
            <i class="fas fa-file-alt mt-5"></i>
          </div>
        </div>
      </div>
      <!-- ================================================================= -->
      <div class="col-xl-3 col-lg-6">
        <!-- small box -->
        <div class="small-box-custom bg-secondary">
          <div class="inner-custom">
            <a href="#" class="small-box-header">COP</a>
            <h3 id="total_cop">-</h3>

            <div class="row">
              <p>Terakhir kali diinput <br />(<span id="tgl_cop"></span>)</small><br />
                <strong id="cop">-</strong> COP
              </p>
            </div>
          </div>
          <div class="icon-custom">
            <!-- <i class="fas fa-plane-arrival mt-5"></i> -->
            <i class="fas fa-file-alt mt-5"></i>
          </div>
        </div>
      </div>
      <!-- ================================================================= -->
      <div class="col-xl-3 col-lg-6">
        <!-- small box -->
        <div class="small-box-custom bg-success">
          <div class="inner-custom">
            <a href="#" class="small-box-header">SSCEC</a>
            <h3 id="total_sscec">-</h3>

            <div class="row">
              <p>Terakhir kali diinput <br />(<span id="tgl_sscec"></span>)</small><br />
                <strong id="sscec">-</strong> SSCEC
              </p>
            </div>
          </div>
          <div class="icon-custom">
            <!-- <i class="fas fa-plane-arrival mt-5"></i> -->
            <i class="fas fa-file-alt mt-5"></i>
          </div>
        </div>
      </div>
      <!-- ================================================================= -->
      <div class="col-xl-3 col-lg-6">
        <!-- small box -->
        <div class="small-box-custom bg-danger">
          <div class="inner-custom">
            <a href="#" class="small-box-header">SSCC</a>
            <h3 id="total_sscc">-</h3>

            <div class="row">
              <p>Terakhir kali diinput <br />(<span id="tgl_sscc"></span>)</small><br />
                <strong id="sscc">-</strong> SSCC
              </p>
            </div>
          </div>
          <div class="icon-custom">
            <!-- <i class="fas fa-plane-arrival mt-5"></i> -->
            <i class="fas fa-file-alt mt-5"></i>
          </div>
        </div>
      </div>
      <!-- ================================================================= -->
      <div class="col-xl-3 col-lg-6">
        <!-- small box -->
        <div class="small-box-custom bg-primary">
          <div class="inner-custom">
            <a href="#" class="small-box-header">P3K</a>
            <h3 id="total_p3k">-</h3>

            <div class="row">
              <p>Terakhir kali diinput <br />(<span id="tgl_p3k"></span>)</small><br />
                <strong id="p3k">-</strong> P3K
              </p>
            </div>
          </div>
          <div class="icon-custom">
            <!-- <i class="fas fa-plane-arrival mt-5"></i> -->
            <i class="fas fa-medkit mt-5"></i>
          </div>
        </div>
      </div>
      <!-- ================================================================= -->
      <div class="col-xl-3 col-lg-6">
        <!-- small box -->
        <div class="small-box-custom bg-secondary">
          <div class="inner-custom">
            <a href="#" class="small-box-header">ICV</a>
            <h3 id="total_icv">-</h3>

            <div class="row">
              <p>Terakhir kali diinput <br />(<span id="tgl_icv"></span>)</small><br />
                <strong id="icv">-</strong> ICV
              </p>
            </div>
          </div>
          <div class="icon-custom">
            <!-- <i class="fas fa-plane-arrival mt-5"></i> -->
            <i class="fas fa-book mt-5"></i>
          </div>
        </div>
      </div>
      <!-- ================================================================= -->
      <div class="col-xl-3 col-lg-6">
        <!-- small box -->
        <div class="small-box-custom bg-success">
          <div class="inner-custom">
            <a href="#" class="small-box-header">SKLT</a>
            <h3 id="total_sklt">-</h3>

            <div class="row">
              <p>Terakhir kali diinput <br />(<span id="tgl_sklt"></span>)</small><br />
                <strong id="sklt">-</strong> SKLT
              </p>
            </div>
          </div>
          <div class="icon-custom">
            <!-- <i class="fas fa-plane-arrival mt-5"></i> -->
            <i class="fas fa-book mt-5"></i>
          </div>
        </div>
      </div>
      <!-- ================================================================= -->
      <div class="col-xl-3 col-lg-6">
        <!-- small box -->
        <div class="small-box-custom bg-danger">
          <div class="inner-custom">
            <a href="#" class="small-box-header">SIAOS</a>
            <h3 id="total_siaos">-</h3>

            <div class="row">
              <p>Terakhir kali diinput <br />(<span id="tgl_siaos"></span>)</small><br />
                <strong id="siaos">-</strong> SIAOS
              </p>
            </div>
          </div>
          <div class="icon-custom">
            <!-- <i class="fas fa-plane-arrival mt-5"></i> -->
            <i class="fas fa-book mt-5"></i>
          </div>
        </div>
      </div>
      <!-- ================================================================= -->
      <div class="col-xl-3 col-lg-6">
        <!-- small box -->
        <div class="small-box-custom bg-primary">
          <div class="inner-custom">
            <a href="#" class="small-box-header">Surat Izin Angkut Jenazah</a>
            <h3 id="total_siaj">-</h3>

            <div class="row">
              <p>Terakhir kali diinput <br />(<span id="tgl_siaj"></span>)</small><br />
                <strong id="siaj">-</strong> Surat Izin Angkut Jenazah
              </p>
            </div>
          </div>
          <div class="icon-custom">
            <!-- <i class="fas fa-plane-arrival mt-5"></i> -->
            <i class="fas fa-book mt-5"></i>
          </div>
        </div>
      </div>
      <!-- ================================================================= -->
      <div class="col-xl-3 col-lg-6">
        <!-- small box -->
        <div class="small-box-custom bg-secondary">
          <div class="inner-custom">
            <a href="#" class="small-box-header">Pengujian Kesehatan (KIR)</a>
            <h3 id="total_kir">-</h3>

            <div class="row">
              <p>Terakhir kali diinput <br />(<span id="tgl_kir"></span>)</small><br />
                <strong id="kir">-</strong> Pengujian Kesehatan (KIR)
              </p>
            </div>
          </div>
          <div class="icon-custom">
            <!-- <i class="fas fa-plane-arrival mt-5"></i> -->
            <i class="fas fa-id-card mt-5"></i>
          </div>
        </div>
      </div>
      <!-- ================================================================= -->
      <div class="col-xl-3 col-lg-6">
        <!-- small box -->
        <div class="small-box-custom bg-secondary">
          <div class="inner-custom">
            <a href="#" class="small-box-header">Buku Kesehatan</a>
            <h3 id="total_bukes">-</h3>

            <div class="row">
              <p>Terakhir kali diinput <br />(<span id="tgl_bukes"></span>)</small><br />
                <strong id="bukes">-</strong> Buku Kesehatan
              </p>
            </div>
          </div>
          <div class="icon-custom">
            <!-- <i class="fas fa-plane-arrival mt-5"></i> -->
            <i class="fas fa-book mt-5"></i>
          </div>
        </div>
      </div>
      <!-- ================================================================= -->
      <div class="col-xl-3 col-lg-6">
        <!-- small box -->
        <div class="small-box-custom bg-success">
          <div class="inner-custom">
            <a href="#" class="small-box-header">Vaksin MM</a>
            <h3 id="total_vaksin_mm">-</h3>

            <div class="row">
              <p>Terakhir kali diinput <br />(<span id="tgl_vaksin_mm"></span>)</small><br />
                <strong id="vaksin_mm">-</strong> Vaksin MM
              </p>
            </div>
          </div>
          <div class="icon-custom">
            <!-- <i class="fas fa-plane-arrival mt-5"></i> -->
            <i class="fas fa-disease mt-5"></i>
          </div>
        </div>
      </div>
      <!-- ================================================================= -->
      <div class="col-xl-3 col-lg-6">
        <!-- small box -->
        <div class="small-box-custom bg-danger">
          <div class="inner-custom">
            <a href="#" class="small-box-header">Vaksin YF</a>
            <h3 id="total_vaksin_yf">-</h3>

            <div class="row">
              <p>Terakhir kali diinput <br />(<span id="tgl_vaksin_yf"></span>)</small><br />
                <strong id="vaksin_yf">-</strong> Vaksin YF
              </p>
            </div>
          </div>
          <div class="icon-custom">
            <!-- <i class="fas fa-plane-arrival mt-5"></i> -->
            <i class="fas fa-disease mt-5"></i>
          </div>
        </div>
      </div>
      <!-- ================================================================= -->
      <div class="col-xl-3 col-lg-6">
        <!-- small box -->
        <div class="small-box-custom bg-primary">
          <div class="inner-custom">
            <a href="#" class="small-box-header">Vaksin Covid</a>
            <h3 id="total_vaksin_covid">-</h3>

            <div class="row">
              <p>Terakhir kali diinput <br />(<span id="tgl_vaksin_covid"></span>)</small><br />
                <strong id="vaksin_covid">-</strong> Vaksin Covid
              </p>
            </div>
          </div>
          <div class="icon-custom">
            <!-- <i class="fas fa-plane-arrival mt-5"></i> -->
            <i class="fas fa-disease mt-5"></i>
          </div>
        </div>
      </div>
      <!-- ================================================================= -->
      <div class="col-xl-3 col-lg-6">
        <!-- small box -->
        <div class="small-box-custom bg-secondary">
          <div class="inner-custom">
            <a href="#" class="small-box-header">Vaksin Polio</a>
            <h3 id="total_vaksin_polio">-</h3>

            <div class="row">
              <p>Terakhir kali diinput <br />(<span id="tgl_vaksin_polio"></span>)</small><br />
                <strong id="vaksin_polio">-</strong> Vaksin Polio
              </p>
            </div>
          </div>
          <div class="icon-custom">
            <!-- <i class="fas fa-plane-arrival mt-5"></i> -->
            <i class="fas fa-disease mt-5"></i>
          </div>
        </div>
      </div>
      <!-- ================================================================= -->

      <!-- ================================================================= -->
    </div>
  </div>

  <script>

  </script>
</section>
<!-- /.content -->
<?= $this->endSection(); ?>