
/* global Chart:false */
  // $( document ).ready(function() {
    $(function() {
      jQuery(document).ready(function() {
        $.ajax({
          url: '/infografis/ajax',
          method: 'GET',
          success: function(data) {
  
            'use strict'
  
            // ==========================================================================
            $("#total_phqc").text(data['total_vaksin'][0]['phqc'])
            $("#phqc").text(data['vaksin'][0]['phqc'])
            $("#tgl_phqc").text(data['vaksin'][0]['created_at'])
            // ==========================================================================
            $("#total_cop").text(data['total_vaksin'][0]['cop'])
            $("#cop").text(data['vaksin'][0]['cop'])
            $("#tgl_cop").text(data['vaksin'][0]['created_at'])
            // ==========================================================================
            $("#total_sscec").text(data['total_vaksin'][0]['sscec'])
            $("#sscec").text(data['vaksin'][0]['sscec'])
            $("#tgl_sscec").text(data['vaksin'][0]['created_at'])
            // ==========================================================================
            $("#total_sscc").text(data['total_vaksin'][0]['sscc'])
            $("#sscc").text(data['vaksin'][0]['sscc'])
            $("#tgl_sscc").text(data['vaksin'][0]['created_at'])
            // ==========================================================================
            $("#total_p3k").text(data['total_vaksin'][0]['p3k'])
            $("#p3k").text(data['vaksin'][0]['p3k'])
            $("#tgl_p3k").text(data['vaksin'][0]['created_at'])
            // ==========================================================================
            $("#total_icv").text(data['total_vaksin'][0]['icv'])
            $("#icv").text(data['vaksin'][0]['icv'])
            $("#tgl_icv").text(data['vaksin'][0]['created_at'])
            // ==========================================================================
            $("#total_sklt").text(data['total_vaksin'][0]['sklt'])
            $("#sklt").text(data['vaksin'][0]['sklt'])
            $("#tgl_sklt").text(data['vaksin'][0]['created_at'])
            // ==========================================================================
            $("#total_siaos").text(data['total_vaksin'][0]['siaos'])
            $("#siaos").text(data['vaksin'][0]['siaos'])
            $("#tgl_siaos").text(data['vaksin'][0]['created_at'])
            // ==========================================================================
            $("#total_siaj").text(data['total_vaksin'][0]['siaj'])
            $("#siaj").text(data['vaksin'][0]['siaj'])
            $("#tgl_siaj").text(data['vaksin'][0]['created_at'])
            // ==========================================================================
            $("#total_kir").text(data['total_vaksin'][0]['kir'])
            $("#kir").text(data['vaksin'][0]['kir'])
            $("#tgl_kir").text(data['vaksin'][0]['created_at'])
            // ==========================================================================
            $("#total_bukes").text(data['total_vaksin'][0]['bukes'])
            $("#bukes").text(data['vaksin'][0]['bukes'])
            $("#tgl_bukes").text(data['vaksin'][0]['created_at'])
            // ==========================================================================
            $("#total_vaksin_mm").text(data['total_vaksin'][0]['vaksin_mm'])
            $("#vaksin_mm").text(data['vaksin'][0]['vaksin_mm'])
            $("#tgl_vaksin_mm").text(data['vaksin'][0]['created_at'])
            // ==========================================================================
            $("#total_vaksin_yf").text(data['total_vaksin'][0]['vaksin_yf'])
            $("#vaksin_yf").text(data['vaksin'][0]['vaksin_yf'])
            $("#tgl_vaksin_yf").text(data['vaksin'][0]['created_at'])
            // ==========================================================================
            $("#total_vaksin_covid").text(data['total_vaksin'][0]['vaksin_covid'])
            $("#vaksin_covid").text(data['vaksin'][0]['vaksin_covid'])
            $("#tgl_vaksin_covid").text(data['vaksin'][0]['created_at'])
            // ==========================================================================
            $("#total_vaksin_polio").text(data['total_vaksin'][0]['vaksin_polio'])
            $("#vaksin_polio").text(data['vaksin'][0]['vaksin_polio'])
            $("#tgl_vaksin_polio").text(data['vaksin'][0]['created_at'])
            // ==========================================================================  
            $("#total_bukes").text(data['total_vaksin'][0]['bukes'])
            $("#bukes").text(data['vaksin'][0]['bukes'])
            $("#tgl_bukes").text(data['vaksin'][0]['created_at'])            
            // ==========================================================================
  
  
            var ticksStyle = {
              fontColor: '#495057',
              fontStyle: 'bold'
            }
  
  
            // var mode = 'index'
            // var intersect = true
            // var tanggal_kapal = []
            // var kapal_datang_kapal = []
            // var kapal_berangkat_kapal = []
            // var abk_datang_kapal = []
            // var abk_berangkat_kapal = []
            // var penumpang_datang_kapal = []
            // var penumpang_berangkat_kapal = []
  
            // for (let i = 0; i < 5; i++) {
            //   if (data['kapal'][i]) {
            //     tanggal_kapal.push(data['kapal'][i]['created_at_kapal'])
            //     kapal_datang_kapal.push(parseInt(data['kapal'][i]['kapal_datang_negeri_kapal'])+parseInt(data['kapal'][i]['kapal_datang_luar_kapal']))
            //     kapal_berangkat_kapal.push(parseInt(data['kapal'][i]['kapal_berangkat_negeri_kapal'])+parseInt(data['kapal'][i]['kapal_berangkat_luar_kapal']))
            //     abk_datang_kapal.push(parseInt(data['kapal'][i]['abk_datang_negeri_kapal'])+parseInt(data['kapal'][i]['abk_datang_luar_kapal']))
            //     abk_berangkat_kapal.push(parseInt(data['kapal'][i]['abk_berangkat_negeri_kapal'])+parseInt(data['kapal'][i]['abk_berangkat_luar_kapal']))
            //     penumpang_datang_kapal.push(parseInt(data['kapal'][i]['penumpang_datang_negeri_kapal'])+parseInt(data['kapal'][i]['penumpang_datang_luar_kapal']))
            //     penumpang_berangkat_kapal.push(parseInt(data['kapal'][i]['penumpang_berangkat_negeri_kapal'])+parseInt(data['kapal'][i]['penumpang_berangkat_luar_kapal']))
            //   }
            // }
  
  
            // var $salesChart = $('#sales-chart')
            // // eslint-disable-next-line no-unused-vars
            // var salesChart = new Chart($salesChart, {
            //   type: 'bar',
            //   data: {
            //     labels: tanggal_kapal,
            //     datasets: [
            //       // =============Total Kedatangan================
            //       {
            //         backgroundColor: '#006a94',
            //         borderColor: '#006a94',
            //         data: kapal_datang_kapal
            //       },
            //       // =============Total Kedatangan================
            //       {
            //         backgroundColor: '#FFED45',
            //         borderColor: '#FFED45',
            //         data: abk_datang_kapal
            //       },
            //       // =============Total Kedatangan================
            //       {
            //         backgroundColor: '#57FF5F',
            //         borderColor: '#57FF5F',
            //         data: penumpang_datang_kapal
            //       },
            //       // =============Total Kedatangan=================
            //       // ============Total Keberangkatan===============
            //       {
            //         backgroundColor: '#003449',
            //         borderColor: '#003449',
            //         data: kapal_berangkat_kapal
            //       },
            //       // ============Total Keberangkatan===============
            //       {
            //         backgroundColor: '#796E00',
            //         borderColor: '#796E00',
            //         data: abk_berangkat_kapal
            //       },
            //       // ============Total Keberangkatan===============
            //       {
            //         backgroundColor: '#008806',
            //         borderColor: '#008806',
            //         data: penumpang_berangkat_kapal
            //       },
            //       // ============Total Keberangkatan===============
            //     ]
            //   },
            //   options: {
            //     maintainAspectRatio: false,
            //     tooltips: {
            //       mode: mode,
            //       intersect: intersect
            //     },
            //     hover: {
            //       mode: mode,
            //       intersect: intersect
            //     },
            //     legend: {
            //       display: false
            //     },
            //     scales: {
            //       yAxes: [{
            //         // display: false,
            //         gridLines: {
            //           display: true,
            //           lineWidth: '4px',
            //           color: 'rgba(0, 0, 0, .2)',
            //           zeroLineColor: 'transparent'
            //         },
            //         ticks: $.extend({
            //           beginAtZero: true,
  
            //           // Include a dollar sign in the ticks
            //           callback: function(value) {
            //             if (value >= 1000) {
            //               value /= 1000
            //               value += 'k'
            //             }
  
            //             return '' + value
            //           }
            //         }, ticksStyle)
            //       }],
            //       xAxes: [{
            //         display: true,
            //         gridLines: {
            //           display: false
            //         },
            //         ticks: ticksStyle
            //       }]
            //     }
            //   }
            // })
            // ==============================================================================
  
            // ==============================================================================
          }
        })
      })
    })
    // });
  
    // lgtm [js/unused-local-variable]