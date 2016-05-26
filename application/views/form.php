
<html xmlns="http://www.w3.org/1999/xhtml">

<title>Papermashup.com | jQuery And PHP Dynamic forms</title>
	
	<style>
	input{
		border:1px solid #ccc;
		padding:8px;
		font-size:14px;
		width:300px;
		}
	.submit{
		width:110px;
		background-color:#FF6;
		padding:3px;
		border:1px solid #FC0;
		margin-top:20px;}	

	</style>
</head>
<body>
	<div id="inner_wrap">
		<div class="dynamic-form">
			<a href="#" id="add">Add</a> | <a href="#" id="remove">Remove</a>  | <a href="#" id="reset">Reset</a>
			<form>
			<div class="inputs">
			<div><input type="text" name="dynamic[]" class="field" value="1"/></div>
			</div>
			<input name="submit" type="button" class="submit" value="Submit" />
			</form>
		</div>
	</div>
</body>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js"></script>
	<script>
	$(document).ready(function(){


		var i = $('input').size() + 1;
		
		$('#add').click(function() {
			$('<div><input type="text" class="field" name="dynamic[]" value="' + i + '" /></div>').fadeIn('slow').appendTo('.inputs');
			i++;
		});
		
		$('#remove').click(function() {
		if(i > 1) {
			$('.field:last').remove();
			i--; 
		}
		});
		
		$('#reset').click(function() {
		while(i > 2) {
			$('.field:last').remove();
			i--;
		}
		});
		

	// here's our click function for when the forms submitted
		
		$('.submit').click(function(){
									
		
		var answers = [];
		var url ='<?php echo base_url('ajax/tangkap') ?>';
		var method='POST';
	    $.each($('.field'), function() {
	        answers.push($(this).val()); 
	    });
		
	    if(answers.length == 0) { 
	        answers = "none"; 
	    }   

		//alert(answers);
		
		
		return false;
									
		});
	});
	</script>
</html>