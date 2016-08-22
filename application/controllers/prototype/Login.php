<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index()
	{	
		$data = ['error' => null];
		$this->load->view('prototype/login', $data);
		

	}

	public function do_login()
	{
		$params = ['email' => $this->input->post('email'),
					'password' => md5(md5($this->input->post('password')))];
		$query = $this->db->get_where('tb_user',$params);

		if ($query->num_rows() == 0) {
			$data = ['error' => 'User belum terdaftar'];
			$this->load->view('prototype/login', $data);
		} else {

			$array = array(
				'is_login' => TRUE,
				'id' => $query->row()->id_user,
				'token' => $query->row()->token,
				'username' => $query->row()->username
			);
			
			$this->session->set_userdata( $array );
			redirect('prototype/dashboard','refresh');
		}
		
	}

	public function logout()
	{
		session_destroy();
		redirect('prototype/login','refresh');
	}

}

/* End of file Login.php */
/* Location: ./application/controllers/prototype/Login.php */