<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Login extends REST_Controller {

	public function index()
	{
		
	}

	public function do_login_post()
	{
		$data=['email'=>$this->post('email'),
		'password'=>md5($this->post('password'))];

		$query=$this->db->get_where('tb_user', $data);
		$ambil=$query->row();
		$data=$query->row();
		$jml=$query->num_rows();

		if ($jml>0) {
			$array = array(
				'is_login' => TRUE,
				'email'=> $ambil->email,
				'id'=> $ambil->id_user,
				'username'=> $ambil->username,
				'status'=> $ambil->status
			);
			
			$this->session->set_userdata( $array );
			$this->response(['error'=>FALSE,
			    			'pesan'=>$data]	
				);

		} else {
			$this->response(['error'=>TRUE,
							'pesan'=>'akun not register']);
		}
		
	}

	public function do_logout_get()
	{
		$user=$this->session->userdata('email');
		session_destroy();
		$this->response(['error'=>FALSE,
						'pesan'=>'akun '.$user.' telah logout']);
	}

	public function session_get()
	{
		$this->response(['pesan'=>$this->session->userdata('email')]);
	}

}

/* End of file Login.php */
/* Location: ./application/controllers/Rest/Login.php */