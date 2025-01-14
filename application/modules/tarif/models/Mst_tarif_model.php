<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mst_tarif_model extends CI_Model {


	var $table = 'mt_master_tarif';
	var $column = array('nama_tarif');
	var $select = 'nama_tarif, mt_master_tarif.kode_bagian, mt_master_tarif.kode_tarif, kode_jenis_tindakan, mt_jenis_tindakan.jenis_tindakan, nama_bagian, revisi_ke, mt_master_tarif.is_active';

	var $order = array('nama_tarif' => 'ASC');

	public function __construct()
	{
		parent::__construct();
	}


	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('mt_jenis_tindakan', 'mt_jenis_tindakan.kode_jenis_tindakan=mt_master_tarif.jenis_tindakan', 'left');
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=mt_master_tarif.kode_bagian', 'left');
		$this->db->join('(select kode_tarif from mt_master_tarif_detail group by kode_tarif) as trf_detail', 'trf_detail.kode_tarif=mt_master_tarif.kode_tarif', 'left');

		if(isset($_GET['unit']) AND $_GET['unit'] != ''){
			$this->db->where('mt_master_tarif.kode_bagian', $_GET['unit']);
		}

		if(isset($_GET['checked_nama_tarif']) AND $_GET['checked_nama_tarif'] == 1){
			if(isset($_GET['nama_tarif']) AND $_GET['nama_tarif'] != ''){
				$this->db->like('mt_master_tarif.nama_tarif', $_GET['nama_tarif']);
			}
		}

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
			$this->db->where_in(''.$this->table.'.kode_tarif',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.kode_tarif',$id);
			$query = $this->db->get();
			// print_r($this->db->last_query());die;
			return $query->row();
		}
		
	}

	public function delete_by_id($id)
	{
		$this->db->where('mt_master_tarif_detail.kode_tarif', $id);
		$this->db->delete('mt_master_tarif_detail');

		$this->db->where_in(''.$this->table.'.kode_tarif', $id);
		$this->db->delete($this->table);

		return true;
	}

	public function delete_tarif_klas($id)
	{
		$this->db->where('mt_master_tarif_detail.kode_master_tarif_detail', $id);
		return $this->db->delete('mt_master_tarif_detail');
	}


	public function get_detail_by_kode_tarif($kode_tarif)
	{
		$this->db->select('kode_master_tarif_detail, mt_master_tarif_detail.kode_tarif, nama_tarif, nama_bagian, mt_master_tarif_detail.kode_klas, jenis_tindakan, nama_klas, CAST(bill_dr1 as INT) as bill_dr1, CAST(bill_dr2 as INT) as bill_dr2, CAST(bill_dr3 as INT) as bill_dr3, CAST(kamar_tindakan as INT) as kamar_tindakan, CAST(biaya_lain as INT) as biaya_lain, CAST(obat as INT) as obat, CAST(alkes as INT) as alkes, CAST(alat_rs as INT) as alat_rs, CAST(adm as INT) as adm, CAST(bhp as INT) as bhp, CAST(pendapatan_rs as INT) as pendapatan_rs, CAST(total as INT) as total, mt_master_tarif_detail.is_active, mt_master_tarif_detail.revisi_ke');
		$this->db->from('mt_master_tarif_detail');
		$this->db->join('mt_klas', 'mt_klas.kode_klas=mt_master_tarif_detail.kode_klas', 'left');
		$this->db->join('mt_master_tarif', 'mt_master_tarif.kode_tarif=mt_master_tarif_detail.kode_tarif', 'left');
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=mt_master_tarif.kode_bagian', 'left');
		$this->db->where('mt_master_tarif_detail.kode_tarif', $kode_tarif);
		$this->db->order_by('nama_klas', 'ASC');
		$result = $this->db->get()->result();
		foreach ($result as $key => $value) {
			$getData[$value->nama_klas][] = $value;
		}
		if(count($result) > 0){
			return array('nama_tarif' => $result[0]->nama_tarif, 'unit'=>$result[0]->nama_bagian, 'result' => $getData);
		}else{
			return [];
		}

		
	}

	public function get_detail_by_kode_tarif_detail($kode_master_tarif_detail)
	{
		$this->db->select('kode_master_tarif_detail, mt_master_tarif_detail.kode_tarif, nama_tarif, mt_master_tarif_detail.kode_klas, kode_bagian, jenis_tindakan, nama_klas, CAST(bill_dr1 as INT) as bill_dr1, CAST(bill_dr2 as INT) as bill_dr2, CAST(bill_dr3 as INT) as bill_dr3, CAST(kamar_tindakan as INT) as kamar_tindakan, CAST(biaya_lain as INT) as biaya_lain, CAST(obat as INT) as obat, CAST(alkes as INT) as alkes, CAST(alat_rs as INT) as alat_rs, CAST(adm as INT) as adm, CAST(bhp as INT) as bhp, CAST(pendapatan_rs as INT) as pendapatan_rs, CAST(total as INT) as total, mt_master_tarif_detail.is_active, mt_master_tarif_detail.revisi_ke');
		$this->db->from('mt_master_tarif_detail');
		$this->db->join('mt_klas', 'mt_klas.kode_klas=mt_master_tarif_detail.kode_klas', 'left');
		$this->db->join('mt_master_tarif', 'mt_master_tarif.kode_tarif=mt_master_tarif_detail.kode_tarif', 'left');
		$this->db->where('kode_master_tarif_detail', $kode_master_tarif_detail);
		$this->db->order_by('nama_tarif', 'ASC');

		return $this->db->get()->row();
		
	}

}
