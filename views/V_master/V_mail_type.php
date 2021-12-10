<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$session = $this->session->userdata('session');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $app_info[0]->app_title." | ".$app_info[0]->app_subtitle; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=0.65">
	<link rel="icon" type="image/png" href="./images/simsega.png"/>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<style type="text/css">
		#content
		{
			margin:40px 50px;
		}
		
		#content a
		{
			text-decoration:none;
		}
		
		#div_tbl_data
		{
			border:1px solid #f2f2f2;
			overflow:auto;
		}
		
		#form_search
		{
			margin:15px 0 10px;
		}
		
		#tbl_search td
		{
			font-size:11px;
		}
		
		#tbl_search, #tbl_data, #tbl_popup, #tbl_form_forward
		{
			border-collapse:collapse;
			width:100%;
		}
		
		#tbl_data th
		{
			font-size:11px;
			background:#6C63FF;
			padding:8px;
			color:white;
			font-weight:normal;
		}
		
		#tbl_data td
		{
			font-size:11px;
			text-align:center;
			padding:8px;
		}
		
		#tbl_data tr:nth-child(odd)
		{
			background:#ccc;
		}
		
		#tbl_data tr:nth-child(even)
		{
			background:#f2f2f2;
		}
		
		#popup, #form_forward
		{
			position:fixed;
			top:0;
			left:0;
			width:100%;
			height:100%;
			background:rgba(0,0,0,0.95);
			display:none;
		}
		
		#popup_content, #form_forward_content
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
		
		#popup_header, #form_forward_header
		{
			background:#6C63FF;
			padding:20px 30px;
			color:white;
		}
		
		#popup_header p, #form_forward_header p
		{
			font-size:12px;
		}
		
		#popup_detail, #form_forward_detail
		{
			padding:5px 30px 30px;
		}
		
		#tbl_popup td, #tbl_form_forward td
		{
			font-size:10px;
			width:25%;
		}
		
		#div_view_file
		{
			display:none;
			background:rgba(0,0,0,0.95);
			position:fixed;
			top:0;
			left:0;
			height:100%;
			width:100%;
			z-index:100;
		}
		
		#view_file
		{
			background:white;
			position:absolute;
			top:50%;
			left:50%;
			transform:translate(-50%, -50%);
			overflow:auto;
			height:80%;
			width:50%;
			border:none;
			padding:20px;
		}
		
		.text_search
		{
			border:1px solid #ccc;
			width:100%;
			padding:5px;
			border-radius:2px;
			outline:none;
			font-size:11px;
			box-sizing:border-box;
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
		
		.textarea
		{
			border:1px solid #ccc;
			width:100%;
			height:80px;
			padding:5px;
			border-radius:2px;
			outline:none;
			font-size:11px;
			box-sizing:border-box;
			resize:none;
		}
		
		.file
		{
			border:1px solid #ccc;
			width:100%;
			padding:5px;
			border-radius:2px;
			outline:none;
			font-size:11px;
			box-sizing:border-box;
		}
		
		.green
		{
			color:#34A853;
		}
		
		.red
		{
			color:#EA4335;
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
		
		.unduh
		{
			float:right;
			border:none;
			background:#6C63FF;
			padding:8px;
			color:white;
			font-size:10px;
			border-radius:2px;
			cursor:pointer;
			outline:none;
			display:none;
		}
		
		.active_page
		{
			background:darkorange;
			color:white;
			padding:5px 10px;
			border-radius:5px;
		}
		
		.inactive_page
		{
			background:#ccc;
			color:white;
			padding:5px 10px;
			border-radius:5px;
		}
		
		@media screen and (max-width:900px)
		{
			#popup_content
			{
				width:70%;
			}
			
			#tbl_data
			{
				width:150%;
			}
			
			#tbl_popup td
			{
				display:block;
				width:100%;
			}
		}
	</style>
	<script>
		function show_popup_form(act, ml_typ_id, ml_typ_nm)
		{
			var popup = document.getElementById('popup');
			popup.style.display = 'block';
			
			var popup_title = document.getElementById('popup_title');
			popup_title.innerHTML = act;
			
			var popup_subtitle = document.getElementById('popup_subtitle');
			popup_subtitle.innerHTML = '<?php echo strtoupper($app_info[0]->app_subtitle); ?>';
			
			var act_name = document.getElementById('act');
			act_name.value = act;
			
			var type_id = document.getElementById('type_id');
			type_id.value = (act == "TAMBAH JENIS SURAT") ? "" : ml_typ_id;
			
			var type_name = document.getElementById('type_name');
			type_name.value = (act == "TAMBAH JENIS SURAT") ? "" : ml_typ_nm;
			type_name.readOnly = (act == "HAPUS JENIS SURAT") ? true : false;
			
			var btn_submit = document.getElementById('btn_submit');
			btn_submit.value = (act == "HAPUS JENIS SURAT") ? "Hapus" : "Simpan";
		}
		
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
	<?php $this->load->view('V_menu'); ?>
	<div id="container">
		<div id="content">
			<h4>Master Jenis Surat</h4>
			<?php if($session[0]->level_id == "1") { ?>
			<span style="float:right; font-size:11px; cursor:pointer;" onclick="show_popup_form('TAMBAH JENIS SURAT')">
					<i class="fas fa-plus-square green"></i>&nbsp;Tambah Jenis Surat
			</span>
			<?php } ?>
			<p style="color:gray; font-size:12px;">Menampilkan jenis surat aktif</p>
			<form id="form_search" method="get" action="<?php echo base_url('mail_type'); ?>">
				<table id="tbl_search">
					<tr>
						<td>
							Jenis Surat<br>
							<input type="text" name="src" class="text_search" value="<?php echo (!empty($src)) ? $src : ""; ?>" placeholder="Masukkan nama level untuk melakukan pencarian" autocomplete="off" autofocus>
						</td>
					</tr>
				</table>
			</form>
			<div id="div_tbl_data">
				<table id="tbl_data">
					<tr>
						<th>No</th>
						<th>Jenis Surat</th>
						<th>Aksi</th>
					</tr>
					<?php
						$no = (empty($this->input->get('page')) or $this->input->get('page') == 1) ? 1 : $this->input->get('page')*20-20+1;
						
						if(empty($master_mail_type))
						{
							echo '
								<tr>
									<td colspan="3" style="text-align:left;">Data tidak ditemukan</td>
								</tr>
							';
						}
						else
						{
							foreach($master_mail_type as $rec)
							{
								$chg = "'UBAH JENIS SURAT'";
								$del = "'HAPUS JENIS SURAT'";
								
								$param = array(
									'type_id' => "'".$rec->type_id."'",
									'type_name' => "'".$rec->type_name."'"
								);
								
								echo '
									<tr>
										<td>'.$no++.'</td>
										<td>'.$rec->type_name.'</td>
										<td>
											<i class="fas fa-pen-square green" style="font-size:13px; cursor:pointer;" onclick="show_popup_form('.$chg.','.implode(",", $param).')"></i>
												&nbsp;
												<i class="fas fa-minus-square red" style="font-size:13px; cursor:pointer;" onclick="show_popup_form('.$del.','.implode(",", $param).')"></i>
										</td>
									</tr>
								';
							}
						}
					?>
				</table>
			</div>
			<div style="margin:25px 0 0; text-align:right;">
				<h6 style="font-size:7px;">
					<?php
						if(!empty($size_master_mail_type))
						{
							$page = ceil($size_master_mail_type/20);
							
							for($i=1; $i<= $page; $i++)
							{
								if(empty($this->input->get('page')) && $i == 1)
								{
									echo '<a href="'.base_url('mail_type').'?src='.$src.'&page='.$i.'" class="active_page">'.$i."</a> ";
								}
								else if($i == $this->input->get('page'))
								{
									echo '<a href="'.base_url('mail_type').'?src='.$src.'&page='.$i.'" class="active_page">'.$i."</a> ";
								}
								else
								{
									echo '<a href="'.base_url('mail_type').'?src='.$src.'&page='.$i.'" class="inactive_page">'.$i."</a> ";
								}
							}
						}
						else
						{
							echo "";
						}
					?>
				</h6>
			</div>
		</div>
	</div>
	
	<div id="popup">
		<div id="popup_content">
			<div id="popup_header">
				<h4 id="popup_title">Message Title</h4>
				<p id="popup_subtitle">LOREM IPSUM SIT DOLOR</p>
			</div>
			<div id="popup_detail">
				<form method="post" action="<?php echo base_url('saving_mail_type'); ?>" enctype="multipart/form-data">
					<input type="hidden" class="text" id="act" name="act">
					<input type="hidden" class="text" id="type_id" name="type_id">
					<table id="tbl_popup">
						<tr>
							<td>
								<br>Jenis Surat<br>
								<input type="text" class="text" id="type_name" name="type_name" autocomplete="off" required>
							</td>
						</tr>
						<tr>
							<td>
								<br>
								<input id="btn_submit" type="submit" class="submit" value="Simpan" style="color:white;">
								<button type="button" class="cancel" onclick="document.getElementById('popup').style.display='none';">Tutup</button>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>
</body>
</html>