<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Token {

	public function index()
	{
		
	}

	function encrypt($str) {
    $kunci = 'scrum';
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
	    $kunci = 'scrum';
	    for ($i = 0; $i < strlen($str); $i++) {
	        $karakter = substr($str, $i, 1);
	        $kuncikarakter = substr($kunci, ($i % strlen($kunci))-1, 1);
	        $karakter = chr(ord($karakter)-ord($kuncikarakter));
	        $hasil .= $karakter;
	        
	    }
	    return $hasil;
	}

}

/* End of file Token.php */
/* Location: ./application/libraries/Token.php */