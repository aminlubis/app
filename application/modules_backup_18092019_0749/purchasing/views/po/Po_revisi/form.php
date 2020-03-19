<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

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
  
    $('#form_permintaan').ajaxForm({
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
          $('#page-area-content').load('purchasing/permintaan/Req_pembelian?_=' + (new Date()).getTime());
        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 

    var flag = ( $('#flag_string').val() ) ? $('#flag_string').val() : '' ;
    var search_by = $('select[name="search_by"]').val();
    var keyword = $('#inputKeyWord').val();

    $('#btn_search_brg').click(function (e) {   

        if ( $('#inputKeyWord').val()=='' ) {
          alert('Silahkan Masukan Kata Kunci !'); return false;
        }

        search_selected_brg(flag, search_by, keyword);

        e.preventDefault();

    });

    $( "#inputKeyWord" ).keypress(function(event) {  
        var keycode =(event.keyCode?event.keyCode:event.which);
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){           
            search_selected_brg(flag, search_by, keyword);       
          }         
          return false;                
        }       
    });  

})

function search_selected_brg(flag, search_by, keyword){

  $.ajax({ //Process the form using $.ajax()
      type      : 'POST', //Method type
      url       : 'Templates/References/getRefBrg', //Your form processing file URL
      data      : {keyword: $('#inputKeyWord').val(), flag: flag, search_by: search_by}, //Forms name
      dataType  : 'json',
      success   : function(data) {
          $('#show_detail_selected_brg').html(data.html);
      }
  })

}
</script>
<style type="text/css">
  .dropdown-item{
    height : 100px;
    width: 300px;
  }
</style>
<!-- 
<div class="page-header">
  <h1>
    <?php echo $title?>
  </h1>
</div>
 -->
<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
      <div class="widget-body">
        <div class="widget-main no-padding">

          <form class="form-horizontal" method="post" id="form_permintaan" action="<?php echo site_url('purchasing/permintaan/Req_pembelian/process')?>" enctype="multipart/form-data" >
            <br>
            <!-- input form hidden -->
            <input type="hidden" name="flag" id="flag_string" value="<?php echo $flag?>">
            <div class="form-group">
              <label class="control-label col-md-2">ID</label>
              <div class="col-md-1">
                <input name="id" id="id" value="<?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->id_tc_po:'';?>" class="form-control" type="text">
              </div>
              <label class="control-label col-md-1">Nomor PO</label>
              <div class="col-md-2">
                <input name="no_po" id="no_po" value="<?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->no_po:'';?>" class="form-control" type="text" >
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Tanggal PO</label>
              <div class="col-md-2">
                <div class="input-group">
                  <input class="form-control date-picker" name="tgl_po" id="tgl_po" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->tgl_po:'';?>"/>
                  <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                  </span>
                </div>
              </div>
              <label class="control-label col-md-2">Estimasi Kirim</label>
              <div class="col-md-2">
                <div class="input-group">
                  <input class="form-control date-picker" name="tgl_po" id="tgl_po" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->tgl_kirim:'';?>"/>
                  <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Diajukan Oleh</label>
              <div class="col-md-2">
                <input name="diajukan_oleh" id="diajukan_oleh" value="<?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->diajukan_oleh:'';?>" class="form-control" type="text">
              </div>
              <label class="control-label col-md-2">Disetujui Oleh</label>
              <div class="col-md-2">
                <input name="disetujui_oleh" id="disetujui_oleh" value="<?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->disetujui_oleh:'';?>" class="form-control" type="text">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">KARS</label>
              <div class="col-md-3">
                <input name="krs" id="krs" value="<?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->krs:KARS;?>" class="form-control" type="text">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Syarat Pembayaran</label>
              <div class="col-md-4">
                <textarea class="form-control" style="height:50px !important"><?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->term_of_pay:'';?></textarea>
              </div>
            </div>
            <br>

            <?php echo $view_brg_po?>
            
            <div class="form-actions center">
              <a onclick="getMenuTabs('purchasing/po/Po_revisi/view_data?flag=<?php echo $flag?>', 'tabs_form_po')" href="#" class="btn btn-sm btn-success">
                <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                Kembali ke daftar
              </a>
              <button type="reset" id="btnReset" class="btn btn-sm btn-danger">
                <i class="ace-icon fa fa-close icon-on-right bigger-110"></i>
                Reset
              </button>
              <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
                <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                Submit
              </button>
            </div>

          </form>

        </div>
      </div>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


