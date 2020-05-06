<?php 
require_once('PHPPrint_escpos/autoload.php');
use Mike42\Escpos\Printer;
// use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
// use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;

class Print_escpos{

    //$pop = POP3::popBeforeSmtp('pop3.example.com', 110, 30, 'username', 'password', 1);

	public function print_direct_ori($params){

        /*$connector = new FilePrintConnector("php://stdout");*/
        $CI =& get_instance();
       
        /* Text */
        // $connector = new WindowsPrintConnector("smb://10.10.10.62/EPSON TM-U220 Tracer RM");
        // $connector = new NetworkPrintConnector("10.10.10.62", 9100);
        $connector = new FilePrintConnector("php://stdout");
        $printer = new Printer($connector);

        /* Initialize */
        //$printer -> initialize();

        $registrasi = $params['result']['registrasi'];
        $nama_pasien = $registrasi->nama_pasien;
        $nama_perusahaan = ($registrasi->nama_perusahaan)?$registrasi->nama_perusahaan:"UMUM";
        $klinik  = $registrasi->nama_bagian;
        $dokter  = $registrasi->nama_pegawai;
        $tanggal  = $CI->tanggal->formatDateTime($registrasi->tgl_jam_masuk);
        $no_reg = $registrasi->no_registrasi;
        $currentdate = $CI->tanggal->formatDateTime(date("Y-m-d h:i:s"));

        $nomor = $params['result']['no_antrian'];
        $no = isset($nomor->no_antrian)?$nomor->no_antrian:'-';

        $petugas_ = $params['result']['petugas'];
        $petugas = ($petugas_->fullname)?$petugas_->fullname:'-';

        $printer -> setJustification(Printer::JUSTIFY_CENTER);

        $printer -> setTextSize(2, 2);
        $printer -> text(COMP_LONG);

        $printer -> setTextSize(1, 1);
        $printer -> text(COMP_ADDRESS_SORT);
        // $printer -> text("Jakarta Selatan\n");

        //$printer -> setTextSize(3, 3);
        $printer -> setEmphasis(true);
        //$printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
        $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer -> text("TRACER\n");      
        $printer -> selectPrintMode();
        //$printer -> selectPrintMode();
        $printer -> setEmphasis(false);

        $printer -> setTextSize(1, 1);
        $printer -> text("---------------------------------\n");
        $printer -> setTextSize(2, 2);
        $printer -> text("No Antrian: ".$no."\n");  
        $printer -> setEmphasis(false);
        
        $printer -> setJustification(Printer::JUSTIFY_LEFT);

        $printer -> setTextSize(2,2);
        $printer -> text("No MR ");

        $printer -> setJustification(Printer::JUSTIFY_CENTER);

        $printer -> setEmphasis(true);
        //$printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
        $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer -> text(": ".$params['no_mr']."\n");
        //$printer -> selectPrintMode();
        $printer -> selectPrintMode();
        $printer -> setEmphasis(false);

        $printer -> setTextSize(1,1);
        $printer -> text("No Reg  : ".$no_reg."\n");

        $printer -> setTextSize(1,1);
        $printer -> text("Nama    : ".$nama_pasien."\n");

        $printer -> setTextSize(1,1);
        $printer -> text("Nasabah : ".$nama_perusahaan."\n");

        $printer -> setTextSize(1,1);
        $printer -> text("Bagian  : ".$klinik."\n");

        $printer -> setTextSize(1,1);
        $printer -> text("Dokter  : ".$dokter."\n");

        $printer -> setTextSize(1,1);
        $printer -> text("Tanggal : ".$tanggal."\n");

        $printer -> setTextSize(1,1);
        $printer -> text("Petugas : ".$petugas."\n");

        $printer -> setEmphasis(true);
        $printer -> setTextSize(2, 2);
        $printer -> text("No Antrian: ".$no."\n");  
        $printer -> setEmphasis(false);


        // $printer -> setJustification(Printer::JUSTIFY_RIGHT);
        // $printer -> setUnderline(Printer::UNDERLINE_DOUBLE );

        // $printer -> feed(1);

        // $printer -> setTextSize(2,2);
        // $printer -> text("No: ".$no."\n");

        $printer -> cut(Printer::CUT_FULL, 1);

        /* Pulse */
        $printer -> pulse();

        /* Always close the printer! On some PrintConnectors, no actual
        * data is sent until the printer is closed. */

        $printer -> close();
    

        return true;
       
    }

    public function print_direct_new_bug($params)
    {
        # code...
        $CI =& get_instance();
                     
        $p = printer_open("\\\\10.10.10.62\EPSON TM-U220 ReceiptE4");
       
        $var_margin_left = 30;
        printer_set_option($p, PRINTER_MODE, "RAW");
        
        printer_start_doc($p);
        printer_start_page($p);

        // define variable
        $registrasi = $params['result']['registrasi'];
        $nama_pasien = $registrasi->nama_pasien;
        $nama_perusahaan = ($registrasi->nama_perusahaan)?$registrasi->nama_perusahaan:"UMUM";
        $klinik  = $registrasi->nama_bagian;
        $dokter  = $registrasi->nama_pegawai;
        $tanggal  = $CI->tanggal->formatDateTime($registrasi->tgl_jam_masuk);
        $no_reg = $registrasi->no_registrasi;
        $currentdate = $CI->tanggal->formatDateTime(date("Y-m-d h:i:s"));

        $nomor = $params['result']['no_antrian'];
        $no = isset($nomor->no_antrian)?$nomor->no_antrian:'-';

        $petugas_ = $params['result']['petugas'];
        $petugas = ($petugas_->fullname)?$petugas_->fullname:'-';


        // header
        $font = printer_create_font("Arial", 50, 20, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, COMP_LONG ,170,0);

        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, COMP_ADDRESS_SORT, 110, 40);
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 3, "000000");
        printer_select_pen($p, $pen);
        printer_draw_line($p, 30, 70, 610, 70);

        // end header

        $font = printer_create_font("Arial", 45, 20, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, '' , 260, 80);

        $font = printer_create_font("Arial", 80, 40, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);

        printer_draw_text($p, "No Antrian: ".$no."\n" , 220, 120);

        printer_draw_text($p, "No MR", $var_margin_left, 200);

        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, ": ".$params['no_mr']."\n" , 110, 200);

        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "No Reg  : ".$no_reg."\n", $var_margin_left, 230);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);

        printer_select_font($p, $font);
        printer_draw_text($p, " : " , 110, 230);

        /*Tanggal*/
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Tanggal", $var_margin_left, 260);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : " , 110, 260);

        /*catatan*/
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Catatan ",$var_margin_left, 300);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " *Jika terlewat 5 nomor antrian, harap ambil antrian baru ! ", 110, 330);
        
        /*printer_draw_text($p, "Catatan ",$var_margin_left, 300);*/


        printer_draw_text($p,  "Tanggal cetak: " ,$var_margin_left, 400);
                        
        printer_delete_font($font);
        printer_delete_pen($pen);
        printer_end_page($p);
        printer_end_doc($p);

        printer_close($p);
       
    }

    public function print_testing()
    {
        # code...
        $CI =& get_instance();
                     
        $p = printer_open("\\\\10.10.10.62\EPSON TM-U220 Receipt");
       
        $var_margin_left = 30;
        printer_set_option($p, PRINTER_MODE, "RAW");
        
    
        printer_start_doc($p);
        printer_start_page($p);

        // header
        $font = printer_create_font("Arial", 50, 20, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, COMP_LONG ,170,0);

        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, COMP_ADDRESS_SORT, 110, 40);
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 3, "000000");
        printer_select_pen($p, $pen);
        printer_draw_line($p, 30, 70, 610, 70);

        // end header

        $font = printer_create_font("Arial", 45, 20, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, '' , 260, 80);

        $font = printer_create_font("Arial", 80, 40, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, '1' , 220, 120);

        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Klinik", $var_margin_left, 200);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : " , 110, 200);

        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Dokter", $var_margin_left, 230);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : " , 110, 230);

        /*Tanggal*/
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Tanggal", $var_margin_left, 260);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : " , 110, 260);

        /*catatan*/
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Catatan ",$var_margin_left, 300);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " *Jika terlewat 5 nomor antrian, harap ambil antrian baru ! ", 110, 330);
        
        /*printer_draw_text($p, "Catatan ",$var_margin_left, 300);*/


        printer_draw_text($p,  "Tanggal cetak: " ,$var_margin_left, 400);
                        
        printer_delete_font($font);
        printer_delete_pen($pen);
        printer_end_page($p);
        printer_end_doc($p);

        printer_close($p);
       
    }

    public function print_resep_gudang($params)
    {
        # code...
        $CI =& get_instance();
                     
        $p = printer_open("\\\\10.10.10.206\EPSON TM-T88V Receipt");
       
        $var_margin_left = 30;
        printer_set_option($p, PRINTER_MODE, "RAW");
        
    
        printer_start_doc($p);
        printer_start_page($p);

        // header
        $font = printer_create_font("Arial", 35, 13, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "INSTALASI FARMASI",140,0);

        $font = printer_create_font("Arial", 35, 13, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, strtoupper(COMP_LONG), 165, 30);

        // line
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 3, "000000");
        printer_select_pen($p, $pen);
        printer_draw_line($p, 30, 70, 610, 70);

        // end header

        // kode trans
        $font = printer_create_font("Arial", 45, 15, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "No. ".$params[0]->kode_trans_far , 170, 80);

        // no mr
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "No. Mr", $var_margin_left, 135);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ". $params[0]->no_mr, 160, 135);

        // nama pasien
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Nama Pasien", $var_margin_left, 165);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ". $params[0]->nama_pasien , 160, 165);

        // dokter pengirim
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Dokter", $var_margin_left, 195);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ". $params[0]->dokter_pengirim , 160, 195);

        // petugas
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Petugas", $var_margin_left, 225);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : " , 160, 225);

        // title
        $font = printer_create_font("Arial", 30, 10, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Pemesanan Obat : " , $var_margin_left, 255);

        $linespace = 300;
        foreach($params as $row_dt){
            // nama obat
            $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($p, $font);
            printer_draw_text($p, $row_dt->nama_brg, $var_margin_left , $linespace);
            $linespace += 30 ;
        }

        // keterangan
        $ln_keterangan = $linespace + 5;
        $font = printer_create_font("Arial", 30, 10, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Keterangan : " , $var_margin_left, $ln_keterangan);
        
        // tanggal cetak
        $line_tgl = $ln_keterangan + 250;
        $font = printer_create_font("Arial", 25, 8, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Tanggal transaksi : ".$CI->tanggal->formatDateTime($params[0]->tgl_trans) , $var_margin_left, $line_tgl);

                        
        printer_delete_font($font);
        printer_delete_pen($pen);
        printer_end_page($p);
        printer_end_doc($p);

        printer_close($p);
       
    }

    public function print_direct($params)
    {
        # code...
        $CI =& get_instance();
                     
        $p = printer_open("\\\\10.10.10.62\EPSON TM-U220 ReceiptE4");
        
        // define
        $font_familiy = "Calibri";
        $var_margin_left = 5;
        $var_margin_draw_text = 125;
        $font_medium_width = 26;
        $font_medium_height = 12;
        $line_space = 20;

        printer_set_option($p, PRINTER_MODE, "RAW");
            
        printer_start_doc($p);
        printer_start_page($p);

        $registrasi = $params['result']['registrasi'];
        $nama_pasien = $registrasi->nama_pasien;
        $nama_perusahaan = ($registrasi->nama_perusahaan)?$registrasi->nama_perusahaan:"UMUM";
        $klinik  = $registrasi->nama_bagian;
        $dokter  = $registrasi->nama_pegawai;
        $tanggal  = $CI->tanggal->formatDateTime($registrasi->tgl_jam_masuk);
        $no_reg = $registrasi->no_registrasi;
        $currentdate = $CI->tanggal->formatDateTime(date("Y-m-d h:i:s"));
        $nomor = $params['result']['no_antrian'];
        $no = isset($nomor->no_antrian)?$nomor->no_antrian:'-';
        $petugas_ = $params['result']['petugas'];
        $petugas = ($petugas_->fullname)?$petugas_->fullname:'-';

        // header
        $font = printer_create_font($font_familiy, 65, 25, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, $no, 180, -10);

        $font = printer_create_font($font_familiy, 30, 12, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, strtoupper(COMP_LONG), 125, 60);

        // address
        $font = printer_create_font($font_familiy, 25, 9, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, COMP_ADDRESS_SORT, 30,90);

        // title tracer
        $font = printer_create_font($font_familiy, 32, 12, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Tracer Pasien", 140, 110);

        // line
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 3, "000000");
        printer_select_pen($p, $pen);
        // printer_draw_line($p, padding-left, margin-bottom-ip, padding-right, margin-bottm-up);
        printer_draw_line($p, 0, 155, 900, 155);

        // end header

        // no mr
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "No. MR ", 100, 165);

        $font = printer_create_font($font_familiy, 45, 17, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, $params['no_mr'], 190, 160);

        // no registrasi
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "No. Reg", $var_margin_left, 210);
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ". $no_reg , $var_margin_draw_text, 210);

        // nama pasien
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Nama", $var_margin_left, 240);
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ". $nama_pasien , $var_margin_draw_text, 240);

        // Poli/Klinik
        // $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        // printer_select_font($p, $font);
        // printer_draw_text($p, "Poli/Klinik", $var_margin_left, 270);
        $font = printer_create_font($font_familiy, 28, 12, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, ucwords($klinik) , 5, 275);

        // dokter pengirim
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Dokter", $var_margin_left, 305);
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ". $dokter , $var_margin_draw_text, 305);

        // Penjamin
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Penjamin", $var_margin_left, 335);
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ". $nama_perusahaan , $var_margin_draw_text, 335);

        // petugas
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Petugas", $var_margin_left, 365);
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ". $petugas , $var_margin_draw_text, 365);

        // Tanggal
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Tanggal", $var_margin_left, 395);
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ". $tanggal , $var_margin_draw_text, 395);

        // footer
        $font = printer_create_font($font_familiy, 20, 8, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Generated by SIRS Setia Mitra - ".date('D, d/m/Y')."" , $var_margin_left, 430);
                        
        printer_delete_font($font);
        printer_delete_pen($pen);
        printer_end_page($p);
        printer_end_doc($p);

        printer_close($p);

        return true;
       
    }

    function title(Printer $printer, $text)
    {
        $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
        $printer -> text("\n" . $text);
        $printer -> selectPrintMode(); // Reset
    }
}

?>