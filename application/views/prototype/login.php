
<form action="<?php echo site_url('prototype/login/do_login') ?>" method="POST">
	<input type="email" name="email" ></input>
	<input type="password" name="password"></input>
	<button type="submit">login</button>
</form>
<?php echo $error ?>