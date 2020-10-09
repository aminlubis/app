<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<link rel="stylesheet" href="assets/css/daterangepicker.min.css" />
<script>

jQuery(function($) {  

  $('.date-picker').datepicker({    

    autoclose: true,    

    todayHighlight: true    

  })  

  //show datepicker when clicking on the icon

  .next().on(ace.click_event, function(){    

    $(this).prev().focus();    

  });  

});

$(document).ready(function(){

    sum_total_biaya_farmasi();

    var kode_trans_far = $('#kode_trans_far').val();

    table = $('#temp_data_pesan').DataTable( {
        "processing": true, 
        "serverSide": true,
        "bInfo": false,
        "bPaginate": false,
        "searching": false,
        "bSort": false,
        "ajax": {
            "url": "farmasi/Entry_resep_ri_rj/get_data_temp_pesanan_obat?relationId="+kode_trans_far+"&flag=biasa&tipe_layanan=<?php echo $tipe_layanan?>",
            "type": "POST"
        },
        "columnDefs": [
            { 
                "targets": [ 0 ], //last column
                "orderable": false, //set not orderable
            },
            {"aTargets" : [0], "mData" : 0, "sClass":  "details-control"}, 
            { "visible": true, "targets": [ 0 ] },
            { "visible": false, "targets": [ 1 ] },
            { "visible": false, "targets": [ 2 ] },
        ],
    }); 

    $('#temp_data_pesan tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );
            var data = table.row( $(this).parents('tr') ).data();
            var ID = data[ 1 ];
            var flag = data[ 2 ];
            var kode_brg = data[ 5 ];
                      

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                /*data*/
                if( flag == 'racikan' ){
                  $.getJSON("farmasi/Entry_resep_racikan/getDetail/" + ID, '', function (data) {
                      response_data = data;
                      // Open this row
                      row.child( format_html( response_data ) ).show();
                      tr.addClass('shown');
                  });
                }else{
                  $.getJSON("farmasi/Entry_resep_ri_rj/getDetail/" + kode_brg +'/'+ ID, '', function (data) {
                      response_data = data;
                      // Open this row
                      row.child( format_html( response_data ) ).show();
                      tr.addClass('shown');
                  });
                }
                
                                
            }
    } );

    $('#temp_data_pesan tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            //achtungShowLoader();
            $(this).removeClass('selected');
            //achtungHideLoader();
        }
        else {
            //achtungShowLoader();
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            //achtungHideLoader();
        }
    } );

    table_racikan = $('#temp_data_obat_racikan').DataTable( {
        "processing": true, 
        "serverSide": true,
        "bInfo": false,
        "bPaginate": false,
        "searching": false,
        "bSort": false,
        "ajax": {
            "url": "farmasi/Entry_resep_ri_rj/get_data_temp_pesanan_obat?relationId="+kode_trans_far+"&flag=racikan&tipe_layanan=<?php echo $tipe_layanan?>",
            "type": "POST"
        },
        "columnDefs": [
            { 
                "targets": [ 0 ], //last column
                "orderable": false, //set not orderable
            },
            {"aTargets" : [0], "mData" : 0, "sClass":  "details-control"}, 
            { "visible": true, "targets": [ 0 ] },
            { "visible": false, "targets": [ 1 ] },
            { "visible": false, "targets": [ 2 ] },
        ],
    }); 

    $('#temp_data_obat_racikan tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table_racikan.row( tr );
            var data = table_racikan.row( $(this).parents('tr') ).data();
            var ID = data[ 1 ];
            var flag = data[ 2 ];
            var kode_brg = data[ 5 ];
                      

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                /*data*/
                $.getJSON("farmasi/Entry_resep_racikan/getDetail/" + ID, '', function (data) {
                    response_data = data;
                    // Open this row
                    row.child( format_html( response_data ) ).show();
                    tr.addClass('shown');
                });
                                
            }
    } );

    $('#temp_data_obat_racikan tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            //achtungShowLoader();
            $(this).removeClass('selected');
            //achtungHideLoader();
        }
        else {
            //achtungShowLoader();
            table_racikan.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            //achtungHideLoader();
        }
    } );

    $('#inputKeyObat').focus();    

    $('#form_entry_resep').ajaxForm({      

      beforeSend: function() {        

        achtungShowLoader();          

      },      

      uploadProgress: function(event, position, total, percentComplete) {        

      },      

      complete: function(xhr) {             

        var data=xhr.responseText;        

        var jsonResponse = JSON.parse(data);        

        if(jsonResponse.status === 200){          

          $.achtung({message: jsonResponse.message, timeout:5});             

          /*renew form*/
          reset_form();

          // kode trans far
          $('#kode_trans_far').val(jsonResponse.kode_trans_far);

          /*reload table*/
          reload_table();
          /*sum total biaya farmasi*/
          sum_total_biaya_farmasi();
          
        }else{          

          $.achtung({message: jsonResponse.message, timeout:5});          

        }    

        achtungHideLoader();  
        
      }      

    });     

    $('#inputKeyObat').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getObatByBagianAutoComplete",
                data: { keyword:query, bag: '060101'},            
                dataType: "json",
                type: "POST",
                success: function (response) {
                  result($.map(response, function (item) {
                      return item;
                  }));
                }
            });
        },
        afterSelect: function (item) {
          // do what is needed with item
          var val_item=item.split(':')[0];
          console.log(val_item);

          var detailObat = getDetailObatByKodeBrg(val_item,'060101');
          $('#jumlah_pesan').focus();

        }
    });

    $( "#jumlah_pesan" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#jml_23').focus();
          }
          return false;       
        }
    });

    // $( "#jumlah_tebus" )
    //   .keypress(function(event) {
    //     var keycode =(event.keyCode?event.keyCode:event.which); 
    //     if(keycode ==13){
    //       event.preventDefault();
    //       if($(this).valid()){
    //         $('#jml_23').focus();
    //       }
    //       return false;       
    //     }
    // });

    $( "#jml_23" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#dosis_start').focus();
          }
          return false;       
        }
    });

    $( "#dosis_start" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#dosis_end').focus();
          }
          return false;       
        }
    });

    $( "#dosis_end" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#satuan_obat').focus();
          }
          return false;       
        }
    });

    $( "#satuan_obat" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#anjuran_pakai').focus();
          }
          return false;       
        }
    });

    $( "#anjuran_pakai" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#catatan').focus();
          }
          return false;       
        }
    });

    $( "#catatan" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#btn_submit').click();
          }
          return false;       
        }
    });

    $('#resep_ditangguhkan').click(function() {
      if($('#sisa_stok').val() <= 0){
        if (!$(this).is(':checked')) {
          $('#btn_submit').attr('disabled', true);
        }else{
          $('#btn_submit').attr('disabled', false);
        }
      }
      
    });


})

function getDetailObatByKodeBrg(kode_brg,kode_bag){

  $.getJSON("<?php echo site_url('templates/references/getDetailObat') ?>?kode="+kode_brg+"&kode_kelompok=<?php echo isset($value)?$value->kode_kelompok:0?>&kode_perusahaan="+$('#kode_perusahaan').val()+"&bag="+kode_bag+"&type=html&type_layan=Rajal", '' , function (response) {
    $('#sisa_stok').val(response.sisa_stok);
    if(response.sisa_stok <= 0){
      $('#jumlah_pesan').focus();
      $('#btn_submit').attr('disabled', true);
      $('#warning_stok_obat').html('<div class="alert alert-danger"><b><i class="fa fa-exclamation-triangle"></i> Peringatan !</b> Stok sudah habis, silahkan lakukan permintaan ke gudang farmasi.</div>');
    $('#detailPembelianObatHtml').html('');
    }else{
      $('#btn_submit').attr('disabled', false);
      $('#warning_stok_obat').html('');
      // cek data obat bpjs yang sudah pernah di beli sebelumnya
      $.getJSON("<?php echo site_url('templates/references/getDataPembelianObat') ?>?kode="+kode_brg+"&kode_perusahaan=<?php echo isset($value)?$value->kode_perusahaan:0?>&bag="+kode_bag+"&type=html&type_layan=Rajal&no_mr="+$('#no_mr').val()+"", '' , function (response) {
        // show warning
        $('#detailPembelianObatHtml').html(response.html);
      })
    }
    /*show detail tarif html*/
    $('#div_detail_obat').show('fast');
    $('#detailObatHtml').html(response.html);

    return response;

  })

}

function edit_obat_resep(kode_brg, kode_tr_resep){

  preventDefault();

  var kode_bag = $('#kode_bagian').val();

  $.getJSON("<?php echo site_url('farmasi/Entry_resep_ri_rj/getDetail') ?>/"+kode_brg+"/"+kode_tr_resep, '' , function (response) {

      getDetailObatByKodeBrg(kode_brg, kode_bag);
      var obj = response.resep_data;
      console.log(obj.kode_brg);
      /*show value form*/
      $('#kode_trans_far').val(obj.kode_trans_far);
      $('#inputKeyObat').val(kode_brg+' : '+obj.nama_brg);
      $('#jumlah_pesan').val(parseInt(obj.jumlah_pesan));
      $('#jumlah_tebus').val(parseInt(obj.jumlah_tebus));
      $('#jml_23').val(obj.jumlah_obat_23);
      $('#harga_r').val(obj.jasa_r);

      /*radio*/
      $("input[name=urgensi][value="+obj.urgensi+"]").prop('checked', true);

      if(obj.prb_ditangguhkan == 1){
        $('input[name=prb_ditangguhkan][type=checkbox]').prop('checked',true);
      }else{
        $('input[name=prb_ditangguhkan][type=checkbox]').prop('checked',false);
      }

      if(obj.resep_ditangguhkan == 1){
        $('input[name=resep_ditangguhkan][type=checkbox]').prop('checked',true);
        $('#btn_submit').attr('disabled', false);
      }else{
        $('input[name=resep_ditangguhkan][type=checkbox]').prop('checked',false);
        $('#btn_submit').attr('disabled', true);
      }
      
      $('#dosis_start').val(obj.dosis_per_hari);
      $('#dosis_end').val(obj.dosis_obat);
      $('#catatan').val(obj.catatan_lainnya);
      $('#satuan_obat').val(obj.satuan_obat);
      $('#anjuran_pakai').val(obj.anjuran_pakai);

      $('#kd_tr_resep').val(obj.relation_id);

  })

}

function format_html ( data ) {
  return data.html;
}

function reset_form(){

  $('#inputKeyObat').focus();
  $('#form_entry_resep')[0].reset();
  $('#kd_tr_resep').val('0');

  $('#inputKeyObat').val('');
  $('#jumlah_pesan').val('');
  $('#jumlah_tebus').val('');
  $('#harga_r').val(500);

  /*radio*/
  $("input[name=urgensi][value=biasa]").prop('checked', true);

  $('#dosis_start').val('');
  $('#dosis_end').val('');
  $('#catatan').val('Minum secara rutin dan dihabiskan.');
  $('#satuan_obat').val('');
  $('#anjuran_pakai').val('');

   /*show detail tarif html*/
  // $('#div_detail_obat').hide('fast');
  $('#detailObatHtml').html('<img src="<?php echo base_url().'assets/img/no-data.png'?>" width="50%">');

}

$('#btn_racikan').click(function () {  
  show_modal('farmasi/Entry_resep_racikan/form/'+$('#kode_trans_far').val()+'?kelompok='+$('#kode_kelompok').val()+'&tipe_layanan='+$('#flag_trans').val()+'', 'RESEP RACIKAN');
})

function reload_table(){
  var kode_trans_far = $('#kode_trans_far').val();
  table.ajax.url("farmasi/Entry_resep_ri_rj/get_data_temp_pesanan_obat?relationId="+kode_trans_far+"&flag=biasa&tipe_layanan=<?php echo $tipe_layanan?>").load();
  // table_racikan.ajax.url("farmasi/Entry_resep_ri_rj/get_data_temp_pesanan_obat?relationId="+kode_trans_far+"&flag=racikan&tipe_layanan=<?php echo $tipe_layanan?>").load();
  sum_total_biaya_farmasi();
}

function delete_resep(myid, flag){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'farmasi/process_entry_resep/delete',
        type: "post",
        data: {ID:myid, flag:flag},
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();  
        },
        uploadProgress: function(event, position, total, percentComplete) {
        },
        complete: function(xhr) {     
          var data=xhr.responseText;
          var jsonResponse = JSON.parse(data);
          if(jsonResponse.status === 200){
            $.achtung({message: jsonResponse.message, timeout:5});
            reload_table();
            reset_form();
          }else{
            $.achtung({message: jsonResponse.message, timeout:5});
          }
          achtungHideLoader();
        }

      });

  }else{
    return false;
  }
  
}

function resep_farmasi_selesai(){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'farmasi/process_entry_resep/process_selesai_resep',
        type: "post",
        data: { ID : $('#kode_trans_far').val(), 'kode_pesan_resep' : $('#no_resep').val(), 'kode_kelompok' : $('#kode_kelompok').val(), 'kode_perusahaan' : $('#kode_perusahaan').val() },
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();  
        },
        uploadProgress: function(event, position, total, percentComplete) {
        },
        complete: function(xhr) {     
          var data=xhr.responseText;
          var jsonResponse = JSON.parse(data);
          if(jsonResponse.status === 200){
            $.achtung({message: jsonResponse.message, timeout:5});
            // show poup cetak resep
            // PopupCenter('farmasi/Process_entry_resep/nota_farmasi/'+jsonResponse.kode_trans_far+'','Nota Farmasi', 530, 550);
            $('#page-area-content').load('farmasi/Process_entry_resep/preview_entry/'+jsonResponse.kode_trans_far+'?flag=RJ');
            // $('#page-area-content').load('farmasi/Entry_resep_ri_rj?flag=RJ');

          }else{
            $.achtung({message: jsonResponse.message, timeout:5});
          }
          achtungHideLoader();
        }

      });

  }else{
    return false;
  }
  
}

function sum_total_biaya_farmasi(){

  var kode_trans_far = $('#kode_trans_far').val();
  $.getJSON("<?php echo site_url('farmasi/process_entry_resep/get_total_biaya_farmasi') ?>/"+kode_trans_far, '' , function (response) {

      $('#td_total_biaya_farmasi').html('<b>Rp. '+formatMoney(response.total)+',-</b>');

  })

}

function duplicate_input(id_input, duplicate_to){
  $('#'+duplicate_to).val( parseInt( $('#'+id_input).val() ) );
}

function rollback_resep_farmasi(id){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'farmasi/process_entry_resep/rollback',
        type: "post",
        data: { ID : id },
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();  
        },
        uploadProgress: function(event, position, total, percentComplete) {
        },
        complete: function(xhr) {     
          var data=xhr.responseText;
          var jsonResponse = JSON.parse(data);
          if(jsonResponse.status === 200){
            $.achtung({message: jsonResponse.message, timeout:5});
            // show poup cetak resep
            $('#page-area-content').load('farmasi/Entry_resep_ri_rj/form/'+$('#no_resep').val()+'?mr='+$('#no_mr').val()+'&tipe_layanan='+$('#flag_trans').val()+'');

          }else{
            $.achtung({message: jsonResponse.message, timeout:5});
          }
          achtungHideLoader();
        }

      });

  }else{
    return false;
  }
}

function changeUrgensi(){
  alert($("input[type='radio'][name='urgensi']:checked").val());
}


</script>

<style type="text/css">
  .pagination{
    margin: 0px 0px !important;
  }
  .well{
    padding: 5px !important;
  }
</style>

<div class="row">

  <div class="col-xs-12">

    <!-- breadcrumbs -->
    <div class="page-header">  
      <h1>
      <?php echo isset($value)?ucwords($value->no_mr):''?> - <?php echo isset($value)?ucwords($value->nama_pasien):''?>        
        <small><i class="ace-icon fa fa-angle-double-right"></i> <?php echo isset($breadcrumbs)?$breadcrumbs:''?></small>        
      </h1>
    </div>  
        
    <form class="form-horizontal" method="post" id="form_entry_resep" enctype="multipart/form-data" autocomplete="off" action="farmasi/process_entry_resep/process">      
      
      <!-- form_hidden -->

      <input type="hidden" name="sisa_stok_hidden" id="sisa_stok" value="0">
      <input type="hidden" name="kd_tr_resep" id="kd_tr_resep" value="0">
      <input type="hidden" name="no_registrasi" value="<?php echo isset($value)?$value->no_registrasi:''?>">
      <input type="hidden" name="no_mr" id="no_mr" value="<?php echo isset($value)?$value->no_mr:''?>">
      <input type="hidden" name="nama_pasien" value="<?php echo isset($value)?$value->nama_pasien:''?>">
      <input type="hidden" name="kode_dokter" value="<?php echo isset($value)?$value->kode_dokter:''?>">
      <input type="hidden" name="dokter_pengirim" value="<?php echo isset($value)?$value->nama_pegawai:''?>">
      <input type="hidden" name="kode_profit" value="<?php echo ($tipe_layanan=='RJ')?2000:1000;?>">
      <input type="hidden" name="kode_bagian" value="<?php echo isset($value)?$value->kode_bagian:''?>" id="kode_bagian">
      <input type="hidden" name="kode_bagian_asal" value="<?php echo isset($value)?$value->kode_bagian_asal:''?>">
      <input type="hidden" name="flag_trans" id="flag_trans" value="<?php echo $tipe_layanan?>">
      <input type="hidden" name="flag_resep" value="biasa">
      <input type="hidden" name="no_kunjungan" id="no_kunjungan" class="form-control" value="<?php echo isset($value)?ucwords($value->no_kunjungan):''?>" >
      <input type="hidden" name="no_resep" id="no_resep" class="form-control" value="<?php echo isset($value)?$value->kode_pesan_resep:''?>" >
      <input type="hidden" name="kode_kelompok" id="kode_kelompok" class="form-control" value="<?php echo isset($value)?$value->kode_kelompok:''?>" >
      <input type="hidden" name="kode_perusahaan" id="kode_perusahaan" class="form-control" value="<?php echo isset($value)?$value->kode_perusahaan:''?>" >
      <input type="hidden" name="kode_poli" id="kode_poli" class="form-control" value="<?php echo isset($value->kode_poli)?$value->kode_poli:0?>" >
      <input type="hidden" name="kode_ri" id="kode_ri" class="form-control" value="<?php echo isset($value->kode_ri)?$value->kode_ri:0?>" >
      <input class="form-control" name="harga_r" id="harga_r" type="hidden" value="500" readonly />


      <div class="row">

        <!-- keterangan pasien -->
        <div class="col-sm-12">
          <table class="table">
            <tr style="background-color: #edf3f4; font-weight: bold">
              <td> <?php echo isset($value)?ucwords($value->kode_pesan_resep):''?> </td>
              <td> <?php echo isset($value)?ucwords($this->tanggal->formatDateTime($value->tgl_pesan)):''?> </td>
              <td> <?php echo isset($value)?ucwords($value->nama_kelompok):''?> <?php echo isset($value)?ucwords($value->nama_perusahaan):''?> </td>
              <td> <?php echo isset($value)?ucwords($value->nama_bagian):''?> </td>
              <td> <?php echo isset($value)?$value->nama_pegawai:''?> </td>
              <td> <div style="font-size: 18px" id="td_total_biaya_farmasi"> <b>Rp.0,-</b>  </div></td>
            </tr>
          </table>
        </div>
        
        <!-- form utama -->
        <div class="col-sm-7">

          <div class="widget-box">
            <div class="widget-header">
                <span class="widget-title" style="font-size: 14px; font-weight: bold; color: black">Form Input Resep Farmasi</span>
              <div class="widget-toolbar">
                <?php if($value->status_tebus != 1) :?>
                <button type="button" id="btn_racikan" class="btn btn-purple btn-xs">
                  <span class="ace-icon fa fa-plus-square icon-on-right bigger-110"></span>
                  Resep Racikan
                </button>

                <button type="button" id="btn_resep_selesai" class="btn btn-primary btn-xs" name="submit" value="resep_selesai" onclick="resep_farmasi_selesai()">
                      <span class="ace-icon fa fa-check-circle icon-on-right bigger-110"></span>
                      Resep Selesai
                </button>
                <?php else: ?>
                  <button type="button" id="btn_rollback" onclick="rollback_resep_farmasi(<?php echo $value->kode_pesan_resep?>)" class="btn btn-danger btn-xs" name="rollback" value="rollback">
                      <span class="ace-icon fa fa-refresh icon-on-right bigger-110"></span>
                      Rollback
                  </button>
                  <!-- <span class="ace-icon fa fa-check-circle icon-on-right bigger-150 green"></span> -->
                <?php endif; ?>

              </div>
            </div>
            <div class="widget-body" style="padding:5px; min-height: 278px !important" >
              <!-- Data Obat -->
              <p><b>FORM OBAT</b></p>
              <div class="form-group">
                <label class="control-label col-sm-2">Kode</label>
                <div class="col-md-2">
                  <input type="text" class="form-control" name="kode_trans_far" id="kode_trans_far" value="<?php echo isset($trans_farmasi->kode_trans_far)?$trans_farmasi->kode_trans_far:''?>" readonly>
                </div> 
                <label class="control-label col-sm-2">Tanggal</label>
                <div class="col-md-3">
                  <div class="input-group">
                      <input name="tgl_resep" id="tgl_resep" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text" value="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>">
                      <span class="input-group-addon">
                        <i class="ace-icon fa fa-calendar"></i>
                      </span>
                    </div>
                </div>
              </div>
              <!-- tanggal -->
              <div class="form-group">
                <label class="control-label col-sm-2">Jenis</label>
                <div class="col-md-5">
                  <div class="radio">
                      <label>
                        <input name="urgensi" type="radio" class="ace" value="cito" onclick="changeUrgensi()" />
                        <span class="lbl"> Cito</span>
                      </label>

                      <label>
                        <input name="urgensi" type="radio" class="ace" value="biasa" onclick="changeUrgensi()" checked/>
                        <span class="lbl"> Biasa</span>
                      </label>
                  </div>
                </div> 

              </div>
              <!-- cari obat -->
              <div class="form-group">
                <label class="control-label col-sm-2">Cari Obat</label>            
                <div class="col-md-8">            
                  <input type="text" name="obat" id="inputKeyObat" class="form-control" placeholder="Masukan Keyword Obat" value="">
                </div>
              </div>

              <!-- jumlah pesan -->
              <div class="form-group">
                <label class="control-label col-sm-2">Jml Pesan/Tebus</label>
                <div class="col-md-2">
                    <input class="form-control" name="jumlah_pesan" id="jumlah_pesan" type="text" style="text-align:center" onchange="duplicate_input('jumlah_pesan','jumlah_tebus')"/>
                </div>
                <div class="col-md-6">
                  <label class="inline" style="margin-top: 4px;margin-left: -12px;">
                    <input type="checkbox" class="ace" name="resep_ditangguhkan" id="resep_ditangguhkan" value="1">
                    <span class="lbl"> Ditangguhkan</span>
                  </label>
                </div>
                <!-- <label class="control-label col-sm-1">Tebus</label>
                <div class="col-md-2">
                    <input class="form-control" name="jumlah_tebus" id="jumlah_tebus" type="text" style="text-align:center" />
                </div> -->
              </div>

              <div class="form-group">
                <label class="control-label col-sm-2">Resep Kronis</label>
                <div class="col-md-2">
                    <input class="form-control" name="jml_23" id="jml_23" type="text" value="<?php echo ($value->kode_perusahaan==120)?23:0?>" style="text-align:center"/>
               
                </div>
                <div class="col-md-6">
                  <label class="inline" style="margin-top: 4px;margin-left: -12px;">
                    <input type="checkbox" class="ace" name="prb_ditangguhkan" id="prb_ditangguhkan" value="1">
                    <span class="lbl"> Ditangguhkan</span>
                  </label>
                </div>
              </div>

              <p style="padding-top: 10px"><b>FORM SIGNA</b></p>

              <div class="form-group">
                  <label class="control-label col-sm-2">Signa</label>
                  <div class="col-md-10">

                    <span class="input-icon">
                      <input name="dosis_start" id="dosis_start" type="text" style="width: 50px;"/>
                    </span>

                    <span class="input-icon" style="padding-left: 4px">
                      <i class="fa fa-times bigger-150"></i>
                    </span>

                    <span class="input-icon">
                      <input name="dosis_end" id="dosis_end" type="text" style="width: 50px;"/>
                    </span>

                    <span class="input-icon">
                      <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'satuan_obat')), 'TAB' , 'satuan_obat', 'satuan_obat', '', '', 'style="margin-left: -2px"');?>
                    </span>

                    <span class="input-icon">
                      <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'anjuran_pakai_obat')), 'Sesudah Makan' , 'anjuran_pakai', 'anjuran_pakai', '', '', 'style="margin-left: -2px"');?>
                    </span>

                  </div>
              </div>

              <div class="form-group">
                  <label class="control-label col-sm-2">Catatan</label>
                  <div class="col-md-1">
                      <input class="form-control" name="catatan" id="catatan" type="text" style="width: 400px" value="Minum secara rutin dan dihabiskan."/>
                  </div>
              </div>
              <?php if($value->status_tebus != 1) :?>
              <div class="form-group">
                  <label class="col-sm-2">&nbsp;</label>
                  <div class="col-md-4" style="margin-left: 4px">
                    <button type="submit" id="btn_submit"  name="submit" class="btn btn-xs btn-primary">
                        <i class="ace-icon fa fa-plus icon-on-right bigger-110"></i>
                        Tambahkan Obat
                    </button>
                  </div>
              </div>
              <?php endif; ?>


            </div>
          </div>
          
        </div>

        <!-- detail selected obat -->
        <div class="col-sm-5 no-padding">
          <div class="widget-box">
            <div class="widget-header">
                <span class="widget-title" style="font-size: 14px; font-weight: bold; color: black">Stok Barang/Obat Farmasi</span>
              <div class="widget-toolbar">

              </div>
            </div>
            <div class="widget-body" style="padding:5px; min-height: 304px !important">
              <div id="div_detail_obat">
                <div id="warning_stok_obat"></div>
                <div id="detailObatHtml" class="center">
                <img src="<?php echo base_url().'assets/img/no-data.png'?>" width="50%">
                </div>
                <div id="detailPembelianObatHtml"></div>
              </div>
            </div>
          </div>
            
        </div>
        
        <!-- datatable detail obat -->
        <div class="col-md-12">

          <table id="temp_data_pesan" class="table table-bordered table-hover">
            <thead>
              <tr>  
                <th class="center" width="50px"></th>
                <th class="center"></th>
                <th class="center"></th>
                <th class="center" width="100px"></th>
                <th width="150px">Tgl Input</th>
                <th>Kode</th>
                <th>Deskripsi Item</th>
                <th width="100px">Jumlah</th>
                <th width="100px">Ditangguhkan</th>
                <th width="100px">Harga Satuan</th>
                <th width="100px">Sub Total</th>
                <th width="100px">Jasa R</th>
                <th width="100px">Total (Rp.)</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

          <!-- <hr>
          <b>RESEP RACIKAN</b>
          <table id="temp_data_obat_racikan" class="table table-bordered table-hover">
            <thead>
              <tr>  
                <th class="center" width="50px"></th>
                <th class="center" ></th>
                <th class="center"></th>
                <th class="center" width="100px"></th>
                <th>Tgl Input</th>
                <th>Kode</th>
                <th>Deskripsi Item</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Sub Total</th>
                <th>Jasa R</th>
                <th>Total (Rp.)</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table> -->

        </div>
        
      </div>

    </form>


  </div>

</div><!-- /.row -->

