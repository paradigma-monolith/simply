<style>
  .fc-daygrid-day-events {
    z-index: -1;
  }
</style>
<script>
  $(document).ready(function() {
    var calendarEl = document.getElementById('calendarDashboardSicute');
    var today = new Date();
    var year = today.getFullYear();
    var month = (today.getMonth() + 1).toString().padStart(2, '0');
    var day = today.getDate().toString().padStart(2, '0');
    var formattedDate = year + '-' + month + '-' + day;

    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth'
      },
      initialView: 'dayGridMonth',
      initialDate: `${formattedDate}`,
      editable: false,
      selectable: true,
      dayMaxEvents: true,
      weekends: false,
      events: [
        <?php foreach ($getSinadi as $data) { ?> {
            title: '<?= $data['kegiatan']; ?> Kegiatan',
            start: '<?= $data['tgl_pelaksanaan']; ?>',
            color: '#16B3AC'
          },
        <?php }; ?>
      ],
      dateClick: function(info) {
        var clickedDate = info.dateStr;
        // alert(clickedDate)
        getDataSinadi(clickedDate)
      },
      dayRender: function(info) {
        var currentMonth = calendar.view.currentStart.getMonth();
        var cellMonth = info.date.getMonth();

        if (currentMonth !== cellMonth) {
          info.el.classList.add('fc-disabled'); // Menambahkan kelas untuk men-disable tampilan sel di luar bulan
        }
      }
    });

    // Menambahkan gaya CSS secara dinamis
    var style = document.createElement('style');
    style.innerHTML = '.fc-disabled { pointer-events: none; opacity: 0.5; }';
    document.head.appendChild(style);

    calendar.render();

    // Fetch hari libur dari API dan tambahkan ke kalender
    fetch('https://libur.deno.dev/api')
      .then(res => res.json())
      .then(data => {
        data.forEach(holiday => {
          calendar.addEvent({
            title: holiday.name,
            start: holiday.date,
            color: '#dc3545', // warna merah untuk hari libur
            allDay: true
          });
        });
      })
      .catch(err => console.error('Error fetching holidays:', err));
  });
</script>
<div class="row">
  <div class="col-sm-8">
    <div class="card">
      <div class="card-header">
        <h4>Data Perjalanan Dinas</h4>
      </div>
      <div class="card-body">
        <div id='calendarDashboardSicute'></div>
      </div>
    </div>
  </div>
  <div class="col-sm-4">
    <?php if ($akses) : ?>
      <div class="card">
        <div class="card-header">
          Export data sinadi
        </div>
        <div class="card-body">
          <form action="/sinadi/export/excel" method="post">
            <div class="form-group">
              <label for="">Range of date</label>
              <input type="text" name="tanggal" class="form-control date-sinadi" required>
            </div>
            <button type="submit" class="float-right btn btn-success"><i class="fas fa-excel"></i> Export</button>
          </form>
        </div>
      </div>
    <?php endif; ?>
    <div class="card mt-3">
      <div class="card-header">
        Rincian Perjalanan Dinas
      </div>
      <div class="card-body" style="min-height: 55vh; max-height: 60vh; overflow-y: scroll;">
        <ul class="list-group list-group-unbordered mb-3 px-3 dataRincianPerjalananDinas">
          <li class="list-group-item">
            "Anda bisa mengklik kolom tanggal yang mempunyai kegiatan untuk melihat rincian"
          </li>
        </ul>
      </div>
    </div>
    <!--<div class="card mt-2">-->
    <!--  <div class="card-header">-->
    <!--    Nominal Pengeluaran Sinadi Yang Terekam Sistem-->
    <!--  </div>-->
    <!--  <div class="card-footer" style="min-height: 10vh;">-->
    <!--    <ul class="list-group list-group-unbordered mb-2 px-2">-->
    <!--      <li class="list-group-item">-->
    <!--        <div class="">-->
    <!--          <h6> Transport :<strong> Rp <?= number_format($nominal['transport'], '2', '.', ','); ?></strong></h6>-->
    <!--          <h6> Uang Harian:<strong> Rp <?= number_format($nominal['harian'], '2', '.', ','); ?></strong></h6>-->
    <!--          <h6> Biaya Penginapan :<strong> Rp <?= number_format($nominal['penginapan'], '2', '.', ','); ?></strong></h6>-->
    <!--        </div>-->
    <!--        <div class="float-right align-bottom text-right">-->
    <!--          <p class="mb-0 pb-0">Total Keseluruhan</p>-->
    <!--          <h3><strong> Rp <?= number_format($nominal['perjalanan'], '2', '.', ','); ?></strong></h3>-->
    <!--        </div>-->
    <!--      </li>-->
    <!--    </ul>-->
    <!--  </div>-->
    <!--</div>-->
  </div>
</div>

<script>
  var prevYear;
  var elementul = $(".dataRincianPerjalananDinas").html()

  $(document).ready(function() {
    $('.date-sinadi').daterangepicker({
      startDate: moment().subtract(1, 'months').startOf('month'),
      endDate: moment(),
      locale: {
        format: 'YYYY-MM-DD'
      }
    });
  });

  function getDataSinadi(tanggal) {

    $.ajax({
      url: `/home/getSinadi`,
      data: {
        tanggal: tanggal
      },
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        $(".dataRincianPerjalananDinas").html("<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>")

      },
      success: function(response) {
        if (response.data) {
          var id_perjalanan = '';
          $(".dataRincianPerjalananDinas").html(
            response.data.map(element => {
              var listItem = '';

              if (id_perjalanan != element['id_perjalanan']) {
                id_perjalanan = element['id_perjalanan'];
                listItem = '<li class="list-group-item">';
                listItem += `<p class="mb-0"><strong>${(element['tgl_pelaksanaan'] == element['tgl_pelaksanaan_akhir']) ? formatTanggalIndo(element['tgl_pelaksanaan']) : `${formatTanggalIndo(element['tgl_pelaksanaan'])} s/d ${formatTanggalIndo(element['tgl_pelaksanaan_akhir'])}`}</strong></p>` +
                  `<p class="mb-0"><strong>${(element['kota'] == element['kota_asal']) ? element['kota'] : `${element['kota_asal']} ke ${element['kota']}`}</strong></p>` +
                  `<p>${element['nama_kegiatan']}</p>`;
              }

              listItem += `<p class="mb-0"><i class="fas fa-circle" style="font-size: 6px;"></i> ${element['nama_pegawai']}</p>`;

              if (id_perjalanan != element['id_perjalanan']) {
                listItem += '</li>';
              }

              return listItem;
            }).join('')
          );
        } else {
          $(".dataRincianPerjalananDinas").html(elementul)
        }
      },
    });
  }

  function formatTanggalIndo(tanggal) {
    // Implementasikan logika format tanggal di sini
    // Contoh sederhana:
    var options = {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    };
    return new Date(tanggal).toLocaleDateString('id-ID', options);
  }
</script>