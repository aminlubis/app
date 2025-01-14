<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tracer_model extends CI_Model {

	var $table = 'tc_kunjungan';
	var $column = array('tc_registrasi.no_registrasi','tc_registrasi.no_mr','mt_master_pasien.nama_pasien','mt_perusahaan.nama_perusahaan','tc_registrasi.tgl_jam_masuk','mt_bagian.nama_bagian', 'mt_karyawan.nama_pegawai');
	var $select = 'tc_registrasi.no_registrasi,tc_registrasi.no_mr,mt_master_pasien.nama_pasien,mt_perusahaan.nama_perusahaan,tc_registrasi.tgl_jam_masuk,mt_bagian.nama_bagian, mt_karyawan.nama_pegawai, print_tracer, stat_pasien, tmp_user.fullname';

	var $order = array('tc_registrasi.tgl_jam_masuk' => 'DESC');

	public function __construct()
	{
		parent::__construct();
	}

	public function save($table, $data)
	{
		/*insert tc_registrasi*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();;
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('tc_registrasi',''.$this->table.'.no_registrasi=tc_registrasi.no_registrasi','left');
		$this->db->join('mt_master_pasien',''.$this->table.'.no_mr=mt_master_pasien.no_mr','left');
		$this->db->join('mt_perusahaan','tc_registrasi.kode_perusahaan=mt_perusahaan.kode_perusahaan','left');
		$this->db->join('mt_bagian',''.$this->table.'.kode_bagian_tujuan=mt_bagian.kode_bagian','left');
		$this->db->join('mt_karyawan',''.$this->table.'.kode_dokter=mt_karyawan.kode_dokter','left');
		$this->db->join('tmp_user','tc_registrasi.no_induk=tmp_user.user_id','left');
		
		/*if isset parameter*/
		if( $_GET ) {
			
			if(isset($_GET['search_by']) AND isset($_GET['keyword'])){
				if( $_GET['search_by'] == 'no_mr' ){
					if($_GET['keyword'] != ''){
						$this->db->where('mt_master_pasien.'.$_GET['search_by'].'', $_GET['keyword']);
					}
				}else{
					$this->db->like('mt_master_pasien.'.$_GET['search_by'].'', $_GET['keyword']);
				}
			}

			if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
				$this->db->where("CAST(tc_registrasi.tgl_jam_masuk as DATE) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");	
				
			}

	        if (isset($_GET['bagian']) AND $_GET['bagian'] != 0) {
	            $this->db->where('tc_registrasi.kode_bagian_masuk', $_GET['bagian']);	
	        }

	        if (isset($_GET['dokterHidden']) AND $_GET['dokterHidden'] != 0) {
	            $this->db->where('tc_registrasi.kode_dokter', $_GET['dokterHidden']);	
	        }
		
		}else{
			$this->db->where(array('YEAR(tc_kunjungan.tgl_masuk)' => date('Y'), 'MONTH(tc_registrasi.tgl_jam_masuk)' => date('m'), 'DAY(tc_registrasi.tgl_jam_masuk)' => date('d')));
		} 
        /*end parameter*/
		/*check level user*/
		//$this->authuser->filtering_data_by_level_user($this->table, $this->session->userdata('user')->user_id);

	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();

		$i = 0;
	
		foreach ($this->column as $item) 
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
			$column[$i] = $item;
			$i++;
		}
		
		if(isset($_POST['order']))
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	
	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->_main_query();
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.no_registrasi',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.no_registrasi',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
	}


	public function update($table, $data, $where)
	{
		$this->db->update($table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$get_data = $this->get_by_id($id);
		$this->db->where_in(''.$this->table.'.no_registrasi', $id);
		return $this->db->update($this->table, array('is_deleted' => 'Y', 'is_active' => 'N'));
	}

	public function save_pm($table, $data)
	{
		/*insert tc_registrasi*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();;
	}


}
