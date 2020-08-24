<?php 

  if($_POST['submit']=='excel') {
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=".$flag.'_'.date('Ymd').".xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
  }

?>

<html>
<head>
  <title>Laporan Umum</title>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/blue.css"/>
</head>
<body>
  <div class="row">
    <div class="col-xs-12">

      <center><h4>Laporan Stok Awal, Penerimaan, Pemakaian Obat </h4></center>
      <b>Parameter :</b> <i><?php echo print_r($_POST);?></i>

      <table class="table">
        <thead>
          <tr style="text-align: center">
            <th rowspan="2">NO</th>
            <th rowspan="2" width="105">Kode Barang<br/></th>
            <th rowspan="2" width="95">Nama Barang</th>
            <th rowspan="2" width="304">HPP Satuan</th>
            <th width="304" colspan="2">Saldo Awal</th>
            <th width="304" colspan="2">Penerimaan/Pembelian</th>
            <th width="304" colspan="2">Penjualan BPJS</th>
            <th width="304" colspan="2">Penjualan Umum</th>
            <th width="304" colspan="2">Penggunaan Internal</th>
            <th width="304" colspan="2">Saldo Akhir</th>
          </tr>
          <tr>
            <th width="304">Quantity</th>
            <th width="304">Jumlah</th>
            <th width="304">Quantity</th>
            <th width="304">Jumlah</th>
            <th width="304">Quantity</th>
            <th width="304">Jumlah</th>
            <th width="304">Quantity</th>
            <th width="304">Jumlah</th>
            <th width="304">Quantity</th>
            <th width="304">Jumlah</th>
            <th width="304">Quantity</th>
            <th width="304">Jumlah</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $no = 0; 
            $jmlpenerimaan=0;
            $jmlpenjualanbpjs=0;
            $jmlpenjualanumum=0;
            $penjualanintrnal=0;
            $jmldistribusi=0;
            $jmlakhir=0;
            $jmlsaldoakhir=0;
          foreach($result['data'] as $row_data){
            $no++; 

            // Saldo Awal
            $qty_saldo_awal = isset($v_saldo[$row_data->kode_brg]) ? ($v_saldo[$row_data->kode_brg] > 0) ? $v_saldo[$row_data->kode_brg] : 0 : 0 ;
            $rp_saldo_awal = $qty_saldo_awal * $row_data->harga_beli;
            $arr_qty_saldo_awal[] = $qty_saldo_awal;
            $arr_rp_saldo_awal[] = $rp_saldo_awal;

            // penerimaan
            $qty_penerimaan = isset($v_penerimaan[$row_data->kode_brg])?$v_penerimaan[$row_data->kode_brg]:0;
            $rp_penerimaan = $qty_penerimaan * $row_data->harga_beli;
            $arr_qty_penerimaan[] = $qty_penerimaan;
            $arr_rp_penerimaan[] = $rp_penerimaan;

            // penjualan bpjs
            $qty_penjualan_bpjs = isset($v_penjualan_bpjs[$row_data->kode_brg]['jumlah'])?$v_penjualan_bpjs[$row_data->kode_brg]['jumlah']:0;
            $rp_penjualan_bpjs = isset($v_penjualan_bpjs[$row_data->kode_brg]['total'])?$v_penjualan_bpjs[$row_data->kode_brg]['total']:0;
            $arr_qty_penjualan_bpjs[] = $qty_penjualan_bpjs;
            $arr_rp_penjualan_bpjs[] = $rp_penjualan_bpjs;

            // penjualan umum
            $qty_penjualan = isset($v_penjualan_umum[$row_data->kode_brg]['jumlah'])?$v_penjualan_umum[$row_data->kode_brg]['jumlah']:0;
            $rp_penjualan = isset($v_penjualan_umum[$row_data->kode_brg]['total'])?$v_penjualan_umum[$row_data->kode_brg]['total']:0;
            $arr_qty_penjualan[] = $qty_penjualan;
            $arr_rp_penjualan[] = $rp_penjualan;

            // bmhp
            $qty_bmhp = isset($v_bmhp[$row_data->kode_brg])?$v_bmhp[$row_data->kode_brg]:0;
            $rp_bmhp = $qty_bmhp * $row_data->harga_beli;
            $arr_qty_bmhp[] = $qty_bmhp;
            $arr_rp_bmhp[] = $rp_bmhp;
            
            // summary
            $qty_saldo_akhir = ($qty_saldo_awal + $qty_penerimaan) - ($qty_penjualan_bpjs + $qty_penjualan + $qty_bmhp);
            $rp_saldo_akhir = ($rp_saldo_awal + $rp_penerimaan ) - ($rp_penjualan_bpjs + $rp_penjualan + $rp_bmhp);
            $arr_qty_saldo_akhir[] = $qty_saldo_akhir;
            $arr_rp_saldo_akhir[] = $rp_saldo_akhir;

            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
                echo '<td>'.$row_data->kode_brg.'</td>';
                echo '<td>'.$row_data->nama_brg.'</td>';
                echo '<td>'.$row_data->harga_beli.'</td>';
                // saldo awal
                echo '<td>'.$qty_saldo_awal.'</td>';
                echo '<td>'.$rp_saldo_awal.'</td>';
                // penerimaan
                echo '<td>'.$qty_penerimaan.'</td>';
                echo '<td>'.$rp_penerimaan.'</td>';
                // penjualan bpjs
                echo '<td>'.$qty_penjualan_bpjs.'</td>';
                echo '<td>'.$rp_penjualan_bpjs.'</td>';
                // penjualan umum
                echo '<td>'.$qty_penjualan.'</td>';
                echo '<td>'.$rp_penjualan.'</td>';
                // bmhp
                echo '<td>'.$qty_bmhp.'</td>';
                echo '<td>'.$rp_bmhp.'</td>';

                echo '<td>'.$qty_saldo_akhir.'</td>';
                echo '<td>'.$rp_saldo_akhir.'</td>';
              ?>
            </tr>
          <?php } ?>
            <tr>
              <td colspan="4"><b>TOTAL </b></td>
              <td></td>
              <td><?php echo array_sum($arr_rp_saldo_awal);?></td> 
              <td></td>
              <td><?php echo array_sum($arr_rp_penerimaan);?></td> 
              <td></td>
              <td><?php echo array_sum($arr_rp_penjualan_bpjs);?></td> 
              <td></td>
              <td><?php echo array_sum($arr_rp_penjualan);?></td> 
              <td></td>
              <td><?php echo array_sum($arr_rp_bmhp);?></td> 
              <td></td>
              <td><?php echo array_sum($arr_rp_saldo_akhir);?></td> 
            </tr>
        </tbody>
      </table>
      <br>
<table border="0" width="100%">
  <tr>
  <td colspan="2" valign="bottom" style="padding-top:25px" align="right"> Jakarta, ..........................</td>
    <tr><td valign="bottom" style="padding-top:25px" align="right">
    <b>Mengetahui<br><br><br><br><br><br>_________________________
  </td>
  <td valign="bottom" style="padding-top:25px" align="right">
    <b>Petugas<br><br><br><br><br><br>_________________________
  </td>
</tr>
</table>
    </div>
  </div>
</body>
</html>






