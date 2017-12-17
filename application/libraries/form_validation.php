<?php  defined('BASEPATH') OR exit('No direct script access allowed');
$config = array(
	'student_put'=>array(
		array('field'=>'email_address','label'=>'email_address','rules'=>'trim|required|valid_email'),
		array('field'=>'password','label'=>'password','rules'=>'trim|required|min_length[8]|max_length[16]'),
		array('field'=>'nama_depan','label'=>'nama_depan','rules'=>'trim|required|max_length[16]'),
		array('field'=>'nama_akhir','label'=>'nama_akhir','rules'=>'trim|required|max_length[16]'),
		array('field'=>'phone_number','label'=>'phone_number','rules'=>'trim|required|alpha_dash'),
		),
	'student_post'=>array(
		array('field'=>'email_address','label'=>'email_address','rules'=>'trim|valid_email'),
		array('field'=>'nama_depan','label'=>'nama_depan','rules'=>'trim|max_length[16]'),
		array('field'=>'nama_akhir','label'=>'nama_akhir','rules'=>'trim|max_length[16]'),
		array('field'=>'phone_number','label'=>'phone_number','rules'=>'trim|alpha_dash'),
		),
	'login_post'=>array(
		array('field'=>'password','label'=>'password','rules'=>'trim|required|min_length[8]|max_length[16]'),
		array('field'=>'email','label'=>'email','rules'=>'trim|required|valid_email'),
		),
	);

 ?>
