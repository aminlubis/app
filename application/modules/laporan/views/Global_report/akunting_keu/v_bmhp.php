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

      <center><h4><?php echo $title?></h4></center>
      <b>Parameter :</b> <i><?php echo print_r($_POST);?></i>

      <table class="greyGridTable">
        <thead>
          <tr>
            <th rowspan="2">NO</th>
            <th rowspan="2" width="105">Kode Barang<br/></th>
            <th rowspan="2" width="95">Nama Barang</th>
            <th rowspan="2" width="304">HPP Satuan</th>
            <th rowspan="2" width="304">Harga Jual Satuan</th>
            <th width="304" colspan="2">Saldo Awal</th>
            <th width="304" colspan="2">Penerimaan/Pembelian</th>
            <th width="304" colspan="2">Penjualan ke Pasien BPJS</th>
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
          <?php $no = 0; 
          foreach($result['data'] as $row_data){
            // $saldopenerimaan=$row_data->jumlah_kirim * $row_data->harga_beli;
            $no++; 
            // data bpjs search arrray
            $key_saldo = $this->master->searchArray($row_data->kode_brg, 'kode_brg', $v_saldo);
            if($row_data->kode_brg == $v_saldo[$key_saldo]['kode_brg']){
              $qtys = isset($v_saldo[$key_saldo])?$v_saldo[$key_saldo]['stok_akhir']:0;
              // $saldoa = isset($v_saldo[$key_saldo])?$v_saldo[$key_saldo]['harga_jual']:0;
            }else{
              $qtys = 0;
              // $saldoa = 0;
            }
            $saldoawal=$qtys * $row_data->hargajual;
            //penerimaan
            $key_penerimaan = $this->master->searchArray($row_data->kode_brg, 'kode_brg', $v_penerimaan);
            if($row_data->kode_brg == $v_penerimaan[$key_penerimaan]['kode_brg']){
              $qty_p = isset($v_penerimaan[$key_penerimaan])?$v_penerimaan[$key_penerimaan]['jumlah_penerimaan']:0;
              // $hjual = isset($v_penerimaan[$key_penerimaan])?$v_penerimaan[$key_penerimaan]['hargajual']:0;
            }else{
              $qty_p = 0;
              // $hjual = 0;
            }
            $saldopenerimaan=$qty_p * $row_data->harga_beli;

            //bpjs
             $key_bpjs = $this->master->searchArray($row_data->kode_brg, 'kode_barang', $dt_pjl_bpjs);
            if($row_data->kode_brg == $dt_pjl_bpjs[$key_bpjs]['kode_barang']){
              $qty = isset($dt_pjl_bpjs[$key_bpjs])?$dt_pjl_bpjs[$key_bpjs]['jumlah']:0;
              $hbpjs = isset($dt_pjl_bpjs[$key_bpjs])?$dt_pjl_bpjs[$key_bpjs]['bill_rs']:0;
            }else{
              $qty = 0;
              $hbpjs = 0;
            }
            $j_bpjs=$qty * $hbpjs;

            //umum
            $key_umum = $this->master->searchArray($row_data->kode_brg, 'kode_barang', $dt_pjl_umum);
            if($row_data->kode_brg == $dt_pjl_umum[$key_umum]['kode_barang']){
              $qty_u = isset($dt_pjl_umum[$key_umum])?$dt_pjl_umum[$key_umum]['jumlah']:0;
              $humum = isset($dt_pjl_umum[$key_umum])?$dt_pjl_umum[$key_umum]['bill_rs']:0;
            }else{
              $qty_u = 0;
              $humum = 0;
            }
            $j_umum=$qty_u * $humum;

            //internal
            $key_internal = $this->master->searchArray($row_data->kode_brg, 'kode_barang', $dt_pjl_internal);
            if($row_data->kode_brg == $dt_pjl_internal[$key_internal]['kode_barang']){
              $qty_i = isset($dt_pjl_internal[$key_internal])?$dt_pjl_internal[$key_internal]['jumlah']:0;
              $hinternal = isset($dt_pjl_internal[$key_internal])?$dt_pjl_internal[$key_internal]['bill_rs']:0;
            }else{
              $qty_i = 0;
              $hinternal = 0;
            }
            $j_internal=$qty_i * $hinternal;
            $saldo_akhir= $qtys + $qty_p - $qty - $qty_u - $qty_i;
            $saldoakhir=$saldo_akhir * $row_data->hargajual;

            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
                echo '<td>'.$row_data->kode_brg.'</td>';
                echo '<td>'.$row_data->nama_brg.'</td>';
                echo '<td>'.number_format($row_data->harga_beli).'</td>';
                echo '<td>'.number_format($row_data->hargajual).'</td>';
                echo '<td>'.$qtys.'</td>';
                echo '<td>'.number_format($saldoawal).'</td>';
                echo '<td>'.$qty_p.'</td>';
                echo '<td>'.number_format($saldopenerimaan).'</td>';
                echo '<td>'.number_format($qty).'</td>';
                echo '<td>'.number_format($j_bpjs).'</td>';
                echo '<td>'.number_format($qty_u).'</td>';
                echo '<td>'.number_format($j_umum).'</td>';
                echo '<td>'.number_format($qty_i).'</td>';
                echo '<td>'.number_format($j_internal).'</td>';
                echo '<td>'.number_format($saldo_akhir).'</td>';
                echo '<td>'.number_format($saldoakhir).'</td>';
              ?>
            </tr>
          <?php } ?>
        </tbody>
      </table>

    </div>
  </div>
</body>
</html>





