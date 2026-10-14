/* global Chart:false */
  // $( document ).ready(function() {
  $(function () {
    jQuery(document).ready(function() {
      $.ajax({
        url: '/infografis/ajax',
        method: 'GET',
        success: function(data) {
          
          'use strict'

          // ==========================================================================
          $("#datang_total_keseluruhan_kapal").text(parseInt(data['total_kapal'][0]['kapal_datang_negeri_kapal_total'])+parseInt(data['total_kapal'][0]['kapal_datang_luar_kapal_total']))
          $("#datang_total_ln_kapal").text(data['total_kapal'][0]['kapal_datang_luar_kapal_total'])                  
          $("#datang_total_dn_kapal").text(data['total_kapal'][0]['kapal_datang_negeri_kapal_total'])
          
          $("#datang_ln_kapal").text(data['kapal'][0]['kapal_datang_luar_kapal'])                  
          $("#datang_dn_kapal").text(data['kapal'][0]['kapal_datang_negeri_kapal'])
          // ==========================================================================
          
          $("#berangkat_total_keseluruhan_kapal").text(parseInt(data['total_kapal'][0]['kapal_berangkat_negeri_kapal_total'])+parseInt(data['total_kapal'][0]['kapal_berangkat_luar_kapal_total']))
          $("#berangkat_total_ln_kapal").text(data['total_kapal'][0]['kapal_berangkat_luar_kapal_total'])                  
          $("#berangkat_total_dn_kapal").text(data['total_kapal'][0]['kapal_berangkat_negeri_kapal_total'])
          
          $("#berangkat_ln_kapal").text(data['kapal'][0]['kapal_berangkat_luar_kapal'])                  
          $("#berangkat_dn_kapal").text(data['kapal'][0]['kapal_berangkat_negeri_kapal'])
          // ==========================================================================
          // ==========================================================================
          $("#datang_total_keseluruhan_abk").text(parseInt(data['total_kapal'][0]['abk_datang_negeri_kapal_total'])+parseInt(data['total_kapal'][0]['abk_datang_luar_kapal_total']))
          $("#datang_total_ln_abk").text(data['total_kapal'][0]['abk_datang_luar_kapal_total'])                  
          $("#datang_total_dn_abk").text(data['total_kapal'][0]['abk_datang_negeri_kapal_total'])
          
          $("#datang_ln_abk").text(data['kapal'][0]['abk_datang_luar_kapal'])                  
          $("#datang_dn_abk").text(data['kapal'][0]['abk_datang_negeri_kapal'])
          // ==========================================================================
          
          $("#berangkat_total_keseluruhan_abk").text(parseInt(data['total_kapal'][0]['abk_berangkat_negeri_kapal_total'])+parseInt(data['total_kapal'][0]['abk_berangkat_luar_kapal_total']))
          $("#berangkat_total_ln_abk").text(data['total_kapal'][0]['abk_berangkat_luar_kapal_total'])                  
          $("#berangkat_total_dn_abk").text(data['total_kapal'][0]['abk_berangkat_negeri_kapal_total'])
          
          $("#berangkat_ln_abk").text(data['kapal'][0]['abk_berangkat_luar_kapal'])                  
          $("#berangkat_dn_abk").text(data['kapal'][0]['abk_berangkat_negeri_kapal'])
          // ==========================================================================
          // ==========================================================================
          $("#datang_total_keseluruhan_penumpang").text(parseInt(data['total_kapal'][0]['penumpang_datang_negeri_kapal_total'])+parseInt(data['total_kapal'][0]['penumpang_datang_luar_kapal_total']))
          $("#datang_total_ln_penumpang").text(data['total_kapal'][0]['penumpang_datang_luar_kapal_total'])                  
          $("#datang_total_dn_penumpang").text(data['total_kapal'][0]['penumpang_datang_negeri_kapal_total'])
          
          $("#datang_ln_penumpang").text(data['kapal'][0]['penumpang_datang_luar_kapal'])                  
          $("#datang_dn_penumpang").text(data['kapal'][0]['penumpang_datang_negeri_kapal'])
          // ==========================================================================
          
          $("#berangkat_total_keseluruhan_penumpang").text(parseInt(data['total_kapal'][0]['penumpang_berangkat_negeri_kapal_total'])+parseInt(data['total_kapal'][0]['penumpang_berangkat_luar_kapal_total']))
          $("#berangkat_total_ln_penumpang").text(data['total_kapal'][0]['penumpang_berangkat_luar_kapal_total'])                  
          $("#berangkat_total_dn_penumpang").text(data['total_kapal'][0]['penumpang_berangkat_negeri_kapal_total'])
          
          $("#berangkat_ln_penumpang").text(data['kapal'][0]['penumpang_berangkat_luar_kapal'])                  
          $("#berangkat_dn_penumpang").text(data['kapal'][0]['penumpang_berangkat_negeri_kapal'])
          // ==========================================================================
          $("#tgl_datang_kapal").text(data['kapal'][0]['updated_at'])
          $("#tgl_berangkat_kapal").text(data['kapal'][0]['updated_at'])
          $("#tgl_datang_abk").text(data['kapal'][0]['updated_at'])
          $("#tgl_berangkat_abk").text(data['kapal'][0]['updated_at'])
          $("#tgl_datang_penumpang").text(data['kapal'][0]['updated_at'])
          $("#tgl_berangkat_penumpang").text(data['kapal'][0]['updated_at'])


          var ticksStyle = {
            fontColor: '#495057',
            fontStyle: 'bold'
          }


          var mode = 'index'
          var intersect = true
          var tanggal_kapal = []
          var kapal_datang_kapal = []
          var kapal_berangkat_kapal = []
          var abk_datang_kapal = []
          var abk_berangkat_kapal = []
          var penumpang_datang_kapal = []
          var penumpang_berangkat_kapal = []

          for (let i = 0; i < 5; i++) {
            if (data['kapal'][i]) {
              tanggal_kapal.push(data['kapal'][i]['created_at_kapal'])
              kapal_datang_kapal.push(parseInt(data['kapal'][i]['kapal_datang_negeri_kapal'])+parseInt(data['kapal'][i]['kapal_datang_luar_kapal']))
              kapal_berangkat_kapal.push(parseInt(data['kapal'][i]['kapal_berangkat_negeri_kapal'])+parseInt(data['kapal'][i]['kapal_berangkat_luar_kapal']))
              abk_datang_kapal.push(parseInt(data['kapal'][i]['abk_datang_negeri_kapal'])+parseInt(data['kapal'][i]['abk_datang_luar_kapal']))
              abk_berangkat_kapal.push(parseInt(data['kapal'][i]['abk_berangkat_negeri_kapal'])+parseInt(data['kapal'][i]['abk_berangkat_luar_kapal']))
              penumpang_datang_kapal.push(parseInt(data['kapal'][i]['penumpang_datang_negeri_kapal'])+parseInt(data['kapal'][i]['penumpang_datang_luar_kapal']))
              penumpang_berangkat_kapal.push(parseInt(data['kapal'][i]['penumpang_berangkat_negeri_kapal'])+parseInt(data['kapal'][i]['penumpang_berangkat_luar_kapal']))
            }
          }


          var $salesChart = $('#sales-chart')
          // eslint-disable-next-line no-unused-vars
          var salesChart = new Chart($salesChart, {
            type: 'bar',
            data: {
              labels: tanggal_kapal,
              datasets: [
                // =============Total Kedatangan================
                {
                  backgroundColor: '#006a94',
                  borderColor: '#006a94',
                  data: kapal_datang_kapal
                },
                // =============Total Kedatangan================
                {
                  backgroundColor: '#FFED45',
                  borderColor: '#FFED45',
                  data: abk_datang_kapal
                },
                // =============Total Kedatangan================
                {
                  backgroundColor: '#57FF5F',
                  borderColor: '#57FF5F',
                  data: penumpang_datang_kapal
                },
                // =============Total Kedatangan=================
                // ============Total Keberangkatan===============
                {
                  backgroundColor: '#003449',
                  borderColor: '#003449',
                  data: kapal_berangkat_kapal
                },
                // ============Total Keberangkatan===============
                {
                  backgroundColor: '#796E00',
                  borderColor: '#796E00',
                  data: abk_berangkat_kapal
                },
                // ============Total Keberangkatan===============
                {
                  backgroundColor: '#008806',
                  borderColor: '#008806',
                  data: penumpang_berangkat_kapal
                },
                // ============Total Keberangkatan===============
              ]
            },
            options: {
              maintainAspectRatio: false,
              tooltips: {
                mode: mode,
                intersect: intersect
              },
              hover: {
                mode: mode,
                intersect: intersect
              },
              legend: {
                display: false
              },
              scales: {
                yAxes: [{
                  // display: false,
                  gridLines: {
                    display: true,
                    lineWidth: '4px',
                    color: 'rgba(0, 0, 0, .2)',
                    zeroLineColor: 'transparent'
                  },
                  ticks: $.extend({
                    beginAtZero: true,

                    // Include a dollar sign in the ticks
                    callback: function(value) {
                      if (value >= 1000) {
                        value /= 1000
                        value += 'k'
                      }

                      return '' + value
                    }
                  }, ticksStyle)
                }],
                xAxes: [{
                  display: true,
                  gridLines: {
                    display: false
                  },
                  ticks: ticksStyle
                }]
              }
            }
          })
          // ==============================================================================
          
          // ==============================================================================
        }
      })
    })
  })
// });

// lgtm [js/unused-local-variable]
