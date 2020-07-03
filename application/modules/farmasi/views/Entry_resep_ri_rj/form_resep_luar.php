<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script>

  $('#inputKeyNamaPasien').typeahead({
          source: function (query, result) {
              $.ajax({
                  url: "templates/references/getNamaPasien",
                  data: { keyword:query},            
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
            var label_item=item.split(':')[1];
            console.log(val_item);
            $('#no_mr').val(val_item);
            $('#nama_pasien').val(label_item);
            $('#inputKeyNamaPasien').val(label_item);
            $('#inputKeyObat').focus();

          }
  });

  $('#inputKeyDokterPengirim').typeahead({
          source: function (query, result) {
              $.ajax({
                  url: "templates/references/getAllDokter",
                  data: { keyword:query},            
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
            var label_item=item.split(':')[1];
            console.log(val_item);
            $('#kode_dokter').val(val_item);
            $('#dokter_pengirim').val(label_item);
            $('#inputKeyDokterPengirim').val(label_item);
            $('#inputKeyObat').focus();

          }
  });

  function set_to_default_name(from_id, to_id){
    $('#'+to_id).val($('#'+from_id).val());
  }
</script>

<h3 class="row header smaller lighter orange">
  <span class="col-sm-8">
    <i class="ace-icon fa fa-bell"></i>
    <?php echo strtoupper($title_form)?>
  </span><!-- /.col -->
</h3>
   
<div class="form-group">
  <label class="control-label col-sm-1">Pasien (a.n)</label>
  <div class="col-md-3">
    <input type="text" class="form-control" name="nama_pasien_keyword" id="inputKeyNamaPasien" value="<?php echo isset($value->nama_pasien)?$value->nama_pasien:''?>" onchange="set_to_default_name('inputKeyNamaPasien', 'nama_pasien')">
  </div>
  <label class="control-label col-sm-1">No.Telp/ HP</label>
  <div class="col-md-2">
    <input type="text" class="form-control" name="no_telp" id="no_telp_pasien" value="<?php echo isset($value->telpon_pasien)?$value->telpon_pasien:''?>">
  </div>
</div>
<div class="form-group">
  <label class="control-label col-sm-1">Alamat</label>
  <div class="col-md-3">
    <textarea name="alamat_pasien" id="alamat_pasien" style="height: 50px !important; margin-bottom: 3px" class="form-control"><?php echo isset($value->alamat_pasien)?$value->alamat_pasien:''?></textarea>
  </div>
</div>

<div class="form-group">
  <label class="control-label col-sm-1">Dokter</label>
  <div class="col-md-3">
    <input type="text" class="form-control" id="inputKeyDokterPengirim" name="dokter_pengirim_keyword" value="<?php echo isset($value->dokter_pengirim)?$value->dokter_pengirim:''?>" onchange="set_to_default_name('inputKeyDokterPengirim', 'dokter_pengirim')">
  </div>
  <button type="submit" id="btn_simpan_header" class="btn btn-primary btn-xs" name="submit" value="header">
        <span class="ace-icon fa fa-save icon-on-right bigger-110"></span>
        Simpan
  </button>
</div>

<hr>