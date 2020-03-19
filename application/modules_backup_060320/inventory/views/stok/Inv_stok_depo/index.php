<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script type="text/javascript">
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

  function click_detail(kode_brg){
    getMenu('inventory/stok/Inv_stok_depo/detail/'+kode_brg+'/'+$('#kode_bagian').val()+'');
  }
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

    <form class="form-horizontal" method="post" id="form_search" action="inventory/stok/Inv_stok_depo/find_data" autocomplete="off">

        <center>
            <h4><?php echo strtoupper($title)?> <br><small style="font-size:12px">Data yang ditampilkan saat ini adalah stok/depo sampai dengan tanggal hari ini <?php echo date('d/M/Y')?> </small></h4>
        </center>
      
        <br>

        <div class="form-group">
          <label class="control-label col-md-2">Pilih Depo/Unit</label>
          <div class="col-md-3">
            <?php 
              echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array()), '060101' , 'kode_bagian', 'kode_bagian', 'form-control', '', '') ?>
          </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-2">Tanggal Terakhir Stok</label>
            <div class="col-md-2">
            <div class="input-group">
                <input class="form-control date-picker" name="tgl" id="tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                <span class="input-group-addon">
                  <i class="fa fa-calendar bigger-110"></i>
                </span>
              </div>
            </div>
            <div class="col-md-4" style="margin-left: -2%">
              <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
                <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
                Search
              </a>
              <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
                <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
                Reset
              </a>
            </div>
        </div>
        
        <div class="clearfix" style="margin-bottom:-5px">
          <?php echo $this->authuser->show_button('inventory/stok/Inv_stok_depo','C','',1)?>
          <?php echo $this->authuser->show_button('inventory/stok/Inv_stok_depo','D','',5)?>
          <div class="pull-right tableTools-container"></div>
        </div>
        <hr class="separator">
        <!-- div.table-responsive -->
        
        <!-- div.dataTables_borderWrap -->
        <div style="margin-top:-27px">
          <table id="dynamic-table" base-url="inventory/stok/Inv_stok_depo" class="table table-striped table-bordered table-hover">
          <thead>
            <tr>  
              <th width="30px" class="center"></th>
              <th width="30px">No</th>
              <th width="100px">Image</th>
              <th>Kode & Nama Barang</th>
              <th>Rasio</th>
              <th>Stok Minimum</th>
              <th>Stok Akhir</th>
              <th>Satuan</th>
              <th>Harga Beli</th>
              <th>Mutasi Terakhir</th>
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



