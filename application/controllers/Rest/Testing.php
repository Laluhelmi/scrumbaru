<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Testing extends REST_Controller {

	public function index()
	{
		
	}

	public function get_all_get()
	{
		$query=$this->db->get('tb_user')->result();
		$this->response(['pesan'=>$query]);
	}

	public function single_get()
	{
		$id=$this->uri->segment(4);
		$query=$this->db->get_where('tb_user', array('id_user' => $id, ))->row();
		$this->response(['pesan'=>$query]);
	}

}

/* End of file Testing.php */
/* Location: ./application/controllers/Rest/Testing.php */