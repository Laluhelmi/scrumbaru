<!DOCTYPE html>
<html>
<head>
	<title>Dashboard Projek Scrum</title>
</head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->

<body>
	
	<div class="col-md-2">
		<label>Token </label>
		<input type="password" class="form-control" value="<?php echo $this->session->userdata('token'); ?>"></input>
		<a href="<?php echo site_url('prototype/login/logout') ?>"> Logout </a> <br>
	</div>
	
	<br>
	<br>
	<div class="col-md-10">
	<h4>List Of Project :</h4>
	<!-- <?php foreach ($projek as $q): ?>
			<?php $ambil_project = $this->db->get_where('tb_project',array('id_project' => $q->id_project))->result(); ?>
			<?php foreach ($ambil_project as $key): ?>
				
					<div class="col-md-3">
						<a href="<?php echo site_url('prototype/dash_project/show/'.$key->id_project) ?>">
							<button class="btn btn-primary btn-lg" type="button">
							  <?php echo $key->judul; ?> <span class="badge">doing</span>
							</button>
						</a>
					</div>
				
			<?php endforeach ?>
	<?php endforeach ?> -->


		<?php foreach ($projek as $q): ?>
			<div class="col-md-3">
						<a href="<?php echo site_url('prototype/dash_project/show/'.$q->id_project) ?>">
							<button class="btn btn-primary btn-lg" type="button">
							  <?php echo $q->judul; ?> <span class="badge">doing</span>
							</button>
						</a>
					</div>
		<?php endforeach ?>
	</div>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</html>