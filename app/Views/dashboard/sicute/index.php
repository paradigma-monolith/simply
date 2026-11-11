<script>
  $(document).ready(function() {
    var calendarEl = document.getElementById('calendarDashboardSicute');
    // Buat objek Date
    var today = new Date();

    // Dapatkan tahun, bulan, dan tanggal
    var year = today.getFullYear();
    var month = (today.getMonth() + 1).toString().padStart(2, '0'); // Tambah 1 karena indeks bulan dimulai dari 0
    var day = today.getDate().toString().padStart(2, '0');

    // Gabungkan dalam format yang diinginkan
    var formattedDate = year + '-' + month + '-' + day;

    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'multiMonthYear,dayGridMonth'
      },
      initialView: 'multiMonthYear',
      initialDate: `${formattedDate}`,
      editable: false,
      selectable: true,
      dayMaxEvents: true, // allow "more" link when too many events
      // multiMonthMaxColumns: 1, // guarantee single column
      // showNonCurrentDates: true,
      // fixedWeekCount: false,
      // businessHours: true,
      weekends: false,
      events: [
        <?php foreach ($getCuti as $data) {
          $tanggal_awal = explode('|', $data['tanggal_awal']);
          $tanggal_akhir = explode('|', $data['tanggal_akhir']);

          if ($data['id_cuti_status_kepegawaian'] == 4 || $data['id_cuti_status_atasan'] == 4 || $data['id_cuti_status_pejabat'] == 4) {
            $color = '#DC3545'; // merah
          } else if ($data['id_cuti_status_kepegawaian'] == 2 && $data['id_cuti_status_atasan'] == 2 && $data['id_cuti_status_pejabat'] == 2) {
            $color = '#28A745'; // ijo
          } else {
            $color = '#FFC107'; // kuning
          }
        ?>
          <?php for ($i = 0; $i < count($tanggal_awal); $i++) { ?> {
              title: '<?= name_capitalize($data['nama']); ?>',
              start: '<?= str_replace('/', '-', $tanggal_awal[$i]); ?>',
              <?php
              $tanggal = str_replace('/', '-', $tanggal_akhir[$i]);
              if (str_replace('/', '-', $tanggal_akhir[$i]) != str_replace('/', '-', $tanggal_awal[$i]))
                $tanggal = date('Y-m-d', strtotime($tanggal . ' + 1 day'));
              ?>
              end: '<?= $tanggal; ?>',
              color: '<?= $color; ?>'
            },
          <?php }; ?>
        <?php }; ?>

      ]
    });

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
  <div class="col-sm-9">
    <div class="card">
      <div class="card-header">
        <h4>Daftar pegawai yang mengajukan cuti</h4>
      </div>
      <div class="card-body">
        <div id='calendarDashboardSicute'></div>
      </div>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="card">
      <div class="card-header">
        Jumlah data cuti <span class="tahun_cuti"><?= date("Y"); ?></span>
      </div>
      <div class="card-body list-cuti-data">
        <ul class="list-group list-group-unbordered mb-3">
          <li class="list-group-item">
            <b>Penolakan</b>
            <div class="float-right">
              <span class="badge badge-pill badge-danger cuti-badge cuti-penolakan">Test</span>
            </div>
          </li>
          <li class="list-group-item">
            <b>Sedang diproses</b>
            <div class="float-right">
              <span class="badge badge-pill badge-warning cuti-badge cuti-proses">Test</span>
            </div>
          </li>
          <li class="list-group-item">
            <b>Terkonfirmasi Cuti</b>
            <div class="float-right">
              <span class="badge badge-pill badge-success cuti-badge cuti-terkonfirmasi">Test</span>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <div class="card mt-3">
      <div class="card-header">
        Export data cuti
      </div>
      <div class="card-body">
        <form action="/cuti/export/excel" method="post">
          <div class="form-group">
            <label for="">Range of date</label>
            <input type="text" name="tanggal" class="form-control date-sicute" required>
          </div>
          <button type="submit" class="float-right btn btn-success"><i class="fas fa-excel"></i> Export</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  var prevYear;

  $(document).ready(function() {
    var tahun = '<?= date('Y'); ?>'
    getListDataCuti(tahun)

    $('.date-sicute').daterangepicker({
      startDate: moment().subtract(1, 'months').startOf('month'),
      endDate: moment(),
      locale: {
        format: 'YYYY-MM-DD'
      }
    });
  });

  $(document).on('click', '.fc-prev-button, .fc-next-button', function() {
    // Mengambil teks dari elemen .fc-toolbar-title
    var currentText = $(".fc-toolbar-title").text();

    // Mengekstrak tahun menggunakan regex
    var extractedYear = currentText.match(/\b\d{4}\b/);

    if (extractedYear) {
      // Jika berhasil mengekstrak tahun
      var currentYear = extractedYear[0];

      if (prevYear !== currentYear) {
        getListDataCuti(currentYear)
        // getListDataCuti(currentYear)
        prevYear = currentYear;
      }
    } else {
      // Jika tidak ada tahun yang ditemukan
      alert("Tidak ada tahun yang ditemukan.");
    }
  });

  function getListDataCuti(year) {
    $(".tahun_cuti").text(year)
    $.ajax({
      url: `/home/getListDataCuti/${year}`,
      method: 'get',
      dataType: 'json',
      beforeSend: function() {
        $(".cuti-badge").html(`<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`)
      },
      success: function(response) {
        if (response.data) {
          console.log(response.data)
          $(".cuti-penolakan").text(response.data.penolakan['penolakan'] + ' x ')
          $(".cuti-proses").text(response.data.proses['proses'] + ' x ')
          $(".cuti-terkonfirmasi").text(response.data.terkonfirmasi['terkonfirmasi'] + " hari")
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Swal.fire({
          position: 'top-end',
          icon: 'error',
          title: 'Oops... ERROR SINTAKS',
          text: 'Terjadi kesalahan pemrograman saat mengambil data silahkan hubungi pihak yang terkait',
          // text: (xhr.status + "\n" + xhr.responseText + "\n" + thrownError),
        })
      }
    });
  }
</script>