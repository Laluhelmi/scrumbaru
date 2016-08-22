<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dash_project extends CI_Controller {

	public function show()
	{	
		$id = $this->uri->segment(4);
		
		$query = $this->db->get_where('tb_project',array('id_project' => $id));
		$info = $query->row();
		
		$ambil_tim = $this->db->get_where('tb_tim',array('id_project' => $id))->result();


	    $q= $this->db->get_where('tb_chat_room',array('id_project'=> $info->id_project))->row();
	    
	  	$query2 = $this->db->get_where('tb_message', array('id_cr'=>$q->id_cr))->result();


      $ambil_hs = $this->db->get_where('tb_head_s', array('id_project' => $id , ))->row();
      $id_h_s = $ambil_hs->id_h_s;
      $sprint = $this->db->get_where('tb_s', array('id_h_s' => $id_h_s , ))->result();
      $jml_sprint = $this->db->get_where('tb_s', array('id_h_s' => $id_h_s , ))->num_rows();


      #menampilkan todo
      $to_do = $this->db->get_where('tb_s', array('id_h_s' => $id_h_s ,
                                                  'status' => 1 ))->result();
      $sum_to__do = $this->db->get_where('tb_s', array('id_h_s' => $id_h_s ,
                                                  'status' => 1 ))->num_rows();
	  	
       #menampilkan doing
      $doing = $this->db->get_where('tb_s', array('id_h_s' => $id_h_s ,
                                                  'status' => 2 ))->result();
      $sum_doing = $this->db->get_where('tb_s', array('id_h_s' => $id_h_s ,
                                                  'status' => 2 ))->num_rows();

       #menampilkan done
      $done = $this->db->get_where('tb_s', array('id_h_s' => $id_h_s ,
                                                  'status' => 3 ))->result();
      $sum_done = $this->db->get_where('tb_s', array('id_h_s' => $id_h_s ,
                                                  'status' => 3 ))->num_rows();
		$lempar = ['info' => $info,
					'tim' => $ambil_tim,
					'cr' => $q->id_cr,
					'obrolan' => $query2,
          'sprint' => $sprint,
          'jml_sprint' => $jml_sprint,
          'to_do' => $to_do,
          'jml_to_do' => $sum_to__do,
          'doing' => $doing,
          'jml_doing' => $sum_doing,
          'done' => $done,
          'jml_done' => $sum_done];
		$this->load->view('prototype/dash_proj', $lempar, FALSE);
	}

	public function kirim_chat()
    {
    	$id_project = $this->uri->segment(4);
    	
        $id_user=$this->input->post("id_user");
        $message=$this->input->post("message");
        $id_cr=$this->input->post("id_cr");
         
        $object = ['id_user'=>$id_user,
                    'message' => $message,
                    'id_cr'=> $id_cr];
        $query = $this->db->insert('tb_message', $object);
        // mysql_query("insert into chat (user,pesan) VALUES ('$user','$pesan')");
        redirect('prototype/Dash_project/show/'.$id_project,'refresh');
        // redirect ("C_chat/ambil_pesan");
    }
     
    public function ambil_pesan()
    {
        $id_cr = $this->uri->segment(4);
        
                     $this->db->select('*');
                     $this->db->from('tb_message');
                     $this->db->join('tb_user', 'tb_user.id_user = tb_message.id_user');
                     $this->db->where('id_cr', $id_cr);
                     $this->db->order_by('id_message', 'asc');
           $query2 = $this->db->get()->result_array();
          foreach ($query2 as $r) {
            if ($r['username']==$this->session->userdata('username')) {
              echo "
                      <div style='color:rgb(132, 135, 137);background-color:#339DD7;padding:10px;margin:10px' align='right'>
                          
                             <small style='color:#fff'> 
                              $r[message]
                            </small>
                          
                          <small>
                            <h6 style='color:#d4cece;margin-left:20px'>
                              $r[waktu]
                            </h6>
                          </small> 
                      </div> 
                  ";
            } else {
              echo "<li>
                      <div style='color:rgb(132, 135, 137);background-color:#EFEFEF;padding:10px;margin-right:10px'>
                          <b>
                              $r[username]
                          </b> : 
                          <small style='color:#000'> 
                              $r[message]
                          </small>
                          <small>
                            <h6 style='color:#d4cece;margin-left:20px'>
                              $r[waktu]
                            </h6>
                          </small> 
                      </div> 
                  </li>";
            }
            
             
           }
    }

}

/* End of file Dash_project.php */
/* Location: ./application/controllers/prototype/Dash_project.php */