<script src="<?php echo base_url().'assets/barcode-master/prototype/sample/prototype.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'assets/barcode-master/prototype/prototype-barcode.js'?>" type="text/javascript"></script>

<script type="text/javascript">

window.onload = generateBarcode;

  function generateBarcode(){

    <?php foreach( $result as $rows_dt ) :?>
    $("barcodeTarget<?php echo $rows_dt->kode_brg?>").update();
    var value = "<?php echo $rows_dt->kode_brg; ?>-<?php echo $rows_dt->relation_id; ?>";
    var btype = "code128";
    
    var settings = {
      output:"css",
      bgColor: "#FFFFFF",
      color: "#000000",
      barWidth: 1.25,
      barHeight: 40,
      moduleSize: 10,
      fontSize: 11,
      posX: 15,
      posY: 15,
      addQuietZone: false
    };
    $("barcodeTarget<?php echo $rows_dt->kode_brg?>").update().show().barcode(value, btype, settings);
  <?php endforeach;  ?>


  }
    
</script> 

<style>
.body{
  width: 265px;
  height: 210px;
  /* border: 1px solid grey; */
  font-weight: bold;
  font-size: 14px;
  font-family: Arial Narrow;
  color: black;
  text-align: left;
  background-color: white; 
}

table{
  font-weight: bold;
}

@media print{ #barPrint{
		display:none;
	}
}
</style>
<!-- <div id="barPrint" style="float: right">
  <button class="tular" onClick="window.close()">Tutup</button>
  <button class="tular" onClick="print()">Cetak</button>
</div> -->
<?php 
  if(count($result) == 0 )
  { 
    echo '<h2>Pemberitahuan</h2>- Tidak ada etiket ditemukan - '; exit; 
  } 

  foreach( $result as $rows ) : 
?>
<center>
<div class="body" style="page-break-after: always;">
  <table border=0 width="100%" style="border-bottom: 1px solid">
    <tr>
      <td><img src="<?php echo base_url().'assets/images/logo-black.png'?>" alt="" style="width: 50px"></td>
      <td align="center">INSTALASI FARMASI<br>
          <?php echo strtoupper(COMP_LONG); ?></td>
      <td><img src="<?php echo base_url().'assets/images/qrcode.png'?>" alt="" style="width: 40px"></td>
    </tr>
  </table> 
  <p style="margin-top: 1px">
    No. Mr : <?php echo $rows->no_mr; ?> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Tgl : <?php echo $this->tanggal->tgl_indo($rows->tgl_trans); ?><br>
    Nama Pasien : <?php echo $rows->nama_pasien; ?><br>
    Tgl Lahir : 23/11/2019 &nbsp; &nbsp; Umur : 30 Th
    <hr style="margin-top: -10px">
  </p>
  <p style="margin-top: -12px">
    <?php echo $rows->nama_brg;?><br>
    Aturan Minum : <?php echo $rows->dosis_obat; ?> x <?php echo $rows->dosis_per_hari; ?> (hari) &nbsp;  &nbsp; &nbsp; <?php echo $rows->jumlah_obat; ?> <?php echo $rows->satuan_obat; ?> <br>
    [<?php echo $rows->anjuran_pakai; ?>]<br>
    Keterangan : <?php echo $rows->catatan_lainnya; ?>
    <!-- <center style="margin-top:-12px">
      Antibiotik
      <div id="barcodeTarget<?php echo $rows->kode_brg?>" class="barcodeTarget" ></div>
    </center> -->
  </p>
</div>
</center>
<?php endforeach;?>

