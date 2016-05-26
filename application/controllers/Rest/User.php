<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class User extends REST_Controller {

	public function index()
	{
		
	}

	public function users_get($id=null)
	{
		if ($this->session->userdata('is_login')!= TRUE) {
			$this->response(['pesan'=>'anda belum login']);
		} else {
			$id=$this->uri->segment(4);
			if ($id!=null) {
				if ($this->session->userdata('id')==$id or $this->session->userdata('status')==2) {
					$query=$this->db->get_where('tb_user', array('id_user' => $id, ))->result();
					$this->response([	'error'=>FALSE,
										'pesan'=>'trusted',
										'data'=>$query]);
				} else {
					$this->response(['error'=>TRUE,
						 				'pesan'=>'not trusted']);
				}

			} else {
				if ($this->session->userdata('status')==2) {

					$query=$this->db->get('tb_user');
					$data=$query->result();
					$jml=$query->num_rows();

					if ($jml>0) {
						$this->response([	'error'=>FALSE,
											'pesans'=>$jml.' data',
											'pesan'=>$data,
											'session'=>$this->session->userdata('email')]);
					} else {
						$this->response([ 	'error'=>FALSE,
											'pesans'=>'DATA masih kosong',
											'pesan'=> 0]);
					}
				} else {
					$this->response(['pesan'=>'anda harus login sebagai admin']);
					
				}
				
			}
		}
		
	}

	public function edit_put()
	{
		if ($this->session->userdata('is_login')!=TRUE) {
			$this->response(['pesan'=>'anda belum login']);
		} else {
			$id=$this->uri->segment(4);
			if ($this->session->userdata('id')==$id /*or $this->session->userdata('status')==2*/) {
				$object=['username'=>$this->put('username'),
							'password'=>md5($this->put('password')),
							'first_name'=>$this->put('first_name'),
							'last_name'=>$this->put('last_name'),
							'display_pic'=>'change pic'];
						$this->db->where('id_user',$id);
				$query =$this->db->update('tb_user', $object);
				$data=$this->db->get_where('tb_user', array('id_user' => $id, ))->result();
				$this->response([	'error'=>FALSE,
									'pesan'=>'update sukses',
									'data'=>$data]);
			} else {
				$this->response(['error'=>TRUE,
					 				'pesan'=>'update gagal']);
			}
				
			
		}
		
	}

}

/* End of file User.php */
/* Location: ./application/controllers/Rest/User.php */