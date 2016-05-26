<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';
class Api extends REST_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('token');
		$this->load->helper(array('spm','my_api'));
		$this->load->model('M_api');
	}

	public function generate_token_post()
	{	#menangkap inputan id_user dan email
		$id_user = $this->post('id_user');
		$email = $this->post('email');

		$data = ['token' => $this->token->encrypt($email)];
		#query update token
		$query=$this->M_api->update('tb_user','id_user',$id_user,$data);
		if ($query == TRUE) {

			$this->response('sukses');//menampilkan respon sukses ketika query bernilai benar

		} else {

			$this->response('gagal');//menampilkan respon gagal ketika query bernilai salah

		}

	}

	#fungsi service untuk login dari user
	public function login_post()
	{	
		$data = remove_unknown_fields($this->post(), $this->form_validation->get_field_names('login_post'));
		$this->form_validation->set_data($data);
		if ($this->form_validation->run('login_post') == false) {
			$this->response([
							'status' => FALSE,
					 	  	'pesan' => $this->form_validation->get_errors_as_array()
					 	  	]);
		} else {
			$params = [
					'email' => $this->post('email'),
					'status' => '1',
		    	   	'password' => md5(md5($this->post('password')))
		    	   	];

			$query = $this->M_api->get_keadaan('tb_user', $params); #model berisi query mencocokan data yang di iputkan  	   
			$cek = $query->num_rows();
			if ($cek < 1) {

				$this->response([
								'status' => FALSE,
					 		    'pesan' => 'user not registered'
					 		     ]);
			} else {

				$data = $query->row();
				$token = $data->token;
				$id = $data->id_user;

				$this->response([
								'status' => TRUE,
					   			'pesan' => array('token'=>$token,)
					   			]);
			}
		}	
	}

	#fungsi service untuk mendapatkan data dari user tertentu, dengan parameter token
	public function profile_get()
	{
		$token = $this->uri->segment(3);

		$params = ['token' => $token];

		$query = $this->M_api->get_keadaan('tb_user', $params);
		if ($query->num_rows()<=0) {

			$this->response([
							'status' => FALSE,
				  			'pesan' => null
				  			]);
		} else {

			$this->response([
							'status' => TRUE,
				  			'pesan' => $query->row()
				  			]);
		}
	}

	#fungsi service untuk mengedit profile dari user tertentu berdasarkan parameter token
	public function edit_profile_post($token=null)
	{
		$token = $this->uri->segment(3);
		if ($token != null) {
			$q = $this->M_api->get_keadaan('tb_user', array('token' => $token,))->num_rows();
			if ($q > 0) {
				$data = remove_unknown_fields($this->post(), $this->form_validation->get_field_names('edit_profile_post'));
				$this->form_validation->set_data($data);
				if ($this->form_validation->run('edit_profile_post') == false) {
					$this->response([
									'status' => FALSE,
					 	  			'pesan' => $this->form_validation->get_errors_as_array()
					 	  			]);
				} else {
					$params = 	[
						   'username' => $this->post('username'),
				   		   'first_name' => $this->post('first_name'),
				   		   'last_name' => $this->post('last_name'),
				   		   'display_pic' => $this->post('display_pic'),
				   		 	];
				
					$query = $this->M_api->update('tb_user','token', $token, $params);
					if ($query == FALSE) {

					 	$this->response([
					 					'status' => FALSE,
					 		          	'pesan' => 'update fail'
					 		          	]);
					} else {
					 	$this->response([
					 					'status' => TRUE,
					 		          	'pesan' => 'update success'
					 		          	]);
					}
				}
			} else {
				$this->response([
								'status' => FALSE,
				 	  			'pesan' => 'unkwon token'
				 	  			]);
			}	
		} else {
			$this->response([
								'status' => FALSE,
				 	  			'pesan' => 'unknow method'
				 	  			]);
		}
		
		
		
		
		 
	}

	#fungsi service yg digunakan untuk reset password
	#menggunakan 2 inputan, token dan email
	public function reset_pass_post()
	{
		$data = remove_unknown_fields($this->post(), $this->form_validation->get_field_names('reset_pass_post'));
		$this->form_validation->set_data($data);
		if ($this->form_validation->run('reset_pass_post') == false) {
			$this->response([
							'status' => FALSE,
			 	  			'pesan' => $this->form_validation->get_errors_as_array()
			 	  			]);
		} else {
			$params = [
						'token' => $this->post('token'),
				   		'email' => $this->post('email')
				   		];
		
			$q = $this->M_api->get_keadaan('tb_user', $params)->row();
			$cek = $this->M_api->get_keadaan('tb_user', $params)->num_rows();

			if ($cek <= 0) {
				
				$this->response([
								'status' => FALSE,
							    'pesan' => 'User not registered !'
							    ]);

			} else {
				$email = $q->email;

				$new_pass = randomString();

				$object = ['password' => md5(md5($new_pass))];
				
				$query = $this->M_api->update('tb_user','email', $email, $object);
				if ($query == TRUE ) {

					$data = [
							'email' => $email,
					       	'password' => $new_pass
					       	];

					$message = $this->parser->parse('email/resetpass', $data, TRUE);
					$send = $this->sendmail($email, 'your New Password Scrum Project Management', $message);

					if ($send == TRUE) {

						$this->response([
										'status' => TRUE,
							            'pesan' => 'The new password has been sent to your email !'
							            ]);

					} else {

						$this->response([
										'status' => FALSE,
							            'pesan' => 'Reset password is failed, please contact administrator !'
							           	]);

					}
					
				} else {

					$this->response([
									'status' => FALSE,
							        'pesan' => 'User not registered !'
							        ]);

				}
			}
		}
		
		
		
		
	}

	#fungsi service yang di gunakan untk menampilkan dashboard scrum berdasarkan projek yg diikuti oleh user tersebut
	public function dashboard_scrum_get($token=null)
    {
    	$token = $this->uri->segment(3);
    	if ($token != 0) {
    		$paramas = ['token' => $token];
    		$ambil_id = $this->M_api->get_keadaan('tb_user', $paramas);
    		$cek = $ambil_id->num_rows();
    		if ($cek <= 0) {
    			$this->response([
    							'status' => FALSE,
    							'pesan' => 'token not registered']);
    		} else {
    			$id_user = $ambil_id->row()->id_user;
    			$query = $this->M_api->get_join_dashboard($id_user)->result();
    			$this->response([
    							'status' => TRUE,
    							'pesan' => $query]);
    		}
    		
    	} else {
    		$this->response([
    						'status' => FALSE,
    						'pesan' => 'unknow method']);
    	}
    	
    }

	#fungsi service yg digunakan untuk mengirim chat di dalam tiap projek, dengan parameter id projek, dan token,
	public function kirim_chat_post($projek = null , $user = null)
    {
    	if ($projek != null && $user !=null) {

			$projek = $this->uri->segment(3);
    		$token = $this->uri->segment(4);

    		$data_token = ['token' => $token];

    		$cek_token = $this->M_api->get_keadaan('tb_user', $data_token);
    		$id_user = $cek_token->row()->id_user; 

    		if ($cek_token->num_rows() < 1) {

    			$this->response([
    							'status' => FALSE,
    				   			'pesan' => 'Token Is not correct'
    				   			]);

    		} else {

    			$data_projek = ['id_project' => $projek];

    			$ambil_id_cr = $this->M_api->get_keadaan('tb_chat_room', $data_projek )->row();
    			$id_cr = $ambil_id_cr->id_cr;
    			
    			$data = remove_unknown_fields($this->post(), $this->form_validation->get_field_names('kirim_chat_post'));
    			$this->form_validation->set_data($data);
    			if ($this->form_validation->run('kirim_chat_post') == false) {
    				$this->response(['status' => FALSE,
			 	  					'pesan'=>$this->form_validation->get_errors_as_array()]);
    			} else {
    				$inputan = [
    						'message' => $this->post('message'),
    						'id_cr' => $id_cr,
    						'id_user' => $id_user
    						];

	    			$query_input = $this->M_api->insert('tb_message', $inputan);
	    			if ($query_input == TRUE) {

	    				$this->response([
	    								'status' => TRUE,
	    				   				'pesan' => 'Send Message success'
	    				   				]);
	    			} else {

	    				$this->response([
	    								'status' => FALSE,
	    				   				'pesan' => 'Send Message Fail'
	    				   				]);
	    			}
    			}	
    			
    		}

		} else {

			$this->response([
							'status' => FALSE,
							'pesan' => 'error unknow'
							]);
		}
    }

    #fungsi service yg digunakan untuk mengambil data chat berdasarkan projek yg di tentukan dari parameter id projek
    public function read_chat_by_project_get($id_project = null)
    {
    	if ($id_project != null) {

    		$data = ['id_project' => $this->uri->segment(3)];
    		$cek_projek = $this->M_api->get_keadaan('tb_project', $data);
    		if ($cek_projek->num_rows() < 1) {

    			$this->response([
    						'status' => FALSE,
    						'pesan' => 'project unknow'
    						]);
    		} else {

    			$ambil_id_cr = $this->M_api->get_keadaan('tb_chat_room', $data)->row()->id_cr;
    			//$params = ['id_cr' => $ambil_id_cr];
    				// $this->db->select('message, waktu, username');
        //             $this->db->from('tb_message');
        //             $this->db->join('tb_user', 'tb_user.id_user = tb_message.id_user');
        //             $this->db->where('id_cr', $ambil_id_cr);
        //             $this->db->order_by('id_message', 'asc');
        //             $query_chat = $this->db->get();

    			$query_chat = $this->M_api->get_join_message($ambil_id_cr);

    			if ($query_chat->num_rows() <= 0) {

    				$this->response([
    						'status' => FALSE,
    						'pesan' => 'chat empty'
    						]);

    			} else {
    				
    				$data_chat = $query_chat->result();
    				$this->response([
    						'status' => TRUE,
    						'pesan' => $data_chat
    						]);

    			}
    		}
    	} else {

    		$this->response([
    						'status' => FALSE,
    						'pesan' => 'error unknow'
    						]);
    	}
    }

    #fungsi service untuk menampilkan anggota tim dalam suatu projek tertentu, dapat diakses dengan parameter id project
    public function show_tim_by_project_get($id_project=null)
    {
    	$id_project = $this->uri->segment(3);
    	if ($id_project != null) {
    		$query = $this->M_api->get_join_tim($id_project);
    		if ($query->num_rows() <= 0) {
    			$this->response([
    						'status' => FALSE,
    						'pesan' => 'tim not defined'
    						]);
    		} else {
    			$data = $query->result();
    			$this->response([
    						'status' => TRUE,
    						'pesan' => $data
    						]);
    		}
    	} else {
    		$this->response([
    						'status' => FALSE,
    						'pesan' => 'method unknow'
    						]);
    	}
    }

    public function show_all_sprint_by_project_get($id_project = null )
    {
    	$id_project = $this->uri->segment(3);
    	if ($id_project != null) {
    		$query =  $this->M_api->get_keadaan('tb_head_s', array('id_project' => $id_project , ));
    		if ($query->num_rows() > 0) {

    			$id_h_s = $query->row()->id_h_s;
    			$params = ['id_h_s' => $id_h_s];
    			$sprint = $this->M_api->get_keadaan('tb_s', $params );
    			if ($sprint->num_rows() <= 0) {
    				$this->response([
    						'status' => FALSE,
    						'pesan' => 'sprint empty'
    						]);
    			} else {
    				$this->response([
    						'status' => TRUE,
    						'pesan' => $sprint->result()
    						]);
    			}
    		} else {
    			$this->response([
    						'status' => FALSE,
    						'pesan' => 'sprint not found'
    						]);
    		}
    	} else {
    		$this->response([
    						'status' => FALSE,
    						'pesan' => 'method unknow'
    						]);
    	}
    }

    public function show_to_do_sprint_by_project_get($id_project = null )
    {
    	$id_project = $this->uri->segment(3);
    	if ($id_project != null) {
    		$query =  $this->M_api->get_keadaan('tb_head_s', array('id_project' => $id_project , ));
    		if ($query->num_rows() > 0) {

    			$id_h_s = $query->row()->id_h_s;
    			$params = ['id_h_s' => $id_h_s,
    						'status' => 1];
    			$sprint = $this->M_api->get_keadaan('tb_s', $params );
    			if ($sprint->num_rows() <= 0) {
    				$this->response([
    						'status' => FALSE,
    						'pesan' => 'sprint empty'
    						]);
    			} else {
    				$this->response([
    						'status' => TRUE,
    						'pesan' => $sprint->result()
    						]);
    			}
    		} else {
    			$this->response([
    						'status' => FALSE,
    						'pesan' => 'sprint not found'
    						]);
    		}
    	} else {
    		$this->response([
    						'status' => FALSE,
    						'pesan' => 'method unknow'
    						]);
    	}
    }

    public function show_doing_sprint_by_project_get($id_project = null )
    {
    	$id_project = $this->uri->segment(3);
    	if ($id_project != null) {
    		$query =  $this->M_api->get_keadaan('tb_head_s', array('id_project' => $id_project , ));
    		if ($query->num_rows() > 0) {

    			$id_h_s = $query->row()->id_h_s;
    			$params = ['id_h_s' => $id_h_s,
    						'status' => 2];
    			$sprint = $this->M_api->get_keadaan('tb_s', $params );
    			if ($sprint->num_rows() <= 0) {
    				$this->response([
    						'status' => FALSE,
    						'pesan' => 'sprint empty'
    						]);
    			} else {
    				$this->response([
    						'status' => TRUE,
    						'pesan' => $sprint->result()
    						]);
    			}
    		} else {
    			$this->response([
    						'status' => FALSE,
    						'pesan' => 'sprint not found'
    						]);
    		}
    	} else {
    		$this->response([
    						'status' => FALSE,
    						'pesan' => 'method unknow'
    						]);
    	}
    }

    public function show_done_sprint_by_project_get($id_project = null )
    {
    	$id_project = $this->uri->segment(3);
    	if ($id_project != null) {
    		$query =  $this->M_api->get_keadaan('tb_head_s', array('id_project' => $id_project , ));
    		if ($query->num_rows() > 0) {

    			$id_h_s = $query->row()->id_h_s;
    			$params = ['id_h_s' => $id_h_s,
    						'status' => 3];
    			$sprint = $this->M_api->get_keadaan('tb_s', $params );
    			if ($sprint->num_rows() <= 0) {
    				$this->response([
    						'status' => FALSE,
    						'pesan' => 'sprint empty'
    						]);
    			} else {
    				$this->response([
    						'status' => TRUE,
    						'pesan' => $sprint->result()
    						]);
    			}
    		} else {
    			$this->response([
    						'status' => FALSE,
    						'pesan' => 'sprint not found'
    						]);
    		}
    	} else {
    		$this->response([
    						'status' => FALSE,
    						'pesan' => 'method unknow'
    						]);
    	}
    }

    #fungsi service yg digunakan untuk menampilkan notif kepada user tertentu ketika di undang ke dalam project
    public function notif_add_to_project_get($token=null)
    {
    	$token = $this->uri->segment(3);
    	if ($token != null) {
    		// $this->response($token);
    		// die;
    		$params = ['token' => $token];
    		$cek_token = $this->M_api->get_keadaan('tb_user', $params)->num_rows();
    		if ($cek_token <= 0) {
    			$this->response([
    						'status' => FALSE,
    						'pesan' => 'token invalid'
    						]);
    		} else {
    			$query = $this->M_api->get_keadaan('tb_user', $params)->row();
    			$id_user = $query->id_user;
    			// $this->response($id_user);
    			// die;
    			// $parameter = [
    			// 			'id_user' => $id_user,
    			//  			'status' => '0'
    			//  			];
    			$cek_notif = $this->M_api->get_join_notif();
    			// $this->response($cek_notif->num_rows());
    			// die;
    			if ($cek_notif->num_rows() <= 0) {
    				$this->response([
    						'status' => FALSE,
    						'pesan' => 'empty notif'
    						]);
    			} else {
    				$data = $cek_notif->result();
    				$this->response([
    						'status' => TRUE,
    						'jumlah' => $cek_notif->num_rows(),
    						'pesan' => $data
    						]);
    			}
    			
    		}
    		
    	} else {
    		$this->response([
    						'status' => FALSE,
    						'pesan' => 'method unknow'
    						]);
    	}
    	
    }

    public function confirm_notif_get()
    {	
    	$id_user = $this->uri->segment(3);
    	$cek_id = $this->M_api->get_keadaan('tb_user', array('id_user' => $id_user , ));
    	if ($cek_id->num_rows() <= 0) {
    			$this->response([
    						'status' => FALSE,
    						'pesan' => 'id user unavailable'
    						]);
    		} else {
    			$params = [
    						'status' => '1'];
    			$update = $this->M_api->update_notif($id_user, $params);
    			if ($update == TRUE) {
    				$this->response([
    						'status' => TRUE,
    						'pesan' => 'update success'
    						]);
    			} else {
    				$this->response([
    						'status' => FALSE,
    						'pesan' => 'update fail'
    						]);
    			}
    			
    		}
    }
    
	public function sendmail($to,$subject,$message)
    {
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
        }
    }

}

/* End of file Api.php */
/* Location: ./application/controllers/Api.php */