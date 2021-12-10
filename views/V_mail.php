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
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
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
			margin:0 0 10px;
		}
		
		#tbl_search td
		{
			font-size:11px;
		}
		
		#tbl_search, #tbl_data, #tbl_popup, #tbl_form_forward, #tbl_form_forward_kepala
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
		
		#popup, #form_forward, #form_forward_kepala
		{
			position:fixed;
			top:0;
			left:0;
			width:100%;
			height:100%;
			background:rgba(0,0,0,0.95);
			display:none;
		}
		
		#popup_content, #form_forward_content, #form_forward_kepala_content
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
		
		#popup_header, #form_forward_header, #form_forward_kepala_header
		{
			background:#6C63FF;
			padding:20px 30px;
			color:white;
		}
		
		#popup_header p, #form_forward_header p, #form_forward_kepala_header p
		{
			font-size:12px;
		}
		
		#popup_detail, #form_forward_detail, #form_forward_kepala_detail
		{
			padding:5px 30px 30px;
		}
		
		#tbl_popup td, #tbl_form_forward td, #tbl_form_forward_kepala td
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
		
		select 
		{
			width:100%;
			border:1px solid #ccc;
			font-size:11px;
			outline:none;
		}

		.select-checkbox option::before
		{
			content: "\2610";
			width: 1.3em;
			text-align: center;
			display: inline-block;
		}

		.select-checkbox option:checked::before
		{
			content: "\2611";
		}

		.select-checkbox-fa option::before
		{
			font-family: FontAwesome;
			content: "\f096";
			width: 1.3em;
			display: inline-block;
			margin-left: 2px;
		}

		.select-checkbox-fa option:checked::before 
		{
			content: "\f046";
		}
		
		#tbl_info
		{
			border-collapse:collapse;
			margin:10px 0;
			width:100%;
		}
		
		#tbl_info td
		{
			width:25%;
		}
		
		.info_td
		{
			padding:15px 0;
			font-size:11px;
			background:url('images/bg_info.jpg');
			background-size:cover;
			text-align:center;
			border-radius:5px;
		}
		
		#tbl_info b
		{
			font-size:15px;
		}
		
		@media screen and (max-width:900px)
		{
			#popup_content
			{
				height:70%;
				width:70%;
			}
			
			#tbl_data
			{
				width:150%;
			}
			
			#tbl_popup td, #tbl_info td
			{
				display:block;
				width:100%;
			}
			
			.info_td
			{
				margin:0 0 10px;
			}
		}
	</style>
	<script>
		function show_popup_form(act, rid, agd_no, ml_dt, ml_rcp_dt, ml_no, ml_fr, ml_to, ml_typ, ml_lvl, ml_cont, ml_fl, stts, ml_nt, ml_dis, ml_dis_dt, ml_ttd)
		{
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function()
			{
				if (this.readyState == 4 && this.status == 200)
				{
					console.log('view updated');
				}
			};
			xhttp.open("GET", "<?php echo base_url('update_view'); ?>"+'?mail_id='+rid, true);
			xhttp.send();
			
			var popup = document.getElementById('popup');
			popup.style.display = 'block';
			
			var popup_title = document.getElementById('popup_title');
			popup_title.innerHTML = act;
			
			var popup_subtitle = document.getElementById('popup_subtitle');
			popup_subtitle.innerHTML = '<?php echo strtoupper($app_info[0]->app_subtitle); ?>';
			
			var act_name = document.getElementById('act');
			act_name.value = act;
			
			var row_id = document.getElementById('row_id');
			row_id.value = (act == "TAMBAH SURAT") ? "" : rid;
			
			var mail_file_del = document.getElementById('mail_file_del');
			mail_file_del.value = (act == "TAMBAH SURAT") ? "" : ml_fl;
			
			var mail_ttd_del = document.getElementById('mail_ttd_del');
			mail_ttd_del.value = (act == "TAMBAH SURAT") ? "" : ml_ttd;
			
			var agenda_no = document.getElementById('agenda_no');
			agenda_no.value = (act == "TAMBAH SURAT") ? "" : agd_no;
			agenda_no.readOnly = (act == "HAPUS SURAT") ? true : false;
			
			var mail_date = document.getElementById('mail_date');
			mail_date.value = (act == "TAMBAH SURAT") ? "<?php echo date("Y-m-d"); ?>" : ml_dt;
			mail_date.readOnly = (act == "HAPUS SURAT") ? true : false;
			
			var mail_receipt_date = document.getElementById('mail_receipt_date');
			mail_receipt_date.value = (act == "TAMBAH SURAT") ? "<?php echo date("Y-m-d"); ?>" : ml_rcp_dt;
			mail_receipt_date.readOnly = (act == "HAPUS SURAT") ? true : false;
			
			var mail_no = document.getElementById('mail_no');
			mail_no.value = (act == "TAMBAH SURAT") ? "" : ml_no;
			mail_no.readOnly = (act == "HAPUS SURAT") ? true : false;
			
			var mail_from = document.getElementById('mail_from');
			mail_from.value = (act == "TAMBAH SURAT") ? "" : ml_fr;
			mail_from.disabled = (act == "HAPUS SURAT") ? true : false;
			
			var mail_to_level = document.getElementById('mail_to_level');
			
			if(act !== 'TAMBAH SURAT')
			{
				var data_mail_to = ml_to.split(",");
				
				for(i=0; i<mail_to_level.options.length; i++)
				{
					for(j=0; j<data_mail_to.length; j++)
					{
						if(data_mail_to[j] == mail_to_level.options[i].value)
						{
							mail_to_level.options[i].selected = true;
						}
					}
				}
			}
			
			mail_to_level.disabled = (act == "HAPUS SURAT") ? true : false;
			
			var mail_type = document.getElementById('mail_type');
			mail_type.value = (act == "TAMBAH SURAT") ? "" : ml_typ;
			mail_type.disabled = (act == "HAPUS SURAT") ? true : false;
			
			var mail_level = document.getElementById('mail_level');
			mail_level.value = (act == "TAMBAH SURAT") ? "" : ml_lvl;
			mail_level.disabled = (act == "HAPUS SURAT") ? true : false;
			
			var mail_content = document.getElementById('mail_content');
			mail_content.value = (act == "TAMBAH SURAT") ? "" : ml_cont;
			mail_content.readOnly = (act == "HAPUS SURAT") ? true : false;
			
			var mail_file = document.getElementById('mail_file');
			mail_file.disabled = (act == "HAPUS SURAT") ? true : false;
			mail_file.required = (act == "TAMBAH SURAT") ? true : false;
			
			var current_file = document.getElementById('current_file');
			current_file.style.display = (act == "TAMBAH SURAT") ? 'none' : 'block';
			current_file.innerHTML = (act == "TAMBAH SURAT") ? '' : '<a href="#" onclick="show_view_file('+"'"+ml_fl+"'"+')">Lihat</a>';
			
			var current_file_ttd = document.getElementById('current_file_ttd');
			current_file_ttd.style.display = (act == "TAMBAH SURAT") ? 'none' : 'block';
			current_file_ttd.innerHTML = (act == "TAMBAH SURAT") ? '' : '<a href="#" onclick="show_view_file('+"'"+ml_ttd+"'"+')">Lihat</a>';
			
			var mail_ttd = document.getElementById('mail_ttd');
			mail_ttd.required = (act == "TAMBAH SURAT") ? true : false;
			mail_ttd.disabled = (act == "HAPUS SURAT") ? true : false;
			
			var btn_submit = document.getElementById('btn_submit');
			btn_submit.value = (act == "HAPUS SURAT") ? "Hapus" : "Simpan";
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
		
		function show_view_file(link, mail_id)
		{
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function()
			{
				if (this.readyState == 4 && this.status == 200)
				{
					console.log('view updated');
				}
			};
			xhttp.open("GET", "<?php echo base_url('update_view'); ?>"+'?mail_id='+mail_id, true);
			xhttp.send();
			
			var div_view_file = document.getElementById('div_view_file');
			div_view_file.style.display = 'block';
			
			var view_file = document.getElementById('view_file');
			view_file.src = link;
		}
		
		function show_form_forward(act, rid, agd_no, ml_dt, ml_rcp_dt, ml_no, ml_fr, ml_to, ml_typ, ml_lvl, ml_cont, ml_fl, stts, ml_nt, ml_dis, ml_dis_dt, ml_ttd)
		{
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function()
			{
				if (this.readyState == 4 && this.status == 200)
				{
					console.log('view updated');
				}
			};
			xhttp.open("GET", "<?php echo base_url('update_view'); ?>"+'?mail_id='+rid, true);
			xhttp.send();
			
			var form_forward = document.getElementById('form_forward');
			form_forward.style.display = 'block';
			
			var form_forward_title = document.getElementById('form_forward_title');
			form_forward_title.innerHTML = act;
			
			var form_forward_subtitle = document.getElementById('form_forward_subtitle');
			form_forward_subtitle.innerHTML = '<?php echo strtoupper($app_info[0]->app_subtitle); ?>';
			
			var act_fwd = document.getElementById('act_fwd');
			act_fwd.value = act;
			
			var row_id_fwd = document.getElementById('row_id_fwd');
			row_id_fwd.value = rid;
			
			var agenda_no_fwd = document.getElementById('agenda_no_fwd');
			agenda_no_fwd.value = agd_no;
			
			var mail_date_fwd = document.getElementById('mail_date_fwd');
			mail_date_fwd.value = ml_dt;
			
			var mail_receipt_date_fwd = document.getElementById('mail_receipt_date_fwd');
			mail_receipt_date_fwd.value = ml_rcp_dt;
			
			var mail_no_fwd = document.getElementById('mail_no_fwd');
			mail_no_fwd.value = ml_no;
			
			var mail_from_fwd = document.getElementById('mail_from_fwd');
			mail_from_fwd.value = ml_fr;
			
			var mail_type_fwd = document.getElementById('mail_type_fwd');
			mail_type_fwd.value = ml_typ;
			
			var mail_level_fwd = document.getElementById('mail_level_fwd');
			mail_level_fwd.value = ml_lvl;
			
			var mail_content_fwd = document.getElementById('mail_content_fwd');
			mail_content_fwd.value = ml_cont;
			
			var current_file_fwd = document.getElementById('current_file_fwd');
			current_file_fwd.innerHTML = '<a href="#" onclick="show_view_file('+"'"+ml_fl+"'"+')">Lihat File</a>';
			
			var status = document.getElementById('status');
			status.value = stts;
			
			var mail_note_fwd = document.getElementById('mail_note_fwd');
			mail_note_fwd.value = ml_nt;
			
			var mail_disposisi_fwd = document.getElementById('mail_disposisi_fwd');
			mail_disposisi_fwd.value = ml_dis;
		}
		
		function get_status(status)
		{
			var mail_disposisi_fwd = document.getElementById('mail_disposisi_fwd');
			
			if(status == "DISPOSISI")
			{
				mail_disposisi_fwd.required = true;
				mail_disposisi_fwd.disabled = false;
			}
			else
			{
				mail_disposisi_fwd.value = '';
				mail_disposisi_fwd.required = false;
				mail_disposisi_fwd.disabled = true;
			}
		}
		
		function show_form_forward_kepala(act, rid, agd_no, ml_dt, ml_rcp_dt, ml_no, ml_fr, ml_to, ml_typ, ml_lvl, ml_cont, ml_fl, stts, ml_nt, ml_dis, ml_dis_dt, ml_ttd)
		{
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function()
			{
				if (this.readyState == 4 && this.status == 200)
				{
					console.log('view updated');
				}
			};
			xhttp.open("GET", "<?php echo base_url('update_view'); ?>"+'?mail_id='+rid, true);
			xhttp.send();
			
			var form_forward_kepala = document.getElementById('form_forward_kepala');
			form_forward_kepala.style.display = 'block';
			
			var form_forward_kepala_title = document.getElementById('form_forward_kepala_title');
			form_forward_kepala_title.innerHTML = act;
			
			var form_forward_kepala_subtitle = document.getElementById('form_forward_kepala_subtitle');
			form_forward_kepala_subtitle.innerHTML = '<?php echo strtoupper($app_info[0]->app_subtitle); ?>';
			
			var act_fwd_kepala = document.getElementById('act_fwd_kepala');
			act_fwd_kepala.value = act;
			
			var row_id_fwd_kepala = document.getElementById('row_id_fwd_kepala');
			row_id_fwd_kepala.value = rid;
			
			var agenda_no_fwd_kepala = document.getElementById('agenda_no_fwd_kepala');
			agenda_no_fwd_kepala.value = agd_no;
			
			var mail_date_fwd_kepala = document.getElementById('mail_date_fwd_kepala');
			mail_date_fwd_kepala.value = ml_dt;
			
			var mail_receipt_date_fwd_kepala = document.getElementById('mail_receipt_date_fwd_kepala');
			mail_receipt_date_fwd_kepala.value = ml_rcp_dt;
			
			var mail_no_fwd_kepala = document.getElementById('mail_no_fwd_kepala');
			mail_no_fwd_kepala.value = ml_no;
			
			var mail_from_fwd_kepala = document.getElementById('mail_from_fwd_kepala');
			mail_from_fwd_kepala.value = ml_fr;
			
			var mail_type_fwd_kepala = document.getElementById('mail_type_fwd_kepala');
			mail_type_fwd_kepala.value = ml_typ;
			
			var mail_level_fwd_kepala = document.getElementById('mail_level_fwd_kepala');
			mail_level_fwd_kepala.value = ml_lvl;
			
			var mail_content_fwd_kepala = document.getElementById('mail_content_fwd_kepala');
			mail_content_fwd_kepala.value = ml_cont;
			
			var mail_note_fwd_kepala = document.getElementById('mail_note_fwd_kepala');
			mail_note_fwd_kepala.value = ml_nt;
			
			var current_file_fwd_kepala = document.getElementById('current_file_fwd_kepala');
			current_file_fwd_kepala.innerHTML = '<a href="#" onclick="show_view_file('+"'"+ml_fl+"'"+')">Lihat File</a>';
		}
		
		function get_status_kepala(status)
		{
			var mail_to_level_fwd_kepala = document.getElementById('mail_to_level_fwd_kepala');
			
			var mail_disposisi_kepala = document.getElementById('mail_disposisi_kepala');
			mail_disposisi_kepala.innerHTML = '';
			
			if(status == "DISPOSISI")
			{
				mail_to_level_fwd_kepala.disabled = false;
				mail_to_level_fwd_kepala.required = true;
			}
			else
			{
				mail_to_level_fwd_kepala.disabled = true;
				mail_to_level_fwd_kepala.required = false;
			}
		}
		
		function add_input_disposisi(level_id)
		{
			var mail_to_level_fwd_kepala = document.getElementById('mail_to_level_fwd_kepala');
			var mail_disposisi_kepala = document.getElementById('mail_disposisi_kepala');
			
			mail_disposisi_kepala.innerHTML = '';
			
			for(i=0; i<mail_to_level_fwd_kepala.options.length; i++)
			{
				if(mail_to_level_fwd_kepala.options[i].selected)
				{
					mail_disposisi_kepala.innerHTML += '<span>'+mail_to_level_fwd_kepala.options[i].innerHTML.toLowerCase()+'</span>'+<?php echo "'"; echo '<select class="select" style="margin:0 0 10px;" name="mail_disposisi[]">'; echo "'+"; foreach($disposisi_list as $rec) { echo "'".'<option value='.'"'.$rec->disposisi_id.'"'.'>'.$rec->disposisi_name.'</option>'."'+"; } echo "'</select>'"; ?>;
				}
			}
		}	
	</script>
</head>
<body onload="check_msg()">
	<?php $this->load->view('V_menu'); ?>
	<div id="container">
		<div id="content">
			<h4>Master Surat</h4>
			<?php if($session[0]->level_id == "1") { ?>
			<span style="float:right; font-size:11px; cursor:pointer;" onclick="show_popup_form('TAMBAH SURAT')">
				<i class="fas fa-plus-square green"></i>&nbsp;Tambah Surat Baru
			</span>
			<?php } ?>
			<p style="color:gray; font-size:12px;">Menampilkan surat yang pernah dikirim</p>
			<table id="tbl_info">
				<tr>
					<td>
						<div class="info_td" style="border-bottom:5px solid #34A853;">
							Total Surat
							<br>
							<b style="color:#34A853;">
								<?php
									echo $mail_info[0]->mail_total;
								?>
							</b>
						</div>
					</td>
					<td>
						<div class="info_td" style="border-bottom:5px solid darkorange;">
							Surat Selesai
							<br>
							<b style="color:darkorange;">
								<?php
									echo $mail_info[0]->mail_done;
								?>
							</b>
						</div>
					</td>
					<td>
						<div class="info_td" style="border-bottom:5px solid teal;">
							Total Pengguna
							<br>
							<b style="color:teal;">
								<?php
									echo $mail_info[0]->total_user;
								?>
							</b>
						</div>
					</td>
					<td>
						<div class="info_td" style="border-bottom:5px solid #EA4335;">
							Total Instansi
							<br>
							<b style="color:#EA4335;">
								<?php
									echo $mail_info[0]->total_company;
								?>
							</b>
						</div>
					</td>
				</tr>
			</table>
			<form id="form_search" method="get" action="<?php echo base_url('mail'); ?>">
				<table id="tbl_search">
					<tr>
						<td style="width:100%;">
							Kata Kunci<br>
							<input type="text" name="src" class="text_search" value="<?php echo (!empty($src)) ? $src : ""; ?>" placeholder="Masukkan nama instansi/nomor agenda/nomor surat untuk melakukan pencarian" autocomplete="off" autofocus>
						</td>
						<td>
							<br>
							<input type="submit" class="submit" value="Cari" style="color:white; padding:6.5px; background:#34A853;">
						</td>
					</tr>
				</table>
			</form>
			<div id="div_tbl_data">
				<table id="tbl_data">
					<tr>
						<th>No</th>
						<th>Asal Surat</th>
						<th>Tgl. Surat</th>
						<th>Tgl. Diterima</th>
						<th>No. Surat</th>
						<th>Sifat Surat</th>
						<th>File Surat</th>
						<th>Dibagikan Oleh</th>
						<th>Dibagikan Ke</th>
						<th>Dibaca</th>
						<th>Aksi</th>
					</tr>
					<?php
						$no = (empty($this->input->get('page')) or $this->input->get('page') == 1) ? 1 : $this->input->get('page')*20-20+1;
						
						if(empty($mail_list))
						{
							echo '
								<tr>
									<td colspan="13" style="text-align:left;">Data tidak ditemukan</td>
								</tr>
							';
						}
						else
						{
							foreach($mail_list as $rec)
							{
								$link = "'".$rec->mail_file."'";
								
								$chg = "'UBAH SURAT'";
								$del = "'HAPUS SURAT'";
								$view = "'PREVIEW SURAT'";
								
								$param = array(
									'row_id' => "'".$rec->row_id."'",
									'agenda_no' => "'".$rec->agenda_no."'",
									'mail_date' => "'".$rec->mail_date."'",
									'mail_receipt_date' => "'".$rec->mail_receipt_date."'",
									'mail_no' => "'".$rec->mail_no."'",
									'mail_from' => "'".$rec->mail_from."'",
									'mail_to_level' => "'".$rec->mail_to_level."'",
									'mail_type' => "'".$rec->mail_type."'",
									'mail_level' => "'".$rec->mail_level."'",
									'mail_content' => "'".trim(preg_replace('/\s\s+/', '\n', str_replace('"', "`", str_replace("'", "`", $rec->mail_content))))."'",
									'mail_file' => "'".$rec->mail_file."'",
									'status' => "'".$rec->status."'",
									'mail_note' => "'".trim(preg_replace('/\s\s+/', '\n', str_replace('"', "`", str_replace("'", "`", $rec->mail_note))))."'",
									'mail_disposisi' => "'".$rec->mail_disposisi."'",
									'mail_disposisi_date' => "'".$rec->mail_disposisi_date."'",
									'mail_ttd' => "'".$rec->mail_ttd."'"
								);
								
								$mail_to_user = (empty($rec->mail_to_user)) ? "-" : (sizeof(explode(",",$rec->mail_to_user))-1)." Orang";
								$dibaca = (in_array($session[0]->row_id, explode(",",$rec->viewed_by)) OR $rec->shared_by == $session[0]->username) ? '<i class="fas fa-check-circle" style="font-size:13px; color:#3498DB;"></i>' : '<i class="fas fa-times-circle" style="font-size:13px; color:#E74C3C;"></i>';
								
								$mail_history = base_url('mail_history')."?mail_id=".$rec->row_id;
								
								if($session[0]->level_id == "1")
								{
									echo '
										<tr>
											<td>'.$no++.'</td>
											<td>'.$rec->company_name.'</td>
											<td>'.strtoupper(date("d M Y", strtotime($rec->mail_date))).'</td>
											<td>'.strtoupper(date("d M Y", strtotime($rec->mail_receipt_date))).'</td>
											<td>'.$rec->mail_no.'</td>
											<td>'.$rec->level_name.'</td>
											<td><a href="#" onclick="show_view_file('.$link.','.$param['row_id'].')" style="text-decoration:underline;">Lihat File</a></td>
											<td>'.$rec->shared_by.'</td>
											<td><a href="'.$mail_history.'" style="text-decoration:underline;">'.(sizeof(explode(",",$rec->mail_to_user))-1).' Orang</a></td>
											<td>'.$dibaca.'</td>
									';
									
									if($rec->stop_forward > 0)
									{
										echo '
												<td>
													<i class="fas fa-ban red" style="font-size:13px; cursor:pointer;"></i>
												</td>
											</tr>
										';
									}
									else
									{
										echo '
												<td>
													<i class="fas fa-pen-square green" style="font-size:13px; cursor:pointer;" onclick="show_popup_form('.$chg.','.implode(",", $param).')"></i>
													&nbsp;
													<i class="fas fa-minus-square red" style="font-size:13px; cursor:pointer;" onclick="show_popup_form('.$del.','.implode(",", $param).')"></i>
												</td>
											</tr>
										';
									}
								}
								else if($session[0]->level_id == "2")
								{
									echo '
										<tr>
											<td>'.$no++.'</td>
											<td>'.$rec->company_name.'</td>
											<td>'.strtoupper(date("d M Y", strtotime($rec->mail_date))).'</td>
											<td>'.strtoupper(date("d M Y", strtotime($rec->mail_receipt_date))).'</td>
											<td>'.$rec->mail_no.'</td>
											<td>'.$rec->level_name.'</td>
											<td><a href="#" onclick="show_view_file('.$link.','.$param['row_id'].')" style="text-decoration:underline;">Lihat File</a></td>
											<td>'.$rec->shared_by.'</td>
											<td><a href="'.$mail_history.'" style="text-decoration:underline;">'.$mail_to_user.'</a></td>
											<td>'.$dibaca.'</td>
									';
									
									if($rec->stop_forward > 0)
									{
										echo '
												<td>
													<i class="fas fa-ban red" style="font-size:13px; cursor:pointer;"></i>
												</td>
											</tr>
										';
									}
									else
									{
										echo '
												<td>
												<i class="fas fa-share green" style="font-size:13px; cursor:pointer;" onclick="show_form_forward_kepala('.$view.','.implode(",", $param).')"></i>
											</td>
										</tr>
										';
									}
								}
								else
								{
									echo '
										<tr>
											<td>'.$no++.'</td>
											<td>'.$rec->company_name.'</td>
											<td>'.strtoupper(date("d M Y", strtotime($rec->mail_date))).'</td>
											<td>'.strtoupper(date("d M Y", strtotime($rec->mail_receipt_date))).'</td>
											<td>'.$rec->mail_no.'</td>
											<td>'.$rec->level_name.'</td>
											<td><a href="#" onclick="show_view_file('.$link.','.$param['row_id'].')" style="text-decoration:underline;">Lihat File</a></td>
											<td>'.$rec->shared_by.'</td>
											<td><a href="'.$mail_history.'" style="text-decoration:underline;">'.$mail_to_user.'</a></td>
											<td>'.$dibaca.'</td>
									';
									
									if($rec->stop_forward > 0)
									{
										echo '
												<td>
													<i class="fas fa-ban red" style="font-size:13px; cursor:pointer;"></i>
												</td>
											</tr>
										';
									}
									else
									{
										echo '
												<td>
													<i class="fas fa-share green" style="font-size:13px; cursor:pointer;" onclick="show_form_forward('.$view.','.implode(",", $param).')"></i>
												</td>
											</tr>
										';
									}
								}
							}
						}
					?>
				</table>
			</div>
			<div style="margin:25px 0 0; text-align:right;">
				<h6 style="font-size:7px;">
					<?php
						if(!empty($size_mail_list))
						{
							$page = ceil($size_mail_list/20);
							
							for($i=1; $i<= $page; $i++)
							{
								if(empty($this->input->get('page')) && $i == 1)
								{
									echo '<a href="'.base_url('mail').'?src='.$src.'&page='.$i.'" class="active_page">'.$i."</a> ";
								}
								else if($i == $this->input->get('page'))
								{
									echo '<a href="'.base_url('mail').'?src='.$src.'&page='.$i.'" class="active_page">'.$i."</a> ";
								}
								else
								{
									echo '<a href="'.base_url('mail').'?src='.$src.'&page='.$i.'" class="inactive_page">'.$i."</a> ";
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
				<form method="post" action="<?php echo base_url('saving_mail'); ?>" enctype="multipart/form-data">
					<input type="hidden" class="text" id="act" name="act">
					<input type="hidden" class="text" id="row_id" name="row_id">
					<input type="hidden" class="text" id="mail_file_del" name="mail_file_del">
					<input type="hidden" class="text" id="mail_ttd_del" name="mail_ttd_del">
					<table id="tbl_popup">
						<tr>
							<td>
								<br>Nomor Agenda *<br>
								<input type="text" class="text" id="agenda_no" name="agenda_no" autocomplete="off" required>
							</td>
							<td>
								<br>Tanggal Surat *<br>
								<input type="date" class="date" id="mail_date" name="mail_date" required>
							</td>
							<td>
								<br>Tanggal Diterima *<br>
								<input type="date" class="date" id="mail_receipt_date" name="mail_receipt_date" required>
							</td>
							<td>
								<br>Nomor Surat *<br>
								<input type="text" class="text" id="mail_no" name="mail_no" autocomplete="off" required>
							</td>
						</tr>
						<tr>
							<td colspan="4">
								<br>Asal Surat *<br>
								<select class="select" id="mail_from" name="mail_from" required>
									<option value="">Pilih</option>
									<?php
										foreach($company_list as $rec)
										{
											echo '
												<option value="'.$rec->company_id.'">'.$rec->company_name.'</option>
											';
										}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="4">
								<br>Kepada *<br>
								<select id="mail_to_level" name="mail_to_level[]" class="select" required>
									<?php
										foreach($user_level_list as $rec)
										{
											if($rec->level_name == 'UMUM')
											{
												echo '
													<option value="'.$rec->level_id.'">'.$rec->level_name.'</option>
												';
											}
										}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td>
								<br>Jenis Surat *<br>
								<select class="select" id="mail_type" name="mail_type" required>
									<option value="">Pilih</option>
									<?php
										foreach($mail_type_list as $rec)
										{
											echo '
												<option value="'.$rec->type_id.'">'.$rec->type_name.'</option>
											';
										}
									?>
								</select>
							</td>
							<td>
								<br>Sifat Surat *<br>
								<select class="select" id="mail_level" name="mail_level" required>
									<option value="">Pilih</option>
									<?php
										foreach($mail_level_list as $rec)
										{
											echo '
												<option value="'.$rec->level_id.'">'.$rec->level_name.'</option>
											';
										}
									?>
								</select>
							</td>
							<td>
								<br>
								<span id="current_file" style="float:right;"></span>
								File Surat (Maks 5 Mb) *<br>
								<input type="file" class="file" id="mail_file" name="mail_file" required>
							</td>
							<td>
								<br>
								<span id="current_file_ttd" style="float:right;"></span>
								Gambar TTD (Maks 5 Mb) *<br>
								<input type="file" class="file" id="mail_ttd" name="mail_ttd" required>
							</td>
						</tr>
						<tr>
							<td colspan="4">
								<br>Isi Surat *<br>
								<textarea class="textarea" id="mail_content" name="mail_content" required></textarea>
							</td>
						</tr>
						<tr>
							<td colspan="4">
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
	
	<div id="form_forward">
		<div id="form_forward_content">
			<div id="form_forward_header">
				<h4 id="form_forward_title">Message Title</h4>
				<p id="form_forward_subtitle">LOREM IPSUM SIT DOLOR</p>
			</div>
			<div id="form_forward_detail">
				<form method="post" action="<?php echo base_url('saving_forward'); ?>" enctype="multipart/form-data">
					<input type="hidden" class="text" id="act_fwd" name="act_fwd">
					<input type="hidden" class="text" id="row_id_fwd" name="row_id_fwd">
					<table id="tbl_form_forward">
						<tr>
							<td>
								<br>Nomor Agenda<br>
								<input type="text" class="text" id="agenda_no_fwd" name="agenda_no_fwd" autocomplete="off" readonly>
							</td>
							<td>
								<br>Tanggal Surat<br>
								<input type="date" class="date" id="mail_date_fwd" name="mail_date_fwd" readonly>
							</td>
							<td>
								<br>Tanggal Diterima<br>
								<input type="date" class="date" id="mail_receipt_date_fwd" name="mail_receipt_date_fwd" readonly>
							</td>
							<td>
								<br>Nomor Surat<br>
								<input type="text" class="text" id="mail_no_fwd" name="mail_no_fwd" autocomplete="off" readonly>
							</td>
						</tr>
						<tr>
							<td>
								<br>Asal Surat<br>
								<select class="select" id="mail_from_fwd" name="mail_from_fwd" disabled>
									<option value="">Pilih</option>
									<?php
										foreach($company_list as $rec)
										{
											echo '
												<option value="'.$rec->company_id.'">'.$rec->company_name.'</option>
											';
										}
									?>
								</select>
							</td>
							<td>
								<br>Jenis Surat<br>
								<select class="select" id="mail_type_fwd" name="mail_type_fwd" disabled>
									<option value="">Pilih</option>
									<?php
										foreach($mail_type_list as $rec)
										{
											echo '
												<option value="'.$rec->type_id.'">'.$rec->type_name.'</option>
											';
										}
									?>
								</select>
							</td>
							<td>
								<br>Sifat Surat<br>
								<select class="select" id="mail_level_fwd" name="mail_level_fwd" disabled>
									<option value="">Pilih</option>
									<?php
										foreach($mail_level_list as $rec)
										{
											echo '
												<option value="'.$rec->level_id.'">'.$rec->level_name.'</option>
											';
										}
									?>
								</select>
							</td>
							<td colspan="2">
								<br>
								File Surat<br>
								<div id="current_file_fwd" style="border:1px solid #ccc; padding:5.5px;"></div>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<br>Isi Surat<br>
								<textarea class="textarea" id="mail_content_fwd" name="mail_content_fwd" readonly></textarea>
							</td>
							<td colspan="2">
								<br>Catatan Disposisi<br>
								<textarea class="textarea" id="mail_note_fwd" name="mail_note"></textarea>
							</td>
						</tr>
						<tr>
							<td>
								<br>Status *<br>
								<select class="select" id="status" name="status" onchange="get_status(this.value)" required>
									<option value="">Pilih</option>
									<option value="DISPOSISI">DISPOSISI</option>
								</select>
							</td>
							<td>
								<br>Kepada *<br>
								<select class="select" name="mail_to_level_fwd" required>
									<option value="">Pilih</option>
									<?php
										foreach($user_level_list as $rec)
										{
											if($session[0]->level_id == "6") // UMUM
											{
												if($rec->level_name == 'SEKRETARIS' OR $rec->level_name == 'PANITERA')
												{
													echo '
														<option value="'.$rec->level_id.'">'.$rec->level_name.'</option>
													';
												}
											}
											else if($session[0]->level_id == "4" OR $session[0]->level_id == "5")
											{
												if($rec->level_name == 'KEPALA' )
												{
													echo '
														<option value="'.$rec->level_id.'">'.$rec->level_name.'</option>
													';
												}
											}
										}
									?>
								</select>
							</td>
							<td colspan="2">
								<br>Untuk *<br>
								<select class="select" id="mail_disposisi_fwd" name="mail_disposisi" required>
									<option value="">Pilih</option>
									<?php
										foreach($disposisi_list as $rec)
										{
											echo '
												<option value="'.$rec->disposisi_id.'">'.$rec->disposisi_name.'</option>
											';
										}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td>
								<br>
								<span id="current_file_ttd" style="float:right;"></span>
								Gambar TTD (Maks 5 Mb) *<br>
								<input type="file" class="file" id="mail_ttd_fwd" name="mail_ttd" required>
							</td>
						</tr>
						<tr>
							<td colspan="4">
								<br>
								<input id="btn_submit_fwd" type="submit" class="submit" value="Simpan" style="color:white;">
								<button type="button" class="cancel" onclick="document.getElementById('form_forward').style.display='none';">Tutup</button>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>
	
	<div id="form_forward_kepala">
		<div id="form_forward_kepala_content">
			<div id="form_forward_kepala_header">
				<h4 id="form_forward_kepala_title">Message Title</h4>
				<p id="form_forward_kepala_subtitle">LOREM IPSUM SIT DOLOR</p>
			</div>
			<div id="form_forward_kepala_detail">
				<form method="post" action="<?php echo base_url('saving_forward_kepala'); ?>" enctype="multipart/form-data">
					<input type="hidden" class="text" id="act_fwd_kepala" name="act_fwd_kepala">
					<input type="hidden" class="text" id="row_id_fwd_kepala" name="row_id_fwd_kepala">
					<table id="tbl_form_forward_kepala">
						<tr>
							<td>
								<br>Nomor Agenda *<br>
								<input type="text" class="text" id="agenda_no_fwd_kepala" name="agenda_no" autocomplete="off" readonly>
							</td>
							<td>
								<br>Tanggal Surat *<br>
								<input type="date" class="date" id="mail_date_fwd_kepala" name="mail_date" readonly>
							</td>
							<td>
								<br>Tanggal Diterima *<br>
								<input type="date" class="date" id="mail_receipt_date_fwd_kepala" name="mail_receipt_date" readonly>
							</td>
							<td>
								<br>Nomor Surat *<br>
								<input type="text" class="text" id="mail_no_fwd_kepala" name="mail_no" autocomplete="off" readonly>
							</td>
						</tr>
						<tr>
							<td>
								<br>Asal Surat *<br>
								<select class="select" id="mail_from_fwd_kepala" name="mail_from" disabled>
									<option value="">Pilih</option>
									<?php
										foreach($company_list as $rec)
										{
											echo '
												<option value="'.$rec->company_id.'">'.$rec->company_name.'</option>
											';
										}
									?>
								</select>
							</td>
							<td>
								<br>Jenis Surat *<br>
								<select class="select" id="mail_type_fwd_kepala" name="mail_from" disabled>
									<option value="">Pilih</option>
									<?php
										foreach($mail_type_list as $rec)
										{
											echo '
												<option value="'.$rec->type_id.'">'.$rec->type_name.'</option>
											';
										}
									?>
								</select>
							</td>
							<td>
								<br>Sifat Surat *<br>
								<select class="select" id="mail_level_fwd_kepala" name="mail_from" disabled>
									<option value="">Pilih</option>
									<?php
										foreach($mail_level_list as $rec)
										{
											echo '
												<option value="'.$rec->level_id.'">'.$rec->level_name.'</option>
											';
										}
									?>
								</select>
							</td>
							<td>
								<br>
								File Surat<br>
								<div id="current_file_fwd_kepala" style="border:1px solid #ccc; padding:5.5px;"></div>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<br>Isi Surat *<br>
								<textarea class="textarea" id="mail_content_fwd_kepala" name="mail_content" readonly></textarea>
							</td>
							<td colspan="2">
								<br>Catatan<br>
								<textarea class="textarea" id="mail_note_fwd_kepala" name="mail_note"></textarea>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<br>Status *<br>
								<select class="select" name="status" onchange="get_status_kepala(this.value)" required>
									<option value="">Pilih</option>
									<option value="SELESAI">SELESAI</option>
								</select>
							</td>
							<td colspan="2">
								<br>
								Gambar TTD (Maks 5 Mb) *<br>
								<input type="file" class="file" name="mail_ttd" required>
							</td>
						</tr>
						<tr>
							<td colspan="4">
								<br>
								<input id="btn_submit_fwd_kepala" type="submit" class="submit" value="Simpan" style="color:white;">
								<button type="button" class="cancel" onclick="document.getElementById('form_forward_kepala').style.display='none';">Tutup</button>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>
	
	<div id="div_view_file" onclick="document.getElementById('div_view_file').style.display='none';">
		<iframe id="view_file"></iframe>
	</div>
</body>
</html>