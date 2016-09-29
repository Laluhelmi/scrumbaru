<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_api extends CI_Model {

	public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

 //  public function insert($table, $data)
	// {
	// 	$this->db->insert($table, $data);
	// 	return $this->db->insert_id() ? TRUE : false;
	// }

	public function insert_pesan($table, $data)
	{
		$this->db->insert($table, $data);
		return TRUE;
	}

	// public function get($table, $kolom, $order_type)
	// {
	// 	$this->db->order_by($kolom, $order_type);
	// 	$data = $this->db->get($table);
	// 	return $data->num_rows() > 0 ? $data->result() : false;
	// }

	// public function get_list($table)
	// {
	// 	$data = $this->db->get($table);
	// 	return $data->num_rows() > 0 ? $data->result() : false;
	// }

	// public function get_list_order($table, $kolom, $order_type, $num, $offset)
	// {
	// 	$this->db->order_by($kolom, $order_type);
	// 	$data = $this->db->get($table,$num, $offset);
	// 	return $data->num_rows() > 0 ? $data->result() : false;
	// }

	public function select($table,$select, $where, $value){
		$this->db->select($select);
		$this->db->where($where, $value);
		$data = $this->db->get($table);
		return $data->first_row();
	}

	// public function get_list_join($table1, $table2, $where1, $where2, $like, $like_value, $kolom, $order_type, $num, $offset)
	// {
	// 	//$this->db->from($table1);
	// 	$this->db->join($table2, "$table2.$where2 = $table1.$where1");
	// 	$this->db->order_by($kolom, $order_type);
	// 	$this->db->like($like, $like_value);
	// 	$data = $this->db->get($table1, $num, $offset);
	// 	return $data->num_rows() > 0 ? $data->result() : false;
	// }

	public function get_keadaan($table, $params)
	{
		$query = $this->db->get_where($table, $params);
		return $query;
	}

	// public function get_join_dashboard($id_user)
	// {
	// 	$this->db->select('tb_tim.id_project, judul, deskripsi, estimasi, tgl_mulai, tgl_selesai');
	// 	$this->db->from('tb_project');
	// 	$this->db->join('tb_tim', 'tb_tim.id_project = tb_project.id_project');
	// 	$this->db->where('tb_tim.id_user', $id_user);
	// 	$query = $this->db->get();
	// 	return $query;
	// }

	public function get_join_message($id_projek)
	{
		$this->db->select('message, tanggal, jam, username, daily_scrum');
        $this->db->from('tb_message');
        $this->db->join('tb_user', 'tb_user.id_user = tb_message.id_user');
        $this->db->where('id_project', $id_projek);
        $this->db->where('tanggal', date("Y-m-d"));
        $this->db->order_by('tanggal', 'asc');
        $query = $this->db->get();
        return $query;
	}
	public function get_join_message_bydate($id_projek, $tanggal)
	{
		$this->db->select('message, tanggal, jam, username');
        $this->db->from('tb_message');
        $this->db->join('tb_user', 'tb_user.id_user = tb_message.id_user');
        $this->db->where('id_project', $id_projek);
        $this->db->where('tanggal', $tanggal);
        $this->db->order_by('tanggal', 'asc');
        $query = $this->db->get();
        return $query;
	}
	public function list_baru($table, $where, $limit, $order,$order_type)
	{	
		$this->db->select('tb_user.username, tb_notif.jabatan, tb_notif.status, tb_notif.waktu, tb_project.judul');
		$this->db->join('tb_user', 'tb_user.id_user = tb_notif.id_inviter');
		$this->db->join('tb_project', 'tb_project.id_project = tb_notif.id_project');
    	$this->db->where($where);
		$this->db->order_by($order,$order_type);
		$data = $this->db->get($table, $limit);
		return $data->num_rows() > 0 ? $data->result() : FALSE;
	}

	public function get_join_tim($id_project)
	{
		$this->db->select('username, jabatan');
        $this->db->from('tb_tim');
         $this->db->join('tb_user', 'tb_user.id_user = tb_tim.id_user');
         $this->db->where('id_project', $id_project);
       $query = $this->db->get();
         return $query;
	 }

	// public function get_join_notif()
	// {
	// 	$this->db->select('id_inviter, judul, waktu, status, tb_notif.id_user');
 //        $this->db->from('tb_notif');
 //        $this->db->join('tb_project', 'tb_notif.id_project = tb_project.id_project');
 //        $this->db->where('status','0');
 //        $this->db->order_by('waktu', 'desc');
 //        $query = $this->db->get();
 //        return $query;
	// }

	// public function get_join_all_sprint($project)
	// {
	// 	$this->db->select('id_s,tb_sprint.tgl_mulai,tb_sprint.tgl_selesai,tb_sprint.judul, tb_sprint.estimasi, status');
	// 	$this->db->from('tb_sprint');
	// 	$this->db->join('tb_productbacklog', 'tb_sprint.id_pb = tb_productbacklog.id_pb');
	// 	$this->db->join('tb_project', 'tb_productbacklog.id_project = tb_project.id_project');
	// 	$this->db->where('tb_productbacklog.id_project', $project);
	// 	$query = $this->db->get();
	// 	return $query;
	// }

	// public function get_join_sprint($project, $status)
	// {
	// 	$this->db->select('id_s,tb_sprint.tgl_mulai,tb_sprint.tgl_selesai,tb_sprint.judul, tb_sprint.estimasi, status');
	// 	$this->db->from('tb_sprint');
	// 	$this->db->join('tb_productbacklog', 'tb_sprint.id_pb = tb_productbacklog.id_pb');
	// 	$this->db->join('tb_project', 'tb_productbacklog.id_project = tb_project.id_project');
	// 	$this->db->where('tb_productbacklog.id_project', $project);
	// 	$this->db->where('tb_sprint.status', $status);
	// 	$query = $this->db->get();
	// 	return $query;
	// }
	public function show_daily($id_project)
	{
		 $this->db->select('distinct(tanggal)');
        $this->db->where('id_project', $id_project);
        $this->db->order_by('tanggal', 'desc');
        $q = $this->db->get('tb_message');
        //$data = $q->result();
        return $q;
	}

  public function get_join_three_table($table1, $table2, $table3, $requirement1, $requirement2, $where, $value, $colum, $order_type){
        $data = array();
        //$this->db->distinct();

        $this->db->select('distinct(judul), tb_project.id_project, deskripsi, tb_project.started_date, tb_project.finished_date, tb_project.created, tb_project.enkrip_name');
    $this->db->join($table1, "$table1.$requirement1 = $table3.$requirement1");
    $this->db->join($table2, "$table2.$requirement2 = $table3.$requirement2");
    $this->db->where($where, $value);
		$this->db->order_by($colum, $order_type);
		//$data = $this->db->get($table3);
		//return $data->num_rows() > 0 ? $data->result() : false;
		$Q = $this->db->get($table3);
        if ($Q->num_rows() > 0) {
            foreach ($Q->result_array() as $row) {
                $data[] = $row;
            }
        }
        $Q->free_result();
        return $data;
  }
	public function get_join_three_table_sprint($table1, $table2, $table3, $requirement1, $requirement2, $where, $value, $colum, $order_type){
	        $data = array();
	        $this->db->select('tb_user.email, tb_user.username, tb_sprint.task, tb_sprint.estimasi, tb_sprint.tgl_mulai, tb_sprint.tgl_selesai, tb_sprint.developer');
	    $this->db->join($table1, "$table1.$requirement1 = $table3.$requirement1");
	    $this->db->join($table2, "$table2.$requirement2 = $table3.$requirement2");
	    $this->db->join('tb_user', 'tb_sprint.developer = tb_user.id_user');
	    $this->db->where($where, $value);
			$this->db->order_by($colum, $order_type);
			//$data = $this->db->get($table3);
			//return $data->num_rows() > 0 ? $data->result() : false;
			$Q = $this->db->get($table3);
	        if ($Q->num_rows() > 0) {
	            foreach ($Q->result_array() as $row) {
	                $data[] = $row;
	            }
	        }
	        $Q->free_result();
	        return $data;
	}

	public function get_join_three_table_sprint_status($table1, $table2, $table3, $requirement1, $requirement2, $where, $value, $colum, $order_type, $status){
        $data = array();
	        $this->db->select('tb_user.email, tb_user.username, tb_sprint.task, tb_sprint.estimasi, tb_sprint.tgl_mulai, tb_sprint.tgl_selesai, tb_sprint.developer');
	    $this->db->join($table1, "$table1.$requirement1 = $table3.$requirement1");
	    $this->db->join($table2, "$table2.$requirement2 = $table3.$requirement2");
	    $this->db->join('tb_user', 'tb_sprint.developer = tb_user.id_user');
	    $this->db->where($where, $value);
	    $this->db->where('tb_sprint.status', $status);
			$this->db->order_by($colum, $order_type);
			//$data = $this->db->get($table3);
			//return $data->num_rows() > 0 ? $data->result() : false;
			$Q = $this->db->get($table3);
	        if ($Q->num_rows() > 0) {
	            foreach ($Q->result_array() as $row) {
	                $data[] = $row;
	            }
	        }
	        $Q->free_result();
	        return $data;
  	}

  	public function sum_join_three_table_sprint_status($table1, $table2, $table3, $requirement1, $requirement2, $where, $value, $colum, $order_type, $status){
        $data = array();
	        	$this->db->select('tb_user.email, tb_user.username, tb_sprint.task, tb_sprint.estimasi, tb_sprint.tgl_mulai, tb_sprint.tgl_selesai, tb_sprint.developer');
	    		$this->db->join($table1, "$table1.$requirement1 = $table3.$requirement1");
	    		$this->db->join($table2, "$table2.$requirement2 = $table3.$requirement2");
	    		$this->db->join('tb_user', 'tb_sprint.developer = tb_user.id_user');
	    		$this->db->where($where, $value);
	    		$this->db->where('tb_sprint.status', $status);
				$this->db->order_by($colum, $order_type);
				$Q = $this->db->get($table3)->num_rows();
	        
	     
	        return $Q;
  	}

	public function update($table, $where, $id, $data)
	{
		$this->db->where($where, $id);
		$this->db->update($table, $data);
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	public function get_kondisi($table, $params)
	{
		$query = $this->db->get_where($table, $params);
		return $query;
	}
	public function get_data($table, $where, $value)
	{
		$this->db->where($where, $value);
		$temp = $this->db->get($table);
		$data = $temp->first_row();
		return $data;
	}

	public function register($data)
  	{
    # code...
	    $this->db->where('email', $data['email']);
	    $temp = $this->db->get('tb_user');
	    if($temp->num_rows() > 0){
	      $message['error'] = "Email is already exist!!";
	      return $message;
    	}else {
      # code...
	      $data['password'] = md5(md5($data['password']));
	      $data['activation_key'] = md5($data['email'].date("Y/m/d"));
	      $this->db->insert('tb_user', $data);
	      return $this->db->insert_id() ? TRUE : FALSE;
    	}
  	}

}

/* End of file M_api.php */
/* Location: ./application/models/M_api.php */