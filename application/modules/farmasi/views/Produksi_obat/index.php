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

    <div class="clearfix" style="margin-bottom:-5px">
      <?php echo $this->authuser->show_button('farmasi/Produksi_obat','C','',1)?>
      <?php echo $this->authuser->show_button('farmasi/Produksi_obat','D','',5)?>
      <?php echo $this->authuser->show_button('farmasi/Produksi_obat','EX','',1)?>

    </div>
    <hr class="separator">
    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
    <table id="dynamic-table" base-url="farmasi/Produksi_obat/get_data" data-id="flag=All" url-detail="farmasi/Produksi_obat/get_detail" class="table table-bordered table-hover">
       <thead>
        <tr>  
        <th width="30px" class="center"></th>
          <th width="40px" class="center"></th>
          <th width="40px"></th>
          <th width="40px"></th>
          <th>Nama Obat</th>
          <th>Satuan</th>
          <th>Rasio</th>
          <th>Tgl Produksi</th>
          <th>Tgl Expired</th>
          <th>Jumlah</th>
          <th>Total Harga Prod</th>
          <th>Harga Satuan</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable_with_detail_custom_url.js'?>"></script>



