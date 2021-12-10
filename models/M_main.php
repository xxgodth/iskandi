<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class M_main extends CI_Model
{
	public function verify($username, $password)
	{
		$query = $this->db->query("
			SELECT a.*, b.level_name AS levelname
			FROM tb_user a
			JOIN tb_user_level b ON b.level_id = a.level_id
			WHERE a.username = '$username'
			AND a.active = '1'
		");
		
		if($query->num_rows() > 0)
		{
			if(password_verify($password, $query->result()[0]->password))
			{
				return $query->result();
			}
			else
			{
				return array();
			}
		}
		else
		{
			return array();
		}
	}
	
	public function mail_type_list()
	{
		$query = $this->db->query("
			SELECT *
			FROM tb_mail_type
			WHERE active = '1'
		");
		
		return $query->result();
	}
	
	public function disposisi_list()
	{
		$query = $this->db->query("
			SELECT *
			FROM tb_disposisi
			WHERE active = '1'
		");
		
		return $query->result();
	}
	
	public function mail_level_list()
	{
		$query = $this->db->query("
			SELECT *
			FROM tb_mail_level
			WHERE active = '1'
		");
		
		return $query->result();
	}
	
	public function user_list($user_id)
	{
		$query = $this->db->query("
			SELECT a.*, b.level_name
			FROM tb_user a
			JOIN tb_user_level b ON b.level_id = a.level_id
			WHERE a.active = '1'
			AND a.level_id <> '1'
			AND a.row_id <> '$user_id'
		");
		
		return $query->result();
	}
	
	public function user_level_list()
	{
		$query = $this->db->query("
			SELECT *
			FROM tb_user_level
			WHERE active = '1'
			AND level_id <> '1'
		");
		
		return $query->result();
	}
	
	public function company_list()
	{
		$query = $this->db->query("
			SELECT *
			FROM tb_company
			WHERE active = '1'
		");
		
		return $query->result();
	}
	
	public function size_mail_list($src)
	{
		$where = (!empty($src)) ? "AND (a.agenda_no LIKE '%$src%' OR a.mail_no LIKE '%$src%' OR g.company_name LIKE '%$src%')" : "";
		
		$query = $this->db->query("
			SELECT 
				a.*, 
				b.level_name, 
				c.username AS shared_by
			FROM tb_mail_admin a
			JOIN tb_mail_level b ON b.level_id = a.mail_level
			JOIN tb_user c ON c.row_id = a.shared_by
			JOIN tb_company g ON g.company_id = a.mail_from
			WHERE a.active = '1'
			AND a.shared_by = '1' 
			$where
			ORDER BY a.viewed_by ASC
		");
		
		return $query->num_rows();
	}
	
	public function mail_list($src, $page = null, $user_id)
	{
		$where = (!empty($src)) ? "AND (a.agenda_no LIKE '%$src%' OR a.mail_no LIKE '%$src%' OR g.company_name LIKE '%$src%')" : "";
		
		if(empty($page) OR $page == 1)
		{
			$limit = 'LIMIT 0, 20';
		}
		else
		{
			$limit = "LIMIT ".($page*20-20).", 20";
		}
		
		$query = $this->db->query("
			SELECT 
				a.*, 
				b.level_name, 
				c.username AS shared_by, 
				d.status, 
				d.mail_note, 
				d.mail_disposisi, 
				d.mail_disposisi_date,
				e.username AS forward_by,
				f.disposisi_name,
				(SELECT COUNT(status) FROM tb_mail_user WHERE mail_id = a.row_id AND status = 'SELESAI') AS stop_forward,
				g.company_name
			FROM tb_mail_admin a
			JOIN tb_mail_level b ON b.level_id = a.mail_level
			JOIN tb_user c ON c.row_id = a.shared_by
			LEFT JOIN tb_mail_user d ON (d.mail_id = a.row_id AND d.mail_to_user LIKE '%$user_id%')
			LEFT JOIN tb_user e ON e.row_id = d.shared_by
			LEFT JOIN tb_disposisi f ON f.disposisi_id = d.mail_disposisi
			JOIN tb_company g ON g.company_id = a.mail_from
			WHERE a.active = '1'
			AND a.shared_by = '1' 
			AND a.mail_to_user LIKE '%$user_id%'
			$where
			ORDER BY a.row_id DESC
			$limit
		");
		
		return $query->result();
	}
	
	public function save_mail($action, $values, $row_id = null, $mail_file_del = null)
	{
		switch($action)
		{
			case "TAMBAH SURAT":
				$query = $this->db->insert('tb_mail_admin', $values);
			break;
			
			case "UBAH SURAT":
				$query = $this->db->update('tb_mail_admin', $values, array('row_id' => $row_id));
			break;
			
			case "HAPUS SURAT":
				$chk_forward = $this->db->query("SELECT * FROM tb_mail_user WHERE mail_id = '$row_id'");
				
				if($chk_forward->num_rows() > 0)
				{
					return false;
				}
				else
				{
					$query = $this->db->update('tb_mail_admin', $values, array('row_id' => $row_id));
				}
			break;
		}
		
		if($query)
		{
			return 'OK';
		}
		else
		{
			return 'FAIL';
		}
	}
	
	public function get_mail_inbox($user_id)
	{
		$query = $this->db->query("
			SELECT *
			FROM
			(
				SELECT count(*) AS mail_to_level
				FROM tb_mail_admin
				WHERE mail_to_user LIKE '%$user_id%'
				AND shared_by = '1'
				AND active = '1'    
			) AS data1,
			(
				SELECT count(*) AS mail_read
				FROM tb_mail_admin
				WHERE viewed_by LIKE '%$user_id%'
				AND shared_by = '1'
				AND active = '1'    
			) AS data2
		");
		
		$mail_unread = $query->row_array()['mail_to_level'] - $query->row_array()['mail_read'];
		
		return json_encode(array('result' => $mail_unread));
	}
	
	public function update_view($mail_id, $user_id)
	{
		$get_existing_view = $this->db->query("SELECT shared_by, viewed_by FROM tb_mail_admin WHERE row_id = '$mail_id'");
		$shared_by = $get_existing_view->row_array()['shared_by'];
		$viewed_by = $get_existing_view->row_array()['viewed_by'];
		
		if(empty($viewed_by))
		{
			if(!in_array($user_id, explode(",",$viewed_by)))
			{
				$this->db->query("UPDATE tb_mail_admin SET viewed_by = '$user_id' WHERE row_id = '$mail_id'");
			}
		}
		else
		{
			if(!in_array($user_id, explode(",",$viewed_by)))
			{
				$this->db->query("UPDATE tb_mail_admin SET viewed_by = CONCAT(viewed_by, ',$user_id') WHERE row_id = '$mail_id'");
			}
		}
	}
	
	public function save_forward($values)
	{
		$query = $this->db->insert('tb_mail_user', $values);
		
		if($values['status'] == "DISPOSISI")
		{
			$query = $this->db->query("
				UPDATE tb_mail_admin 
				SET mail_to_level = CONCAT(mail_to_level, ',$values[mail_to_level]'),
				mail_to_user = CONCAT(mail_to_user, ',$values[mail_to_user]')
				WHERE row_id = '$values[mail_id]'
			");
		}
		
		if($query)
		{
			return 'OK';
		}
		else
		{
			return 'FAIL';
		}
	}
	
	public function data_disposisi($user_id)
	{
		$query = $this->db->query("
			SELECT 
				a.*
			FROM tb_mail_admin a
			WHERE a.active = '1'
		");
		
		return $query->result();
	}
	
	public function size_master_company($src)
	{
		$where = "";
		
		if(!empty($src))
		{
			$where = (!empty($src)) ? "AND company_name LIKE '%$src%'" : "";
		}
		
		$query = $this->db->query("
			SELECT *
			FROM tb_company
			WHERE active = '1'
			$where
			ORDER BY company_id DESC
		");
		
		return $query->num_rows();
	}
	
	public function master_company($src, $page = null)
	{
		$where = (!empty($src)) ? "AND company_name LIKE '%$src%'" : "";
		
		if(empty($page) OR $page == 1)
		{
			$limit = 'LIMIT 0, 20';
		}
		else
		{
			$limit = "LIMIT ".($page*20-20).", 20";
		}
		
		$query = $this->db->query("
			SELECT *
			FROM tb_company
			WHERE active = '1'
			$where
			ORDER BY company_id DESC
			$limit
		");
		
		return $query->result();
	}
	
	public function save_company($action, $values, $company_id = null)
	{
		switch($action)
		{
			case "TAMBAH INSTANSI":
				$query = $this->db->insert('tb_company', $values);
			break;
			
			case "UBAH INSTANSI":
				$query = $this->db->update('tb_company', $values, array('company_id' => $company_id));
			break;
			
			case "HAPUS INSTANSI":
				$query = $this->db->update('tb_company', $values, array('company_id' => $company_id));
			break;
		}
		
		if($query)
		{
			return 'OK';
		}
		else
		{
			return 'FAIL';
		}
	}
	
	public function size_master_mail_level($src)
	{
		$where = "";
		
		if(!empty($src))
		{
			$where = (!empty($src)) ? "AND level_name LIKE '%$src%'" : "";
		}
		
		$query = $this->db->query("
			SELECT *
			FROM tb_mail_level
			WHERE active = '1'
			$where
			ORDER BY level_id DESC
		");
		
		return $query->num_rows();
	}
	
	public function master_mail_level($src, $page = null)
	{
		$where = (!empty($src)) ? "AND level_name LIKE '%$src%'" : "";
		
		if(empty($page) OR $page == 1)
		{
			$limit = 'LIMIT 0, 20';
		}
		else
		{
			$limit = "LIMIT ".($page*20-20).", 20";
		}
		
		$query = $this->db->query("
			SELECT *
			FROM tb_mail_level
			WHERE active = '1'
			$where
			ORDER BY level_id DESC
			$limit
		");
		
		return $query->result();
	}
	
	public function save_mail_level($action, $values, $level_id = null)
	{
		switch($action)
		{
			case "TAMBAH LEVEL SURAT":
				$query = $this->db->insert('tb_mail_level', $values);
			break;
			
			case "UBAH LEVEL SURAT":
				$query = $this->db->update('tb_mail_level', $values, array('level_id' => $level_id));
			break;
			
			case "HAPUS LEVEL SURAT":
				$query = $this->db->update('tb_mail_level', $values, array('level_id' => $level_id));
			break;
		}
		
		if($query)
		{
			return 'OK';
		}
		else
		{
			return 'FAIL';
		}
	}
	
	public function size_master_mail_type($src)
	{
		$where = "";
		
		if(!empty($src))
		{
			$where = (!empty($src)) ? "AND type_name LIKE '%$src%'" : "";
		}
		
		$query = $this->db->query("
			SELECT *
			FROM tb_mail_type
			WHERE active = '1'
			$where
			ORDER BY type_id DESC
		");
		
		return $query->num_rows();
	}
	
	public function master_mail_type($src, $page = null)
	{
		$where = (!empty($src)) ? "AND type_name LIKE '%$src%'" : "";
		
		if(empty($page) OR $page == 1)
		{
			$limit = 'LIMIT 0, 20';
		}
		else
		{
			$limit = "LIMIT ".($page*20-20).", 20";
		}
		
		$query = $this->db->query("
			SELECT *
			FROM tb_mail_type
			WHERE active = '1'
			$where
			ORDER BY type_id DESC
			$limit
		");
		
		return $query->result();
	}
	
	public function save_mail_type($action, $values, $type_id = null)
	{
		switch($action)
		{
			case "TAMBAH JENIS SURAT":
				$query = $this->db->insert('tb_mail_type', $values);
			break;
			
			case "UBAH JENIS SURAT":
				$query = $this->db->update('tb_mail_type', $values, array('type_id' => $type_id));
			break;
			
			case "HAPUS JENIS SURAT":
				$query = $this->db->update('tb_mail_type', $values, array('type_id' => $type_id));
			break;
		}
		
		if($query)
		{
			return 'OK';
		}
		else
		{
			return 'FAIL';
		}
	}
	
	public function size_master_user($src)
	{
		$where = "";
		
		if(!empty($src))
		{
			$where = (!empty($src)) ? "AND username LIKE '%$src%'" : "";
		}
		
		$query = $this->db->query("
			SELECT a.*, b.level_name
			FROM tb_user a
			JOIN tb_user_level b ON b.level_id = a.level_id
			WHERE a.active = '1'
			AND a.level_id <> '1'
			$where
			ORDER BY row_id DESC
		");
		
		return $query->num_rows();
	}
	
	public function master_user($src, $page = null)
	{
		$where = (!empty($src)) ? "AND a.username LIKE '%$src%'" : "";
		
		if(empty($page) OR $page == 1)
		{
			$limit = 'LIMIT 0, 20';
		}
		else
		{
			$limit = "LIMIT ".($page*20-20).", 20";
		}
		
		$query = $this->db->query("
			SELECT a.*, b.level_name
			FROM tb_user a
			JOIN tb_user_level b ON b.level_id = a.level_id
			WHERE a.active = '1'
			AND a.level_id <> '1'
			$where
			ORDER BY a.row_id DESC
			$limit
		");
		
		return $query->result();
	}
	
	public function save_user($action, $values, $row_id = null)
	{
		switch($action)
		{
			case "TAMBAH USER":
				$query = $this->db->insert('tb_user', $values);
			break;
			
			case "UBAH USER":
				$query = $this->db->update('tb_user', $values, array('row_id' => $row_id));
			break;
			
			case "HAPUS USER":
				$query = $this->db->update('tb_user', $values, array('row_id' => $row_id));
			break;
		}
		
		if($query)
		{
			return 'OK';
		}
		else
		{
			return 'FAIL';
		}
	}
	
	public function size_master_user_level($src)
	{
		$where = "";
		
		if(!empty($src))
		{
			$where = (!empty($src)) ? "AND level_name LIKE '%$src%'" : "";
		}
		
		$query = $this->db->query("
			SELECT *
			FROM tb_user_level
			WHERE active = '1'
			AND level_id <> '1'
			$where
			ORDER BY level_id DESC
		");
		
		return $query->num_rows();
	}
	
	public function master_user_level($src, $page = null)
	{
		$where = (!empty($src)) ? "AND level_name LIKE '%$src%'" : "";
		
		if(empty($page) OR $page == 1)
		{
			$limit = 'LIMIT 0, 20';
		}
		else
		{
			$limit = "LIMIT ".($page*20-20).", 20";
		}
		
		$query = $this->db->query("
			SELECT *
			FROM tb_user_level
			WHERE active = '1'
			AND level_id <> '1'
			$where
			ORDER BY level_id DESC
			$limit
		");
		
		return $query->result();
	}
	
	public function save_user_level($action, $values, $level_id = null)
	{
		switch($action)
		{
			case "TAMBAH LEVEL PENGGUNA":
				$query = $this->db->insert('tb_user_level', $values);
			break;
			
			case "UBAH LEVEL PENGGUNA":
				$query = $this->db->update('tb_user_level', $values, array('level_id' => $level_id));
			break;
			
			case "HAPUS LEVEL PENGGUNA":
				$query = $this->db->update('tb_user_level', $values, array('level_id' => $level_id));
			break;
		}
		
		if($query)
		{
			return 'OK';
		}
		else
		{
			return 'FAIL';
		}
	}
	
	public function size_master_disposisi($src)
	{
		$where = "";
		
		if(!empty($src))
		{
			$where = (!empty($src)) ? "AND disposisi_name LIKE '%$src%'" : "";
		}
		
		$query = $this->db->query("
			SELECT *
			FROM tb_disposisi
			WHERE active = '1'
			$where
			ORDER BY disposisi_id DESC
		");
		
		return $query->num_rows();
	}
	
	public function master_disposisi($src, $page = null)
	{
		$where = (!empty($src)) ? "AND disposisi_name LIKE '%$src%'" : "";
		
		if(empty($page) OR $page == 1)
		{
			$limit = 'LIMIT 0, 20';
		}
		else
		{
			$limit = "LIMIT ".($page*20-20).", 20";
		}
		
		$query = $this->db->query("
			SELECT *
			FROM tb_disposisi
			WHERE active = '1'
			$where
			ORDER BY disposisi_id DESC
			$limit
		");
		
		return $query->result();
	}
	
	public function save_disposisi($action, $values, $disposisi_id = null)
	{
		switch($action)
		{
			case "TAMBAH DISPOSISI":
				$query = $this->db->insert('tb_disposisi', $values);
			break;
			
			case "UBAH DISPOSISI":
				$query = $this->db->update('tb_disposisi', $values, array('disposisi_id' => $disposisi_id));
			break;
			
			case "HAPUS DISPOSISI":
				$query = $this->db->update('tb_disposisi', $values, array('disposisi_id' => $disposisi_id));
			break;
		}
		
		if($query)
		{
			return 'OK';
		}
		else
		{
			return 'FAIL';
		}
	}
	
	public function app_info()
	{
		$query = $this->db->query("
			SELECT *
			FROM tb_app
		");
		
		return $query->result();
	}
	
	public function save_app_info($values)
	{
		$query = $this->db->update('tb_app', $values);
		
		return ($query) ? 'OK' : 'FAIL';
	}
	
	public function save_user_profile($values, $user_id)
	{
		$query = $this->db->update('tb_user', $values, array('row_id' => $user_id));
		
		return ($query) ? 'OK' : 'FAIL';
	}
	
	public function get_user_list($level_id)
	{
		$query = $this->db->query("
			SELECT row_id, username
			FROM tb_user
			WHERE level_id = '$level_id'
			AND active = '1'
		");
		
		return json_encode(array('result' => $query->result()));
	}
	
	public function get_opt_disposisi($level_id)
	{
		$query = $this->db->query("
			SELECT disposisi_id, disposisi_name
			FROM tb_disposisi
			WHERE level_id = '$level_id'
			AND active = '1'
		");
		
		return json_encode(array('result' => $query->result()));
	}
	
	public function get_user($level_id)
	{
		$mail_to_user = array();
		
		$query = $this->db->query("
			SELECT row_id
			FROM tb_user
			WHERE level_id IN ($level_id)
			AND active = '1'
		");
		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $rec)
			{
				$mail_to_user[] = $rec->row_id;
			}
		}
		
		return implode(",", $mail_to_user);
	}
	
	public function mail_main($mail_id)
	{
		$data = array();
		
		$query = $this->db->query("
			SELECT a.*, b.company_name, c.level_name, d.username as shared_by_name, e.type_name
			FROM tb_mail_admin a
			JOIN tb_company b ON b.company_id = a.mail_from
			JOIN tb_mail_level c ON c.level_id = a.mail_level
			JOIN tb_user d ON d.row_id = a.shared_by
			JOIN tb_mail_type e ON e.type_id = a.mail_type
			WHERE a.row_id = '$mail_id'
		");
		
		foreach($query->result() as $rec)
		{
			$data[] = $rec;
			$data[0]->mail_to_level_name = $this->M_main->get_level_name(substr($rec->mail_to_level, 0, strpos($rec->mail_to_level,",1")));
		}
		
		return $data;
	}
	
	public function mail_disposisi($mail_id)
	{
		$data = array();
		
		$query = $this->db->query("
			SELECT a.*, b.username as shared_by, c.disposisi_name, d.mail_ttd AS ttd_admin
			FROM tb_mail_user a
			JOIN tb_user b ON b.row_id = a.shared_by
			LEFT JOIN tb_disposisi c ON c.disposisi_id = a.mail_disposisi
			JOIN tb_mail_admin d ON d.row_id = a.mail_id
			WHERE mail_id = '$mail_id'
			ORDER BY row_id ASC
		");
		
		$i = 0;
		
		foreach($query->result() as $rec)
		{
			$data[] = $rec;
			
			if(!empty($rec->mail_to_user))
			{
				$data[$i]->mail_to_user_name = $this->M_main->get_user_name($rec->mail_to_user);
				
				$i++;
			}
		}
		
		return $data;
	}
	
	public function get_level_name($level_id)
	{
		$mail_to_level_name = array();
		
		$query = $this->db->query("
			SELECT level_name
			FROM tb_user_level
			WHERE level_id IN ($level_id)
			AND active = '1'
		");
		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $rec)
			{
				$mail_to_level_name[] = $rec->level_name;
			}
		}
		
		return implode(",", $mail_to_level_name);
	}
	
	public function get_user_name($user_id)
	{
		$mail_to_level_name = array();
		
		$query = $this->db->query("
			SELECT username
			FROM tb_user
			WHERE row_id IN ($user_id)
			AND active = '1'
		");
		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $rec)
			{
				$mail_to_level_name[] = $rec->username;
			}
		}
		
		return implode(",", $mail_to_level_name);
	}
	
	public function mail_info($level_id, $user_id)
	{
		$query = $this->db->query("
			SELECT *
			FROM
			(
				SELECT COUNT(*) AS mail_total
				FROM tb_mail_admin
				WHERE shared_by = '1'
			) as data1,
			(
				SELECT COUNT(*) AS mail_done
				FROM tb_mail_user a
				JOIN tb_mail_admin b ON b.row_id = a.mail_id
				WHERE b.shared_by = '1'
				AND status = 'SELESAI'
				AND b.active = '1'
			) as data2,
			(
				SELECT COUNT(*) AS total_user
				FROM tb_user 
				WHERE active = '1'
				AND level_id <> '1'
			) as data3,
			(
				SELECT COUNT(*) AS total_company
				FROM tb_company
				WHERE active = '1'
			) as data4
		");
		
		return $query->result();
	}
	
	public function save_forward_kepala($values, $status)
	{
		if($status == "DISPOSISI")
		{
			$query = $this->db->insert_batch('tb_mail_user', $values);		
			
			for($i=0; $i<sizeof($values); $i++)
			{
				$query = $this->db->query("
					UPDATE tb_mail_admin 
					SET mail_to_level = CONCAT(mail_to_level, ',".$values[$i]['mail_to_level']."'),
					mail_to_user = CONCAT(mail_to_user, ',".$values[$i]['mail_to_user']."')
					WHERE row_id = '".$values[$i]['mail_id']."'
				");
			}
		}
		else
		{
			$query = $this->db->insert('tb_mail_user', $values);		
		}
		
		return ($query) ? 'OK' : 'FAIL';
	}
	
	public function data_rekap($from, $to, $type)
	{
		if($type == "Surat Masuk")
		{
			$query = $this->db->query("
				SELECT
					a.*,
					d.type_name as mail_type,
					e.level_name as mail_level,
					b.company_name as mail_from,
					c.username as shared_by
				FROM tb_mail_admin a
				LEFT JOIN tb_company b ON b.company_id = a.mail_from
				LEFT JOIN tb_user c ON c.row_id = a.shared_by
				LEFT JOIN tb_mail_type d ON d.type_id = a.mail_type
				LEFT JOIN tb_mail_level e ON e.level_id = a.mail_level
				WHERE a.mail_date BETWEEN '$from' AND '$to'
				AND a.active = '1'
			");
		}
		else
		{
			$query = $this->db->query("SELECT * FROM tb_mailoutbox");
		}
		
		return $query->result();
	}
	
	public function mail_outbox($src, $page)
	{
		$where = (!empty($src)) ? "AND (agenda_no LIKE '%$src%' OR mail_no LIKE '%$src%')" : "";
		
		if(empty($page) OR $page == 1)
		{
			$limit = 'LIMIT 0, 20';
		}
		else
		{
			$limit = "LIMIT ".($page*20-20).", 20";
		}
		
		$query = $this->db->query("
			SELECT * FROM tb_mailoutbox
			WHERE agenda_no <> ''
			$where
			ORDER BY row_id DESC
			$limit
		");
		
		return $query->result();
	}
	
	public function size_mailoutbox_list($src)
	{
		$where = (!empty($src)) ? "AND (agenda_no LIKE '%$src%' OR mail_no LIKE '%$src%')" : "";
		
		$query = $this->db->query("
			SELECT * FROM tb_mailoutbox
			WHERE agenda_no <> ''
			$where
			ORDER BY row_id DESC
		");
		
		return $query->num_rows();
	}
	
	public function save_mailoutbox($action, $values, $row_id = null)
	{
		switch($action)
		{
			case "TAMBAH SURAT KELUAR":
				$query = $this->db->insert('tb_mailoutbox', $values);
				
				return ($query) ? 'OK' : 'FAIL';
			break;
			
			case "UBAH SURAT KELUAR":
				$query = $this->db->update('tb_mailoutbox', $values, array('row_id' => $row_id));
				
				return ($query) ? 'OK' : 'FAIL';
			break;
			
			case "HAPUS SURAT KELUAR":
				$query = $this->db->delete('tb_mailoutbox', array('row_id' => $row_id));
				
				return ($query) ? 'OK' : 'FAIL';
			break;
		}
	}
	
	public function get_ttd($mail_id)
	{
		$query = $this->db->query("
			SELECT 
				mail_ttd AS ttd_admin,
				(SELECT mail_ttd FROM tb_mail_user WHERE mail_id = '$mail_id' AND shared_by = '2') AS ttd_umum,
				(SELECT mail_ttd FROM tb_mail_user WHERE mail_id = '$mail_id' AND shared_by = '3') AS ttd_manager,
				(SELECT mail_ttd FROM tb_mail_user WHERE mail_id = '$mail_id' AND shared_by = '5') AS ttd_keuangan,
				(SELECT mail_ttd FROM tb_mail_user WHERE mail_id = '$mail_id' AND shared_by = '4') AS ttd_kepala
			FROM tb_mail_admin
			WHERE row_id = '$mail_id'
		");
		
		return $query->result();
	}
}
