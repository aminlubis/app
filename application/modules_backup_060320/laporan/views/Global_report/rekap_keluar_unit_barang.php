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
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
</head>
<body>
  <div class="row">
    <div class="col-xs-12">
      <center><h4><?php echo $title?></h4></center>
      <br>
      <br>
      <table class="table">
        <thead>
          <tr>
            <th>NO</th>
            <th width="100">Nama Unit/Bagian</th>
            <th width="100">Total Harga Beli (Rp. )</th>    
          </tr>
        </thead>
        <tbody>
          <?php $no = 0; 
          foreach($result['data'] as $row_data){
            $no++; 
            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
                  echo '<td>'.$row_data->nama_bagian.'</td>';
                  echo '<td>'.number_format($row_data->hargabeli).'</td>';
              ?>
            </tr>
          <?php 
        // endforeach; 
      }?>
    </table>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






