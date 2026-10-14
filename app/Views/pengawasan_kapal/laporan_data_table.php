<table id="laporanTable" class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>No</th>
      <th>Waktu Submit</th>
      <th>Nama Kapal</th>
      <th>Datang Dari</th>
      <th>Ke Pelabuhan</th>
      <th>Negara Asal</th>
      <th>Gross Tonnage</th>
      <th>Kategori</th>
      <th>Skoring</th>
      <th>Kresiko</th>
      <th>Status</th>
      <th>Verifikasi</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $nomor = 1;
    foreach ($pengawasanKapal as $data) :
    ?>
      <tr>
        <td><?= $nomor++; ?></td>
        <td>
          <?php 
          // Coba berbagai kemungkinan nama field timestamp
          $waktuSubmit = '-';
          if (isset($data['created_at'])) {
            $waktuSubmit = date('d/m/Y H:i:s', strtotime($data['created_at']));
          } elseif (isset($data['date_created'])) {
            $waktuSubmit = date('d/m/Y H:i:s', strtotime($data['date_created']));
          } elseif (isset($data['created_date'])) {
            $waktuSubmit = date('d/m/Y H:i:s', strtotime($data['created_date']));
          } elseif (isset($data['date_updated'])) {
            $waktuSubmit = date('d/m/Y H:i:s', strtotime($data['date_updated']));
          }
          echo $waktuSubmit;
          ?>
        </td>
        <td><?= $data['nama']; ?></td>
        <td><?= $data['datang_dari']; ?></td>
        <td><?= ucwords(strtolower($data['pelabuhan'])); ?></td>
        <td><?= isset($data['origin_country']) ? $data['origin_country'] : '-'; ?></td>
        <td><?= isset($data['gross_tonnage']) ? $data['gross_tonnage'] : '-'; ?></td>
        <td>
          <?php 
          // Menentukan kategori berdasarkan upload Master's Statement Letter
          // Jika ada upload surat pernyataan nahkoda = Luar Negeri
          // Jika tidak ada upload = Dalam Negeri
          $kategori = 'Dalam Negeri';
          if (isset($data['surat_pernyataan_nahkoda']) && !empty($data['surat_pernyataan_nahkoda'])) {
            $kategori = 'Luar Negeri';
          }
          ?>
          <span class="badge <?= $kategori == 'Dalam Negeri' ? 'badge-primary' : 'badge-warning'; ?>">
            <?= $kategori; ?>
          </span>
        </td>
        <td><?= $data['skoring']; ?></td>
        <td>
          <?php 
          // Hitung faktor resiko berdasarkan skoring jika kosong
          $kresiko = $data['kresiko'];
          if (empty($kresiko) && isset($data['skoring'])) {
            $skoring = (int)$data['skoring'];
            if ($skoring >= 20) {
              $kresiko = 'TINGGI';
            } elseif ($skoring >= 10 && $skoring <= 19) {
              $kresiko = 'SEDANG';
            } else {
              $kresiko = 'RENDAH';
            }
          }
          
          // Styling berdasarkan level resiko
          $badgeClass = 'badge-secondary';
          if ($kresiko == 'TINGGI') {
            $badgeClass = 'badge-danger';
          } elseif ($kresiko == 'SEDANG') {
            $badgeClass = 'badge-warning';
          } elseif ($kresiko == 'RENDAH') {
            $badgeClass = 'badge-success';
          }
          ?>
          <span class="badge <?= $badgeClass; ?>"><?= $kresiko; ?></span>
        </td>
        <td>
          <?php 
          // Cek status verifikasi
          $statusVerifikasi = isset($data['status_verifikasi']) ? $data['status_verifikasi'] : 'pending';
          
          if ($statusVerifikasi == 'pending') {
            echo '<span class="badge badge-warning">Belum Verifikasi</span>';
          } else {
            // Tampilkan status yang sudah diverifikasi
            $hasilStatus = $data['hasilStatus'];
            // Jika status masih KONFIRMASI (data lama), ubah jadi SANDAR
            if ($hasilStatus == 'KONFIRMASI') {
                $hasilStatus = 'SANDAR';
            }
            
            if (empty($hasilStatus) && isset($data['skoring'])) {
              $skoring = (int)$data['skoring'];
              if ($skoring >= 20) {
                $hasilStatus = 'ZONA KARANTINA';
              } else {
                $hasilStatus = 'SANDAR';
              }
            }
            
            // Styling berdasarkan status
            $statusClass = 'badge-secondary';
            if ($hasilStatus == 'ZONA KARANTINA') {
              $statusClass = 'badge-danger';
            } elseif ($hasilStatus == 'SANDAR') {
              $statusClass = 'badge-success';
            }
            ?>
            <div>
              <span class="badge <?= $statusClass; ?>"><?= $hasilStatus; ?></span>
              <?php
              $hasVerificationInfo = (isset($data['catatan_verifikasi']) && !empty($data['catatan_verifikasi'])) ||
                                     (isset($data['verified_by']) && !empty($data['verified_by']));
              if ($hasVerificationInfo): ?>
                <div style="font-size: 11px; color: #6c757d; margin-top: 2px;">
                  <?php if (isset($data['verified_by']) && !empty($data['verified_by'])): ?>
                    Diverifikasi oleh: <?= htmlspecialchars($data['verified_by'], ENT_QUOTES, 'UTF-8'); ?>
                    <?php if (isset($data['verified_at']) && !empty($data['verified_at'])): ?>
                      (<?= date('d/m/Y H:i', strtotime($data['verified_at'])); ?>)
                    <?php endif; ?>
                  <?php endif; ?>
                  <?php if (isset($data['catatan_verifikasi']) && !empty($data['catatan_verifikasi'])): ?>
                    <br>Catatan: <?= htmlspecialchars($data['catatan_verifikasi'], ENT_QUOTES, 'UTF-8'); ?>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
            <?php
          }
          ?>
        </td>
        <td>
          <?php
          if ($statusVerifikasi != 'pending') {
            $hasVerificationInfo = (isset($data['catatan_verifikasi']) && !empty($data['catatan_verifikasi'])) ||
                                   (isset($data['verified_by']) && !empty($data['verified_by']));
            if ($hasVerificationInfo) {
              $verText = "";
              if (!empty($data['verified_by'])) {
                $verText .= "Diverifikasi oleh: " . $data['verified_by'];
                if (!empty($data['verified_at'])) {
                  $verText .= " (" . date('d/m/Y H:i', strtotime($data['verified_at'])) . ")";
                }
              }
              if (!empty($data['catatan_verifikasi'])) {
                $verText .= ($verText ? " " : "") . "Catatan: " . $data['catatan_verifikasi'];
              }
              echo htmlspecialchars($verText, ENT_QUOTES, 'UTF-8');
            } else {
              echo "-";
            }
          } else {
            echo "-";
          }
          ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
