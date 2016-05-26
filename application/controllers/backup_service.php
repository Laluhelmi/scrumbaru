<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';
class Service extends REST_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('token');
	}

	public function index()
	{
		
	}


	public function generate_token_post()
	{
		$id_user=$this->post('id_user');
		$email=$this->post('email');
		$data=['token'=>$this->token->encrypt($email)];
		$this->db->where('id_user', $id_user);
		$query=$this->db->update('tb_user',$data);

		if ($query==TRUE) {
			$this->response('sukses');
		} else {
			$this->response('gagal');
		}
		


	}

	public function login_post()
	{
		$params=['email'=>$this->post('email'),
		    	 'password'=>md5(md5($this->post('password')))];
		$query=$this->db->get_where('tb_user',$params);
		$cek=$query->num_rows();
		if ($cek<1) {
			$this->response(['status'=>FALSE,
				 		     'pesan'=>null]);
		} else {
			$data=$query->row();
			$token=$data->token;
			$this->response(['status'=>TRUE,
				   			 'pesan'=>$token]);
		}
		
	}



	public function profile_get()
	{
		$token=$this->uri->segment(3);
		$query=$this->db->get_where('tb_user',array('token' =>$token , ));
		if ($query->num_rows<=0) {
			$this->response(['status'=>FALSE,
				  			 'pesan'=>null]);
		} else {
			$this->response(['status'=>TRUE,
				  			 'pesan'=>$query->row()]);
		}
	}

	public function edit_profile_post()
	{
		$token=$this->uri->segment(3);
		$params=['username'=>$this->post('username'),
		   		 'first_name'=>$this->post('first_name'),
		   		 'last_name'=>$this->post('last_name'),
		   		 'display_pic'=>$this->post('display_pic'),
		   		 ];
		 $this->db->where('token', $token);
		 $query=$this->db->update('tb_user', $params);
		 if ($query==FALSE) {
		 	$this->response(['status'=>FALSE,
		 		          		'pesan'=>'update fail']);
		 } else {
		 	$this->response(['status'=>TRUE,
		 		          		'pesan'=>'update success']);
		 }
		 
	}

	public function reset_pass_post()
	{
		$this->load->helper(array('spm'));
		$token=$this->post('token');
		$email=$this->post('email');
		$q=$this->db->get_where('tb_user',array('token'=>$token,
			                                    'email'=>$email))->row();
		$email=$q->email;
		$new_pass = randomString();
		$object= array('password'=>md5(md5($new_pass)));
		$this->db->where('email', $email);
		$query=$this->db->update('tb_user', $object);
		if ($query) {
			$data=['email'=>$email,
			       'password'=> $new_pass];
			$message=$this->parser->parse('email/resetpass', $data, TRUE);
			$send=$this->sendmail($email, 'your New Password Scrum Project Management', $message);
			if ($send==TRUE) {
				$this->response(['status'=>TRUE,
					             'pesan'=>'The new password has been sent to your email !']);
			} else {
				$this->response(['status'=>FALSE,
					             'pesan'=>'Reset password is failed, please contact administrator !']);
			}
			
		} else {
			$this->response(['status'=>FALSE,
					             'pesan'=>'User not registered !']);
		}
		
	}

	public function sendmail($to,$subject,$message)
    {
      # code...
      $config = Array(
          'protocol' => 'smtp',
          'smtp_host' => 'ssl://smtp.googlemail.com',
          'smtp_port' => 465,
          'smtp_user' => 'scrumprojectmanagements@gmail.com', // change it to yours
          'smtp_pass' => 'kodingskripsi12', // change it to yours
          'mailtype' => 'html',
          'charset' => 'iso-8859-1',
          'wordwrap' => TRUE
        );

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from('scrumprojectmanagements@gmail.com'); // change it to yours
        $this->email->to($to);// change it to yours
        $this->email->subject($subject);
        $this->email->message($message);
        if($this->email->send())
        {
          return TRUE;
        }else{
          return FALSE;
          // show_error($this->email->print_debugger());
        }
    }
	// public function show_projek_by_user_get()
	// {
	// 	$token=$this->uri->segment(3);
	// 	$query=$this->db->get_where('tb_user',array('token'=>$token));
	// 	$cek=$query->num_rows();
	// 	if ($cek<=0) {
	// 		$this->response(['status'=>FALSE,
	// 			        		'pesan'=>null]);
	// 	} else {
	// 		$data=$query->row();
	// 		$id_user=$data->id_user;
	// 		$query_projek=$this->db->get_where('tb_project', array('id_user'=>$id_user));
	// 		$jmlh=$query_projek->num_rows();
	// 		if ($jmlh<=0) {
	// 			$this->response(['status'=>FALSE,
	// 				        		'pesan'=>null]);
	// 		} else {
				
	// 			$data_projek=$query_projek->result();
	// 			$this->response(['status'=>TRUE,
	// 			    			'pesan'=>$data_projek]);
	// 		}
	// 	}
		
		
		
	// }

	// public function show_user_by_project_get()
	// {
	// 	$id_project=$this->uri->segment(3);
	// 	$query=$this->db->get_where('tb_tim',array('id_project'=>$id_project));
	// 	$jmlh=$query->num_rows();
	// 	if ($jmlh<=0) {
	// 		$this->response(['status'=>FALSE,
	// 			             'pesan'=>null]);
	// 	} else {
	// 		$data_tim=$query->result();
	// 		$this->response(['status'=>TRUE,
	// 			             'pesan'=>$data_tim]);
	// 	}
		
	// }

}

/* End of file Service.php */
/* Location: ./application/controllers/Service.php */