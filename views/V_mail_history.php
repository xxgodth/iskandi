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
	<link rel="icon" type="image/png" href="./images/logo.png"/>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<style type="text/css">
		#content
		{
			margin:40px 50px;
		}
		
		#tbl_mail_main
		{
			width:100%;
			border-collapse:collapse;
			margin:10px 0 0;
		}
		
		#tbl_mail_main th
		{
			font-size:11px;
			background:#6C63FF;
			padding:8px;
			color:white;
			font-weight:normal;
		}
		
		#tbl_mail_main td
		{
			font-size:11px;
			text-align:center;
			padding:8px;
		}
		
		#tbl_mail_main tr:nth-child(odd)
		{
			background:#ccc;
		}
		
		#tbl_mail_main tr:nth-child(even)
		{
			background:#f2f2f2;
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
		
		@media screen and (max-width:900px)
		{
			#view_file
			{
				width:70%;
			}
		}
	</style>
	<script>
		function show_view_file(link)
		{
			var div_view_file = document.getElementById('div_view_file');
			div_view_file.style.display = 'block';
			
			var view_file = document.getElementById('view_file');
			view_file.src = link;
		}
		
		function show_disposisi_rpt()
		{
			var div_view_file = document.getElementById('div_view_file');
			div_view_file.style.display = 'block';
			
			var view_file = document.getElementById('view_file');
			view_file.src = "<?php echo base_url('disposisi_pdf'); ?>"+'?mail_id='+"<?php echo $this->input->get('mail_id'); ?>";
		}
	</script>
</head>
<body>
	<?php $this->load->view('V_menu'); ?>
	<div id="container">
		<div id="content">
			<h4>Histori Surat</h4>
			<p style="color:gray; font-size:12px;">Menampilkan perjalanan surat yang pernah dibagikan</p>
			<br>
			<span style="float:right; font-size:11px;">
				<a href="#" onclick="show_disposisi_rpt()">Cetak Lembar Disposisi</a>
			</span>
			<h5>Asal Surat</h5>
			<table id="tbl_mail_main">
				<tr>
					<th>Tgl. Surat</th>
					<th>Tgl. Diterima</th>
					<th>No. Agenda</th>
					<th>No. Surat</th>
					<th>Nama Instansi</th>
					<th>Sifat Surat</th>
					<th>File Surat</th>
					<th>Dibagikan Oleh</th>
					<th>Gambar TTD</th>
					<th>Dibagikan Ke</th>
				</tr>
				<?php
					if(empty($mail_main))
					{
						echo '
						<tr>
							<td colspan="9" style="text-align:left;">Data tidak ditemukan</td>
						</tr>';
					}
					else
					{
						foreach($mail_main as $rec)
						{
							$link = "'".$rec->mail_file."'";
							$link2 = "'".$rec->mail_ttd."'";
							
							echo '
								<td>'.strtoupper(date("d M Y", strtotime($rec->mail_date))).'</td>
								<td>'.strtoupper(date("d M Y", strtotime($rec->mail_receipt_date))).'</td>
								<td>'.$rec->agenda_no.'</td>
								<td>'.$rec->mail_no.'</td>
								<td>'.$rec->company_name.'</td>
								<td>'.$rec->level_name.'</td>
								<td><a href="#" onclick="show_view_file('.$link.')" style="text-decoration:underline;">Lihat</a></td>
								<td>'.$rec->shared_by_name.'</td>
								<td><a href="#" onclick="show_view_file('.$link2.')" style="text-decoration:underline;">Lihat</a></td>
								<td>'.str_replace(",","<br>",$rec->mail_to_level_name).'</td>
							';
						}
					}
				?>
			</table>
			<br>
			<h5>Disposisi Surat</h5>
			<table id="tbl_mail_main">
				<tr>
					<th>Tgl. Disposisi</th>
					<th>Dibagikan Oleh</th>
					<th>Gambar TTD</th>
					<th>Dibagikan Ke</th>
					<th>Keterangan Disposisi</th>
					<th>Catatan Disposisi</th>
					<th>Status</th>
				</tr>
				<?php
					if(empty($mail_disposisi))
					{
						echo '
						<tr>
							<td colspan="6" style="text-align:left;">Data tidak ditemukan</td>
						</tr>';
					}
					else
					{
						foreach($mail_disposisi as $rec)
						{
							$mail_disposisi_date = (empty($rec->mail_disposisi_date)) ? "-" : strtoupper(date("d M Y", strtotime($rec->mail_disposisi_date)));
							$mail_disposisi = ($rec->mail_disposisi == "0") ? "-" : $rec->disposisi_name;
							$mail_to_user_name = (empty($rec->mail_to_user_name)) ? "-" : $rec->mail_to_user_name;
							$mail_note = (empty($rec->mail_note)) ? "-" : ((strlen($rec->mail_note) > 100) ? substr($rec->mail_note,0,50)."..." : $rec->mail_note);
							$link2 = "'".$rec->mail_ttd."'";
							
							echo '
								<tr>
									<td>'.$mail_disposisi_date.'</td>
									<td>'.$rec->shared_by.'</td>
									<td><a href="#" onclick="show_view_file('.$link2.')" style="text-decoration:underline;">Lihat</a></td>
									<td>'.$mail_to_user_name.'</td>
									<td>'.$mail_disposisi.'</td>
									<td>'.$mail_note.'</td>
									<td>'.$rec->status.'</td>
								</tr>
							';
						}
					}
				?>
			</table>
		</div>
	</div>
	
	<div id="div_view_file" onclick="document.getElementById('div_view_file').style.display='none';">
		<iframe id="view_file"></iframe>
	</div>
</body>
</html>