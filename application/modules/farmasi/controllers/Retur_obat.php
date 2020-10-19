<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Retur_obat extends MX_Controller {

    /*function constructor*/
    function __construct() 
    {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/Retur_obat');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Retur_obat_model', 'Retur_obat');
        // load library
        $this->load->library('Print_direct');
        $this->load->library('Print_escpos'); 
        $this->load->library('stok_barang');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() 
    { 
        /*define variable data*/
        $data = array(
            'title' => $this->title ,
            'breadcrumbs' => $this->breadcrumbs->show(),
        );
        /*load view index*/
        $this->load->view('Retur_obat/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Entry Resep '.strtolower($this->title).'', 'Retur_obat/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Retur_obat->get_by_id($id);
        $data['detail_obat'] = $this->Retur_obat->get_detail_resep_data($id)->result();
        // echo '<pre>';print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Retur_obat/form', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Retur_obat->get_datatables();
        if(isset($_GET['search']) AND $_GET['search']==TRUE){
            $this->find_data(); exit;
        }
        $data = array();
        $no = $_POST['start'];
        $atts = array('class' => 'btn btn-xs btn-warning','width'       => 900,'height'      => 500,'scrollbars'  => 'no','status'      => 'no','resizable'   => 'no','screenx'     => 1000,'screeny'     => 80,'window_name' => '_blank'
            );
        
        foreach ($list as $row_list) {
            $no++;
            // $flag = $this->regex->_genRegex($row_list->no_resep, 'RQXAZ');
            $flag = preg_replace('/[^A-Za-z\?!]/', '', $row_list->no_resep);

            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $status_lunas = ($row_list->kode_tc_trans_kasir == null) ? 0 : 1 ;
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'farmasi/Process_entry_resep/preview_entry/".$row_list->kode_trans_far."?flag=".$flag."&status_lunas=".$status_lunas."'".')">'.$row_list->kode_trans_far.'</a></div>';

            // $row[] = '<div class="center">'.$row_list->kode_trans_far.'</div>';
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_trans);
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = $row_list->dokter_pengirim;
            $row[] = $row_list->nama_pelayanan;
            if($row_list->kode_tc_trans_kasir == null) {
                $row[] = '<div class="center">
                            <label class="label lebel-xs label-success"> <i class="fa fa-check-circle"></i> Selesai</label>
                          </div>';
            }else{
                $row[] = '<div class="center"><label class="label lebel-xs label-primary"> <i class="fa fa-money"></i> Lunas</label></div>';
            }
            
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Retur_obat->count_all(),
                        "recordsFiltered" => $this->Retur_obat->count_filtered(),
                        "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        
        $this->load->library('form_validation');
        // form validation

        $this->form_validation->set_rules('kode_trans_far', 'Kode Transaksi', 'trim|required');

        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");        

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            //die(validation_errors());
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            /*execution*/
            $this->db->trans_begin();
            
            foreach ($_POST['retur'] as $key => $value) {
                # code...
                $dt_existing = $this->db->get_where('fr_tc_far_detail', array('kd_tr_resep' => $_POST['kd_tr_resep'][$key]) )->row();
                // print_r($dt_existing);die;
                if($value > 0){
                    $jumlah_his_retur = $dt_existing->jumlah_retur + $value;
                    $sisa = $dt_existing->jumlah_tebus - $value;
                    $harga_satuan = $dt_existing->harga_jual;                    
                    $subtotal_stl_retur = $harga_satuan * $sisa;
                    $data_farmasi_detail = array(
                        'jumlah_pesan' => $sisa,
                        'jumlah_tebus' => $sisa,
                        'jumlah_retur' => $jumlah_his_retur,
                        'sub_total' => $subtotal_stl_retur,
                        'total' => ($sisa > 0) ? $subtotal_stl_retur + $dt_existing->harga_r : 0,
                        'tgl_retur' => date('Y-m-d H:i:s'),
                        'retur_by' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'),
                        'updated_date' => date('Y-m-d H:i:s'),
                        'updated_by' => json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'))),
                    );
                    // print_r($data_farmasi_detail);die;
                    //  fr_tc_far_detail_log
                    $this->db->update('fr_tc_far_detail_log', $data_farmasi_detail, array('relation_id' => $_POST['kd_tr_resep'][$key], 'kode_trans_far' => $_POST['kode_trans_far']) );
                    /*save log*/
                    $this->logs->save('fr_tc_far_detail_log', $_POST['kd_tr_resep'][$key], 'update record on entry resep module', json_encode($data_farmasi_detail), 'relation_id');

                    //  fr_tc_far_detail
                    $harga_retur = $harga_satuan * $jumlah_his_retur;
                    $data_farmasi = array();
                    $data_farmasi['jumlah_pesan'] = $sisa;
                    $data_farmasi['jumlah_tebus'] = $sisa;
                    $data_farmasi['jumlah_retur'] = $jumlah_his_retur;
                    $data_farmasi['harga_r_retur'] = $harga_retur;
                    $data_farmasi['biaya_tebus'] = ($sisa > 0) ? $subtotal_stl_retur + $dt_existing->harga_r : 0;
                    $data_farmasi['status_retur'] = 1;
                    // print_r($data_farmasi);die;
                    $this->db->update('fr_tc_far_detail', $data_farmasi, array('kd_tr_resep' => $_POST['kd_tr_resep'][$key], 'kode_trans_far' => $_POST['kode_trans_far']) );
                    /*save log*/
                    $this->logs->save('fr_tc_far_detail', $_POST['kd_tr_resep'][$key], 'update record on entry resep module', json_encode($data_farmasi),'kd_tr_resep');
                    
                    if($dt_existing->id_tc_far_racikan == 0){
                        // retur stok ke farmasi
                        $this->stok_barang->stock_process($key, $value, '060101', 8 ," (Retur) Kode. ".$_POST['kode_trans_far']." ", 'restore');
                        $jumlah_di_retur = $value;
                        
                    }
                    // else{
                    //     // get detail racikan
                    //     $dt_racikan = $this->db->get_where('tc_far_racikan_detail', array('id_tc_far_racikan' => $key) )->result();
                    //     foreach ($dt_racikan as $key_racikan => $value_racikan) {
                    //         // retur stok ke farmasi
                    //         $this->stok_barang->stock_process($value_racikan->kode_brg, $value_racikan->jumlah, '060101', 8 ," (Retur Obat Racikan) Kode Transaksi : ".$_POST['kode_trans_far']." ", 'restore');
                    //     }
                    // }

                    // insert history retur
                    $kdhis = $this->master->get_max_number("fr_tc_far_his","kd_his");
                    $no_retur = $this->master->get_max_number("fr_tc_far_his","no_retur");
                    $data_his_retur["kd_his"] = $kdhis;
                    $data_his_retur["kd_tr_resep"] = $_POST['kd_tr_resep'][$key];
                    $data_his_retur["tgl_his_retur"] = date("Y-m-d H:i:s");
                    $data_his_retur["jumlah_retur_his"] = $value;
                    $data_his_retur["biaya_retur_his"] = $harga_retur;
                    $data_his_retur["no_retur"] = $no_retur;
                    $this->db->insert("fr_tc_far_his", $data_his_retur);

                    // update tc_trans_pelayanan by kd_tr_resep
                    if( $sisa > 0 ){
                        $bill_rs = ($sisa > 0) ? $harga_satuan * $sisa : 0;
                        $data_trans_pelayanan = array(
                            'jumlah' => $sisa,
                            'bill_rs' => $bill_rs,
                        );
                        // print_r($data_trans_pelayanan);die;
                        $this->db->where('kd_tr_resep', $_POST['kd_tr_resep'][$key])->update('tc_trans_pelayanan', $data_trans_pelayanan);
                    }else{
                        $this->db->where('kd_tr_resep', $_POST['kd_tr_resep'][$key])->delete('tc_trans_pelayanan');
                    }

                    $this->db->trans_commit();
                    
                }
            }
                        
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
            }
        
        }

    }

    public function process_copy_resep()
    {
        
        $this->load->library('form_validation');
        // form validation

        $this->form_validation->set_rules('content', 'Tulis Resep', 'trim|required');
        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");        

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            //die(validation_errors());
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            /*execution*/
            $this->db->trans_begin();
            
             /*update existing*/
             $data_farmasi['copy_resep_text'] = $_POST['content'];
             $data_farmasi['updated_date'] = date('Y-m-d H:i:s');
             $data_farmasi['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
             $this->db->update('fr_tc_far', $data_farmasi, array('kode_trans_far' => $_POST['kode_trans_far']) );
             /*save log*/
             $this->logs->save('fr_tc_far', $_POST['kode_trans_far'], 'update record on entry resep module', json_encode($data_farmasi),'kode_trans_far');

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'kode_trans_far' => $_POST['kode_trans_far']));
            }
        
        }

    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function get_kode_eticket(){
        /*string to array*/
        // $arr_id = explode(',', $_POST['ID']);
        $url_qry = http_build_query($_POST['ID']);
        echo json_encode(array('params' => $url_qry));
    }

    public function preview_etiket(){
        
        // get etiket data from query string
        $resep_log = $this->Retur_obat->get_etiket_data();
        // echo '<pre>';print_r($resep_log->result());die;
        $data = array();
        $data['result'] = $resep_log->result();
        $this->load->view('farmasi/Retur_obat/preview_etiket', $data);

    }

    public function preview_copy_resep($kode_trans_far){
        
        // get etiket data from query string
        $resep_log = $this->db->join('mt_master_pasien','mt_master_pasien.no_mr=fr_tc_far.no_mr','left')->get_where('fr_tc_far', array('kode_trans_far' => $kode_trans_far) )->row();
        // echo '<pre>';print_r($resep_log);die;
        $data = array();
        $data['result'] = $resep_log;
        $this->load->view('farmasi/Retur_obat/preview_copy_resep', $data);

    }

    public function print_tracer_obat($kode_trans_far)
    {   
        $resep_log = $this->Retur_obat->get_detail_resep_data($kode_trans_far);
        // echo '<pre>';print_r($resep_log->result());die;
        $this->print_escpos->print_resep_gudang($resep_log->result());
        $data = array();
        $data['result'] = $resep_log->result();
        $this->load->view('farmasi/Retur_obat/preview_tracer_obat', $data);
    }


}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
