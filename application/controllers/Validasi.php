<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Validasi extends CI_Controller {

	public function index()
	{
		echo "tampilan login";
	}

	public function kode()
	{
		//$this->load->library('encryption');

		$kode=$this->uri->segment(3);
		
		$email=$this->decrypt($kode);
		if ($email=='pratamasetya98@gmail.com') {
			echo "akun sudah aktif";
		} else {
			redirect('validasi','refresh');
		}
		

	}

	public function enkripsi()
	{
		$this->load->library('encryption');
		$string = "pratamasetya98@gmail.com";
        $encript =  $this->encryption->encrypt($string); //enkripsi string
        $decript = $this->encryption->decrypt($encript); //dekripsi string (mengembalikan string ke semula setelah di enkripsi
 
        echo $encript;
        echo $decript;
	}

	function decrypt($str) {
	    $str = base64_decode(urldecode($str));
	    $hasil = '';
	    $kunci = '979a218e0632df2935317f98d47956c7979a218e0632df2935317f98d47956c7979a218e0632df2935317f98d47956c7979a218e0632df2935317f98d47956c7';
	    for ($i = 0; $i < strlen($str); $i++) {
	        $karakter = substr($str, $i, 1);
	        $kuncikarakter = substr($kunci, ($i % strlen($kunci))-1, 1);
	        $karakter = chr(ord($karakter)-ord($kuncikarakter));
	        $hasil .= $karakter;
	        
	    }
	    return $hasil;
	}

}

/* End of file Validasi.php */
/* Location: ./application/controllers/Validasi.php */