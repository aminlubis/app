<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
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

$('select[name="klinik"]').change(function () {      

    if ($(this).val()) {          

        $.getJSON("<?php echo site_url('Templates/References/getDokterByBagian') ?>/" + $(this).val() , function (data) {              

            $('#dokter option').remove();                

            $('<option value="">-Pilih Dokter-</option>').appendTo($('#dokter'));                

            $.each(data, function (i, o) {                  

                $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#dokter'));                    

            });                

        });            

    } else {          

        $('#dokter option').remove()            

    }        

}); 


</script>
<div class="row">
  <div class="col-xs-12">

    <div class="page-header">
      <h1>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
        </small>
      </h1>
    </div><!-- /.page-header -->

    <form class="form-horizontal" method="post" id="form_search" action="registration/Riwayat_kunjungan_poli/find_data">

    <div class="col-md-12">

      <center><h4>FORM PENCARIAN DATA PASIEN RUJUKAN<br><small style="font-size:12px">Data yang ditampilkan saat ini adalah data Tanggal <?php echo $this->tanggal->formatDate( date('Y-m-d') ) ?> </small></h4></center>
      <br>

      <div class="form-group">
        <label class="control-label col-md-2">Bulan</label>
          <div class="col-md-2">
            <?php echo $this->master->get_bulan('' , 'bulan', 'bulan', 'form-control', '','') ?>
          </div>
          <label class="control-label col-md-1">Tahun</label>
          <div class="col-md-2">
            <?php echo $this->master->get_tahun('' , 'tahun', 'tahun', 'form-control', '', '') ?>
          </div>
      </div>

      <div class="form-group">
          <label class="control-label col-md-2">Bagian</label>
          <div class="col-md-4">
              <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('pelayanan' => 1, 'validasi' => '0300', 'status_aktif' => 1) ), '' , 'bagian_asal', 'bagian_asal', 'form-control', '', '') ?>
          </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Tanggal Masuk</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>

          <label class="control-label col-md-1">s/d Tanggal</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2 ">&nbsp;</label>
        <div class="col-md-10">
          <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Search
          </a>
          <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
            <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
            Reset
          </a>
          <a href="#" id="btn_export_excel" class="btn btn-xs btn-success">
            <i class="fa fa-file-word-o bigger-110"></i>
            Export Excel
          </a>
        </div>
      </div>

    </div>

    <hr class="separator">
    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="registration/Reg_pasien_rujukan" class="table table-bordered table-hover">
       <thead>
        <tr>  
          <th width="30px" class="center"></th>
          <th></th>
          <th>Kode</th>
          <th>No Mr</th>
          <th>Nama Pasien</th>
          <th>Tanggal Dirujuk</th>
          <th>Rujukan dari</th>
          <th>Tujuan</th>
          <th>Status</th>
          
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>



