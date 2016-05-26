<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_api extends CI_Model {

	public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  public function insert($table, $data)
	{
		$this->db->insert($table, $data);
		return $this->db->insert_id() ? TRUE : false;
	}

	public function get($table, $kolom, $order_type)
	{
		$this->db->order_by($kolom, $order_type);
		$data = $this->db->get($table);
		return $data->num_rows() > 0 ? $data->result() : false;
	}

	public function get_list($table)
	{
		$data = $this->db->get($table);
		return $data->num_rows() > 0 ? $data->result() : false;
	}

	public function get_list_order($table, $kolom, $order_type, $num, $offset)
	{
		$this->db->order_by($kolom, $order_type);
		$data = $this->db->get($table,$num, $offset);
		return $data->num_rows() > 0 ? $data->result() : false;
	}

	public function select($table,$select, $where, $value){
		$this->db->select($select);
		$this->db->where($where, $value);
		$data = $this->db->get($table);
		return $data->first_row();
	}

	public function get_list_join($table1, $table2, $where1, $where2, $like, $like_value, $kolom, $order_type, $num, $offset)
	{
		//$this->db->from($table1);
		$this->db->join($table2, "$table2.$where2 = $table1.$where1");
		$this->db->order_by($kolom, $order_type);
		$this->db->like($like, $like_value);
		$data = $this->db->get($table1, $num, $offset);
		return $data->num_rows() > 0 ? $data->result() : false;
	}

	public function get_keadaan($table, $params)
	{
		$query = $this->db->get_where($table, $params);
		return $query;
	}

	public function get_join_dashboard($id_user)
	{
		$this->db->select('tb_tim.id_project, judul, deskripsi, estimasi, tgl_mulai, tgl_selesai');
		$this->db->from('tb_project');
		$this->db->join('tb_tim', 'tb_tim.id_project = tb_project.id_project');
		$this->db->where('tb_tim.id_user', $id_user);
		$query = $this->db->get();
		return $query;
	}

	public function get_join_message($ambil_id_cr)
	{
		$this->db->select('message, waktu, username');
        $this->db->from('tb_message');
        $this->db->join('tb_user', 'tb_user.id_user = tb_message.id_user');
        $this->db->where('id_cr', $ambil_id_cr);
        $this->db->order_by('id_message', 'asc');
        $query = $this->db->get();
        return $query;
	}

	public function get_join_tim($id_project)
	{
		$this->db->select('username, jabatan');
        $this->db->from('tb_tim');
        $this->db->join('tb_user', 'tb_user.id_user = tb_tim.id_user');
        $this->db->where('id_project', $id_project);
        $this->db->order_by('jabatan', 'asc');
        $query = $this->db->get();
        return $query;
	}

	public function get_join_notif()
	{
		$this->db->select('id_notif, judul, waktu, status, tb_notif.id_user');
        $this->db->from('tb_notif');
        $this->db->join('tb_project', 'tb_notif.id_project = tb_project.id_project');
        $this->db->where('status','0');
        $this->db->order_by('id_notif', 'desc');
        $query = $this->db->get();
        return $query;
	}

	public function update($table, $where, $id, $data)
	{
		$this->db->where($where, $id);
		$this->db->update($table, $data);
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	public function update_notif($id_user, $params)
	{
		$this->db->where('id_user', $id_user);
		$this->db->update('tb_notif', $params);
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	public function delete($table, $where, $id)
	{
		$del = $this->db->delete($table, array($where => $id ));
		return $del ? TRUE : FALSE;
	}

	public function search($table, $order, $order_type, $like, $num, $cari, $offset)
	{
    	$this->db->like($like,$cari);
   		$this->db->order_by($order,$order_type);
   		$data= $this->db->get($table, $num, $offset);
   		return $data->num_rows() > 0 ? $data->result() : FALSE;
	}

	public function list_baru($table, $limit, $order,$order_type)
	{
		$this->db->order_by($order,$order_type);
		$data_bk = $this->db->get($table, $limit);
		return $data_bk->num_rows() > 0 ? $data_bk->result() : FALSE;
	}

	public function distinct($table, $field)
	{
		$this->db->select($field);
		$this->db->distinct();
		$data = $this->db->get($table);
		return $data->result();
	}

}

/* End of file M_api.php */
/* Location: ./application/models/M_api.php */