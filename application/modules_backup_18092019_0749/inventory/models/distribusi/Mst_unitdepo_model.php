<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mst_unitdepo_model extends CI_Model {

	var $table = 'mt_depo_stok_nm_v';
	var $column = array('mst_unitdepo.nama_kategori','mst_unitdepo.nama_golongan','mst_unitdepo.nama_sub_golongan','mst_unitdepo.kode_kategori','mst_unitdepo.kode_golongan',
		'mst_unitdepo.kode_sub_golongan','mst_unitdepo.kode_brg','mst_unitdepo.nama_brg','mst_unitdepo.kode_bagian','mst_unitdepo.stok_minimum','mst_unitdepo.stok_maksimum', 
		'mst_unitdepo.jml_sat_kcl','mst_unitdepo.satuan_kecil');
	var $select = 'nama_kategori,nama_golongan,nama_sub_golongan,kode_kategori,kode_golongan,kode_sub_golongan,kode_brg,nama_brg,kode_bagian, stok_minimum, stok_maksimum, jml_sat_kcl, satuan_kecil';
	var $where = 'kode_bagian<>070101';
	var $order = array('nama_kategori,nama_golongan,nama_sub_golongan, nama_brg' => 'ASC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->where($this->where);
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
		$this->db->select($this->select);
		$this->db->from($this->table);
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.education_id',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.education_id',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where_in(''.$this->table.'.education_id', $id);
		return $this->db->delete($this->table);
	}


}
