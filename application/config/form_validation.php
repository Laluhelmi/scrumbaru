<?php  defined('BASEPATH') OR exit('No direct script access allowed');
$config = array(
		'kirim_chat_post' => array(
			array('field'=>'message','label'=>'message','rules'=>'trim|required'),
		),
		'reset_pass_post' => array(
			array('field'=>'email','label'=>'email','rules'=>'trim|required|valid_email'),
		),
		'edit_profile_post' => array(
			array('field'=>'username','label'=>'username','rules'=>'trim|required'),
			array('field'=>'first_name','label'=>'first_name','rules'=>'trim|required|alpha_dash|max_length[16]'),
			array('field'=>'last_name','label'=>'last_name','rules'=>'trim|required|alpha_dash|max_length[16]'),
		),
		'login_post' => array(
			array('field'=>'email','label'=>'email','rules'=>'trim|required|valid_email'),
			array('field'=>'password','label'=>'password','rules'=>'trim|required'),
		),
		'register_post' => array(
			array('field'=>'first_name','label'=>'first_name','rules'=>'trim|required'),
			array('field'=>'last_name','label'=>'last_name','rules'=>'trim|required'),
			array('field'=>'email','label'=>'email','rules'=>'trim|required|valid_email'),
			array('field'=>'password','label'=>'password','rules'=>'trim|required|min_length[6]|max_length[12]'),
			array('field'=>'username','label'=>'username','rules'=>'trim|required'),
		),
	);

 ?>