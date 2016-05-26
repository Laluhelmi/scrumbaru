<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Project extends REST_Controller {

	public function index()
	{
		
	}

	public function projects_get($id=null)
	{	
		$id=$this->uri->segment(4);
		if ($id!=null) {
			$query=$this->db->get_where('tb_project', array('id_project' => $id, ));
			$data=$query->result();
			$jml=$query->num_rows();
			if ($jml>0) {
				$this->response(['error'=> FALSE,
								'pesan'=> 'data tersedia',
								'data'=>$data
								]);
			} else {
				
				$this->response(['error'=> FALSE,
								'pesan'=> 'data tidak ada',
								'data'=>null]);
			}	

		} else {
			if ($this->session->userdata('is_login')!=TRUE and $this->session->userdata('status')!=2) {
				$this->response(['pesan'=>'not trusted']);
			} else {
				$query=$this->db->get('tb_project');
				$data=$query->result();
				$jml=$query->num_rows();
				if ($jml>0) {
					$this->response(['error'=> FALSE,
						 				'pesan'=>'data tersedia',
						 				'data'=>$data]);
				} else {
					$this->response(['error'=> TRUE,
										'pesan'=>'data kosong',
										'data'=>null]);
				}
			}
			
		}
			
		
	}
	public function projects_by_user_get($id=null)
	{
		$id=$this->uri->segment(4);
		if ($id!=null) {
			$query=$this->db->get_where('tb_project', array('id_user' =>$id , ));
			$data=$query->result();
			$jml=$query->num_rows();
			if ($jml>0) {
				$this->response(['error'=>FALSE,
									'pesan'=>'project by id_user '.$id,
									'data'=>$data]);
			} else {
				$this->response(['pesan'=>'data tidak ada']);
			}
		} else {
			$this->response(['error'=> TRUE,
							'pesan'=>'function not allowed']);
		}
		
		
		
	}

}

/* End of file Project.php */
/* Location: ./application/controllers/Rest/Project.php */