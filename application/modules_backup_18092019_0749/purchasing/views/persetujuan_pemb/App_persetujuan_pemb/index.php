<script type="text/javascript">
  $( "#keyword_form" ).keypress(function(event) {  
      var keycode =(event.keyCode?event.keyCode:event.which);
      if(keycode ==13){          
        event.preventDefault();         
        if($(this).valid()){           
          $('#btn_search_data').click();    
        }         
        return false;                
      }       
  });
</script>
<div class="row">
  <div class="col-xs-12">

    <form class="form-horizontal" method="post" id="form_search" action="permintaan/Req_riwayat_permintaan_pemb">

        <center>
            <h4>PERSETUJUAN PERMINTAAN PEMBELIAN GUDANG <?php echo($flag=='non_medis')?'UMUM':'MEDIS'?> <br><small style="font-size:12px">Data yang ditampilkan saat ini adalah Data Permintaan Pembelian Gudang <?php echo($flag=='non_medis')?'Umum':'Medis'?> Tahun <?php echo date('Y')?> </small></h4>
        </center>
      
        <br>
        <!-- hidden form -->
        <div class="form-group">
            <label class="control-label col-md-2">Pencarian berdasarkan</label>
            <div class="col-md-2" style="margin-left:-2%">
              <select name="search_by" id="search_by" class="form-control">
                <option value="">-Silahkan Pilih-</option>
                <option value="no_mr" selected>Kode Permintaan</option>
                <option value="nama_pasien">Petugas</option>
              </select>
            </div>

            <label class="control-label col-md-1">Keyword</label>
            <div class="col-md-2" style="margin-left:-2%">
              <input type="text" class="form-control" name="keyword" id="keyword_form">
            </div>

            <label class="control-label col-md-1">Status</label>
            <div class="col-md-2">
                <select name="status_ranap" id="status_ranap" style="margin-left:-4%">
                  <option value="" selected>- Silahkan Pilih -</option>
                  <option value="masih dirawat">Masih dirawat</option>
                  <option value="sudah pulang">Sudah Pulang</option>
                  <!-- <option value="belum lunas">Sudah Lunas</option> -->
                </select>
            </div>

            <div class="col-md-2" style="margin-left:-4.8%">
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
      <hr class="separator">

      <div style="margin-top:-27px">

        <table id="dynamic-table" base-url="purchasing/persetujuan_pemb/App_persetujuan_pemb/get_data?flag=<?php echo $flag?>" url-detail="purchasing/persetujuan_pemb/App_persetujuan_pemb/get_detail/<?php echo $flag?>" class="table table-bordered table-hover">
          <thead>
          <tr>  
            <th width="30px" class="center"></th>
            <th width="40px" class="center"></th>
            <th width="40px"></th>
            <th width="50px">ID</th>
            <th>Kode Permohonan</th>
            <th>Tanggal Permohonan</th>
            <th>Petugas</th>
            <th>No Persetujuan</th>
            <th>Tanggal Persetujuan</th>
            <th>Status</th>
            <th>Keterangan</th>
            
          </tr>
          </thead>
          <tbody>
          </tbody>
        </table>

      </div>

    </form>
  </div><!-- /.col -->
</div><!-- /.row -->


<script src="<?php echo base_url().'assets/js/custom/als_datatable_with_detail.js'?>"></script>



