<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {



	public function index()
	{
		$id_user = $this->session->userdata('id');
					 $this->db->select('*');
					 $this->db->from('tb_project');
					 $this->db->join('tb_tim', 'tb_tim.id_project = tb_project.id_project');
					 $this->db->where('tb_tim.id_user', $id_user);
		$ambil_tim = $this->db->get();
		$query = $ambil_tim->result();
		$data = ['projek' => $query];
		$this->load->view('prototype/dashboard', $data);
	}

}

/* End of file Dashboard.php */
/* Location: ./application/controllers/prototype/Dashboard.php */