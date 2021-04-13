<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adm_pembayaran_riwayat extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'adm_pasien/pembayaran_hutang/Adm_pembayaran_riwayat');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('adm_pasien/pembayaran_hutang/Adm_pembayaran_riwayat_model', 'Adm_pembayaran_riwayat');
        $this->load->model('billing/Billing_model', 'Billing');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        //echo '<pre>';print_r($this->session->all_userdata());
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
        );
        /*load view index*/
        $this->load->view('pembayaran_hutang/Adm_pembayaran_riwayat/index', $data);
    }

    public function form($id='')
    {

        $qry_url = http_build_query($_GET);
        /*if id is not null then will show form edit*/
            /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Pembayaran Hutang Supplier '.strtolower($this->title).'', 'Adm_pembayaran_riwayat/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id.'?'.$qry_url);
        /*get value by id*/
        $data['value'] = $this->Adm_pembayaran_riwayat->get_by_id($id); 
        $data['detail_faktur'] = $this->Adm_pembayaran_riwayat->get_log_data($id);
        /*initialize flag for form*/
        $data['flag'] = "update";
    
        /*title header*/
        $data['qry_url'] = $qry_url;
        $data['title'] = $this->title;
        // echo '<pre>'; print_r($data);die;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('pembayaran_hutang/Adm_pembayaran_riwayat/form', $data);
    }


    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }
    
    public function get_data()
    {
        /*get data from model*/
        $list = $this->Adm_pembayaran_riwayat->get_datatables();
        $qry_url = ($_GET) ? http_build_query($_GET) : '' ;
        // print_r($list);die;
        $data = array();
        $arr_total = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $arr_total[] = $row_list->total_harga;
            $row[] = '<div class="center"></div>';
            $row[] = $row_list->id_tc_hutang_supplier_inv;
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $row_list->no_kuitansi_pembayaran;
            $row[] = '<div class="center">'.$this->tanggal->formatDateDmy($row_list->tgl_pembayaran).'</div>';
            $row[] = $row_list->no_terima_faktur;
            $row[] = $row_list->namasupplier;
            $row[] = $row_list->penerima_pembayaran;
            $row[] = '<div class="pull-right">'.number_format($row_list->total_harga).'</div>';
            $row[] = ($row_list->flag_bayar == 1) ? '<div class="center green"><b>Lunas</b></div>' : '<div class="center orange"><b>Dalam proses pengajuan</b></div>';
            $row[] = '<div class="center"><a href="#" onclick="PopupCenter('."'purchasing/tukar_faktur/Tf_riwayat_tukar_faktur/preview_ttf?ID=".$row_list->id_tc_hutang_supplier_inv."'".','."'BUKTI TANDA TERIMA FAKTUR'".',900,650);"><i class="fa fa-print green bigger-150"></i></a></div>';
            $row[] = '<div class="center"><a href="#" onclick="PopupCenter('."'adm_pasien/pembayaran_hutang/Adm_pembayaran_riwayat/preview_bp?ID=".$row_list->id_tc_hutang_supplier_inv."'".','."'BUKTI TANDA TERIMA FAKTUR'".',900,650);"><i class="fa fa-print red bigger-150"></i></a></div>';
            $row[] = '<div class="center"><a href="#" onclick="PopupCenter('."'adm_pasien/pembayaran_hutang/Adm_pembayaran_riwayat/preview_kuitansi?nm=".$row_list->namasupplier."&inv=".$row_list->no_terima_faktur."&tgl=".$row_list->tgl_faktur."&jml=".$row_list->total_harga."'".','."'Preview Kuitansi'".',900,650);"><i class="fa fa-print blue bigger-150"></i></a></div>';
            $data[] = $row;
              
        }
        
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Adm_pembayaran_riwayat->count_all(),
                        "recordsFiltered" => $this->Adm_pembayaran_riwayat->count_filtered(),
                        "data" => $data,
                        "total_billing" => array_sum($arr_total),
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_log_data($id_tc_hutang_supplier_inv)
    {
        /*get data from model*/
        $list = $this->Adm_pembayaran_riwayat->get_log_data($id_tc_hutang_supplier_inv);
        // echo '<pre>';print_r($list);die;
        $data = array(
            'id_tc_hutang_supplier_inv' => $id_tc_hutang_supplier_inv,
            'result' => $list,
        ); 
        $html = $this->load->view('pembayaran_hutang/Adm_pembayaran_riwayat/detail_table', $data, true);

        echo json_encode(array('html' => $html, 'data' => $list));
    }

    public function get_invoice_detail($id_tc_tagih)
    {
        /*get data from model*/
        $list = $this->Adm_pembayaran_riwayat->get_invoice_detail($id_tc_tagih);

        $no_invoice = $list[0]->no_invoice_tagih;
        echo json_encode(array('data' => $list, 'no_invoice' => $no_invoice));
    }

    public function get_penerimaan_detail($id_penerimaan)
    {
        /*get data from model*/
        $list = $this->Adm_pembayaran_riwayat->get_penerimaan_detail($id_penerimaan, $_GET['flag']);
        $no = 0;
        foreach ($list as $key => $value) {
            $no++;
            $arr_subtotal[] = $value->dpp;
            $getData[] = array(
                'count_num' => $no,
                'nama_brg' => $value->nama_brg,
                'jml_kirim' => $value->jumlah_kirim_decimal,
                'satuan' => $value->satuan_besar,
                'harga_satuan' => $value->harga_net,
                'subtotal' => $value->dpp,
            );
        }
        echo json_encode(array('data' => $getData, 'kode_penerimaan' => $list[0]->kode_penerimaan, 'tgl_penerimaan' => $this->tanggal->formatDateDmy($list[0]->tgl_penerimaan), 'total' => array_sum($arr_subtotal)));
    }

    public function preview_bp(){
        $data = array();
        $list = $this->Adm_pembayaran_riwayat->get_by_id($_GET['ID']);
        $data['value'] = $list;
        // echo '<pre>'; print_r($data);die;
        $this->load->view('pembayaran_hutang/Adm_pembayaran_riwayat/preview_bp', $data);
    }

    public function preview_kuitansi(){
 
        
        $data = array(
            'inv' => $_GET['inv'],
            'name' => $_GET['nm'],
            'tgl' => $_GET['tgl'],
            'total' => $_GET['jml'],
        );
        $this->load->view('pembayaran_hutang/Adm_pembayaran_riwayat/preview_kuitansi', $data, false);
         
    }

}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */