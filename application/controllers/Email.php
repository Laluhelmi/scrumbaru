<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Email extends REST_Controller {

	public function index()
	{
		
	}

	public function register_post()
	{
	   $this->load->library('email');
	   $this->load->helper('email');
	   $this->load->library('encryption');
	   //menagkap dari inputan
	   $f='pratamasetya98@gmail.com';
	   $t=$this->post('to');
	   $s=$this->post('subject');
	   $e=$this->encrypt($f);
	   $url='http://localhost/servicescrum/validasi/kode/';
	   $view='isi email';
	   $i=$view;
	   //$i=$this->post('isi');

	   //cek valid email
	   if (valid_email($t)) {
	   	   	//$this->response('email valid');
	   		 //konfigurasi email
		   $config = array();
		   $config['charset'] = 'utf-8';
		   $config['useragent'] = 'Codeigniter'; //bebas sesuai keinginan kamu
		   $config['protocol']= "smtp";
		   $config['mailtype']= "html";
		   $config['smtp_host']= "ssl://smtp.gmail.com";
		   $config['smtp_port']= "465";
		   $config['smtp_timeout']= "5";
		   $config['smtp_user']= "pratamasetya98@gmail.com";              //isi dengan email anda
		   $config['smtp_pass']= "20091994";            // isi dengan password dari email anda
		   $config['crlf']="\r\n";
		   $config['newline']="\r\n";
		 
		   $config['wordwrap'] = TRUE;


		   //memanggil library email dan set konfigurasi untuk pengiriman email
		   
		   $this->email->initialize($config);
		 //konfigurasi pengiriman kotak di view ke pengiriman email di gmail
		   $this->email->from($f);
		   $this->email->to($t);
		   $this->email->subject($s);
		   $this->email->message($i);

		   if($this->email->send())
		   {
		    	$this->response('sukses');
		   }else
		   {
		    	$this->response('gagal');
		   }

	   } else {
	   		$this->response('emailu kurang @');
	   }

	   
   
	  
	}

	public function mail_to_get()
	{
		 $to = "pratamasetya98@gmail.com";
         $subject = "This is subject";
         
         $message = "<b>This is HTML message.</b>";
         $message .= "<h1>This is headline.</h1>";
         
         $header = "From:pratamasetya98@gmail.com \r\n";
         $header .= "Cc:afgh@somedomain.com \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";
         
         $kirim = mail ($to,$subject,$message,$header);
        
		
		if (!$kirim) {
			$this->response('gagal');
		} else {
			$this->response('sukses');
		}
		
	}

	function encrypt($str) {
    $kunci = 'scrum%&%($($%&$*%^$*^$*%';
    $hasil = '';
    for ($i = 0; $i < strlen($str); $i++) {
        $karakter = substr($str, $i, 1);
        $kuncikarakter = substr($kunci, ($i % strlen($kunci))-1, 1);
        $karakter = chr(ord($karakter)+ord($kuncikarakter));
        $hasil .= $karakter;
        
    }
    return urlencode(base64_encode($hasil));
	}
	 
	function decrypt($str) {
	    $str = base64_decode(urldecode($str));
	    $hasil = '';
	    $kunci = '979a218e0632df2935317f98d47956c7';
	    for ($i = 0; $i < strlen($str); $i++) {
	        $karakter = substr($str, $i, 1);
	        $kuncikarakter = substr($kunci, ($i % strlen($kunci))-1, 1);
	        $karakter = chr(ord($karakter)-ord($kuncikarakter));
	        $hasil .= $karakter;
	        
	    }
	    return $hasil;
	}

}

/* End of file Email.php */
/* Location: ./application/controllers/Email.php */