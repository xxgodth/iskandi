<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

$session = $this->session->userdata('session');

$hari = array('Sun' => 'MINGGU', 'Mon' => 'SENIN', 'Tue' => 'SELASA', 'Wed' => 'RABU', 'Thu' => 'KAMIS', 'Fri' => "JUM'AT", 'Sat' => 'SABTU');
$bulan = array('Jan' => 'JAN', 'Feb' => 'FEB', 'Mar' => 'MAR', 'Apr' => 'APR', 'May' => 'MEI', 'Jun' => 'JUN', 'Jul' => 'JUL', 'Aug' => 'AGU', 'Sep' => 'SEP', 'Oct' => 'OKT', 'Nov' => 'NOV', 'Dec' => 'DEC');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link href="https://fonts.googleapis.com/css2?family=Heebo:wght@500&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<style type="text/css">
		@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');
		
		html, *
		{
			font-family: 'Poppins', sans-serif;
			margin:0;
		}
		
		hr
		{
			border:1px solid #f2f2f2;
		}
		
		input:read-only, textarea:read-only
		{
			color:gray;
		}
		
		#header
		{
			background:#6C63FF;
			padding:20px;
			color:white;
		}
		
		#header span
		{
			float:right;
			font-size:11px;
			margin:4px 0 0 0;
		}
		
		#header a
		{
			text-decoration:none;
			color:white;
		}
		
		#menu
		{
			display:none;
			position:fixed;
			top:0;
			left:0;
			height:100%;
			background:white;
			box-shadow:0 0 10px gray;
		}
		
		#menu a
		{
			text-decoration:none;
			color:black;
		}
		
		.menu_header
		{
			padding:20px 50px;
			text-align:center;
			color:#6C63FF;
			background:url('images/bg.jpg');
			background-size:cover;
			background-repeat:no-repeat;
		}
		
		.menu_header p
		{
			font-size:11px;
			color:gray;
			line-height:20px;
		}
		
		.menu_content
		{
			padding:30px;
			line-height:18px;
		}
		
		.menu_content a h6
		{
			margin:3px 0;
			color:black;
			font-weight:normal;
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
			z-index:100;
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
		
		#msg_content p, #notif_content p
		{
			font-size:12px;
		}
		
		#notif
		{
			position:fixed;
			bottom:50px;
			right:65px;
			background:#ffd633;
			padding:20px;
			width:350px;
			border-radius:5px;
			display:none;
		}
		
		#app, #user_profile, #form_rekap
		{
			position:fixed;
			top:0;
			left:0;
			width:100%;
			height:100%;
			background:rgba(0,0,0,0.95);
			display:none;
			z-index:200;
		}
		
		#app_content, #user_profile_content, #form_rekap_content
		{
			position:absolute;
			top:50%;
			left:50%;
			transform:translate(-50%, -50%);
			background:white;
			overflow:auto;
			border-radius:3px;
			width:55%;
		}
		
		#app_header, #user_profile_header, #form_rekap_header
		{
			background:#6C63FF;
			padding:20px 30px;
			color:white;
		}
		
		#app_header p, #user_profile_header p, #form_rekap_header p
		{
			font-size:12px;
		}
		
		#app_detail, #user_profile_detail, #form_rekap_detail
		{
			padding:5px 30px 30px;
		}
		
		#tbl_app
		{
			width:100%;
		}
		
		#tbl_app td, #tbl_user_profile td, #tbl_form_rekap td
		{
			font-size:10px;
			width:25%;
		}
		
		.text
		{
			border:1px solid #ccc;
			width:100%;
			padding:5px;
			border-radius:2px;
			outline:none;
			font-size:11px;
			box-sizing:border-box;
		}
		
		.password
		{
			border:1px solid #ccc;
			width:100%;
			padding:5px;
			border-radius:2px;
			outline:none;
			font-size:11px;
			box-sizing:border-box;
		}
		
		.date
		{
			border:1px solid #ccc;
			width:100%;
			padding:4px;
			border-radius:2px;
			outline:none;
			font-size:11px;
			box-sizing:border-box;
			background:white;
		}
		
		.select
		{
			border:1px solid #ccc;
			width:100%;
			padding:4.5px;
			border-radius:2px;
			outline:none;
			font-size:11px;
			box-sizing:border-box;
			cursor:pointer;
			background:white;
		}
		
		.submit
		{
			border:none;
			background:#6C63FF;
			padding:8px;
			width:100px;
			color:white;
			font-size:10px;
			border-radius:2px;
			cursor:pointer;
			outline:none;
		}
		
		.cancel
		{
			border:none;
			background:#ccc;
			padding:8px;
			width:100px;
			font-size:10px;
			border-radius:2px;
			cursor:pointer;
			outline:none;
		}
		
		@media screen and (max-width:900px)
		{
			#app_content, #form_rekap_content, #user_profile_content
			{
				width:70%;
			}
			
			#tbl_app td
			{
				display:block;
				width:100%;
			}
		}
	</style>
	<script>
		var user_id = '<?php echo $session[0]->row_id; ?>';
		var user_level = '<?php echo $session[0]->level_id; ?>';
		
		var interval = setInterval(function()
		{
			if(user_level !== "1")
			{
				get_mail_inbox(user_id);
			}
		}, 
		5000);
		
		function show_menu()
		{
			document.getElementById('menu').style.display = 'block';
			document.getElementById('menu').className = 'animated slideInLeft';
		}
		
		function hide_menu()
		{
			document.getElementById('menu').className = 'animated slideOutLeft';
		}
		
		function get_mail_inbox(user_id)
		{
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function()
			{
				if (this.readyState == 4 && this.status == 200)
				{
					var result = JSON.parse(xhttp.responseText).result;
					
					if(result > 0)
					{
						clearInterval(interval);
						
						var audio = new Audio('./sound/notif.wav');
						
						var notif = document.getElementById('notif');
						notif.style.display = 'block';
						notif.className = 'animated fadeInUp';
						
						audio.play();
						
						var notif_header = document.getElementById('notif_header');
						notif_header.innerHTML = '<b>Informasi !</b> '+'Anda memiliki '+result+' surat yang belum dibaca';
						
						setTimeout(function()
						{
							notif.className = 'animated fadeOutDown';
							
							interval = setInterval(function()
							{
								get_mail_inbox(user_id);
							}, 
							5000);
						}, 
						10000);
					}
				}
			};
			xhttp.open("GET", "<?php echo base_url('get_mail_inbox'); ?>"+'?user_id='+user_id, true);
			xhttp.send();
		}
		
		function show_app_info()
		{
			var app = document.getElementById('app');
			app.style.display = 'block';
			
			var app_title = document.getElementById('app_title');
			app_title.innerHTML = 'PENGATURAN APLIKASI';
			
			var app_subtitle = document.getElementById('app_subtitle');
			app_subtitle.innerHTML = '<?php echo strtoupper($app_info[0]->app_subtitle); ?>';
		}
		
		function show_user_profile()
		{
			var user_profile = document.getElementById('user_profile');
			user_profile.style.display = 'block';
			
			var user_profile_title = document.getElementById('user_profile_title');
			user_profile_title.innerHTML = 'PENGATURAN PROFIL';
			
			var user_profile_subtitle = document.getElementById('user_profile_subtitle');
			user_profile_subtitle.innerHTML = '<?php echo strtoupper($app_info[0]->app_subtitle); ?>';
		}
		
		function show_rekap()
		{
			var form_rekap = document.getElementById('form_rekap');
			form_rekap.style.display = 'block';
			
			var form_rekap_title = document.getElementById('form_rekap_title');
			form_rekap_title.innerHTML = 'REKAP SURAT';
			
			var form_rekap_subtitle = document.getElementById('form_rekap_subtitle');
			form_rekap_subtitle.innerHTML = '<?php echo strtoupper($app_info[0]->app_subtitle); ?>';
		}
	</script>
</head>
<body>
	<div id="header">
		<span>
			<i class="fas fa-circle" style="font-size:10px;"></i>&nbsp;<?php echo $hari[date("D")].", ".date("d")." ".$bulan[date("M")]." ".date("Y");?>&nbsp; &nbsp; 
			<i class="fas fa-user-circle"></i>&nbsp;<?php echo strtoupper($session[0]->username); ?> &nbsp; 
			<a href="<?php echo base_url('logout'); ?>">
				<i class="fas fa-arrow-alt-circle-right"></i>&nbsp;KELUAR
			</a>
		</span>
		<h4>
			<i class="fas fa-bars" style="font-size:17px; margin-right:5px; cursor:pointer;" title="Menu" onclick="show_menu()"></i>
			SISTEM INFORMASI KELOLA NASKAH DINAS
		</h4>
	</div>
	
	<div id="menu">
		<div class="menu_header">
			<img src="./images/simsega.png" height="90" width="90">
			<h5>SISTEM INFORMASI KELOLA NASKAH DINAS</h5>
			<p>
				<?php echo strtoupper($app_info[0]->app_company); ?><br>
				<?php echo strtoupper($session[0]->username); ?>
			</p>
		</div>
		<div class="menu_content">
			<h6>MASTER</h6>
			<a href="<?php echo base_url('mail'); ?>">
				<h6>Surat Masuk</h6>
			</a>
			<?php if($session[0]->level_id == "1") { ?>
			<a href="<?php echo base_url('mail_outbox'); ?>">
				<h6>Surat Keluar</h6>
			</a>
			<a href="<?php echo base_url('company'); ?>">
				<h6>Instansi</h6>
			</a>
			<a href="<?php echo base_url('mail_type'); ?>">
				<h6>Jenis Surat</h6>
			</a>
			<a href="<?php echo base_url('mail_level'); ?>">
				<h6>Level Surat</h6>
			</a>
			<a href="<?php echo base_url('disposisi'); ?>">
				<h6>Disposisi</h6>
			</a>
			<br>
			<h6>REKAP SURAT</h6>
			<a href="#" onclick="show_rekap()">
				<h6>Unduh Rekap Surat</h6>
			</a>
			<br>
			<h6>PENGATURAN</h6>
			<a href="#" onclick="show_user_profile()">
				<h6>Profil</h6>
			</a>
			<a href="#" onclick="show_app_info()">
				<h6>Aplikasi</h6>
			</a>
			<?php } else { ?>
			<br>
			<h6>PENGATURAN</h6>
			<a href="#" onclick="show_user_profile()">
				<h6>Profil</h6>
			</a>
			<?php } ?>
			<br>
			<h6>LAINNYA</h6>
			<a href="<?php echo base_url('logout'); ?>">
				<h6>Keluar</h6>
			</a>
		</div>
		
		<div style="position:absolute; top:0; left:0; width:100%;">
			<div style="text-align:right; padding:20px;">
				<a href="#" onclick="hide_menu()">
					<h6><i class="fas fa-times-circle" style="color:tomato; font-size:17px;" title="Tutup Menu"></i></h6>
				</a>
			</div>
		</div>
	</div>
	
	<div id="msg">
		<div id="msg_content">
			<i class="fas fa-info-circle" style="margin:0 0 10px; font-size:35px; color:darkorange;"></i>
			<h4 id="msg_header">Message Title</h4>
			<p id="msg_detail">Message Description</p>
		</div>
	</div>
	
	<div id="notif">
		<div id="notif_content">
			<i class="fas fa-info-circle animated pulse infinite" style="float:left; margin-right:5px;"></i>
			<p id="notif_header" style="font-size:11px;">Message Title</p>
		</div>
	</div>
	
	<div id="app">
		<div id="app_content">
			<div id="app_header">
				<h4 id="app_title">Message Title</h4>
				<p id="app_subtitle">LOREM IPSUM SIT DOLOR</p>
			</div>
			<div id="app_detail">
				<form method="post" action="<?php echo base_url('saving_app_info'); ?>" enctype="multipart/form-data">
					<table id="tbl_app">
						<tr>
							<td>
								<br>Judul Aplikasi<br>
								<input type="text" class="text" id="app_title" name="app_title" value="<?php echo $app_info[0]->app_title; ?>" autocomplete="off" required>
							</td>
						</tr>
						<tr>
							<td>
								<br>Sub Judul Aplikasi<br>
								<input type="text" class="text" id="app_subtitle" name="app_subtitle" value="<?php echo $app_info[0]->app_subtitle; ?>" autocomplete="off" required>
							</td>
						</tr>
						<tr>
							<td>
								<br>Nama Instansi<br>
								<input type="text" class="text" id="app_company" name="app_company" value="<?php echo $app_info[0]->app_company; ?>" autocomplete="off" required>
							</td>
						</tr>
						<tr>
							<td>
								<br>
								<input type="submit" class="submit" value="Simpan" style="color:white;">
								<button type="button" class="cancel" onclick="document.getElementById('app').style.display='none';">Tutup</button>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>
	
	<div id="user_profile">
		<div id="user_profile_content">
			<div id="user_profile_header">
				<h4 id="user_profile_title">Message Title</h4>
				<p id="user_profile_subtitle">LOREM IPSUM SIT DOLOR</p>
			</div>
			<div id="user_profile_detail">
				<form method="post" action="<?php echo base_url('saving_user_profile'); ?>" enctype="multipart/form-data">
					<table id="tbl_user_profile">
						<tr>
							<td>
								<br>Nama Pengguna<br>
								<input type="hidden" class="text" id="user_id" name="user_id" value="<?php echo $session[0]->row_id; ?>">
								<input type="text" class="text" id="user_name" name="user_name" value="<?php echo $session[0]->username; ?>" autocomplete="off" required>
							</td>
						</tr>
						<tr>
							<td>
								<br>Sandi Baru<br>
								<input type="password" class="password" id="user_pass" name="user_pass" required>
							</td>
						</tr>
						<tr>
							<td>
								<br>
								<input type="submit" class="submit" value="Simpan" style="color:white;">
								<button type="button" class="cancel" onclick="document.getElementById('user_profile').style.display='none';">Tutup</button>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>
	
	<div id="form_rekap">
		<div id="form_rekap_content">
			<div id="form_rekap_header">
				<h4 id="form_rekap_title">Message Title</h4>
				<p id="form_rekap_subtitle">LOREM IPSUM SIT DOLOR</p>
			</div>
			<div id="form_rekap_detail">
				<form method="post" action="<?php echo base_url('download_rekap'); ?>">
					<table id="tbl_form_rekap">
						<tr>
							<td>
								<br>Tanggal Surat (Mulai) *<br>
								<input type="date" class="date" name="rekap_from" value="<?php echo date("Y-m-d"); ?>" required>
							</td>
						</tr>
						<tr>
							<td>
								<br>Tanggal Surat (Sampai) *<br>
								<input type="date" class="date" name="rekap_to" value="<?php echo date("Y-m-d"); ?>" required>
							</td>
						</tr>
						<tr>
							<td>
								<br>Jenis Surat *<br>
								<select class="select" name="mail_type" required>
									<option value="">Pilih</option>
									<option value="Surat Masuk">Surat Masuk</option>
									<option value="Surat Keluar">Surat Keluar</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>
								<br>Ekspor Ke *<br>
								<select class="select" name="rekap_type" required>
									<option value="">Pilih</option>
									<option value="EXCEL">EXCEL</option>
									<option value="PDF">PDF</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>
								<br>
								<input type="submit" class="submit" value="Unduh" style="color:white;">
								<button type="button" class="cancel" onclick="document.getElementById('form_rekap').style.display='none';">Tutup</button>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>
</body>
</html>