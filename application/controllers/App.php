<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';
class App extends REST_Controller {

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
	 				 		     ], REST_Controller::HTTP_OK);
	 		} else {

	 			$data = $query->row();
	 			$token = $data->token;
	 			$id = $data->id_user;

	 			$this->response([
	 							'status' => TRUE,
	 				   			'pesan' => array('token'=>$token,)
	 				   			], REST_Controller::HTTP_OK);
	 		}
	 	}	
	 }

	public function profile_get()
	{
		$token = $this->uri->segment(3);

		$params = ['token' => $token];

		$query = $this->M_api->get_keadaan('tb_user', $params);
		if ($query->num_rows()<=0) {

			$this->response([
							'status' => FALSE,
				  			'pesan' => null
				  			], REST_Controller::HTTP_OK);
		} else {

			$this->response([
							'status' => TRUE,
				  			'pesan' => $query->row()
				  			], REST_Controller::HTTP_OK);
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
					 	  			], REST_Controller::HTTP_BAD_REQUEST);
				} else {
					$params = 	[
						   'username' => $this->post('username'),
				   		   'first_name' => $this->post('first_name'),
				   		   'last_name' => $this->post('last_name'),
				   		 	];
				
					$query = $this->M_api->update('tb_user','token', $token, $params);
					if ($query == FALSE) {

					 	$this->response([
					 					'status' => FALSE,
					 		          	'pesan' => 'update fail'
					 		          	], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
					} else {
					 	$this->response([
					 					'status' => TRUE,
					 		          	'pesan' => 'update success'
					 		          	], REST_Controller::HTTP_CREATED);
					}
				}
			} else {
				$this->response([
								'status' => FALSE,
				 	  			'pesan' => 'unkwon token'
				 	  			], REST_Controller::HTTP_BAD_REQUEST);
			}	
		} else {
			$this->response([
								'status' => FALSE,
				 	  			'pesan' => 'unknow method'
				 	  			], REST_Controller::HTTP_BAD_REQUEST);
		}
		 
	}

	public function register_post()
    {
        $data = remove_unknown_fields($this->post(), $this->form_validation->get_field_names('register_post'));

        $this->form_validation->set_data($data);
        if ($this->form_validation->run('register_post') == false) {
            # code...
            $this->response([
                            'status' => FALSE,
                            'pesan' => $this->form_validation->get_errors_as_array()
                            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $data = $this->post();
            if ($this->M_api->get_data('tb_user', 'username', $data['username'])) {
                $this->response([
                                'status' => FALSE,
                                'pesan' => 'username already exist'
                                ], REST_Controller::HTTP_OK);
            } else {

                $data['token'] = $this->token->encrypt($data['email']);
                $temp = $this->M_api->register($data);

                if ($temp === TRUE) {
                    # code...
                    $to = $data['email'];
                    $act_key = md5($this->M_api->select('tb_user','activation_key','email', $to)->activation_key);
                    $subject = "Activation Account Scrum Project Management";
                    $message = "<a href='http://scrum.alfatech.id/index.php/spm/activation?email=$to&activation_key=$act_key'>Click here</a> to activation your account!!";
                    $this->sendmail($to, $subject, $message);

                    $this->response([
                                'status' => TRUE,
                                'pesan' => 'Your account is registered'
                                ], REST_Controller::HTTP_CREATED);
                } else {
                    # code...
                    $this->response([
                                'status' => TRUE,
                                'pesan' => 'Register is failed'
                                ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
                
            }

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
                            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $params = [
                        // 'token' => $this->post('token'),
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
                                        ], REST_Controller::HTTP_CREATED);

                    } else {

                        $this->response([
                                        'status' => FALSE,
                                        'pesan' => 'Reset password is failed, please contact administrator !'
                                        ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

                    }
                    
                } else {

                    $this->response([
                                    'status' => FALSE,
                                    'pesan' => 'User not registered !'
                                    ], REST_Controller::HTTP_OK);

                }
            }
        }   
        
    }

	#fungsi service yang di gunakan untk menampilkan dashboard scrum berdasarkan projek yg diikuti oleh user tersebut 'done'
	public function dashboard_scrum_get($token=null)
    {
    	$token = $this->uri->segment(3);

    	
    		$paramas = ['token' => $token];
    		$ambil_id = $this->M_api->get_keadaan('tb_user', $paramas);
    		$cek = $ambil_id->num_rows();
            
    		if ($cek <= 0) {
    			$this->response([
    							'status' => FALSE,
    							'pesan' => 'token not registered'], REST_Controller::HTTP_OK);
    		} else {
    			$id_user = $ambil_id->row()->id_user;
                $card= $this->M_api->get_join_three_table("tb_user", "tb_tim", "tb_project", "id_user", "id_project", "tb_tim.id_user", $id_user, "created", "asc");
            
                if($card){
                  
                $this->response([
                                    'status' => TRUE,
                                    'pesan' => $card], REST_Controller::HTTP_OK);
                } else {
                
                $this->response([
                                'status' => FALSE,
                                'pesan' => 'empty project'], REST_Controller::HTTP_OK);
                }
    		}
    }

    public function daily_chat_get($id_project = null)
    {
        if ($id_project == null) {
            $this->response([
                            'status' => FALSE,
                            'pesan' => 'unknow method'
                            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $id_project = $this->uri->segment(3);
            $cek = $this->M_api->show_daily($id_project)->num_rows();
            if ($cek <= 0) {
                $this->response([
                            'status' => FALSE,
                            'pesan' => 'empty daily'], REST_Controller::HTTP_OK);
            } else {
                $query = $this->M_api->show_daily($id_project)->result();

                $this->response([
                                'status' => TRUE,
                                'pesan' => $query], REST_Controller::HTTP_OK);
            }
        }
            
    }

    #fungsi service yg digunakan untuk mengirim chat di dalam tiap projek, dengan parameter id projek, dan token,
	public function kirim_chat_post($projek = null , $user = null)
    {
    	if ($projek != null && $user !=null) {

			$projek = $this->uri->segment(3);
    		$token = $this->uri->segment(4);

    		$cek_project = $this->M_api->get_keadaan('tb_project', array('id_project' => $projek,))->num_rows();
    		if ($cek_project<1) {
    			$this->response([
    							'status' => FALSE,
    				   			'pesan' => 'project not found'
    				   			], REST_Controller::HTTP_OK);
    		}
    		
    		$data_token = ['token' => $token];

    		$cek_token = $this->M_api->get_keadaan('tb_user', $data_token);
    		$id_user = $cek_token->row()->id_user; 

    		if ($cek_token->num_rows() < 1) {

    			$this->response([
    							'status' => FALSE,
    				   			'pesan' => 'Token Is not correct'
    				   			], REST_Controller::HTTP_BAD_REQUEST);

    		} else {

    			$data_projek = ['id_project' => $projek];

    			$data = remove_unknown_fields($this->post(), $this->form_validation->get_field_names('kirim_chat_post'));
    			$this->form_validation->set_data($data);
    			if ($this->form_validation->run('kirim_chat_post') == false) {
    				$this->response(['status' => FALSE,
			 	  					'pesan'=>$this->form_validation->get_errors_as_array()], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    			} else {
    				
    				$inputan = [
    						'message' => $this->post('message'),
    						// 'id_cr' => $id_cr,
    						'id_user' => $id_user,
    						'id_project' => $projek,
                            'tanggal' => date("Y-m-d"),
                            'jam' => gmdate("h:i:sa", time()+60*60*7)
    						];
                            //date("h:i:sa")
	    			$query_input = $this->M_api->insert_pesan('tb_message', $inputan);
	    			
	    			if ($query_input == TRUE) {
	    				$this->response([
	    								'status' => TRUE,
	    				   				'pesan' => 'Send Message success'
	    				   				], REST_Controller::HTTP_CREATED);
	    			} else {
	    				$this->response([
	    								'status' => FALSE,
	    				   				'pesan' => 'Send Message Fail'
	    				   				], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
	    			}
    			}	
    			
    		}

		} else {

			$this->response([
							'status' => FALSE,
							'pesan' => 'error unknow'
							], REST_Controller::HTTP_BAD_REQUEST);
		}
    }

    #fungsi service yg digunakan untuk mengambil data chat berdasarkan projek yg di tentukan dari parameter id projek "done"
    public function read_chat_by_project_bynow_get($id_project = null)
    {
    	if ($id_project != null) {

    		$data = ['id_project' => $this->uri->segment(3)];
    		$cek_projek = $this->M_api->get_keadaan('tb_project', $data);
    		if ($cek_projek->num_rows() < 1) {

    			$this->response([
    						'status' => FALSE,
    						'pesan' => 'project unknow'
    						], REST_Controller::HTTP_OK);
    		} else {

    			// $ambil_id_cr = $this->M_api->get_keadaan('tb_chat_room', $data)->row()->id_cr;
    			$id_projek = $data['id_project'];

    			$query_chat = $this->M_api->get_join_message($id_projek);

    			if ($query_chat->num_rows() <= 0) {

    				$this->response([
    						'status' => FALSE,
    						'pesan' => 'chat empty'
    						], REST_Controller::HTTP_OK);

    			} else {
    				
    				$data_chat = $query_chat->result();
    				$this->response([
    						'status' => TRUE,
    						'pesan' => $data_chat
    						], REST_Controller::HTTP_OK);

    			}
    		}
    	} else {

    		$this->response([
    						'status' => FALSE,
    						'pesan' => 'error unknow'
    						], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    	}
    }

     public function read_chat_by_project_bydate_get($id_project = null)
    {
        if ($id_project != null) {

            $data = ['id_project' => $this->uri->segment(3)];
            $tanggal = $this->uri->segment(4);
            $cek_projek = $this->M_api->get_keadaan('tb_project', $data);
            if ($cek_projek->num_rows() < 1) {

                $this->response([
                            'status' => FALSE,
                            'pesan' => 'project unknow'
                            ], REST_Controller::HTTP_OK);
            } else {

                // $ambil_id_cr = $this->M_api->get_keadaan('tb_chat_room', $data)->row()->id_cr;
                $id_projek = $data['id_project'];

                $query_chat = $this->M_api->get_join_message_bydate($id_projek, $tanggal);

                if ($query_chat->num_rows() <= 0) {

                    $this->response([
                            'status' => FALSE,
                            'pesan' => 'chat empty'
                            ], REST_Controller::HTTP_OK);

                } else {
                    
                    $data_chat = $query_chat->result();
                    $this->response([
                            'status' => TRUE,
                            'pesan' => $data_chat
                            ], REST_Controller::HTTP_OK);

                }
            }
        } else {

            $this->response([
                            'status' => FALSE,
                            'pesan' => 'error unknow'
                            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show_tim_by_project_get($id_project=null)
    {
    	$id_project = $this->uri->segment(3);
        $cek_project = $this->M_api->get_keadaan('tb_project', array('id_project' => $id_project, ))->num_rows();
        if ($cek_project < 1) {
            $this->response([
                            'status' => FALSE,
                            'pesan' => 'unknow project'
                            ], REST_Controller::HTTP_OK);
        }
    	if ($id_project != null) {
    		$query = $this->M_api->get_join_tim($id_project);
    		if ($query->num_rows() <= 0) {
    			$this->response([
    						'status' => FALSE,
    						'pesan' => 'tim empty'
    						], REST_Controller::HTTP_OK);
    		} else {
    			$data = $query->result();
    			$this->response([
    						'status' => TRUE,
    						'pesan' => $data
    						], REST_Controller::HTTP_OK);
    		}
    	} else {
    		$this->response([
    						'status' => FALSE,
    						'pesan' => 'method unknow'
    						], REST_Controller::HTTP_BAD_REQUEST);
    	}
    }

    public function show_all_sprint_get($id_project = null )
    {
        
    	$id_project = $this->uri->segment(3);
    	if ($id_project != null) {
    		$query =  $this->M_api->get_join_three_table_sprint('tb_project', 'tb_sprint', 'tb_productbacklog','id_project', 'id_pb', 'tb_productbacklog.id_project', $id_project, 'tb_productbacklog.priority','asc');
    		if ($query) {

    			$this->response([
    						'status' => TRUE,
    						'pesan' => $query
    						], REST_Controller::HTTP_OK);
    		} else {
    			$this->response([
    						'status' => FALSE,
    						'pesan' => 'sprint not found'
    						], REST_Controller::HTTP_OK);
    		}
    	} else {
    		$this->response([
    						'status' => FALSE,
    						'pesan' => 'method unknow'
    						], REST_Controller::HTTP_BAD_REQUEST);
    	}
    }

    public function show_todo_sprint_get($id_project = null )
    {
    	$id_project = $this->uri->segment(3);
    	if ($id_project != null) {
            $status = "1";
    		$query =   $this->M_api->get_join_three_table_sprint_status('tb_project', 'tb_sprint', 'tb_productbacklog','id_project', 'id_pb', 'tb_productbacklog.id_project', $id_project, 'tb_productbacklog.priority','asc', $status);
            $jumlah = $this->M_api->sum_join_three_table_sprint_status('tb_project', 'tb_sprint', 'tb_productbacklog','id_project', 'id_pb', 'tb_productbacklog.id_project', $id_project, 'tb_productbacklog.priority','asc', $status);
    		if ($query) {

    			$this->response([
    						'status' => TRUE,
    						'pesan' => $query,
                            'jumlah' => $jumlah
    						], REST_Controller::HTTP_OK);
    		} else {
    			$this->response([
    						'status' => FALSE,
    						'pesan' => 'sprint not found'
    						], REST_Controller::HTTP_OK);
    		}
    	} else {
    		$this->response([
    						'status' => FALSE,
    						'pesan' => 'method unknow'
    						], REST_Controller::HTTP_BAD_REQUEST);
    	}
    }

    public function show_doing_sprint_get($id_project = null )
    {
    	$id_project = $this->uri->segment(3);
    	if ($id_project != null) {
             $status = "2";
    		$query =  $this->M_api->get_join_three_table_sprint_status('tb_project', 'tb_sprint', 'tb_productbacklog','id_project', 'id_pb', 'tb_productbacklog.id_project', $id_project, 'tb_productbacklog.priority','asc', $status);
            $jumlah = $this->M_api->sum_join_three_table_sprint_status('tb_project', 'tb_sprint', 'tb_productbacklog','id_project', 'id_pb', 'tb_productbacklog.id_project', $id_project, 'tb_productbacklog.priority','asc', $status);
    		if ($query) {

    			$this->response([
    						'status' => TRUE,
    						'pesan' => $query,
                            'jumlah' => $jumlah
    						], REST_Controller::HTTP_OK);
    		} else {
    			$this->response([
    						'status' => FALSE,
    						'pesan' => 'sprint not found'
    						], REST_Controller::HTTP_OK);
    		}
    	} else {
    		$this->response([
    						'status' => FALSE,
    						'pesan' => 'method unknow'
    						], REST_Controller::HTTP_BAD_REQUEST);
    	}
    }

    public function show_done_sprint_get($id_project = null )
    {
    	$id_project = $this->uri->segment(3);
    	if ($id_project != null) {
             $status = "3";
    		$query = $this->M_api->get_join_three_table_sprint_status('tb_project', 'tb_sprint', 'tb_productbacklog','id_project', 'id_pb', 'tb_productbacklog.id_project', $id_project, 'tb_productbacklog.priority','asc', $status);
            $jumlah = $this->M_api->sum_join_three_table_sprint_status('tb_project', 'tb_sprint', 'tb_productbacklog','id_project', 'id_pb', 'tb_productbacklog.id_project', $id_project, 'tb_productbacklog.priority','asc', $status);
    		if ($query) {

    			$this->response([
    						'status' => TRUE,
    						'pesan' => $query,
                            'jumlah' => $jumlah
    						], REST_Controller::HTTP_OK);
    		} else {
    			$this->response([
    						'status' => FALSE,
    						'pesan' => 'sprint not found'
    						], REST_Controller::HTTP_OK);
    		}
    	} else {
    		$this->response([
    						'status' => FALSE,
    						'pesan' => 'method unknow'
    						], REST_Controller::HTTP_BAD_REQUEST);
    	}
    }
    public function show_verified_sprint_get($id_project = null )
    {
        $id_project = $this->uri->segment(3);
        if ($id_project != null) {
             $status = "4";
            $query = $this->M_api->get_join_three_table_sprint_status('tb_project', 'tb_sprint', 'tb_productbacklog','id_project', 'id_pb', 'tb_productbacklog.id_project', $id_project, 'tb_productbacklog.priority','asc', $status);
            $jumlah = $this->M_api->sum_join_three_table_sprint_status('tb_project', 'tb_sprint', 'tb_productbacklog','id_project', 'id_pb', 'tb_productbacklog.id_project', $id_project, 'tb_productbacklog.priority','asc', $status);
            if ($query) {

                $this->response([
                            'status' => TRUE,
                            'pesan' => $query,
                            'jumlah' => $jumlah
                            ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                            'status' => FALSE,
                            'pesan' => 'sprint not found'
                            ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                            'status' => FALSE,
                            'pesan' => 'method unknow'
                            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

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
    						], REST_Controller::HTTP_BAD_REQUEST);
    		} else {
    			$query = $this->M_api->get_keadaan('tb_user', $params)->row();
    			$id_user = $query->id_user;
    			
    			$notif = $this->M_api->list_baru('tb_notif', array('tb_notif.id_user'=> $id_user), 3, 'waktu', 'desc');
               
    			if (!$notif) {
    				$this->response([
    						'status' => FALSE,
    						'pesan' => 'empty notif'
    						], REST_Controller::HTTP_OK);
    			} else {
    				$data = $notif;
    				$this->response([
    						'status' => TRUE,
    						'pesan' => $data
    						], REST_Controller::HTTP_OK);
    			}
    			
    		}
    		
    	} else {
    		$this->response([
    						'status' => FALSE,
    						'pesan' => 'method unknow'
    						], REST_Controller::HTTP_BAD_REQUEST);
    	}
    	
    }

    public function sendmail($to,$subject,$message)
    { 
    
    $config = Array(
        'protocol' => 'smtp',
        'smtp_host' => 'ssl://ellie.rapidplex.com',
        'smtp_port' => 465,
        'smtp_user' => 'scrum@alfatech.id', // change it to yours
        'smtp_pass' => 'scrum2016', // change it to yours
        'mailtype' => 'html',
        'charset' => 'iso-8859-1',
        'wordwrap' => TRUE
      );

      $this->load->library('email', $config);
      $this->email->set_newline("\r\n");
      $this->email->from('info@scrum.karanglo.net','No Replay'); // change it to yours
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

         public function recive_token_post()
     {
         # code...
        $token = $this->post('fcm_token');
        $object = ['fcm_token' => $token];
        $insert = $this->db->insert('fcm_info', $object);
        if ($insert) {
            # code...
            $this->response([
                            'status' => TRUE,
                            'pesan' => "server success recive token"
                            ]);
        } else {
            # code...
            $this->response([
                            'status' => FALSE,
                            'pesan' => "invalid recive token"
                            ]);
        }
        
     }
     public function sender_notif_post()
     {
         # code...
        $message = $this->post('message');
        $title = $this->post('title');

        $path_to_fcm = 'https://fcm.googleapis.com/fcm/send';
        $server_key = "AIzaSyAhsXKd8XTAuIz_KqxOJN6DqDg9etMl-TQ";
       // $this->db->order_by('id', 'desc');
        $ambil = $this->db->get('fcm_info', array('nomer' => 1, ))->row();
        print_r($ambil);
        die;
        $key = $ambil[0];
        //echo $key;
        die;
        
            $headers = array(
                    'Authorization:key=' .$server_key ,
                    'Content-Type:application/json'
                    );

            $fields = array('to' =>$key ,
                            'notification' => array('title' =>$title ,
                                                    'body' =>$message ));


            $payload = json_encode($fields);

            $curl_session = curl_init();
            curl_setopt($curl_session, CURLOPT_URL, $path_to_fcm);
            curl_setopt($curl_session, CURLOPT_POST, true);
            curl_setopt($curl_session, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl_session, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($curl_session, CURLOPT_POSTFIELDS, $payload);

            $result = curl_exec($curl_session);

     }

    // public function tes_daily_get()
    // {
    //     $q=$this->db->get_where('tb_message', array('tanggal' => date("y-m-d")  ))->result();
    //     print_r($q);
    // }

}

/* End of file App.php */
/* Location: ./application/controllers/App.php */