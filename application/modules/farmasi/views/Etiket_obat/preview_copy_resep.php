<script src="<?php echo base_url().'assets/barcode-master/prototype/sample/prototype.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'assets/barcode-master/prototype/prototype-barcode.js'?>" type="text/javascript"></script>

<script type="text/javascript">

window.onload = generateBarcode;

  function generateBarcode(){

    $("barcodeTarget<?php echo $result->kode_trans_far?>").update();
    var value = "<?php echo $result->kode_trans_far; ?>";
    var btype = "code128";
    
    var settings = {
      output:"css",
      bgColor: "#FFFFFF",
      color: "#000000",
      barWidth: 2,
      barHeight: 40,
      moduleSize: 20,
      fontSize: 11,
      posX: 15,
      posY: 15,
      addQuietZone: false
    };
    $("barcodeTarget<?php echo $result->kode_trans_far?>").update().show().barcode(value, btype, settings);
 


  }
    
</script> 

<style>
.body{
  width: 265px;
  height: 210px;
  /* border: 1px solid grey; */
  /* font-weight: bold; */
  font-family: Arial, Helvetica, sans-serif;
  color: black;
  text-align: left;
  background-color: white; 
  margin-left: 30px;
}

.monotype_style{
    font-family : Monotype Corsiva, Times, Serif !important;
    padding-right: 10px
  }

@media print{ #barPrint{
		display:none;
	}
}

br {
    display: block;
    margin-bottom: 10px;
}
</style>
  
  <div style="width: 390px; padding-top:143px">
    <div style="padding-left:70px;padding-bottom:7px" class="monotype_style">
      <?php echo ucwords(strtolower($result->nama_pasien)); ?>
    </div>
    <div style="padding-bottom:7px">
      <span style="padding-left:110px;" class="monotype_style"><?php echo $result->kode_trans_far; ?></span>
      <span style="padding-left:100px;" class="monotype_style"><?php echo $this->tanggal->formatDatedmY($result->tgl_trans); ?></span>
    </div>
    <div style="padding-left:100px;" class="monotype_style"><?php echo $result->dokter_pengirim; ?></div>
  </div>
  <br>
  <div style="min-height: 500px; margin-top: 30px; margin-left: 30px">
    <?php echo $result->copy_resep_text; ?>
  </div>
  <!-- <p style="margin-top: -12px">
    <table style="font-size: 11px">
      <tr>
        <td>Pro</td>
        <td>: <?php echo ucwords(strtolower($result->nama_pasien)); ?></td>
      </tr>
      <tr>
        <td>Umur</td>
        <td>: <?php echo $this->tanggal->AgeWithYearMonthDay($result->tgl_lhr)?></td>
      </tr>
      <tr>
        <td>Alamat</td>
        <td>: <?php echo ucwords(strtolower($result->almt_ttp_pasien)); ?></td>
      </tr>
    </table>
  </p> -->
  <p style="font-weight: normal; font-size: 10px; font-style: italic; margin-left: 30px; text-align: center">
    a copy of this recipe is generated by the system.
  </p>
</div>
</center>

