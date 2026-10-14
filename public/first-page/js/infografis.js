
$(function () {
  // jQuery(document).ready(function() {
    $.ajax({
      url: '/auth/ajax',
      method: 'GET',
      success: function(data) {      
        
        'use strict'

        // ==========================================================================
        var num1 = parseInt(data['total_kapal'][0]['kapal_datang_negeri_kapal_total'])+parseInt(data['total_kapal'][0]['kapal_datang_luar_kapal_total']);
        $.ajax({
          url:'/format_angka/'+num1,
          method: 'get',
          beforeSend: function() {
            $("#datang_total_keseluruhan_kapal").html(num1)
          },
          success: function(format_angka) { 
            $("#datang_total_keseluruhan_kapal").text(format_angka)
          }
        })
        var num2 = data['total_kapal'][0]['kapal_datang_luar_kapal_total'];
        $.ajax({
          url:'/format_angka/'+num2,
          method: 'get',
          beforeSend: function() {
            $("#datang_total_ln_kapal").html(num2)
          },
          success: function(format_angka) { 
            $("#datang_total_ln_kapal").text(format_angka)
          }
        })
                      
        var num3 = data['total_kapal'][0]['kapal_datang_negeri_kapal_total'];
        $.ajax({
          url:'/format_angka/'+num3,
          method: 'get',
          beforeSend: function() {
            $("#datang_total_dn_kapal").html(num3)
          },
          success: function(format_angka) { 
            $("#datang_total_dn_kapal").text(format_angka)
          }
        })
        
        // ==========================================================================
        
        var num4 = parseInt(data['total_kapal'][0]['kapal_berangkat_negeri_kapal_total'])+parseInt(data['total_kapal'][0]['kapal_berangkat_luar_kapal_total']);
        $.ajax({
          url:'/format_angka/'+num4,
          method: 'get',
          beforeSend: function() {
            $("#berangkat_total_keseluruhan_kapal").html(num4)
          },
          success: function(format_angka) { 
            $("#berangkat_total_keseluruhan_kapal").text(format_angka)
          }
        })
        
        var num5 = data['total_kapal'][0]['kapal_berangkat_luar_kapal_total'];
        $.ajax({
          url:'/format_angka/'+num5,
          method: 'get',
          beforeSend: function() {
            $("#berangkat_total_ln_kapal").html(num5)
          },
          success: function(format_angka) { 
            $("#berangkat_total_ln_kapal").text(format_angka)
          }
        })
             
        var num6 = data['total_kapal'][0]['kapal_berangkat_negeri_kapal_total'];
        $.ajax({
          url:'/format_angka/'+num6,
          method: 'get',
          beforeSend: function() {
            $("#berangkat_total_dn_kapal").html(num6)
          },
          success: function(format_angka) { 
            $("#berangkat_total_dn_kapal").text(format_angka)
          }
        })
        
        
        // ==========================================================================
        var num7 = parseInt(data['total_kapal'][0]['abk_datang_negeri_kapal_total'])+parseInt(data['total_kapal'][0]['abk_datang_luar_kapal_total']);
        $.ajax({
          url:'/format_angka/'+num7,
          method: 'get',
          beforeSend: function() {
            $("#datang_total_keseluruhan_abk").html(num7)
          },
          success: function(format_angka) { 
            $("#datang_total_keseluruhan_abk").text(format_angka)
          }
        })
        
        var num8 = data['total_kapal'][0]['abk_datang_luar_kapal_total'];
        $.ajax({
          url:'/format_angka/'+num8,
          method: 'get',
          beforeSend: function() {
            $("#datang_total_ln_abk").html(num8)
          },
          success: function(format_angka) { 
            $("#datang_total_ln_abk").text(format_angka)
          }
        })    
        
        var num9 = data['total_kapal'][0]['abk_datang_negeri_kapal_total'];
        $.ajax({
          url:'/format_angka/'+num9,
          method: 'get',
          beforeSend: function() {
            $("#datang_total_dn_abk").html(num9)
          },
          success: function(format_angka) { 
            $("#datang_total_dn_abk").text(format_angka)
          }
        })    
        
        // ==========================================================================
                  
        var num10 = parseInt(data['total_kapal'][0]['abk_berangkat_negeri_kapal_total'])+parseInt(data['total_kapal'][0]['abk_berangkat_luar_kapal_total']);
        $.ajax({
          url:'/format_angka/'+num10,
          method: 'get',
          beforeSend: function() {
            $("#berangkat_total_keseluruhan_abk").html(num10)
          },
          success: function(format_angka) { 
            $("#berangkat_total_keseluruhan_abk").text(format_angka)
          }
        })    
        
        var num11 = data['total_kapal'][0]['abk_berangkat_luar_kapal_total'];
        $.ajax({
          url:'/format_angka/'+num11,
          method: 'get',
          beforeSend: function() {
            $("#berangkat_total_ln_abk").html(num11)
          },
          success: function(format_angka) { 
            $("#berangkat_total_ln_abk").text(format_angka)
          }
        })    

        var num12 = data['total_kapal'][0]['abk_berangkat_negeri_kapal_total'];
        $.ajax({
          url:'/format_angka/'+num12,
          method: 'get',
          beforeSend: function() {
            $("#berangkat_total_dn_abk").html(num12)
          },
          success: function(format_angka) { 
            $("#berangkat_total_dn_abk").text(format_angka)
          }
        })    
        
         // ==========================================================================
        
         var num13 = parseInt(data['total_kapal'][0]['penumpang_datang_negeri_kapal_total'])+parseInt(data['total_kapal'][0]['penumpang_datang_luar_kapal_total']);
         $.ajax({
            url:'/format_angka/'+num13,
            method: 'get',
            beforeSend: function() {
              $("#datang_total_keseluruhan_penumpang_kapal").html(num13)
            },
            success: function(format_angka) { 
              $("#datang_total_keseluruhan_penumpang_kapal").text(format_angka)
            }
         })  
         
         var num14 = data['total_kapal'][0]['penumpang_datang_luar_kapal_total'];
         $.ajax({
            url:'/format_angka/'+num14,
            method: 'get',
            beforeSend: function() {
              $("#datang_total_ln_penumpang_kapal").html(num14)
            },
            success: function(format_angka) { 
              $("#datang_total_ln_penumpang_kapal").text(format_angka)
            }
         })
         
         var num15 = data['total_kapal'][0]['penumpang_datang_negeri_kapal_total'];
         $.ajax({
            url:'/format_angka/'+num15,
            method: 'get',
            beforeSend: function() {
              $("#datang_total_dn_penumpang_kapal").html(num15)
            },
            success: function(format_angka) { 
              $("#datang_total_dn_penumpang_kapal").text(format_angka)
            }
         })  
        
        // ==========================================================================        
         
        var num16 = parseInt(data['total_kapal'][0]['penumpang_berangkat_negeri_kapal_total'])+parseInt(data['total_kapal'][0]['penumpang_berangkat_luar_kapal_total']);
        $.ajax({
          url:'/format_angka/'+num16,
          method: 'get',
          beforeSend: function() {
            $("#berangkat_total_keseluruhan_penumpang_kapal").html(num16)
          },
          success: function(format_angka) { 
            $("#berangkat_total_keseluruhan_penumpang_kapal").text(format_angka)
          }
        })
        
        var num17 = data['total_kapal'][0]['penumpang_berangkat_luar_kapal_total'];
        $.ajax({
          url:'/format_angka/'+num17,
          method: 'get',
          beforeSend: function() {
            $("#berangkat_total_ln_penumpang_kapal").html(num17)
          },
          success: function(format_angka) { 
            $("#berangkat_total_ln_penumpang_kapal").text(format_angka)
          }
        })
        
        var num18 = data['total_kapal'][0]['penumpang_berangkat_negeri_kapal_total'];
        $.ajax({
          url:'/format_angka/'+num18,
          method: 'get',
          beforeSend: function() {
            $("#berangkat_total_dn_penumpang_kapal").html(num18)
          },
          success: function(format_angka) { 
            $("#berangkat_total_dn_penumpang_kapal").text(format_angka)
          }
        })
        
        // ==========================================================================
        
        var num19 = parseInt(data['total_pesawat'][0]['pesawat_datang_negeri_pesawat_total'])+parseInt(data['total_pesawat'][0]['pesawat_datang_luar_pesawat_total']);
        $.ajax({
          url:'/format_angka/'+num19,
          method: 'get',
          beforeSend: function() {
            $("#datang_total_keseluruhan_pesawat").html(num19)
          },
          success: function(format_angka) { 
            $("#datang_total_keseluruhan_pesawat").text(format_angka)
          }
        })
                
        var num20 = data['total_pesawat'][0]['pesawat_datang_luar_pesawat_total'];
        $.ajax({
          url:'/format_angka/'+num20,
          method: 'get',
          beforeSend: function() {
            $("#datang_total_ln_pesawat").html(num20)
          },
          success: function(format_angka) { 
            $("#datang_total_ln_pesawat").text(format_angka)
          }
        })
                  
        var num21 = data['total_pesawat'][0]['pesawat_datang_negeri_pesawat_total'];
        $.ajax({
          url:'/format_angka/'+num21,
          method: 'get',
          beforeSend: function() {
            $("#datang_total_dn_pesawat").html(num21)
          },
          success: function(format_angka) { 
            $("#datang_total_dn_pesawat").text(format_angka)
          }
        })
        // ==========================================================================
                  
        var num22 = parseInt(data['total_pesawat'][0]['pesawat_berangkat_negeri_pesawat_total'])+parseInt(data['total_pesawat'][0]['pesawat_berangkat_luar_pesawat_total']);
        $.ajax({
          url:'/format_angka/'+num22,
          method: 'get',
          beforeSend: function() {
            $("#berangkat_total_keseluruhan_pesawat").html(num22)
          },
          success: function(format_angka) { 
            $("#berangkat_total_keseluruhan_pesawat").text(format_angka)
          }
        })
                  
        var num23 = data['total_pesawat'][0]['pesawat_berangkat_luar_pesawat_total'];
        $.ajax({
          url:'/format_angka/'+num23,
          method: 'get',
          beforeSend: function() {
            $("#berangkat_total_ln_pesawat").html(num23)
          },
          success: function(format_angka) { 
            $("#berangkat_total_ln_pesawat").text(format_angka)
          }
        })
                     
        var num24 = data['total_pesawat'][0]['pesawat_berangkat_negeri_pesawat_total'];
        $.ajax({
          url:'/format_angka/'+num24,
          method: 'get',
          beforeSend: function() {
            $("#berangkat_total_dn_pesawat").html(num24)
          },
          success: function(format_angka) { 
            $("#berangkat_total_dn_pesawat").text(format_angka)
          }
        })
        // ==========================================================================
                     
        var num25 = parseInt(data['total_pesawat'][0]['crew_datang_negeri_pesawat_total'])+parseInt(data['total_pesawat'][0]['crew_datang_luar_pesawat_total']);
        $.ajax({
          url:'/format_angka/'+num25,
          method: 'get',
          beforeSend: function() {
            $("#datang_total_keseluruhan_crew").html(num25)
          },
          success: function(format_angka) { 
            $("#datang_total_keseluruhan_crew").text(format_angka)
          }
        })
        
        var num26 = data['total_pesawat'][0]['crew_datang_luar_pesawat_total'];
        $.ajax({
          url:'/format_angka/'+num26,
          method: 'get',
          beforeSend: function() {
            $("#datang_total_ln_crew").html(num26)
          },
          success: function(format_angka) { 
            $("#datang_total_ln_crew").text(format_angka)
          }
        })
                   
        var num27 = data['total_pesawat'][0]['crew_datang_negeri_pesawat_total'];
        $.ajax({
          url:'/format_angka/'+num27,
          method: 'get',
          beforeSend: function() {
            $("#datang_total_dn_crew").html(num27)
          },
          success: function(format_angka) { 
            $("#datang_total_dn_crew").text(format_angka)
          }
        })        
        // ==========================================================================
        
        var num28 = parseInt(data['total_pesawat'][0]['crew_berangkat_negeri_pesawat_total'])+parseInt(data['total_pesawat'][0]['crew_berangkat_luar_pesawat_total']);
        $.ajax({
          url:'/format_angka/'+num28,
          method: 'get',
          beforeSend: function() {
            $("#berangkat_total_keseluruhan_crew").html(num28)
          },
          success: function(format_angka) { 
            $("#berangkat_total_keseluruhan_crew").text(format_angka)
          }
        })
        
        var num29 = data['total_pesawat'][0]['crew_berangkat_luar_pesawat_total'];
        $.ajax({
          url:'/format_angka/'+num29,
          method: 'get',
          beforeSend: function() {
            $("#berangkat_total_ln_crew").html(num29)
          },
          success: function(format_angka) { 
            $("#berangkat_total_ln_crew").text(format_angka)
          }
        })
                    
        var num30 = data['total_pesawat'][0]['crew_berangkat_negeri_pesawat_total'];
        $.ajax({
          url:'/format_angka/'+num30,
          method: 'get',
          beforeSend: function() {
            $("#berangkat_total_dn_crew").html(num30)
          },
          success: function(format_angka) { 
            $("#berangkat_total_dn_crew").text(format_angka)
          }
        })        
        // ==========================================================================
        
        var num31 = parseInt(data['total_pesawat'][0]['penumpang_datang_negeri_pesawat_total'])+parseInt(data['total_pesawat'][0]['penumpang_datang_luar_pesawat_total']);
        $.ajax({
          url:'/format_angka/'+num31,
          method: 'get',
          beforeSend: function() {
            $("#datang_total_keseluruhan_penumpang_pesawat").html(num31)
          },
          success: function(format_angka) { 
            $("#datang_total_keseluruhan_penumpang_pesawat").text(format_angka)
          }
        })   
        
        var num32 = data['total_pesawat'][0]['penumpang_datang_luar_pesawat_total'];
        $.ajax({
          url:'/format_angka/'+num32,
          method: 'get',
          beforeSend: function() {
            $("#datang_total_ln_penumpang_pesawat").html(num32)
          },
          success: function(format_angka) { 
            $("#datang_total_ln_penumpang_pesawat").text(format_angka)
          }
        })
                           
        var num33 = data['total_pesawat'][0]['penumpang_datang_negeri_pesawat_total'];
        $.ajax({
          url:'/format_angka/'+num33,
          method: 'get',
          beforeSend: function() {
            $("#datang_total_dn_penumpang_pesawat").html(num33)
          },
          success: function(format_angka) { 
            $("#datang_total_dn_penumpang_pesawat").text(format_angka)
          }
        })
        // ==========================================================================
                          
        var num34 = parseInt(data['total_pesawat'][0]['penumpang_berangkat_negeri_pesawat_total'])+parseInt(data['total_pesawat'][0]['penumpang_berangkat_luar_pesawat_total']);
        $.ajax({
          url:'/format_angka/'+num34,
          method: 'get',
          beforeSend: function() {
            $("#berangkat_total_keseluruhan_penumpang_pesawat").html(num34)
          },
          success: function(format_angka) { 
            $("#berangkat_total_keseluruhan_penumpang_pesawat").text(format_angka)
          }
        })
                          
        var num35 = data['total_pesawat'][0]['penumpang_berangkat_luar_pesawat_total'];
        $.ajax({
          url:'/format_angka/'+num35,
          method: 'get',
          beforeSend: function() {
            $("#berangkat_total_ln_penumpang_pesawat").html(num35)
          },
          success: function(format_angka) { 
            $("#berangkat_total_ln_penumpang_pesawat").text(format_angka)
          }
        })
                          
        var num36 = data['total_pesawat'][0]['penumpang_berangkat_negeri_pesawat_total'];
        $.ajax({
          url:'/format_angka/'+num36,
          method: 'get',
          beforeSend: function() {
            $("#berangkat_total_dn_penumpang_pesawat").html(num36)
          },
          success: function(format_angka) { 
            $("#berangkat_total_dn_penumpang_pesawat").text(format_angka)
          }
        })
        
        // ==========================================================================
                    
        var num37 = parseInt(data['total_vaksin'][0]['phqc']);
        $.ajax({
          url:'/format_angka/'+num37,
          method: 'get',
          beforeSend: function() {
            $("#phqc").html(num37)
          },
          success: function(format_angka) { 
            $("#phqc").text(format_angka)
          }
        })
                    
        var num38 = parseInt(data['total_vaksin'][0]['cop']);
        $.ajax({
          url:'/format_angka/'+num38,
          method: 'get',
          beforeSend: function() {
            $("#cop").html(num38)
          },
          success: function(format_angka) { 
            $("#cop").text(format_angka)
          }
        })
                    
        var num39 = parseInt(data['total_vaksin'][0]['sscec']);
        $.ajax({
          url:'/format_angka/'+num39,
          method: 'get',
          beforeSend: function() {
            $("#sscec").html(num39)
          },
          success: function(format_angka) { 
            $("#sscec").text(format_angka)
          }
        })
                    
        var num40 = parseInt(data['total_vaksin'][0]['sscc']);
        $.ajax({
          url:'/format_angka/'+num40,
          method: 'get',
          beforeSend: function() {
            $("#sscc").html(num40)
          },
          success: function(format_angka) { 
            $("#sscc").text(format_angka)
          }
        })
                    
        var num41 = parseInt(data['total_vaksin'][0]['p3k']);
        $.ajax({
          url:'/format_angka/'+num41,
          method: 'get',
          beforeSend: function() {
            $("#p3k").html(num41)
          },
          success: function(format_angka) { 
            $("#p3k").text(format_angka)
          }
        })
        
        // ==========================================================================
               
        var num42 = parseInt(data['total_vaksin'][0]['icv']);
        $.ajax({
          url:'/format_angka/'+num42,
          method: 'get',
          beforeSend: function() {
            $("#icv").html(num42)
          },
          success: function(format_angka) { 
            $("#icv").text(format_angka)
          }
        })
               
        var num43 = parseInt(data['total_vaksin'][0]['sklt']);
        $.ajax({
          url:'/format_angka/'+num43,
          method: 'get',
          beforeSend: function() {
            $("#sklt").html(num43)
          },
          success: function(format_angka) { 
            $("#sklt").text(format_angka)
          }
        })
               
        var num44 = parseInt(data['total_vaksin'][0]['siaos']);
        $.ajax({
          url:'/format_angka/'+num44,
          method: 'get',
          beforeSend: function() {
            $("#siaos").html(num44)
          },
          success: function(format_angka) { 
            $("#siaos").text(format_angka)
          }
        })
               
        var num45 = parseInt(data['total_vaksin'][0]['siaj']);
        $.ajax({
          url:'/format_angka/'+num45,
          method: 'get',
          beforeSend: function() {
            $("#siaj").html(num45)
          },
          success: function(format_angka) { 
            $("#siaj").text(format_angka)
          }
        })
               
        var num46 = parseInt(data['total_vaksin'][0]['kir']);
        $.ajax({
          url:'/format_angka/'+num46,
          method: 'get',
          beforeSend: function() {
            $("#kir").html(num46)
          },
          success: function(format_angka) { 
            $("#kir").text(format_angka)
          }
        })
        // ==========================================================================
             
        var num47 = parseInt(data['total_vaksin'][0]['vaksin_mm']);
        $.ajax({
          url:'/format_angka/'+num47,
          method: 'get',
          beforeSend: function() {
            $("#vaksin_mm").html(num47)
          },
          success: function(format_angka) { 
            $("#vaksin_mm").text(format_angka)
          }
        })
             
        var num47 = parseInt(data['total_vaksin'][0]['vaksin_yf']);
        $.ajax({
          url:'/format_angka/'+num47,
          method: 'get',
          beforeSend: function() {
            $("#vaksin_yf").html(num47)
          },
          success: function(format_angka) { 
            $("#vaksin_yf").text(format_angka)
          }
        })
             
        var num47 = parseInt(data['total_vaksin'][0]['vaksin_covid']);
        $.ajax({
          url:'/format_angka/'+num47,
          method: 'get',
          beforeSend: function() {
            $("#vaksin_covid").html(num47)
          },
          success: function(format_angka) { 
            $("#vaksin_covid").text(format_angka)
          }
        })
      }
    })
  // })
});