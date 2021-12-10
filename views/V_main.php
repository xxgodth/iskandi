<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $app_info[0]->app_title." | ".$app_info[0]->app_subtitle; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=0.65">
	<link rel="icon" type="image/png" href="./images/simsega.png"/>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<style type="text/css">
		@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');
		
		html, *
		{
			font-family: 'Poppins', sans-serif;
			margin:0;
		}
		
		html
		{
			background:url('images/bg.jpg');
			background-size:cover;
			background-repeat:no-repeat;
			height:100%;
		}
		
		#content
		{
			position:absolute;
			top:47%;
			left:50%;
			transform:translate(-50%, -50%);
			width:100%;
		}
		
		#content p
		{
			color:gray;
			font-size:12px;
		}
		
		#content h2
		{
			color:#6C63FF;
		}
		
		#tbl_main
		{
			width;100%;
		}
		
		#tbl_main td
		{
			width:50%;
		}
		
		#form_login
		{
			margin:15px 0 0;
		}
		
		.left_side
		{
			border-right:2px solid #ccc; 
			padding-right:30px; 
			text-align:right;
		}
		
		.right_side
		{
			padding-left:30px;
			text-align:left;
		}
		
		#msg
		{
			position:fixed;
			top:0;
			left:0;
			width:100%;
			height:100%;
			background:rgba(0,0,0,0.95);
			display:none;
		}
		
		#msg_content
		{
			position:absolute;
			top:50%;
			left:50%;
			transform:translate(-50%, -50%);
			background:white;
			text-align:center;
			padding:30px 0;
			width:30%;
			border-radius:5px;
		}
		
		#msg_content p
		{
			font-size:12px;
		}
		
		.text
		{
			border:1px solid #ccc;
			width:100%;
			padding:8px;
			border-radius:2px;
			outline:none;
			font-size:11px;
			box-sizing:border-box;
		}
		
		.password
		{
			border:1px solid #ccc;
			width:100%;
			padding:8px;
			border-radius:2px;
			outline:none;
			font-size:11px;
			box-sizing:border-box;
			margin:15px 0;
		}
		
		.submit
		{
			border:none;
			background:#6C63FF;
			width:30%;
			padding:8px;
			color:white;
			font-size:11px;
			border-radius:2px;
			cursor:pointer;
			outline:none;
		}
		
		@media screen and (max-width:900px)
		{
			#content
			{
				width:70%;
			}
			
			#tbl_main td
			{
				display:block;
				width:100%;
				padding:0;
				border:0;
			}
			
			.left_side
			{
				text-align:center;
			}
			
			.right_side
			{
				text-align:center;
				margin:20px 0 0;
			}
			
			.submit
			{
				width:100%;
			}
			
			#msg_content
			{
				width:70%;
			}
		}
	</style>
	<script>
		function check_msg()
		{
			var msg = document.getElementById('msg');
				
			<?php $msg = $this->session->flashdata('msg'); ?>
			
			var msg_header = '<?php if(empty($msg['msg_header'])) { echo ""; } else { echo $msg['msg_header']; } ?>';
			var msg_detail = '<?php if(empty($msg['msg_detail'])) { echo ""; } else { echo $msg['msg_detail']; } ?>';
			
			if(msg_header !==  '')
			{
				msg.style.display = 'block';
				
				document.getElementById('msg_header').innerHTML = msg_header;
				document.getElementById('msg_detail').innerHTML = msg_detail;
				
				setTimeout(function()
				{
					msg.style.display = 'none';
				}, 
				3000);
			}
		}
	</script>
</head>
<body onload="check_msg()">
	<div id="container">
		<div id="content">
			<center>
				<table id="tbl_main">
					<tr>
						<td class="left_side">
							<img src="./images/simsega.png" height="210" width="210">
							<h3><?php echo $app_info[0]->app_title; ?></h3>
							<h2><?php echo $app_info[0]->app_subtitle; ?></h2>
							<p style="margin:5px 0 0;"><?php echo $app_info[0]->app_company; ?></p>
						</td>
						<td class="right_side">
							<h3>Kelola Akun</h3>
							<p>Masukkan nama pengguna dan sandi</p>
							<form id="form_login" method="post" action="<?php echo base_url('verifying'); ?>">
								<input type="text" name="username" class="text" placeholder="Nama Pengguna" autocomplete="off" maxlength="30" autofocus required>
								<input type="password" name="password" class="password" placeholder="Sandi" autocomplete="off" maxlength="30" required>
								<input type="submit" class="submit" value="Masuk">
							</form>
						</td>
					</tr>
				</table>
			</center>
		</div>
	</div>
	
	<div id="msg">
		<div id="msg_content">
			<i class="fas fa-info-circle" style="margin:0 0 10px; font-size:35px; color:darkorange;"></i>
			<h4 id="msg_header">Message Title</h4>
			<p id="msg_detail">Message Description</p>
		</div>
	</div>
</body>
</html>