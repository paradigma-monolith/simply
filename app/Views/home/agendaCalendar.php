<?php
$eventsJson = json_encode($calendarEvents ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
if ($eventsJson === false) {
  $eventsJson = '[]';
}
$totalAgendaCount = isset($eventCount) ? (int) $eventCount : count($calendarEvents ?? []);
$monthlyCount = isset($monthlyRecurringCount) ? (int) $monthlyRecurringCount : 0;
?>

<div class="sh-cal-card">
  <div class="sh-cal-header">
    <div class="d-flex align-items-center" style="gap: 10px;">
      <div class="sh-cal-icon">
        <i class="fas fa-calendar-alt"></i>
      </div>
      <div>
        <h6 class="mb-0 font-weight-bold" style="color: #0f172a; font-size: 0.96rem;">Jadwal Agendaku</h6>
        <small class="text-muted" style="font-size: 0.76rem;">
          <?= $totalAgendaCount; ?> agenda terjadwal<?= $monthlyCount > 0 ? ' • <strong style="color:#4f46e5;">' . $monthlyCount . ' rutin bulanan</strong>' : ''; ?>
        </small>
      </div>
    </div>
    <a href="/home/agenda" class="btn btn-sm btn-outline-teal sh-cal-manage-btn">
      <i class="fas fa-plus mr-1"></i> Kelola Agenda
    </a>
  </div>

  <div class="sh-cal-body">
    <div id="calendar"></div>

    <div class="sh-cal-footer">
      <div class="d-flex flex-wrap align-items-center justify-content-between mb-2" style="gap: 8px;">
        <div class="d-flex flex-wrap align-items-center" style="gap: 14px; font-size: 0.78rem; color: #475569;">
          <span class="d-inline-flex align-items-center">
            <span class="sh-legend-dot" style="background: #0d9488;"></span> Agenda Saya
          </span>
          <span class="d-inline-flex align-items-center">
            <span class="badge px-1 py-0 mr-1" style="background:#eef2ff;color:#4338ca;border:1px solid #c7d2fe;font-size:0.68rem;">↻</span> Agenda Rutin Bulanan
          </span>
          <span class="d-inline-flex align-items-center">
            <span class="sh-legend-dot" style="background: #dc2626;"></span> Hari Libur Nasional
          </span>
        </div>
        <small class="text-muted" style="font-size: 0.73rem;">
          <i class="far fa-hand-pointer mr-1"></i>Klik tanggal atau agenda untuk detail
        </small>
      </div>
      <div id="holiday-list" class="sh-holiday-list">
        <span class="text-muted small">Memuat hari libur nasional...</span>
      </div>
    </div>
  </div>
</div>

<!-- Modal khusus detail agenda (ID unik agar tidak bentrok dengan #modal-md di layout) -->
<div class="modal fade" id="modalAgendaDetail" tabindex="-1" role="dialog" aria-labelledby="modalAgendaDetailTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius: 14px; border: none; overflow: hidden; box-shadow: 0 18px 42px rgba(15, 23, 42, 0.2);">
      <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
        <h5 class="modal-title font-weight-bold" id="modalAgendaDetailTitle" style="font-size: 1rem; color: #0f172a;">Detail Agenda</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modalAgendaDetailBody" style="max-height: 420px; overflow-y: auto; font-size: 0.88rem; color: #334155;"></div>
      <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 10px 16px;">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal" style="border-radius: 8px;">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
  (function() {
    if (typeof FullCalendar === 'undefined') {
      var elErr = document.getElementById('calendar');
      if (elErr) {
        elErr.innerHTML = '<div class="alert alert-warning mb-0">Komponen kalender belum termuat. Silakan muat ulang halaman.</div>';
      }
      return;
    }

    // Pastikan modal detail agenda tunggal dan terpasang di body
    var existingModals = document.querySelectorAll('body > #modalAgendaDetail');
    existingModals.forEach(function(m) { m.remove(); });
    var modalEl = document.getElementById('modalAgendaDetail');
    if (modalEl && modalEl.parentNode !== document.body) {
      document.body.appendChild(modalEl);
    }

    var calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    var initialEvents = <?= $eventsJson; ?>;
    var allHolidays = [];

    function escapeHtml(str) {
      return String(str || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
    }

    function convertUrlsToLinks(text) {
      if (!text) return '<span class="text-muted font-italic">Tidak ada deskripsi.</span>';
      var safeText = escapeHtml(text);
      var urlRegex = /((?:https?:\/\/|www\.)(?:[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b(?:[-a-zA-Z0-9()@:%_\+.~#?&//=]*)))|([a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}(?:\/[-a-zA-Z0-9()@:%_\+.~#?&//=]*)?)/g;
      return safeText.replace(urlRegex, function(url) {
        var href = /^https?:\/\//i.test(url) ? url : ('https://' + url);
        return '<button type="button" class="btn btn-xs btn-primary link-button mx-1" data-url="' + escapeHtml(href) + '"><i class="fas fa-external-link-alt mr-1"></i>Buka Link</button>';
      });
    }

    function openAgendaModal(titleText, bodyHtml) {
      $('#modalAgendaDetailTitle').text(titleText);
      var $body = $('#modalAgendaDetailBody');
      $body.html(bodyHtml);
      $body.off('click', '.link-button').on('click', '.link-button', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var url = $(this).data('url');
        if (url) window.open(url, '_blank', 'noopener,noreferrer');
      });
      $('#modalAgendaDetail').modal('show');
    }

    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      buttonText: {
        today: 'Hari Ini',
        month: 'Bulan',
        week: 'Minggu',
        day: 'Hari'
      },
      initialView: 'dayGridMonth',
      initialDate: new Date(),
      showNonCurrentDates: false,
      fixedWeekCount: false,
      dayMaxEvents: 3,
      eventMaxStack: 3,
      moreLinkContent: function(args) {
        return '+' + args.num + ' lagi';
      },
      moreLinkClick: function(info) {
        calendar.changeView('timeGridDay', info.date);
        return false;
      },
      themeSystem: 'bootstrap',
      displayEventTime: false,
      height: 'auto',
      contentHeight: 'auto',
      editable: false,
      droppable: false,
      events: initialEvents,
      eventDidMount: function(info) {
        if (info.event && info.event.title) {
          info.el.setAttribute('title', info.event.title);
        }
      },
      dateClick: function(info) {
        var clickedDate = info.dateStr;
        var eventsOnDate = calendar.getEvents().filter(function(ev) {
          if (!ev.startStr) return false;
          var startDate = ev.startStr.split('T')[0];
          var endDate = ev.endStr ? ev.endStr.split('T')[0] : startDate;
          if (ev.allDay) {
            return startDate === clickedDate;
          }
          return clickedDate >= startDate && clickedDate <= endDate;
        });

        if (eventsOnDate.length === 0) return;

        var html = '';
        eventsOnDate.forEach(function(ev) {
          var accent = ev.backgroundColor || '#0d9488';
          var props = ev.extendedProps || {};
          var timeInfo = props.isHoliday
            ? '<span class="badge badge-danger">Hari Libur Nasional</span>'
            : (props.startLabel ? '<small class="text-muted"><i class="far fa-clock mr-1"></i>' + escapeHtml(props.startLabel) + ' — ' + escapeHtml(props.endLabel || '') + '</small>' : '');
          var recurBadge = props.isRecurringMonthly
            ? '<span class="badge ml-1 px-2 py-1" style="background:#eef2ff;color:#4338ca;border:1px solid #c7d2fe;font-size:0.7rem;"><i class="fas fa-redo-alt mr-1"></i>Rutin Tiap Bulan (Tgl ' + escapeHtml(props.recurringDay || '') + ')</span>'
            : '';

          html += '<div class="sh-modal-event-item" style="border-left-color: ' + escapeHtml(accent) + ';">'
            + '<div class="font-weight-bold mb-1" style="color: #0f172a;">' + escapeHtml(props.rawTitle || ev.title) + recurBadge + '</div>'
            + (timeInfo ? '<div class="mb-1">' + timeInfo + '</div>' : '')
            + '<div style="font-size: 0.84rem; color: #475569; line-height: 1.5;">' + convertUrlsToLinks(props.description) + '</div>'
            + '</div>';
        });

        var formattedDate = new Date(clickedDate + 'T00:00:00').toLocaleDateString('id-ID', {
          weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
        });
        openAgendaModal('Agenda • ' + formattedDate, html);
      },
      eventClick: function(info) {
        var ev = info.event;
        var props = ev.extendedProps || {};
        var accent = ev.backgroundColor || '#0d9488';
        var timeHtml = props.isHoliday
          ? '<span class="badge badge-danger px-2 py-1"><i class="fas fa-flag mr-1"></i>Hari Libur Nasional</span>'
          : '<span class="badge badge-light border text-dark px-2 py-1"><i class="far fa-clock text-info mr-1"></i>'
              + escapeHtml(props.startLabel || '') + (props.endLabel ? ' — ' + escapeHtml(props.endLabel) : '')
            + '</span>';
        var recurHtml = props.isRecurringMonthly
          ? ' <span class="badge px-2 py-1 ml-1" style="background:#eef2ff;color:#4338ca;border:1px solid #c7d2fe;"><i class="fas fa-redo-alt mr-1"></i>Berulang Tiap Bulan • Tgl ' + escapeHtml(props.recurringDay || '') + (props.recurringUntil ? ' (' + escapeHtml(props.recurringUntil) + ')' : '') + '</span>'
          : '';

        var bodyHtml = '<div class="sh-modal-event-item mb-0" style="border-left-color: ' + escapeHtml(accent) + ';">'
          + '<div class="mb-2">' + timeHtml + recurHtml + '</div>'
          + '<div style="line-height: 1.6; color: #334155;">' + convertUrlsToLinks(props.description) + '</div>'
          + '</div>';

        openAgendaModal(props.rawTitle || ev.title || 'Detail Agenda', bodyHtml);
      }
    });

    calendar.render();

    function applyHolidays(holidays) {
      if (!Array.isArray(holidays)) return;
      allHolidays = holidays;
      // Gunakan batchRendering agar FullCalendar hanya reflow 1x bukan N kali
      calendar.batchRendering(function() {
        holidays.forEach(function(holiday) {
          if (!holiday || !holiday.date || !holiday.name) return;
          calendar.addEvent({
            title: holiday.name,
            start: holiday.date,
            backgroundColor: '#dc2626',
            borderColor: '#dc2626',
            allDay: true,
            extendedProps: {
              description: 'Hari Libur Nasional: ' + holiday.name,
              isHoliday: true
            }
          });
        });
      });
      updateHolidayList();
    }

    function updateHolidayList() {
      var listContainer = document.getElementById('holiday-list');
      if (!listContainer) return;

      if (!allHolidays || allHolidays.length === 0) {
        listContainer.innerHTML = '<span class="text-muted small font-italic">Data hari libur belum tersedia.</span>';
        return;
      }

      var currentStart = calendar.view.currentStart;
      var currentMonth = currentStart.getMonth();
      var currentYear = currentStart.getFullYear();

      var monthHolidays = allHolidays.filter(function(h) {
        if (!h || !h.date) return false;
        var d = new Date(h.date + 'T00:00:00');
        return d.getMonth() === currentMonth && d.getFullYear() === currentYear;
      });

      if (monthHolidays.length > 0) {
        var html = '<div class="sh-holiday-pills">';
        monthHolidays.forEach(function(h) {
          var tgl = new Date(h.date + 'T00:00:00').toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
          html += '<span class="sh-holiday-pill"><strong>' + escapeHtml(tgl) + '</strong> ' + escapeHtml(h.name) + '</span>';
        });
        html += '</div>';
        listContainer.innerHTML = html;
      } else {
        listContainer.innerHTML = '<span class="text-muted small font-italic">Tidak ada hari libur nasional di bulan ini.</span>';
      }
    }

    calendar.on('datesSet', updateHolidayList);

    // Muat daftar hari libur nasional dengan cache localStorage 7 hari
    (function loadHolidays() {
      var yearKey = 'simply_holidays_v1_' + new Date().getFullYear();
      var TTL_MS = 7 * 24 * 60 * 60 * 1000;

      try {
        var cachedRaw = localStorage.getItem(yearKey);
        if (cachedRaw) {
          var parsed = JSON.parse(cachedRaw);
          if (parsed && Array.isArray(parsed.data) && (Date.now() - parsed.ts) < TTL_MS) {
            applyHolidays(parsed.data);
            return;
          }
        }
      } catch (e) {}

      var controller = typeof AbortController !== 'undefined' ? new AbortController() : null;
      var timer = controller ? setTimeout(function() { controller.abort(); }, 6000) : null;

      fetch('https://libur.deno.dev/api', { signal: controller ? controller.signal : undefined })
        .then(function(res) { return res.json(); })
        .then(function(data) {
          if (timer) clearTimeout(timer);
          if (Array.isArray(data)) {
            try {
              localStorage.setItem(yearKey, JSON.stringify({ ts: Date.now(), data: data }));
            } catch (e) {}
            applyHolidays(data);
          }
        })
        .catch(function() {
          if (timer) clearTimeout(timer);
          var listContainer = document.getElementById('holiday-list');
          if (listContainer) {
            listContainer.innerHTML = '<span class="text-muted small font-italic">Daftar hari libur nasional sedang tidak dapat dijangkau.</span>';
          }
        });
    })();
  })();
</script>

<style>
  .sh-cal-card {
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(15, 23, 42, 0.045);
    overflow: hidden;
    margin-bottom: 16px;
  }
  .sh-cal-header {
    padding: 14px 18px;
    border-bottom: 1px solid rgba(15, 23, 42, 0.08);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    background: #ffffff;
  }
  .sh-cal-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #f0fdfa;
    color: #0d9488;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
  }
  .sh-cal-manage-btn {
    border-radius: 9px;
    border: 1px solid #0d9488;
    color: #0d9488 !important;
    font-weight: 600;
    font-size: 0.78rem;
    padding: 5px 12px;
    transition: all 0.2s ease;
  }
  .sh-cal-manage-btn:hover {
    background: #0d9488;
    color: #ffffff !important;
  }
  .sh-cal-body {
    padding: 14px 16px 0;
  }
  .sh-cal-footer {
    margin: 14px -16px 0;
    padding: 12px 18px 14px;
    background: #f8fafc;
    border-top: 1px solid rgba(15, 23, 42, 0.07);
  }
  .sh-legend-dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 6px;
  }
  .sh-holiday-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    max-height: 92px;
    overflow-y: auto;
  }
  .sh-holiday-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.75rem;
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
    border-radius: 999px;
    padding: 3px 10px;
  }
  .sh-modal-event-item {
    border-left: 4px solid #0d9488;
    background: #f8fafc;
    border-radius: 8px;
    padding: 11px 13px;
    margin-bottom: 10px;
  }

  /* Modern FullCalendar Overrides */
  #calendar .fc-header-toolbar {
    margin-bottom: 12px !important;
    gap: 8px;
  }
  #calendar .fc-toolbar-title {
    font-size: 1.15rem !important;
    font-weight: 700 !important;
    color: #0f172a;
  }
  #calendar .fc-button {
    border-radius: 8px !important;
    padding: 5px 11px !important;
    font-size: 0.78rem !important;
    font-weight: 600 !important;
    box-shadow: none !important;
  }
  #calendar .fc-button-primary {
    background-color: #f1f5f9 !important;
    border-color: #e2e8f0 !important;
    color: #334155 !important;
  }
  #calendar .fc-button-primary:hover {
    background-color: #e2e8f0 !important;
    border-color: #cbd5e1 !important;
    color: #0f172a !important;
  }
  #calendar .fc-button-primary:not(:disabled).fc-button-active,
  #calendar .fc-button-primary:not(:disabled):active {
    background-color: #0d9488 !important;
    border-color: #0d9488 !important;
    color: #ffffff !important;
  }
  #calendar .fc-col-header-cell {
    background: #f8fafc;
    padding: 6px 0;
    font-size: 0.76rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #64748b;
  }
  #calendar .fc-daygrid-day {
    min-height: 76px;
    transition: background-color 0.15s ease;
  }
  #calendar .fc-daygrid-day:hover {
    background-color: #f8fafc;
  }
  #calendar .fc-daygrid-day-number {
    font-weight: 600;
    font-size: 0.82rem;
    color: #334155;
    padding: 5px 7px;
  }
  #calendar .fc-day-today {
    background-color: #f0fdfa !important;
  }
  #calendar .fc-day-today .fc-daygrid-day-number {
    background-color: #0d9488;
    color: #ffffff !important;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    margin: 3px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
  }
  #calendar .fc-daygrid-event {
    font-size: 0.72rem;
    padding: 2px 5px;
    margin: 1px 2px;
    border-radius: 5px;
    border: none !important;
    line-height: 1.25;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: pointer;
  }
  #calendar .fc-daygrid-more-link {
    background-color: #f1f5f9;
    color: #0f766e;
    font-weight: 600;
    font-size: 0.7rem;
    padding: 1px 6px;
    border-radius: 999px;
    margin: 2px;
    display: inline-block;
  }
  @media (max-width: 767.98px) {
    #calendar .fc-header-toolbar {
      flex-direction: column;
      align-items: stretch;
    }
    #calendar .fc-toolbar-chunk {
      display: flex;
      justify-content: center;
    }
    #calendar .fc-daygrid-day {
      min-height: 54px;
    }
  }
</style>
