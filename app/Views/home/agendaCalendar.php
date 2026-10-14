<div class="container-fluid">
    <div class="row">
      <!-- /.col -->
      <div class="col-md-12">
        <div class="card card-info">
          <div class="card-header text-center">
            <h5>Jadwal Agendaku</h5>
          </div>
          <div class="card-body p-0">
            <!-- THE CALENDAR -->
            <div id="calendar"></div>
            
            <!-- Keterangan Tambahan -->
            <div class="p-3 border-top bg-light">
              <h6 class="font-weight-bold mb-2">Keterangan Kalender:</h6>
              <div class="d-flex flex-wrap align-items-center mb-2">
                <div class="d-flex align-items-center mr-4 mb-2">
                  <span style="display:inline-block; width:16px; height:16px; background-color:#dc3545; border-radius:4px; margin-right:8px;"></span>
                  <span>Hari Libur Nasional</span>
                </div>
              </div>
              <div id="holiday-list" class="text-muted" style="font-size: 0.9em; max-height: 100px; overflow-y: auto;">
                <!-- Daftar hari libur bulan ini akan dimuat di sini -->
              </div>
              <small class="text-muted mt-2 d-block">
                <i>* Klik pada tanggal atau blok warna untuk melihat detail agenda / hari libur.</i>
              </small>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>

  <!-- Modal untuk menampilkan agenda -->
  <div class="modal fade" id="modal-md" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <!-- Konten header akan diisi oleh JavaScript -->
        </div>
        <div class="modal-body">
          <!-- Konten akan diisi oleh JavaScript -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(function() {
      console.log('FullCalendar initialization started...');
      
      // Pastikan FullCalendar sudah tersedia sebelum inisialisasi
      if (typeof FullCalendar === 'undefined') {
        console.error('FullCalendar library tidak ditemukan!');
        return;
      }
      
      console.log('FullCalendar library found, proceeding with initialization...');

      function ini_events(ele, description) {
        ele.each(function() {
          var eventObject = {
            title: $.trim($(this).text()),
            description: description // Tambahkan deskripsi ke dalam objek acara
          }
          $(this).data('eventObject', eventObject)

          $(this).draggable({
            zIndex: 1070,
            revert: true,
            revertDuration: 0
          })

        })
      }

      var date = new Date()
      var Calendar = FullCalendar.Calendar;
      var Draggable = FullCalendar.Draggable;
      var calendarEl = document.getElementById('calendar');

      // Pastikan element calendar tersedia
      if (!calendarEl) {
        console.error('Element calendar tidak ditemukan!');
        return;
      }

      // Calculate date range for 12 months starting from current month
      var currentDate = new Date();
      
      var calendar = new Calendar(calendarEl, {
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        initialView: 'dayGridMonth',
        initialDate: currentDate,
        showNonCurrentDates: false,
        fixedWeekCount: false,
        dayMaxEvents: true,
        eventMaxStack: 3,
        moreLinkContent: function(args) {
          return '+' + args.num + ' agenda';
        },
        moreLinkClick: function(info) {
          // Saat klik "+X agenda", tampilkan di view hari
          calendar.changeView('timeGridDay', info.date);
          return false;
        },
        themeSystem: 'bootstrap',
        displayEventTime: false,
        eventTimeFormat: {
          hour: 'numeric',
          minute: '2-digit',
          omitZeroMinute: false,
          meridiem: false
        },
        height: 'auto',
        contentHeight: 'auto',
        events: [
          <?php 
          $event_count = 0;
          foreach ($getData as $data) : 
            try {
              $peruntukan = @unserialize($data['peruntukan']);
              if ($peruntukan === false && $data['peruntukan'] !== 'b:0;') {
                continue;
              }
              if (!is_array($peruntukan)) {
                continue;
              }
              if (in_array($id_user, $peruntukan)) {
                $event_count++;
                $tanggal = $data['waktu_pelaksana_awal'];
                $tanggal_objek = new DateTime($tanggal);

                $start_tahun = $tanggal_objek->format('Y');
                $start_bulan = $tanggal_objek->format('m') - 1;
                $start_tanggal = $tanggal_objek->format('d');
                $start_jam = $tanggal_objek->format('H');
                $start_menit = $tanggal_objek->format('i');

                $tanggal = $data['waktu_pelaksana_akhir'];
                $tanggal_objek = new DateTime($tanggal);

                $end_tahun = $tanggal_objek->format('Y');
                $end_bulan = $tanggal_objek->format('m') - 1;
                $end_tanggal = $tanggal_objek->format('d');
                $end_jam = $tanggal_objek->format('H');
                $end_menit = $tanggal_objek->format('i');
            ?> {
                  title: '<?= addslashes($data['topik'] ?? ''); ?>',
                  start: new Date(<?= $start_tahun; ?>, <?= $start_bulan; ?>, <?= $start_tanggal; ?>, <?= $start_jam; ?>, <?= $start_menit; ?>),
                  end: new Date(<?= $end_tahun; ?>, <?= $end_bulan; ?>, <?= $end_tanggal; ?>, <?= $end_jam; ?>, <?= $end_menit; ?>),
                  allDay: false,
                  backgroundColor: '<?= isset($data['warna_agenda']) && !empty($data['warna_agenda']) ? $data['warna_agenda'] : '#0073b7'; ?>',
                  borderColor: '<?= isset($data['warna_agenda']) && !empty($data['warna_agenda']) ? $data['warna_agenda'] : '#0073b7'; ?>',
                  description: '<?= addslashes(trim(preg_replace('/\s+/', ' ', $data['deskripsi'] ?? ''))); ?>'
                },
          <?php 
              }
            } catch (Exception $e) {
              continue;
            }
          endforeach; 
          ?>
        ],
        eventDidMount: function(info) {
          // Error handling individual event rendering
          try {
            // Tambahkan tooltip untuk menampilkan detail agenda saat hover
            if (info.event.title) {
              $(info.el).attr('title', info.event.title);
              $(info.el).attr('data-toggle', 'tooltip');
              $(info.el).attr('data-placement', 'top');
              $(info.el).tooltip({
                html: true,
                delay: { show: 500, hide: 100 }
              });
            }
          } catch (error) {
            console.error('Error mounting event:', error);
          }
        },
        editable: true,
        droppable: true,

        dateClick: function(info) {
          // Tampilkan agenda untuk tanggal yang diklik
          var clickedDate = info.dateStr;
          var eventsOnDate = calendar.getEvents().filter(function(event) {
            var eventStart = event.startStr.split('T')[0];
            return eventStart === clickedDate;
          });
          
          if (eventsOnDate.length > 0) {
            var htmlContent = '<h5>Agenda untuk ' + clickedDate + '</h5>';
            htmlContent += '<div style="max-height: 300px; overflow-y: auto;">';
            
            eventsOnDate.forEach(function(event) {
              var description = event.extendedProps.description || 'Tidak ada deskripsi';
              description = convertUrlsToLinks(description);
              
              htmlContent += '<div class="alert alert-info" style="margin: 5px 0; padding: 10px;">';
              htmlContent += '<strong>' + event.title + '</strong><br>';
              htmlContent += '<small class="text-muted">' + description + '</small>';
              htmlContent += '</div>';
            });
            
            htmlContent += '</div>';
            
            $("#modal-md").find('.modal-header').html(
              '<h5 class="modal-title">Agenda Tanggal ' + clickedDate + '</h5>' +
              '<button type="button" class="close" data-dismiss="modal">&times;</button>'
            );
            $("#modal-md").find('.modal-body').html(htmlContent);
            
            // Tambahkan event handler untuk tombol link
            $("#modal-md").find('.modal-body').off('click', '.link-button').on('click', '.link-button', function(e) {
              e.preventDefault();
              e.stopPropagation();
              var url = $(this).data('url');
              if (url) {
                window.open(url, '_blank');
              }
            });
            
            $("#modal-md").modal('show');
          }
        },
        
        eventClick: function(info) {
          var event = info.event;
          // Menampilkan deskripsi dalam alert
          $("#modal-md").find('.modal-header').html(
            '<button class="btn btn-sm btn-light float-right" onclick=\'$(\'#modal-md\').modal(\'hide\')\'>Tutup</button>'
          )
          
          // Pastikan link dalam deskripsi dapat diklik
          var description = event.extendedProps.description;
          
          // Konversi URL menjadi link yang dapat diklik
          description = convertUrlsToLinks(description);
          
          // Menggunakan jQuery untuk membuat deskripsi HTML menjadi aktif
          $("#modal-md").find('.modal-body').html(description);
          
          // Tambahkan event handler untuk tombol link yang dibuat secara dinamis
          $("#modal-md").find('.modal-body').off('click', '.link-button').on('click', '.link-button', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var url = $(this).data('url');
            if (url) {
              window.open(url, '_blank');
            }
          });
          
          $("#modal-md").modal('show')
        }

      });

      // Fungsi untuk mengubah URL menjadi link yang dapat diklik
      function convertUrlsToLinks(text) {
        if (!text) return '';
        
        // Regex untuk mendeteksi URL dengan berbagai format
        var urlRegex = /((?:https?:\/\/|www\.)(?:[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b(?:[-a-zA-Z0-9()@:%_\+.~#?&//=]*)))|([a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}(?:\/[-a-zA-Z0-9()@:%_\+.~#?&//=]*)?)/g;
        
        // Ganti URL dengan tombol link
        return text.replace(urlRegex, function(url) {
          var href = url;
          // Tambahkan protokol http jika tidak ada
          if (!href.match(/^https?:\/\//i)) {
            href = 'http://' + href;
          }
          return '<button type="button" class="btn btn-sm btn-primary link-button" data-url="' + href + '" style="margin: 2px;">Link</button>';
        });
      }

      calendar.render();
      
      // Fetch hari libur dari API dan tambahkan ke kalender
      let allHolidays = [];
      fetch('https://libur.deno.dev/api')
        .then(res => res.json())
        .then(data => {
          allHolidays = data;
          data.forEach(holiday => {
            calendar.addEvent({
              title: holiday.name,
              start: holiday.date,
              color: '#dc3545', // warna merah untuk hari libur
              allDay: true,
              description: 'Hari Libur Nasional' // Menambahkan deskripsi
            });
          });
          // Tampilkan list libur awal sesuai bulan aktif
          updateHolidayList();
        })
        .catch(err => console.error('Error fetching holidays:', err));

      // Fungsi untuk mengupdate daftar libur di bawah kalender berdasarkan bulan yang tampil
      function updateHolidayList() {
        if (!allHolidays || allHolidays.length === 0) return;
        
        let currentStart = calendar.view.currentStart;
        let currentMonth = currentStart.getMonth();
        let currentYear = currentStart.getFullYear();
        
        let monthHolidays = allHolidays.filter(holiday => {
          let hDate = new Date(holiday.date);
          return hDate.getMonth() === currentMonth && hDate.getFullYear() === currentYear;
        });

        let listContainer = document.getElementById('holiday-list');
        if (monthHolidays.length > 0) {
          let listHtml = '<ul class="mb-0 pl-3">';
          monthHolidays.forEach(h => {
            let tgl = new Date(h.date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            listHtml += `<li><strong>${tgl}</strong>: ${h.name}</li>`;
          });
          listHtml += '</ul>';
          listContainer.innerHTML = listHtml;
        } else {
          listContainer.innerHTML = '<i>Tidak ada hari libur nasional di bulan ini.</i>';
        }
      }

      // Tangkap event ketika view kalender berganti bulan (misal klik prev/next)
      calendar.on('datesSet', function() {
        updateHolidayList();
      });
      
      // Inisialisasi tooltip untuk event
      $('[data-toggle="tooltip"]').tooltip();
      
      var currColor = '#3c8dbc'
      $('#color-chooser > li > a').click(function(e) {
        e.preventDefault()
        currColor = $(this).css('color')
        $('#add-new-event').css({
          'background-color': currColor,
          'border-color': currColor
        })
      })
      $('#add-new-event').click(function(e) {
        e.preventDefault()
        var val = $('#new-event').val()
        var description = 'Deskripsi tambahan untuk acara ini'; // Deskripsi default
        if (val.length == 0) {
          return
        }

        var event = $('<div />')
        event.css({
          'background-color': currColor,
          'border-color': currColor,
          'color': '#fff'
        }).addClass('external-event')
        event.text(val)
        ini_events(event, description); // Memanggil fungsi dengan deskripsi tambahan
        $('#new-event').val('')
      })
    })
  </script>
  
  <style>
    .link-button {
      cursor: pointer;
    }
    .link-button:hover {
      opacity: 0.8;
    }
    
    /* Styling untuk tampilan dayGridMonth yang lebih rapih */
    .fc-daygrid-day {
      min-height: 80px;
      position: relative;
    }
    
    .fc-daygrid-day-top {
      position: relative;
      z-index: 2;
      background: white;
      padding: 2px;
    }
    
    .fc-daygrid-day-number {
      font-weight: 600;
      font-size: 0.9em;
      color: #333;
      padding: 4px 6px;
    }
    
    .fc-daygrid-day-events {
      margin-top: 4px;
      padding: 0 2px;
    }
    
    .fc-daygrid-event {
      font-size: 0.75em;
      padding: 2px 4px;
      margin: 1px 0;
      border-radius: 3px;
      line-height: 1.2;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 100%;
      display: block;
      box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    
    .fc-daygrid-event:hover {
      overflow: visible;
      z-index: 1000;
      position: relative;
      white-space: normal;
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    
    .fc-day-today {
      background-color: #e3f2fd !important;
      border: 2px solid #2196f3 !important;
    }
    
    .fc-day-today .fc-daygrid-day-number {
      background-color: #2196f3;
      color: white !important;
      border-radius: 50%;
      width: 24px;
      height: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
    }
    
    .fc-more-link {
      font-size: 0.7em;
      color: #666;
      text-decoration: none;
      padding: 1px 3px;
      border-radius: 2px;
      background-color: #f5f5f5;
    }
    
    .fc-more-link:hover {
      background-color: #e0e0e0;
      color: #333;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .fc-daygrid-day {
        min-height: 60px;
      }
      
      .fc-daygrid-event {
        font-size: 0.7em;
        padding: 1px 2px;
      }
      
      .fc-toolbar {
        flex-direction: column;
        gap: 10px;
      }
      
      .fc-toolbar-chunk {
        display: flex;
        justify-content: center;
      }
    }
    
    @media (max-width: 480px) {
      .fc-daygrid-day {
        min-height: 50px;
      }
      
      .fc-daygrid-event {
        font-size: 0.65em;
      }
      
      .fc-daygrid-day-number {
        font-size: 0.8em;
        padding: 2px 4px;
      }
    }
    
    /* Styling untuk modal */
     #modal-md .modal-body {
       max-height: 400px;
       overflow-y: auto;
     }
     
     /* Styling khusus untuk event agar tidak overlap dengan tanggal */
     .fc-daygrid-event-harness {
       margin-top: 2px;
       margin-bottom: 1px;
     }
     
     .fc-daygrid-event-dot {
       margin-right: 4px;
       margin-left: 2px;
     }
     
     .fc-event-title {
       font-weight: 500;
       font-size: 0.85em;
     }
     
     .fc-daygrid-more-link {
       background-color: #f8f9fa;
       border: 1px solid #dee2e6;
       color: #495057;
       font-size: 0.7em;
       padding: 1px 4px;
       border-radius: 3px;
       margin-top: 2px;
       display: inline-block;
     }
     
     .fc-daygrid-more-link:hover {
       background-color: #e9ecef;
       color: #212529;
       text-decoration: none;
     }
     
     /* Styling untuk tooltip */
     .tooltip {
       font-size: 0.85em;
       z-index: 9999;
     }
     
     .tooltip-inner {
       max-width: 300px;
       padding: 8px 12px;
       background-color: #343a40;
       border-radius: 4px;
       box-shadow: 0 2px 8px rgba(0,0,0,0.2);
     }
     
     .tooltip.bs-tooltip-top .arrow::before {
       border-top-color: #343a40;
     }
     
     /* Styling untuk kalender container */
     #calendar {
       background: white;
       border-radius: 8px;
       box-shadow: 0 2px 10px rgba(0,0,0,0.1);
       padding: 15px;
       margin: 10px;
     }
     
     .fc-header-toolbar {
       margin-bottom: 15px !important;
     }
     
     .fc-toolbar-title {
       font-size: 1.5em;
       font-weight: 600;
       color: #333;
     }
     
     .fc-button {
       border-radius: 4px !important;
       padding: 6px 12px !important;
       font-size: 0.9em !important;
     }
     
     .fc-button-primary {
       background-color: #007bff !important;
       border-color: #007bff !important;
     }
     
     .fc-button-primary:hover {
       background-color: #0056b3 !important;
       border-color: #0056b3 !important;
     }
     
     .fc-button-primary:not(:disabled).fc-button-active {
       background-color: #0056b3 !important;
       border-color: #0056b3 !important;
     }
  </style>