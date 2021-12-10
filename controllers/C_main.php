<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

require_once('./vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class C_main extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('M_main');
	}
	
	public function index()
	{
		$include['app_info'] = $this->M_main->app_info();
		
		$this->load->view('V_main', $include);
	}
	
	public function verify()
	{
		$username = strtoupper($this->input->post('username'));
		$password = strtoupper($this->input->post('password'));
		
		$verify = $this->M_main->verify($username, $password);
		
		if(empty($verify))
		{
			$msg = array(
				'msg_header' => "Mohon Maaf",
				'msg_detail' => "Pengguna dan sandi tidak valid"
			);
			
			$this->session->set_flashdata('msg', $msg);
			
			redirect(base_url(), 'refresh');
		}
		else
		{
			$this->session->set_userdata('session', $verify);
			
			redirect('main', 'refresh');
		}
	}
	
	public function user_page()
	{
		$session = $this->session->userdata('session');
		
		if(empty($session[0]->username))
		{
			redirect('logout', 'refresh');
		}
		else
		{
			redirect('mail', 'refresh');
		}
	}
	
	public function mail_page()
	{
		$session = $this->session->userdata('session');
		
		if(empty($session[0]->username))
		{
			redirect('logout', 'refresh');
		}
		else
		{
			$src = strtoupper($this->input->get('src'));
			$page = strtoupper($this->input->get('page'));
			
			$include['mail_info'] = $this->M_main->mail_info($session[0]->level_id, $session[0]->row_id);
			$include['app_info'] = $this->M_main->app_info();
			$include['user_level_list'] = $this->M_main->user_level_list();
			$include['disposisi_list'] = $this->M_main->disposisi_list();
			$include['mail_type_list'] = $this->M_main->mail_type_list();
			$include['mail_level_list'] = $this->M_main->mail_level_list();
			$include['company_list'] = $this->M_main->company_list();
			$include['user_list'] = $this->M_main->user_list($session[0]->row_id);
			$include['mail_list'] = $this->M_main->mail_list($src, $page, $session[0]->row_id);
			$include['size_mail_list'] = $this->M_main->size_mail_list($src);
			$include['src'] = $src;
			
			$this->load->view('V_mail', $include);
		}
	}
	
	public function save_mail()
	{
		$session = $this->session->userdata('session');
		
		if(empty($session[0]->username))
		{
			redirect('logout', 'refresh');
		}
		else
		{
			$action = $this->input->post('act');
			$row_id = $this->input->post('row_id');
			$mail_file_del = $this->input->post('mail_file_del');
			$mail_ttd_del = $this->input->post('mail_ttd_del');
			
			switch($action)
			{
				case "TAMBAH SURAT":
					$values = array(
						'agenda_no' => strtoupper($this->input->post('agenda_no')),
						'mail_date' => $this->input->post('mail_date'),
						'mail_receipt_date' => $this->input->post('mail_receipt_date'),
						'mail_no' => strtoupper($this->input->post('mail_no')),
						'mail_from' => $this->input->post('mail_from'),
						'mail_to_level' => implode(",", $this->input->post('mail_to_level')),
						'mail_to_user' => $this->M_main->get_user(implode(",", $this->input->post('mail_to_level'))),
						'mail_type' => $this->input->post('mail_type'),
						'mail_level' => $this->input->post('mail_level'),
						'mail_content' => strtoupper($this->input->post('mail_content')),
						'mail_file' => './mails/'.$_FILES["mail_file"]["name"],
						'shared_by' => $session[0]->row_id,
						'viewed_by' => $session[0]->row_id,
						'mail_ttd' => './signatures/'.$_FILES["mail_ttd"]["name"]
					);
					
					$values['mail_to_level'] =  $values['mail_to_level'].",".$session[0]->level_id;
					$values['mail_to_user'] =  $values['mail_to_user'].",".$session[0]->row_id;
					
					$target_dir	= './mails/';
					$target_file = $target_dir . basename($_FILES["mail_file"]["name"]);
						
					move_uploaded_file($_FILES["mail_file"]["tmp_name"], $target_file);
					
					$target_dir2	= './signatures/';
					$target_file2= $target_dir2 . basename($_FILES["mail_ttd"]["name"]);
						
					move_uploaded_file($_FILES["mail_ttd"]["tmp_name"], $target_file2);
					
					$save_mail = $this->M_main->save_mail($action, $values);
					
					$msg = ($save_mail) ? array('msg_header' => "Berhasil", 'msg_detail' => "Surat berhasil dibuat") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Surat gagal dibuat");
				break;
				
				case "UBAH SURAT":
					$values = array(
						'agenda_no' => strtoupper($this->input->post('agenda_no')),
						'mail_date' => $this->input->post('mail_date'),
						'mail_receipt_date' => $this->input->post('mail_receipt_date'),
						'mail_no' => strtoupper($this->input->post('mail_no')),
						'mail_from' => $this->input->post('mail_from'),
						'mail_to_level' => implode(",", $this->input->post('mail_to_level')),
						'mail_to_user' => $this->M_main->get_user(implode(",", $this->input->post('mail_to_level'))),
						'mail_type' => $this->input->post('mail_type'),
						'mail_level' => $this->input->post('mail_level'),
						'mail_content' => strtoupper($this->input->post('mail_content')),
						'viewed_by' => $session[0]->row_id,
						'mail_ttd' => './signatures/'.$_FILES["mail_ttd"]["name"]
					);
					
					$values['mail_to_level'] =  $values['mail_to_level'].",".$session[0]->level_id;
					$values['mail_to_user'] =  $values['mail_to_user'].",".$session[0]->row_id;
					
					if(!empty($_FILES["mail_file"]["name"]))
					{
						unlink($mail_file_del);
				
						$values['mail_file'] = './mails/'.$_FILES["mail_file"]["name"];
						
						$target_dir	= './mails/';
						$target_file = $target_dir . basename($_FILES["mail_file"]["name"]);
							
						move_uploaded_file($_FILES["mail_file"]["tmp_name"], $target_file);
					}
					
					if(!empty($_FILES["mail_ttd"]["name"]))
					{
						unlink($mail_ttd_del);
				
						$values['mail_ttd'] = './signatures/'.$_FILES["mail_ttd"]["name"];
						
						$target_dir	= './signatures/';
						$target_file = $target_dir . basename($_FILES["mail_ttd"]["name"]);
							
						move_uploaded_file($_FILES["mail_ttd"]["tmp_name"], $target_file);
					}
					
					$save_mail = $this->M_main->save_mail($action, $values, $row_id, $mail_file_del);
					
					$msg = ($save_mail) ? array('msg_header' => "Berhasil", 'msg_detail' => "Surat berhasil diperbarui") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Surat gagal diperbarui");
				break;
				
				case "HAPUS SURAT":
					$values = array('active' => "0");
					
					$save_mail = $this->M_main->save_mail($action, $values, $row_id);
					
					$msg = ($save_mail) ? array('msg_header' => "Berhasil", 'msg_detail' => "Surat berhasil dihapus") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Surat gagal dihapus");
				break;
			}
			
			$this->session->set_flashdata('msg', $msg);
			
			redirect('mail', 'refresh');
		}
	}
	
	public function get_mail_inbox()
	{
		echo $this->M_main->get_mail_inbox($this->input->get('user_id'));
	}
	
	public function update_view()
	{
		$session = $this->session->userdata('session');
		
		echo $this->M_main->update_view($this->input->get('mail_id'), $session[0]->row_id);
	}
	
	public function save_forward()
	{
		$session = $this->session->userdata('session');
		
		if(empty($session[0]->username))
		{
			redirect('logout', 'refresh');
		}
		else
		{
			$status = $this->input->post('status');
			
			if($status == "DISPOSISI")
			{
				$mail_id = $this->input->post('row_id_fwd');
				$mail_to_level = $this->input->post('mail_to_level_fwd');
				$mail_to_user = $this->M_main->get_user($mail_to_level);
				
				$values = array(
					'mail_id' => $mail_id,
					'mail_to_level' => $mail_to_level,
					'mail_to_user' => $mail_to_user,
					'shared_by' => $session[0]->row_id,
					'viewed_by' => $session[0]->row_id,
					'status'=> $status,
					'mail_note'=> strtoupper($this->input->post('mail_note')),
					'mail_disposisi'=> $this->input->post('mail_disposisi'),
					'mail_disposisi_date' => date("Y-m-d"),
					'mail_ttd' => './signatures/'.$_FILES["mail_ttd"]["name"]
				);
			}
			else
			{
				$values = array(
					'mail_id' => $this->input->post('row_id_fwd'),
					'mail_to_level' => "",
					'mail_to_user' => "",
					'shared_by' => $session[0]->row_id,
					'viewed_by' => "",
					'status'=> $status,
					'mail_note'=> strtoupper($this->input->post('mail_note')),
					'mail_disposisi_date' => date("Y-m-d"),
					'mail_ttd' => './signatures/'.$_FILES["mail_ttd"]["name"]
				);
			}
			
			$target_dir	= './signatures/';
			$target_file = $target_dir . basename($_FILES["mail_ttd"]["name"]);
				
			move_uploaded_file($_FILES["mail_ttd"]["tmp_name"], $target_file);
			
			$save_forward = $this->M_main->save_forward($values);
					
			$msg = ($save_forward) ? array('msg_header' => "Berhasil", 'msg_detail' => "Surat berhasil ditindaklanjuti") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Surat gagal ditindaklanjuti");
			
			$this->session->set_flashdata('msg', $msg);
			
			redirect('mail', 'refresh');
		}
	}
	
	public function disposisi_pdf()
	{
		$session = $this->session->userdata('session');
		
		$data_main = $this->M_main->mail_main($this->input->get('mail_id'));
		$data_disposisi = $this->M_main->mail_disposisi($this->input->get('mail_id'));
		$app_info = $this->M_main->app_info();
		
		if(!empty($data_disposisi))
		{
			$this->load->library('Mc_tbl');
			
			$pdf=new PDF_MC_Table();
			
			// $pdf = new FPDF('P','mm', 'A4');
			$pdf->AddPage();
			
			$pdf->SetFont('Arial','B', 10);
			$pdf->Cell(1.2);
			$pdf->Cell(187, 5, $app_info[0]->app_company, 0, 1, 'L');
			
			$pdf->Ln(3);
			
			$pdf->SetFont('Arial','B', 10);
			$pdf->Cell(187, 5, 'LEMBAR DISPOSISI', 0, 1, 'C');
			
			$pdf->Ln(1);
			
			$pdf->SetFont('Arial','B', 8);
			$pdf->Cell(187, 5, 'NO. AGENDA : '.$data_main[0]->agenda_no, 0, 1, 'C');
			
			$pdf->Ln(5);
			
			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(2.5);
			$pdf->SetWidths(array(40,145));
			$pdf->SetAligns(array('L', 'L'));
			
			$pdf->Row(array("TERIMA DARI", $data_main[0]->company_name));
			
			$pdf->Ln(0);
			
			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(2.5);
			$pdf->SetWidths(array(40,52.5,40,52.5));
			$pdf->SetAligns(array('L', 'L'));
			
			$pdf->Row(array(
				"TANGGAL SURAT", 
				date("d-m-Y", strtotime($data_main[0]->mail_date)),
				"TANGGAL TERIMA", 
				date("d-m-Y", strtotime($data_main[0]->mail_receipt_date))
			));
			
			$pdf->Ln(0);
			
			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(2.5);
			$pdf->SetWidths(array(40,52.5,40,52.5));
			$pdf->SetAligns(array('L', 'L'));
			
			$pdf->Row(array(
				"NO. SURAT", 
				$data_main[0]->mail_no,
				"SIFAT SURAT", 
				$data_main[0]->level_name
			));
			
			$pdf->Ln(0);
			
			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(2.5);
			$pdf->SetWidths(array(40,145));
			$pdf->SetAligns(array('L', 'L'));
			
			$pdf->Row(array("PERIHAL", $data_main[0]->type_name));
			
			$pdf->Ln(0);
			
			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(2.5);
			$pdf->SetWidths(array(40,145));
			$pdf->SetAligns(array('L', 'L'));
			
			$pdf->Row(array("ISI SURAT", $data_main[0]->mail_content));
			
			$pdf->Ln(5);
			
			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(1.2);
			$pdf->Cell(187, 5, 'KEPADA YTH :', 0, 1, 'L');
			
			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(2.5);
			$pdf->SetWidths(array(30,30,30,30,35,30));
			$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C','C'));
			
			$pdf->Row(array(
				"TGL. PENYELESAIAN",
				"DIBAGIKAN OLEH",
				"DIBAGIKAN KE",
				"ISI DISPOSISI",
				"CATATAN DISPOSISI",
				"STATUS"
			));
			
			$pdf->SetFont('Arial','',7);
			$pdf->SetAligns(array('C', 'L', 'L', 'L', 'L','C'));
			
			foreach($data_disposisi as $rec)
			{
				$mail_disposisi_date = date("d-m-Y", strtotime($rec->mail_disposisi_date));
				$mail_disposisi = ($rec->mail_disposisi == "0") ? "-" : $rec->disposisi_name;
				$mail_to_user_name = (empty($rec->mail_to_user_name)) ? "-" : $rec->mail_to_user_name;
				$mail_note = (empty($rec->mail_note)) ? "-" : $rec->mail_note;
				
				$pdf->Cell(2.5);
				$pdf->Row(array(
					$mail_disposisi_date,
					$rec->shared_by,
					$mail_to_user_name,
					$mail_disposisi,
					$mail_note,
					$rec->status
				));
			}
			
			$pdf->Ln(5);
			
			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(1.2);
			$pdf->Cell(187, 5, 'TANDA TANGAN', 0, 1, 'L');
			
			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(2.5);
			$pdf->SetWidths(array(37,37,37,37,37));
			$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C'));
			
			$pdf->Row(array(
				"ADMIN",
				"UMUM",
				"PANITERA",
				"SEKRETARIS",
				"KETUA"
			));
			
			$get_ttd = $this->M_main->get_ttd($this->input->get('mail_id'));
			
			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(2.5);
			$pdf->SetWidths(array(37,37,37,37,37));
			$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C'));
			
			foreach($get_ttd as $rec)
			{
				$pdf->MultiCell(185, 26, '', 1, 'C', 0);
				$pdf->Cell(2.5);
				$pdf->Row(array(
					(!empty($rec->ttd_admin)) ? $pdf->Image($rec->ttd_admin,17,130,-400) : $pdf->Image('./images/empty.png',17,130,-400),
					(!empty($rec->ttd_umum)) ? $pdf->Image($rec->ttd_umum,58,130,-400) : $pdf->Image('./images/empty.png',58,130,-400),
					(!empty($rec->ttd_manager)) ? $pdf->Image($rec->ttd_manager,93,130,-400) : $pdf->Image('./images/empty.png',93,130,-400),
					(!empty($rec->ttd_keuangan)) ? $pdf->Image($rec->ttd_keuangan,130,130,-400) : $pdf->Image('./images/empty.png',130,130,-400),
					(!empty($rec->ttd_kepala)) ? $pdf->Image($rec->ttd_kepala,165,130,-400) : $pdf->Image('./images/empty.png',165,130,-400),
				));
			}
			
			$pdf->Output();
		}
	}
	
	public function company()
	{
		$session = $this->session->userdata('session');
		
		if(empty($session[0]->username))
		{
			redirect('logout', 'refresh');
		}
		else
		{
			$src = strtoupper($this->input->get('src'));
			$page = strtoupper($this->input->get('page'));
			
			$include['app_info'] = $this->M_main->app_info();
			$include['master_company'] = $this->M_main->master_company($src, $page);
			$include['size_master_company'] = $this->M_main->size_master_company($src);
			$include['src'] = $src;
			
			$this->load->view('V_master/V_company', $include);
		}
	}
	
	public function save_company()
	{
		$session = $this->session->userdata('session');
		
		if(empty($session[0]->username))
		{
			redirect('logout', 'refresh');
		}
		else
		{
			$action = $this->input->post('act');
			$company_id = $this->input->post('company_id');
			
			switch($action)
			{
				case "TAMBAH INSTANSI":
					$values = array(
						'company_name' => strtoupper($this->input->post('company_name'))
					);
					
					$save_company = $this->M_main->save_company($action, $values);
					
					$msg = ($save_company) ? array('msg_header' => "Berhasil", 'msg_detail' => "Instansi berhasil dibuat") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Instansi gagal dibuat");
				break;
				
				case "UBAH INSTANSI":
					$values = array(
						'company_name' => strtoupper($this->input->post('company_name'))
					);
					
					$save_company = $this->M_main->save_company($action, $values, $company_id);
					
					$msg = ($save_company) ? array('msg_header' => "Berhasil", 'msg_detail' => "Instansi berhasil diperbarui") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Instansi gagal diperbarui");
				break;
				
				case "HAPUS INSTANSI":
					$values = array('active' => "0");
					
					$save_company = $this->M_main->save_company($action, $values, $company_id);
					
					$msg = ($save_company) ? array('msg_header' => "Berhasil", 'msg_detail' => "Instansi berhasil dihapus") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Instansi gagal dihapus");
				break;
			}
			
			$this->session->set_flashdata('msg', $msg);
			
			redirect('company', 'refresh');
		}
	}
	
	public function mail_level()
	{
		$session = $this->session->userdata('session');
		
		if(empty($session[0]->username))
		{
			redirect('logout', 'refresh');
		}
		else
		{
			$src = strtoupper($this->input->get('src'));
			$page = strtoupper($this->input->get('page'));
			
			$include['app_info'] = $this->M_main->app_info();
			$include['master_mail_level'] = $this->M_main->master_mail_level($src, $page);
			$include['size_master_mail_level'] = $this->M_main->size_master_mail_level($src);
			$include['src'] = $src;
			
			$this->load->view('V_master/V_mail_level', $include);
		}
	}
	
	public function save_mail_level()
	{
		$session = $this->session->userdata('session');
		
		if(empty($session[0]->username))
		{
			redirect('logout', 'refresh');
		}
		else
		{
			$action = $this->input->post('act');
			$level_id = $this->input->post('level_id');
			
			switch($action)
			{
				case "TAMBAH LEVEL SURAT":
					$values = array(
						'level_name' => strtoupper($this->input->post('level_name'))
					);
					
					$save_mail_level = $this->M_main->save_mail_level($action, $values);
					
					$msg = ($save_mail_level) ? array('msg_header' => "Berhasil", 'msg_detail' => "Level surat berhasil dibuat") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Level surat gagal dibuat");
				break;
				
				case "UBAH LEVEL SURAT":
					$values = array(
						'level_name' => strtoupper($this->input->post('level_name'))
					);
					
					$save_mail_level = $this->M_main->save_mail_level($action, $values, $level_id);
					
					$msg = ($save_mail_level) ? array('msg_header' => "Berhasil", 'msg_detail' => "Level surat berhasil diperbarui") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Level surat gagal diperbarui");
				break;
				
				case "HAPUS LEVEL SURAT":
					$values = array('active' => "0");
					
					$save_mail_level = $this->M_main->save_mail_level($action, $values, $level_id);
					
					$msg = ($save_mail_level) ? array('msg_header' => "Berhasil", 'msg_detail' => "Level surat berhasil dihapus") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Level surat gagal dihapus");
				break;
			}
			
			$this->session->set_flashdata('msg', $msg);
			
			redirect('mail_level', 'refresh');
		}
	}
	
	public function mail_type()
	{
		$session = $this->session->userdata('session');
		
		if(empty($session[0]->username))
		{
			redirect('logout', 'refresh');
		}
		else
		{
			$src = strtoupper($this->input->get('src'));
			$page = strtoupper($this->input->get('page'));
			
			$include['app_info'] = $this->M_main->app_info();
			$include['master_mail_type'] = $this->M_main->master_mail_type($src, $page);
			$include['size_master_mail_type'] = $this->M_main->size_master_mail_type($src);
			$include['src'] = $src;
			
			$this->load->view('V_master/V_mail_type', $include);
		}
	}
	
	public function save_mail_type()
	{
		$session = $this->session->userdata('session');
		
		if(empty($session[0]->username))
		{
			redirect('logout', 'refresh');
		}
		else
		{
			$action = $this->input->post('act');
			$type_id = $this->input->post('type_id');
			
			switch($action)
			{
				case "TAMBAH JENIS SURAT":
					$values = array(
						'type_name' => strtoupper($this->input->post('type_name'))
					);
					
					$save_mail_type = $this->M_main->save_mail_type($action, $values);
					
					$msg = ($save_mail_type) ? array('msg_header' => "Berhasil", 'msg_detail' => "Level surat berhasil dibuat") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Level surat gagal dibuat");
				break;
				
				case "UBAH JENIS SURAT":
					$values = array(
						'type_name' => strtoupper($this->input->post('type_name'))
					);
					
					$save_mail_type = $this->M_main->save_mail_type($action, $values, $type_id);
					
					$msg = ($save_mail_type) ? array('msg_header' => "Berhasil", 'msg_detail' => "Level surat berhasil diperbarui") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Level surat gagal diperbarui");
				break;
				
				case "HAPUS JENIS SURAT":
					$values = array('active' => "0");
					
					$save_mail_type = $this->M_main->save_mail_type($action, $values, $type_id);
					
					$msg = ($save_mail_type) ? array('msg_header' => "Berhasil", 'msg_detail' => "Level surat berhasil dihapus") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Level surat gagal dihapus");
				break;
			}
			
			$this->session->set_flashdata('msg', $msg);
			
			redirect('mail_type', 'refresh');
		}
	}
	
	public function user()
	{
		$session = $this->session->userdata('session');
		
		if(empty($session[0]->username))
		{
			redirect('logout', 'refresh');
		}
		else
		{
			$src = strtoupper($this->input->get('src'));
			$page = strtoupper($this->input->get('page'));
			
			$include['app_info'] = $this->M_main->app_info();
			$include['master_user_level'] = $this->M_main->master_user_level(null);
			$include['master_user'] = $this->M_main->master_user($src, $page);
			$include['size_master_user'] = $this->M_main->size_master_user($src);
			$include['src'] = $src;
			
			$this->load->view('V_master/V_user', $include);
		}
	}
	
	public function save_user()
	{
		$session = $this->session->userdata('session');
		
		if(empty($session[0]->username))
		{
			redirect('logout', 'refresh');
		}
		else
		{
			$action = $this->input->post('act');
			$row_id = $this->input->post('row_id');
			
			switch($action)
			{
				case "TAMBAH USER":
					$values = array(
						'username' => strtoupper($this->input->post('username')),
						'level_id' => $this->input->post('level_id'),
						'password' => password_hash("123", PASSWORD_DEFAULT)
					);
					
					$save_user = $this->M_main->save_user($action, $values);
					
					$msg = ($save_user) ? array('msg_header' => "Berhasil", 'msg_detail' => "Pengguna berhasil dibuat") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Pengguna gagal dibuat");
				break;
				
				case "UBAH USER":
					$values = array(
						'username' => strtoupper($this->input->post('username')),
						'level_id' => $this->input->post('level_id')
					);
					
					$save_user = $this->M_main->save_user($action, $values, $row_id);
					
					$msg = ($save_user) ? array('msg_header' => "Berhasil", 'msg_detail' => "Pengguna berhasil diperbarui") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Pengguna gagal diperbarui");
				break;
				
				case "HAPUS USER":
					$values = array('active' => "0");
					
					$save_user = $this->M_main->save_user($action, $values, $row_id);
					
					$msg = ($save_user) ? array('msg_header' => "Berhasil", 'msg_detail' => "Pengguna berhasil dihapus") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Pengguna gagal dihapus");
				break;
			}
			
			$this->session->set_flashdata('msg', $msg);
			
			redirect('user', 'refresh');
		}
	}
	
	public function user_level()
	{
		$session = $this->session->userdata('session');
		
		if(empty($session[0]->username))
		{
			redirect('logout', 'refresh');
		}
		else
		{
			$src = strtoupper($this->input->get('src'));
			$page = strtoupper($this->input->get('page'));
			
			$include['app_info'] = $this->M_main->app_info();
			$include['master_user_level'] = $this->M_main->master_user_level($src, $page);
			$include['size_master_user_level'] = $this->M_main->size_master_user_level($src);
			$include['src'] = $src;
			
			$this->load->view('V_master/V_user_level', $include);
		}
	}
	
	public function save_user_level()
	{
		$session = $this->session->userdata('session');
		
		if(empty($session[0]->username))
		{
			redirect('logout', 'refresh');
		}
		else
		{
			$action = $this->input->post('act');
			$level_id = $this->input->post('level_id');
			
			switch($action)
			{
				case "TAMBAH LEVEL PENGGUNA":
					$values = array(
						'level_name' => strtoupper($this->input->post('level_name'))
					);
					
					$save_user_level = $this->M_main->save_user_level($action, $values);
					
					$msg = ($save_user_level) ? array('msg_header' => "Berhasil", 'msg_detail' => "Level pengguna berhasil dibuat") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Level pengguna gagal dibuat");
				break;
				
				case "UBAH LEVEL PENGGUNA":
					$values = array(
						'level_name' => strtoupper($this->input->post('level_name'))
					);
					
					$save_user_level = $this->M_main->save_user_level($action, $values, $level_id);
					
					$msg = ($save_user_level) ? array('msg_header' => "Berhasil", 'msg_detail' => "Level pengguna berhasil diperbarui") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Level pengguna gagal diperbarui");
				break;
				
				case "HAPUS LEVEL PENGGUNA":
					$values = array('active' => "0");
					
					$save_user_level = $this->M_main->save_user_level($action, $values, $level_id);
					
					$msg = ($save_user_level) ? array('msg_header' => "Berhasil", 'msg_detail' => "Level pengguna berhasil dihapus") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Level pengguna gagal dihapus");
				break;
			}
			
			$this->session->set_flashdata('msg', $msg);
			
			redirect('user_level', 'refresh');
		}
	}
	
	public function disposisi()
	{
		$session = $this->session->userdata('session');
		
		if(empty($session[0]->username))
		{
			redirect('logout', 'refresh');
		}
		else
		{
			$src = strtoupper($this->input->get('src'));
			$page = strtoupper($this->input->get('page'));
			
			$include['app_info'] = $this->M_main->app_info();
			$include['master_user_level'] = $this->M_main->master_user_level(null);
			$include['master_disposisi'] = $this->M_main->master_disposisi($src, $page);
			$include['size_master_disposisi'] = $this->M_main->size_master_disposisi($src);
			$include['src'] = $src;
			
			$this->load->view('V_master/V_disposisi', $include);
		}
	}
	
	public function save_disposisi()
	{
		$session = $this->session->userdata('session');
		
		if(empty($session[0]->username))
		{
			redirect('logout', 'refresh');
		}
		else
		{
			$action = $this->input->post('act');
			$disposisi_id = $this->input->post('disposisi_id');
			
			switch($action)
			{
				case "TAMBAH DISPOSISI":
					$values = array(
						'disposisi_name' => strtoupper($this->input->post('disposisi_name'))
					);
					
					$save_disposisi = $this->M_main->save_disposisi($action, $values);
					
					$msg = ($save_disposisi) ? array('msg_header' => "Berhasil", 'msg_detail' => "Disposisi berhasil dibuat") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Disposisi gagal dibuat");
				break;
				
				case "UBAH DISPOSISI":
					$values = array(
						'disposisi_name' => strtoupper($this->input->post('disposisi_name'))
					);
					
					$save_disposisi = $this->M_main->save_disposisi($action, $values, $disposisi_id);
					
					$msg = ($save_disposisi) ? array('msg_header' => "Berhasil", 'msg_detail' => "Disposisi berhasil diperbarui") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Disposisi gagal diperbarui");
				break;
				
				case "HAPUS DISPOSISI":
					$values = array('active' => "0");
					
					$save_disposisi = $this->M_main->save_disposisi($action, $values, $disposisi_id);
					
					$msg = ($save_disposisi) ? array('msg_header' => "Berhasil", 'msg_detail' => "Disposisi berhasil dihapus") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Disposisi gagal dihapus");
				break;
			}
			
			$this->session->set_flashdata('msg', $msg);
			
			redirect('disposisi', 'refresh');
		}
	}
	
	public function save_app_info()
	{
		$session = $this->session->userdata('session');
		
		if(empty($session[0]->username))
		{
			redirect('logout', 'refresh');
		}
		else
		{
			$values = array(
				'app_title' => $this->input->post('app_title'),
				'app_subtitle' => $this->input->post('app_subtitle'),
				'app_company' => $this->input->post('app_company')
			);
			
			$save_app_info = $this->M_main->save_app_info($values);
					
			$msg = ($save_app_info) ? array('msg_header' => "Berhasil", 'msg_detail' => "Informasi aplikasi berhasil diperbarui") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Informasi aplikasi gagal diperbarui");
			
			$this->session->set_flashdata('msg', $msg);
			
			redirect('mail', 'refresh');
		}
	}
	
	public function save_user_profile()
	{
		$session = $this->session->userdata('session');
		
		if(empty($session[0]->username))
		{
			redirect('logout', 'refresh');
		}
		else
		{
			$values = array(
				'username' => $this->input->post('user_name'),
				'password' => password_hash(strtoupper($this->input->post('user_pass')), PASSWORD_DEFAULT)
			);
			
			$save_user_profile = $this->M_main->save_user_profile($values, $this->input->post('user_id'));
					
			$msg = ($save_user_profile) ? array('msg_header' => "Berhasil", 'msg_detail' => "Profil berhasil diperbarui") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Profil gagal diperbarui");
			
			$this->session->set_flashdata('msg', $msg);
			
			redirect('logout', 'refresh');
		}
	}
	
	public function get_user_list()
	{
		echo $this->M_main->get_user_list($this->input->get('level_id'));
	}
	
	public function get_opt_disposisi()
	{
		echo $this->M_main->get_opt_disposisi($this->input->get('level_id'));
	}
	
	public function mail_history()
	{
		$session = $this->session->userdata('session');
		
		if(empty($session[0]->username))
		{
			redirect('logout', 'refresh');
		}
		else
		{
			$include['app_info'] = $this->M_main->app_info();
			$include['mail_main'] = $this->M_main->mail_main($this->input->get('mail_id'));
			$include['mail_disposisi'] = $this->M_main->mail_disposisi($this->input->get('mail_id'));
			
			$this->load->view('V_mail_history', $include);
		}
	}
	
	public function save_forward_kepala()
	{
		$session = $this->session->userdata('session');
		
		if(empty($session[0]->username))
		{
			redirect('logout', 'refresh');
		}
		else
		{
			$status = $this->input->post('status');
			
			if($status == "DISPOSISI")
			{
				$mail_id = $this->input->post('row_id_fwd_kepala');
				$mail_to_level = $this->input->post('mail_to_level');
				$mail_disposisi = $this->input->post('mail_disposisi');
				
				for($i=0; $i<sizeof($mail_to_level); $i++)
				{
					$values[] = array(
						'mail_id' => $mail_id,
						'mail_to_level' => $mail_to_level[$i],
						'mail_to_user' => $this->M_main->get_user($mail_to_level[$i]),
						'shared_by' => $session[0]->row_id,
						'viewed_by' => $session[0]->row_id,
						'status'=> $status,
						'mail_note'=> strtoupper($this->input->post('mail_note')),
						'mail_disposisi'=> $mail_disposisi[$i],
						'mail_disposisi_date' => date("Y-m-d"),
						'mail_ttd' => './signatures/'.$_FILES["mail_ttd"]["name"]
					);
				}
			}
			else
			{
				$values = array(
					'mail_id' => $this->input->post('row_id_fwd_kepala'),
					'mail_to_level' => "",
					'mail_to_user' => "",
					'shared_by' => $session[0]->row_id,
					'viewed_by' => "",
					'status'=> $status,
					'mail_note'=> strtoupper($this->input->post('mail_note')),
					'mail_disposisi_date' => date("Y-m-d"),
					'mail_ttd' => './signatures/'.$_FILES["mail_ttd"]["name"]
				);
			}
			
			$target_dir	= './signatures/';
			$target_file = $target_dir . basename($_FILES["mail_ttd"]["name"]);
				
			move_uploaded_file($_FILES["mail_ttd"]["tmp_name"], $target_file);
			
			$save_forward_kepala = $this->M_main->save_forward_kepala($values, $status);
					
			$msg = ($save_forward_kepala) ? array('msg_header' => "Berhasil", 'msg_detail' => "Surat berhasil ditindaklanjuti") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Surat gagal ditindaklanjuti");
			
			$this->session->set_flashdata('msg', $msg);
			
			redirect('mail', 'refresh');
		}
	}
	
	public function download_rekap()
	{
		$rekap_from = $this->input->post('rekap_from');
		$rekap_to = $this->input->post('rekap_to');
		$rekap_type = $this->input->post('rekap_type');
		$mail_type = $this->input->post('mail_type');
		
		$app_info = $this->M_main->app_info();
			$data_rekap = $this->M_main->data_rekap($rekap_from,$rekap_to, $mail_type);
		
		if($mail_type == "Surat Masuk")
		{
			if($rekap_type == "EXCEL")
			{
				$filename = "REKAP SURAT (".strtoupper(date('d M Y', strtotime($rekap_from)))." - ".strtoupper(date('d M Y', strtotime($rekap_to))).")";
				
				$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('./template/template_rekap.xlsx');
				$sheet = $spreadsheet->getActiveSheet();
				
				$sheet->setCellValue('A2', strtoupper($app_info[0]->app_title." (".$app_info[0]->app_subtitle.")"));
				$sheet->setCellValue('A3', strtoupper($app_info[0]->app_company));
				
				$sheet->setCellValue('B6', strtoupper(date('d M Y', strtotime($rekap_from))));
				$sheet->setCellValue('B7', strtoupper(date('d M Y', strtotime($rekap_to))));
				
				$row = 10;
				
				$style_data = array(
					'font'  => array('size'  => 10),
					'borders' => array(
						'allBorders' => array(
							'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
							'color' => array('argb' => '000'),
						),
					)
				);
				
				$no = 1;
				
				foreach($data_rekap as $rec)
				{
					$sheet->setCellValue('A'.$row, $no++);
					$sheet->setCellValue('B'.$row, strtoupper(date('d-m-Y', strtotime($rec->mail_date))));
					$sheet->setCellValue('C'.$row, strtoupper(date('d-m-Y', strtotime($rec->mail_receipt_date))));
					$sheet->setCellValue('D'.$row, $rec->agenda_no);
					$sheet->setCellValue('E'.$row, $rec->mail_no);
					$sheet->setCellValue('F'.$row, $rec->mail_type);
					$sheet->setCellValue('G'.$row, $rec->mail_level);
					$sheet->setCellValue('H'.$row, $rec->mail_from);
					$sheet->setCellValue('I'.$row, $this->M_main->get_level_name(substr($rec->mail_to_level,0,strpos($rec->mail_to_level,",1"))));
					$sheet->setCellValue('J'.$row, $rec->mail_content);
					$sheet->setCellValue('K'.$row, " ");
					
					$sheet->getStyle('A'.$row.':J'.$row)->applyFromArray($style_data);
					
					$row++;
				}
				
				$writer = new Xlsx($spreadsheet);
					
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');
				$writer->save('php://output');
			}
			else
			{
				$app_info = $this->M_main->app_info();
				
				$this->load->library('Mc_tbl');
				
				$pdf=new PDF_MC_Table();
				
				$pdf->AddPage('L', 'A4');
				
				$pdf->SetFont('Arial','B', 10);
				$pdf->Cell(280, 5, 'REKAP SURAT', 0, 1, 'C');
				
				$pdf->Ln(1);
				
				$pdf->SetFont('Arial','B', 8);
				$pdf->Cell(280, 5, strtoupper($app_info[0]->app_subtitle), 0, 1, 'C');
				
				$pdf->Ln(1);
				
				$pdf->SetFont('Arial','B', 8);
				$pdf->Cell(280, 5, strtoupper($app_info[0]->app_company), 0, 1, 'C');
				
				$pdf->Ln(1);
				
				$pdf->SetFont('Arial','', 6);
				$pdf->Cell(280, 5, strtoupper(date("d M Y", strtotime($rekap_from)))." - ".strtoupper(date("d M Y", strtotime($rekap_to))), 0, 1, 'C');
				
				$pdf->Ln(5);
				
				$pdf->SetFont('Arial','B',7);
				$pdf->Cell(2.5);
				$pdf->SetWidths(array(15,20,20,25,25,30,30,30,30,48));
				$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C','C','C', 'C', 'C', 'C', 'C'));
				
				$pdf->Row(array(
					"NO",
					"TGL SURAT",
					"TGL DITERIMA",
					"NO. AGENDA",
					"NO. SURAT",
					"SIFAT SURAT",
					"PERIHAL",
					"TERIMA DARI",
					"DIBAGIKAN KE",
					"ISI SURAT"
				));
				
				$no = 1;
				
				$pdf->SetFont('Arial','',7);
				$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C','C','C', 'C', 'C', 'L'));
				
				foreach($data_rekap as $rec)
				{
					$pdf->Cell(2.5);
					$pdf->Row(array(
						$no++,
						date("d-m-Y", strtotime($rec->mail_date)),
						date("d-m-Y", strtotime($rec->mail_receipt_date)),
						$rec->agenda_no,
						$rec->mail_no,
						$rec->mail_level,
						$rec->mail_type,
						$rec->mail_from,
						$this->M_main->get_level_name(substr($rec->mail_to_level,0,strpos($rec->mail_to_level,",1"))),
						$rec->mail_content
					));
				}
				
				$pdf->Ln(1);
				
				$pdf->SetFont('Arial','', 6);
				$pdf->Cell(276, 5, date("d-m-Y : H:i"), 0, 1, 'R');
				
				$pdf->Output('D', "REKAP SURAT - ".strtoupper($app_info[0]->app_company)." (".strtoupper(date("d M Y", strtotime($rekap_from)))." - ".strtoupper(date("d M Y", strtotime($rekap_to))).").pdf");
			}
		}
		else
		{
			if($rekap_type == "EXCEL")
			{
				$filename = "REKAP SURAT KELUAR (".strtoupper(date('d M Y', strtotime($rekap_from)))." - ".strtoupper(date('d M Y', strtotime($rekap_to))).")";
				
				$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('./template/template_rekap_keluar.xlsx');
				$sheet = $spreadsheet->getActiveSheet();
				
				$sheet->setCellValue('A2', strtoupper($app_info[0]->app_title." (".$app_info[0]->app_subtitle.")"));
				$sheet->setCellValue('A3', strtoupper($app_info[0]->app_company));
				
				$sheet->setCellValue('B6', strtoupper(date('d M Y', strtotime($rekap_from))));
				$sheet->setCellValue('B7', strtoupper(date('d M Y', strtotime($rekap_to))));
				
				$row = 10;
				
				$style_data = array(
					'font'  => array('size'  => 10),
					'borders' => array(
						'allBorders' => array(
							'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
							'color' => array('argb' => '000'),
						),
					)
				);
				
				$no = 1;
				
				foreach($data_rekap as $rec)
				{
					$sheet->setCellValue('A'.$row, $no++);
					$sheet->setCellValue('B'.$row, strtoupper(date('d-m-Y', strtotime($rec->mail_date))));
					$sheet->setCellValue('C'.$row, $rec->agenda_no);
					$sheet->setCellValue('D'.$row, $rec->mail_no);
					$sheet->setCellValue('E'.$row, $rec->klasifikasi_no);
					$sheet->setCellValue('F'.$row, $rec->mail_purpose);
					$sheet->setCellValue('G'.$row, $rec->mail_content);
					$sheet->setCellValue('H'.$row, $rec->mail_note);
					
					$sheet->getStyle('A'.$row.':H'.$row)->applyFromArray($style_data);
					
					$row++;
				}
				
				$writer = new Xlsx($spreadsheet);
					
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');
				$writer->save('php://output');
			}
			else
			{
				$app_info = $this->M_main->app_info();
				
				$this->load->library('Mc_tbl');
				
				$pdf=new PDF_MC_Table();
				
				$pdf->AddPage('L', 'A4');
				
				$pdf->SetFont('Arial','B', 10);
				$pdf->Cell(280, 5, 'REKAP SURAT', 0, 1, 'C');
				
				$pdf->Ln(1);
				
				$pdf->SetFont('Arial','B', 8);
				$pdf->Cell(280, 5, strtoupper($app_info[0]->app_subtitle), 0, 1, 'C');
				
				$pdf->Ln(1);
				
				$pdf->SetFont('Arial','B', 8);
				$pdf->Cell(280, 5, strtoupper($app_info[0]->app_company), 0, 1, 'C');
				
				$pdf->Ln(1);
				
				$pdf->SetFont('Arial','', 6);
				$pdf->Cell(280, 5, strtoupper(date("d M Y", strtotime($rekap_from)))." - ".strtoupper(date("d M Y", strtotime($rekap_to))), 0, 1, 'C');
				
				$pdf->Ln(5);
				
				$pdf->SetFont('Arial','B',7);
				$pdf->Cell(2.5);
				$pdf->SetWidths(array(15,30,30,35,35,30,49,49));
				$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C','C','C', 'C', 'C'));
				
				$pdf->Row(array(
					"NO",
					"TGL SURAT",
					"NO. AGENDA",
					"NO. SURAT",
					"KODE KLASIFIKASI",
					"TUJUAN SURAT",
					"ISI RINGKAS",
					"KETERANGAN"
				));
				
				$no = 1;
				
				$pdf->SetFont('Arial','',7);
				$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C','C','C', 'C'));
				
				foreach($data_rekap as $rec)
				{
					$pdf->Cell(2.5);
					$pdf->Row(array(
						$no++,
						date("d-m-Y", strtotime($rec->mail_date)),
						$rec->agenda_no,
						$rec->mail_no,
						$rec->klasifikasi_no,
						$rec->mail_purpose,
						$rec->mail_content,
						$rec->mail_content
					));
				}
				
				$pdf->Ln(1);
				
				$pdf->SetFont('Arial','', 6);
				$pdf->Cell(276, 5, date("d-m-Y : H:i"), 0, 1, 'R');
				
				$pdf->Output('D', "REKAP SURAT KELUAR - ".strtoupper($app_info[0]->app_company)." (".strtoupper(date("d M Y", strtotime($rekap_from)))." - ".strtoupper(date("d M Y", strtotime($rekap_to))).").pdf");
			}
		}
	}
	
	public function mail_outbox()
	{
		$session = $this->session->userdata('session');
		
		if(empty($session[0]->username))
		{
			redirect('logout', 'refresh');
		}
		else
		{
			$src = strtoupper($this->input->get('src'));
			$page = strtoupper($this->input->get('page'));
			
			$include['mail_info'] = $this->M_main->mail_info($session[0]->level_id, $session[0]->row_id);
			$include['app_info'] = $this->M_main->app_info();
			$include['mail_outbox'] = $this->M_main->mail_outbox($src, $page);
			$include['size_mailoutbox_list'] = $this->M_main->size_mailoutbox_list($src);
			$include['src'] = $src;
			
			$this->load->view('V_mailoutbox', $include);
		}
	}
	
	public function save_mailoutbox()
	{
		$session = $this->session->userdata('session');
		
		if(empty($session[0]->username))
		{
			redirect('logout', 'refresh');
		}
		else
		{
			$action = $this->input->post('act');
			$row_id = $this->input->post('row_id');
			$mail_file_del = $this->input->post('mail_file_del');
			
			switch($action)
			{
				case "TAMBAH SURAT KELUAR":
					$values = array(
						'agenda_no' => strtoupper($this->input->post('agenda_no')),
						'klasifikasi_no' => strtoupper($this->input->post('klasifikasi_no')),
						'mail_purpose' => strtoupper($this->input->post('mail_purpose')),
						'mail_date' => $this->input->post('mail_date'),
						'mail_no' => strtoupper($this->input->post('mail_no')),
						'mail_file' => './mailoutbox/'.$_FILES["mail_file"]["name"],
						'mail_content' => strtoupper($this->input->post('mail_content')),
						'mail_note' => strtoupper($this->input->post('mail_note'))
					);
					
					$target_dir	= './mailoutbox/';
					$target_file = $target_dir . basename($_FILES["mail_file"]["name"]);
						
					move_uploaded_file($_FILES["mail_file"]["tmp_name"], $target_file);
					
					$save_mailoutbox = $this->M_main->save_mailoutbox($action, $values);
					
					$msg = ($save_mailoutbox) ? array('msg_header' => "Berhasil", 'msg_detail' => "Surat Keluar berhasil dibuat") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Surat Keluar  gagal dibuat");
				break;
				
				case "UBAH SURAT KELUAR":
					$values = array(
						'agenda_no' => strtoupper($this->input->post('agenda_no')),
						'klasifikasi_no' => strtoupper($this->input->post('klasifikasi_no')),
						'mail_purpose' => strtoupper($this->input->post('mail_purpose')),
						'mail_date' => $this->input->post('mail_date'),
						'mail_no' => strtoupper($this->input->post('mail_no')),
						'mail_file' => './mailoutbox/'.$_FILES["mail_file"]["name"],
						'mail_content' => strtoupper($this->input->post('mail_content')),
						'mail_note' => strtoupper($this->input->post('mail_note'))
					);
					
					if(!empty($_FILES["mail_file"]["name"]))
					{
						unlink($mail_file_del);
				
						$values['mail_file'] = './mailoutbox/'.$_FILES["mail_file"]["name"];
						
						$target_dir	= './mailoutbox/';
						$target_file = $target_dir . basename($_FILES["mail_file"]["name"]);
							
						move_uploaded_file($_FILES["mail_file"]["tmp_name"], $target_file);
					}
					
					$save_mailoutbox = $this->M_main->save_mailoutbox($action, $values, $row_id);
					
					$msg = ($save_mailoutbox) ? array('msg_header' => "Berhasil", 'msg_detail' => "Surat Keluar  berhasil diperbarui") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Surat Keluar  gagal diperbarui");
				break;
				
				case "HAPUS SURAT KELUAR":
					unlink($mail_file_del);
					
					$save_mailoutbox = $this->M_main->save_mailoutbox($action, null, $row_id);
					
					$msg = ($save_mailoutbox) ? array('msg_header' => "Berhasil", 'msg_detail' => "Surat Keluar  berhasil dihapus") : array('msg_header' => "Mohon Maaf", 'msg_detail' => "Surat Keluar  gagal dihapus");
				break;
			}
			
			$this->session->set_flashdata('msg', $msg);
			
			redirect('mail_outbox', 'refresh');
		}
	}
	
	public function logout()
	{
		$this->session->unset_userdata('session');
			
		redirect(base_url(), 'refresh');
	}
}
