<!DOCTYPE html>
<html>
<head>
	<title>Scrum | <?php echo $info->judul ?></title>
</head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
<body>
<div class="container-fluid">
	<div class="col-md-12" align="center" style="padding: 10px; background-color: rgb(51, 157, 215)">
		<div class="col-md-7" align="right">
			<a href="<?php echo site_url('prototype/dashboard') ?>"><button class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="bottom" title="<?php echo $info->judul ?>">Dashboard Scrum</button></a>
		</div>
		
		<div class="col-md-5" align="right">
			<!-- Button trigger modal -->
			<button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#myModal">
			  Show Team
			</button>

			<!-- Modal -->
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" align="center" id="myModalLabel">Team Project</h4>
			      </div>
			      <div class="modal-body">
			        
						<table class="table">
						
						<tr>
							<th>Nama</th>
							<th>Jabatan</th>
						</tr>
						<?php foreach ($tim as $t): ?>
							<tr>
								<td>
									<?php 
										
										echo $this->db->get_where('tb_user',array('id_user' => $t->id_user))->row()->username;
										 ?>
								</td>
								<td><?php echo $t->jabatan ?></td>
							</tr>
						<?php endforeach ?>
					</table>
					
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
			      </div>
			    </div>
			  </div>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="col-md-6">
			<div class="col-md-12">
				<table class="table" border="0">
					<tr>
						<th colspan="2"></th>
					</tr>
					<tr>
						<td>Judul</td>
						<td>:</td>
						<td><?php echo $info->judul ?></td>
					</tr>
					<tr>
						<td>Deskripsi</td>
						<td>:</td>
						<td><?php echo $info->deskripsi ?></td>
					</tr>
					<tr>
						<td>Estimasi</td>
						<td>:</td>
						<td><?php echo $info->estimasi ?> hari</td>
					</tr>
				</table>
			</div>
			
		</div>
		<div class="col-md-6">
			<div class="col-md-12" style="margin-top: 10px">
				  <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
			        Show Chat
			      </button>
			      	<div class="collapse" id="collapseExample">
			          <div id="divExample" style="height:250px;width:auto;border:1px solid #ccc;font:16px/26px Georgia, Garamond, Serif;overflow:auto;margin-top: 10px">
			             <ul id="isi_chat"> 
			             <!--  <?php foreach ($obrolan as $o): ?>
			             				<li><?php echo $o->id_user ?> : <?php echo $o->message ?></li>
			             <?php endforeach ?>  -->
			              <ul>
			          </div>
			          <!-- <form action="<?php echo site_url('prototype/dash_project/kirim_chat/'.$info->id_project) ?>" method="POST"> -->
			          <div class="col-md-2">
			              <input value="<?php echo $this->session->userdata('id');?>" type="hidden" id="id_user" class="form-control" >
			          </div>
			           <div class="col-md-8" style="margin-top: 10px" align="left">
			              <input value="<?php echo $cr ;?>" type="hidden" id="id_cr" class="form-control">
			          </div>
			          <div class="col-md-8" style="margin-top:0px; padding: 5px" align="left">
			              <input placeholder="message" type="text" id="message" class="form-control">
			          </div>
			          <div class="col-md-2" style="margin-top:0px ;padding: 5px">
			          <input type="button" value="kirim" id="kirim" class="btn btn-md btn-warning"> 
			          <!-- <button class="btn btn-warning btn-md" type="submit">kirim</button> -->
			          </div>
		         <!--  </form> -->
		     		</div>
		    </div>
		</div>
	</div>
	<div class="col-md-12" style="padding: 10px; height: 5px;background-color: #337AB7"></div>
	<div class="col-md-2" >
		<div class="list-group" style="margin-top: 10px">
					 <a href="#" class="list-group-item active">Sprint</a>
					<div class="list-group-item" style="height:300px;width:auto;border:1px solid #ccc;font:16px/26px Georgia, Garamond, Serif;overflow:auto;margin-top: 10px">
						<h4 class="list-group-item-heading">
							<ul class="list-group">

							<?php foreach ($sprint as $s): ?>
								<a href=""><li class="list-group-item"><?php echo $s->judul ?></li></a>
							<?php endforeach ?>
							    
							    
							 </ul>
						</h4>
					</div>
					<div class="list-group-item">
						<!-- <span class="badge">14</span>Help -->
					</div> <a class="list-group-item active"><span class="badge"><?php echo $jml_sprint ?></span>Total : </a>
				</div>
	</div>
	<div class="col-md-2" >
		<div class="list-group" style="margin-top: 10px">
					 <a href="#" class="list-group-item active">To Do</a>
					<div class="list-group-item" style="height:300px;width:auto;border:1px solid #ccc;font:16px/26px Georgia, Garamond, Serif;overflow:auto;margin-top: 10px">
						<h4 class="list-group-item-heading">
							<ul class="list-group">

							<?php foreach ($to_do as $s): ?>
								<a href=""><li class="list-group-item"><?php echo $s->judul ?></li></a>
							<?php endforeach ?>
							    
							    
							 </ul>
						</h4>
					</div>
					<div class="list-group-item">
						<!-- <span class="badge">14</span>Help -->
					</div> <a class="list-group-item active"><span class="badge"><?php echo $jml_to_do ?></span>Total : </a>
				</div>
	</div>
	<div class="col-md-2" >
		<div class="list-group" style="margin-top: 10px">
					 <a href="#" class="list-group-item active">doing</a>
					<div class="list-group-item" style="height:300px;width:auto;border:1px solid #ccc;font:16px/26px Georgia, Garamond, Serif;overflow:auto;margin-top: 10px">
						<h4 class="list-group-item-heading">
							<ul class="list-group">

							<?php foreach ($doing as $s): ?>
								<a href=""><li class="list-group-item"><?php echo $s->judul ?></li></a>
							<?php endforeach ?>
							    
							    
							 </ul>
						</h4>
					</div>
					<div class="list-group-item">
						<!-- <span class="badge">14</span>Help -->
					</div> <a class="list-group-item active"><span class="badge"><?php echo $jml_doing ?></span>Total : </a>
				</div>
	</div>
	<div class="col-md-2" >
		<div class="list-group" style="margin-top: 10px">
					 <a href="#" class="list-group-item active">Done</a>
					<div class="list-group-item" style="height:300px;width:auto;border:1px solid #ccc;font:16px/26px Georgia, Garamond, Serif;overflow:auto;margin-top: 10px">
						<h4 class="list-group-item-heading">
							<ul class="list-group">

							<?php foreach ($done as $s): ?>
								<a href=""><li class="list-group-item"><?php echo $s->judul ?></li></a>
							<?php endforeach ?>
							    
							    
							 </ul>
						</h4>
					</div>
					<div class="list-group-item">
						<!-- <span class="badge">14</span>Help -->
					</div> <a class="list-group-item active"><span class="badge"><?php echo $jml_done ?></span>Total : </a>
				</div>
	</div>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script>    
        $(document).ready(function(){
         
        function tampildata(){
           var objDiv = document.getElementById("divExample");
           objDiv.scrollTop = objDiv.scrollHeight;
           $.ajax({
            type:"POST",
            url:"<?php echo site_url('prototype/dash_project/ambil_pesan/'.$cr);?>",    
            success: function(data){                 
                     $('#isi_chat').html(data);
            }  
           });
        }
   
   
         $('#kirim').click(function(){
           var message = $('#message').val(); 
           var id_user = $('#id_user').val(); 
           var id_cr = $('#id_cr').val(); 
           $.ajax({
            type:"POST",
            url:"<?php echo site_url('prototype/dash_project/kirim_chat/'.$info->id_project);?>",    
            data: 'message=' + message + '&id_cr=' + id_cr +'&id_user=' + id_user,        
            success: function(data){                 
              $('#isi_chat').html(data);
            }  
           });
          });
           
           
          setInterval(function(){
                     tampildata();},1000);
        });
    </script>
</html>