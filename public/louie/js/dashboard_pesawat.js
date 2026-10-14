/* global Chart:false */
  // $( document ).ready(function() {
  $(function () {
    jQuery(document).ready(function() {
      $.ajax({
        url: '/infografis/ajax',
        method: 'GET',
        success: function(data) {
          function bil(bilangan){
    	
            var	number_string = bilangan.toString(),
              sisa 	= number_string.length % 3,
              rupiah 	= number_string.substr(0, sisa),
              ribuan 	= number_string.substr(sisa).match(/\d{3}/g);
                
            if (ribuan) {
              separator = sisa ? '.' : '';
              rupiah += separator + ribuan.join('.');
            }
            
            // Cetak hasil
            document.write(rupiah);
          }
          'use strict'

          // ==========================================================================
          $("#datang_total_keseluruhan_pesawat").text(parseInt(data['total_pesawat'][0]['pesawat_datang_negeri_pesawat_total'])+parseInt(data['total_pesawat'][0]['pesawat_datang_luar_pesawat_total']))
          $("#datang_total_ln_pesawat").text(data['total_pesawat'][0]['pesawat_datang_luar_pesawat_total'])                  
          $("#datang_total_dn_pesawat").text(data['total_pesawat'][0]['pesawat_datang_negeri_pesawat_total'])
          
          $("#datang_ln_pesawat").text(data['pesawat'][0]['pesawat_datang_luar_pesawat'])                  
          $("#datang_dn_pesawat").text(data['pesawat'][0]['pesawat_datang_negeri_pesawat'])
          // ==========================================================================
          
          $("#berangkat_total_keseluruhan_pesawat").text(parseInt(data['total_pesawat'][0]['pesawat_berangkat_negeri_pesawat_total'])+parseInt(data['total_pesawat'][0]['pesawat_berangkat_luar_pesawat_total']))
          $("#berangkat_total_ln_pesawat").text(data['total_pesawat'][0]['pesawat_berangkat_luar_pesawat_total'])                  
          $("#berangkat_total_dn_pesawat").text(data['total_pesawat'][0]['pesawat_berangkat_negeri_pesawat_total'])
          
          $("#berangkat_ln_pesawat").text(data['pesawat'][0]['pesawat_berangkat_luar_pesawat'])                  
          $("#berangkat_dn_pesawat").text(data['pesawat'][0]['pesawat_berangkat_negeri_pesawat'])
          // ==========================================================================
          // ==========================================================================
          $("#datang_total_keseluruhan_crew").text(parseInt(data['total_pesawat'][0]['crew_datang_negeri_pesawat_total'])+parseInt(data['total_pesawat'][0]['crew_datang_luar_pesawat_total']))
          $("#datang_total_ln_crew").text(data['total_pesawat'][0]['crew_datang_luar_pesawat_total'])                  
          $("#datang_total_dn_crew").text(data['total_pesawat'][0]['crew_datang_negeri_pesawat_total'])
          
          $("#datang_ln_crew").text(data['pesawat'][0]['crew_datang_luar_pesawat'])                  
          $("#datang_dn_crew").text(data['pesawat'][0]['crew_datang_negeri_pesawat'])
          // ==========================================================================
          
          $("#berangkat_total_keseluruhan_crew").text(parseInt(data['total_pesawat'][0]['crew_berangkat_negeri_pesawat_total'])+parseInt(data['total_pesawat'][0]['crew_berangkat_luar_pesawat_total']))
          $("#berangkat_total_ln_crew").text(data['total_pesawat'][0]['crew_berangkat_luar_pesawat_total'])                  
          $("#berangkat_total_dn_crew").text(data['total_pesawat'][0]['crew_berangkat_negeri_pesawat_total'])
          
          $("#berangkat_ln_crew").text(data['pesawat'][0]['crew_berangkat_luar_pesawat'])                  
          $("#berangkat_dn_crew").text(data['pesawat'][0]['crew_berangkat_negeri_pesawat'])
          // ==========================================================================
          // ==========================================================================
          $("#datang_total_keseluruhan_penumpang").text(parseInt(data['total_pesawat'][0]['penumpang_datang_negeri_pesawat_total'])+parseInt(data['total_pesawat'][0]['penumpang_datang_luar_pesawat_total']))
          $("#datang_total_ln_penumpang").text(data['total_pesawat'][0]['penumpang_datang_luar_pesawat_total'])                  
          $("#datang_total_dn_penumpang").text(data['total_pesawat'][0]['penumpang_datang_negeri_pesawat_total'])
          
          $("#datang_ln_penumpang").text(data['pesawat'][0]['penumpang_datang_luar_pesawat'])                  
          $("#datang_dn_penumpang").text(data['pesawat'][0]['penumpang_datang_negeri_pesawat'])
          // ==========================================================================
          
          $("#berangkat_total_keseluruhan_penumpang").text(parseInt(data['total_pesawat'][0]['penumpang_berangkat_negeri_pesawat_total'])+parseInt(data['total_pesawat'][0]['penumpang_berangkat_luar_pesawat_total']))
          $("#berangkat_total_ln_penumpang").text(data['total_pesawat'][0]['penumpang_berangkat_luar_pesawat_total'])                  
          $("#berangkat_total_dn_penumpang").text(data['total_pesawat'][0]['penumpang_berangkat_negeri_pesawat_total'])
          
          $("#berangkat_ln_penumpang").text(data['pesawat'][0]['penumpang_berangkat_luar_pesawat'])                  
          $("#berangkat_dn_penumpang").text(data['pesawat'][0]['penumpang_berangkat_negeri_pesawat'])
          // ==========================================================================
          $("#tgl_datang_pesawat").text(data['pesawat'][0]['updated_at'])
          $("#tgl_berangkat_pesawat").text(data['pesawat'][0]['updated_at'])
          $("#tgl_datang_crew").text(data['pesawat'][0]['updated_at'])
          $("#tgl_berangkat_crew").text(data['pesawat'][0]['updated_at'])
          $("#tgl_datang_penumpang").text(data['pesawat'][0]['updated_at'])
          $("#tgl_berangkat_penumpang").text(data['pesawat'][0]['updated_at'])


          var ticksStyle = {
            fontColor: '#495057',
            fontStyle: 'bold'
          }


          var mode = 'index'
          var intersect = true
          var tanggal_pesawat = []
          var pesawat_datang_pesawat = []
          var pesawat_berangkat_pesawat = []
          var crew_datang_pesawat = []
          var crew_berangkat_pesawat = []
          var penumpang_datang_pesawat = []
          var penumpang_berangkat_pesawat = []

          for (let i = 0; i < 5; i++) {
            if (data['pesawat'][i]) {
              tanggal_pesawat.push(data['pesawat'][i]['created_at_pesawat'])
              pesawat_datang_pesawat.push(parseInt(data['pesawat'][i]['pesawat_datang_negeri_pesawat'])+parseInt(data['pesawat'][i]['pesawat_datang_luar_pesawat']))
              pesawat_berangkat_pesawat.push(parseInt(data['pesawat'][i]['pesawat_berangkat_negeri_pesawat'])+parseInt(data['pesawat'][i]['pesawat_berangkat_luar_pesawat']))
              crew_datang_pesawat.push(parseInt(data['pesawat'][i]['crew_datang_negeri_pesawat'])+parseInt(data['pesawat'][i]['crew_datang_luar_pesawat']))
              crew_berangkat_pesawat.push(parseInt(data['pesawat'][i]['crew_berangkat_negeri_pesawat'])+parseInt(data['pesawat'][i]['crew_berangkat_luar_pesawat']))
              penumpang_datang_pesawat.push(parseInt(data['pesawat'][i]['penumpang_datang_negeri_pesawat'])+parseInt(data['pesawat'][i]['penumpang_datang_luar_pesawat']))
              penumpang_berangkat_pesawat.push(parseInt(data['pesawat'][i]['penumpang_berangkat_negeri_pesawat'])+parseInt(data['pesawat'][i]['penumpang_berangkat_luar_pesawat']))
            }
          }


          var $salesChart = $('#sales-chart')
          // eslint-disable-next-line no-unused-vars
          var salesChart = new Chart($salesChart, {
            type: 'bar',
            data: {
              labels: tanggal_pesawat,
              datasets: [
                // =============Total Kedatangan================
                {
                  backgroundColor: '#006a94',
                  borderColor: '#006a94',
                  data: pesawat_datang_pesawat
                },
                // =============Total Kedatangan================
                {
                  backgroundColor: '#FFED45',
                  borderColor: '#FFED45',
                  data: crew_datang_pesawat
                },
                // =============Total Kedatangan================
                {
                  backgroundColor: '#57FF5F',
                  borderColor: '#57FF5F',
                  data: penumpang_datang_pesawat
                },
                // =============Total Kedatangan=================
                // ============Total Keberangkatan===============
                {
                  backgroundColor: '#003449',
                  borderColor: '#003449',
                  data: pesawat_berangkat_pesawat
                },
                // ============Total Keberangkatan===============
                {
                  backgroundColor: '#796E00',
                  borderColor: '#796E00',
                  data: crew_berangkat_pesawat
                },
                // ============Total Keberangkatan===============
                {
                  backgroundColor: '#008806',
                  borderColor: '#008806',
                  data: penumpang_berangkat_pesawat
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
