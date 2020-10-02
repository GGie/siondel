<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting_model extends CI_Model {
	
	
	
	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	
	
	
	
	
	
	
	
	
	public function create_users()
	{
		update_load_data('USER');
		
		return $this->db->insert('tbl_user',
			array(
				'user_id'			=> $this->auto_users_id(),
				'iduser'			=> $this->input->post('iduser'),
				'nmuser'			=> $this->input->post('nmuser'),
				'group_id'			=> $this->input->post('group_id'),
				'fullname'			=> $this->input->post('fullname'),
				'pwd'				=> $this->input->post('pass'),
				'site'				=> $this->session->userdata('site'),
				'status_id' 		=> $this->input->post('status_id'),
				'input_by' 			=> $this->session->userdata('user_id'),
				'input_date' 		=> date('Y-m-d H:i:s')
		));
	}
	
	
	
	
	
	
	public function create_groups()
	{
		update_load_data('GROUP');
		
		return $this->db->insert('group',
			array(
				'group_id'			=> $this->auto_groups_id(),
				'group_name'		=> $this->input->post('group_name'),
				'desc'				=> $this->input->post('desc'),
				'site'				=> site(),
				'input_by' 			=> $this->session->userdata('user_id'),
				'input_date' 		=> date('Y-m-d H:i:s')
		));
	}
	
	public function update_groups($id)
	{
		if ( !$this->input->post('group_id') )
			return false;
		
		update_load_data('GROUP');
		
		$this->db->where('group_id', $this->input->post('group_id'));
		return $this->db->update('group',
			array(
				'group_name'		=> $this->input->post('group_name'),
				'desc'				=> $this->input->post('desc'),
				'update_by'			=> $this->session->userdata('user_id'),
				'update_date' 		=> date('Y-m-d H:i:s')
		));
	}
	
	
	
	
	public function update_users($id)
	{
		if ( !$this->input->post('user_id') )
			return false;
		
		update_load_data('USER');
		
		$this->db->where('user_id', $this->input->post('user_id'));
		return $this->db->update('tbl_user',
			array(
				'iduser'			=> $this->input->post('iduser'),
				'nmuser'			=> $this->input->post('nmuser'),
				'fullname'			=> $this->input->post('fullname'),
				'pwd'				=> $this->input->post('pass'),
				'atasan_id'			=> $this->input->post('atasan_id'),
				'group_id'			=> $this->input->post('group_id'),
				'dept_id'			=> $this->input->post('dept_id'),
				'jabatan_id'		=> $this->input->post('jabatan_id'),
				'status_id' 		=> $this->input->post('status_id'),
				'update_by'			=> $this->session->userdata('user_id'),
				'update_date' 		=> date('Y-m-d H:i:s')
		));
	}
	
	
	
	
	
	
	public function change_password()
	{
		
		$data['fullname']		= $this->input->post('fullname');
		
		if ( $this->input->post('pwd') != '' ) {
			$data['pwd']			= $this->input->post('pwd');
		}

		$data['update_by']		= $this->session->userdata('user_id');
		$data['update_date']	= date('Y-m-d H:i:s');
		
		$this->db->where('user_id', $this->input->post('user_id'));
		return $this->db->update('tbl_user', $data);
	}
	
	
	
	
	
	
	public function delete_users($id)
	{
		update_load_data('USER');
		
		$this->db->where('user_id', $id);
		$this->db->delete('tbl_user'); 
		return true;
	}
	
	
	
	
	
	
	// public function getJsons($group_id)
	// {
		// $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		// $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
		// $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'menu_order';
		// $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
		// $offset = ($page-1) * $rows;
		
		// $result = array();
		// $result['total'] = $this->db->get('menu')->num_rows();
		// $row = array();
		
		// $this->db->where('status_id', 1);
		// $this->db->limit($rows,$offset);
		// $this->db->order_by($sort,$order);
		// $criteria = $this->db->get('menu');
		
		// foreach($criteria->result_array() as $data)
		// {
				// $spasi = str_repeat("<font color='white'>.....</font>", $data['menu_level']);
			
			// $view 		= $this->_checked($data['menu_id'], 'view', $group_id);
			// $created 	= $this->_checked($data['menu_id'], 'created', $group_id);
			// $updated 	= $this->_checked($data['menu_id'], 'updated', $group_id);
			// $cancelled 	= $this->_checked($data['menu_id'], 'cancelled', $group_id);
			// $deleted 	= $this->_checked($data['menu_id'], 'deleted', $group_id);
			// $print 		= $this->_checked($data['menu_id'], 'print', $group_id);
			
			// $row[] = array(
				// 'menu_id'	=> $data['menu_id'],
				// 'group_id'	=> $group_id,
				// 'menu'		=> $spasi . " <img width='8px' src='" . base_url() . "assets/css/icons/arrow.png'> " . $data['title'],
				// 'view'		=> "<input type='checkbox' name='view' 	 onclick='check_click(" . $data['menu_id'] . ", \"view\", \"" . $group_id . "\")' id='view" . $data['menu_id'] . "' " . $view . ">",
				// 'created'	=> "<input type='checkbox' name='created' onclick='check_click(" . $data['menu_id'] . ", \"created\", \"" . $group_id . "\")' id='created" . $data['menu_id'] . "' " . $created . ">",
				// 'updated'	=> "<input type='checkbox' name='updated' onclick='check_click(" . $data['menu_id'] . ", \"updated\", \"" . $group_id . "\")' id='updated" . $data['menu_id'] . "' " . $updated . ">",
				// 'cancelled'	=> "<input type='checkbox' name='cancelled' onclick='check_click(" . $data['menu_id'] . ", \"cancelled\", \"" . $group_id . "\")' id='cancelled" . $data['menu_id'] . "' " . $cancelled . ">",
				// 'deleted'	=> "<input type='checkbox' name='deleted' onclick='check_click(" . $data['menu_id'] . ", \"deleted\", \"" . $group_id . "\")' id='deleted" . $data['menu_id'] . "' " . $deleted . ">",
				// 'print'		=> "<input type='checkbox' name='print' onclick='check_click(" . $data['menu_id'] . ", \"print\", \"" . $group_id . "\")' id='print" . $data['menu_id'] . "' " . $print . ">",
			// );
			
		// }
		// $result=array_merge($result,array('rows'=>$row));
		// return json_encode($result);
	// }
	
	
	
	
	
	//menu home/permissions
	public function getJson($group_id)
	{
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'menu_order';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
		$offset = ($page-1) * $rows;
		
		$result = array();
		$row = array();
		
		$this->db->where('status_id', 1);
		$this->db->where('menu_pid', 1); //menu_pid dashboard
		$this->db->order_by($sort,$order);
		$criteria = $this->db->get('menu');
		
		foreach($criteria->result_array() as $data)
		{	
			//menu_pid = 1
			$view_ 		= $this->_checked($data['menu_id'], 'view', $group_id);
			
			$spasi_ = str_repeat("<font color='white'>.</font>", $data['menu_level']);
			$row[] = array(
				'menu_id'	=> $data['menu_id'],
				'group_id'	=> $group_id,
				'menu'		=> $spasi_ . $spasi_ . " <img width='8px' src='" . base_url() . "asset/easyui/css/icons/arrow.png'> " . $data['title'],
				'view'		=> "<input type='checkbox' name='view' onclick='check_click(" . $data['menu_id'] . ", \"view\", \"" . $group_id . "\")' id='view" . $data['menu_id'] . "' " . $view_ . ">",
					'created'	=> "",
					'updated'	=> "",
					'cancelled'	=> "",
					'deleted'	=> "",
					'print'		=> "",
					'downloaded'=> "",
					'uploaded'	=> "",
					'closed'	=> "",
					'verified'	=> "",
				);
			
			//menu_pid != 1
			$this->db->where('status_id', 1);
			$this->db->where('menu_pid', $data['menu_id']);
			$this->db->order_by($sort,$order);
			$menu_child = $this->db->get('menu');
			foreach($menu_child->result_array() as $menu)
			{
				$spasi = str_repeat("<font color='white'>..</font>", $menu['menu_level']);
					
				$view 		= $this->_checked($menu['menu_id'], 'view', $group_id);
				$created 	= $this->_checked($menu['menu_id'], 'created', $group_id);
				$updated 	= $this->_checked($menu['menu_id'], 'updated', $group_id);
				$cancelled 	= $this->_checked($menu['menu_id'], 'cancelled', $group_id);
				$deleted 	= $this->_checked($menu['menu_id'], 'deleted', $group_id);
				$print 		= $this->_checked($menu['menu_id'], 'print', $group_id);
				$downloaded	= $this->_checked($menu['menu_id'], 'downloaded', $group_id);
				$uploaded	= $this->_checked($menu['menu_id'], 'uploaded', $group_id);
				$closed		= $this->_checked($menu['menu_id'], 'closed', $group_id);
				$verified	= $this->_checked($menu['menu_id'], 'verified', $group_id);
				
				$row[] = array(
					'menu_id'	=> $menu['menu_id'],
					'url'		=> $menu['url'],
					'group_id'	=> $group_id,
					'menu'		=> $spasi . $spasi . " <img width='8px' src='" . base_url() . "asset/easyui/css/icons/arrow.png'> " . strtoupper($menu['title']),
					'view'		=> (!$menu['view']) ? "" : "<input type='checkbox' name='view' 	 onclick='check_click(" . $menu['menu_id'] . ", \"view\", \"" . $group_id . "\")' id='view" . $menu['menu_id'] . "' " . $view . ">",
					'created'	=> (!$menu['created']) ? "" : "<input type='checkbox' name='created' onclick='check_click(" . $menu['menu_id'] . ", \"created\", \"" . $group_id . "\")' id='created" . $menu['menu_id'] . "' " . $created . ">",
					'updated'	=> (!$menu['updated']) ? "" : "<input type='checkbox' name='updated' onclick='check_click(" . $menu['menu_id'] . ", \"updated\", \"" . $group_id . "\")' id='updated" . $menu['menu_id'] . "' " . $updated . ">",
					'cancelled'	=> (!$menu['cancelled']) ? "" : "<input type='checkbox' name='cancelled' onclick='check_click(" . $menu['menu_id'] . ", \"cancelled\", \"" . $group_id . "\")' id='cancelled" . $menu['menu_id'] . "' " . $cancelled . ">",
					'deleted'	=> (!$menu['deleted']) ? "" : "<input type='checkbox' name='deleted' onclick='check_click(" . $menu['menu_id'] . ", \"deleted\", \"" . $group_id . "\")' id='deleted" . $menu['menu_id'] . "' " . $deleted . ">",
					'print'		=> (!$menu['print']) ? "" : "<input type='checkbox' name='print' onclick='check_click(" . $menu['menu_id'] . ", \"print\", \"" . $group_id . "\")' id='print" . $menu['menu_id'] . "' " . $print . ">",
					'downloaded'=> (!$menu['uploaded']) ? "" : "<input type='checkbox' name='downloaded' onclick='check_click(" . $menu['menu_id'] . ", \"downloaded\", \"" . $group_id . "\")' id='downloaded" . $menu['menu_id'] . "' " . $downloaded . ">",
					'uploaded'	=> (!$menu['uploaded']) ? "" : "<input type='checkbox' name='uploaded' onclick='check_click(" . $menu['menu_id'] . ", \"uploaded\", \"" . $group_id . "\")' id='uploaded" . $menu['menu_id'] . "' " . $uploaded . ">",
					'closed'	=> (!$menu['closed']) ? "" : "<input type='checkbox' name='closed' onclick='check_click(" . $menu['menu_id'] . ", \"closed\", \"" . $group_id . "\")' id='closed" . $menu['menu_id'] . "' " . $closed . ">",
					'verified'	=> (!$menu['verified']) ? "" : "<input type='checkbox' name='verified' onclick='check_click(" . $menu['menu_id'] . ", \"verified\", \"" . $group_id . "\")' id='verified" . $menu['menu_id'] . "' " . $verified . ">",
				);
			}
			
		}
		$result=array_merge($result,array('rows'=>$row));
		return json_encode($result);
	}

	
	
	
	
	
	public function _checked($menu_id, $field, $group_id) {
		$this->db->select('*');
		$this->db->where('group_id', $group_id);
		$this->db->where('menu_id', $menu_id);
		$data = $this->db->get('groups_roles');
		
		foreach ( $data->result() as $roles ) {
			if ( $roles->$field == 1 ) { return 'checked'; } else { return '0'; }
		}
		
	}
	
	
	
	
	
	
	// public function getJson_detail($id)
	// {
		// $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		// $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		// $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'menu_order';
		// $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
		// $offset = ($page-1) * $rows;
		
		// $result = array();
		// $result['total'] = $this->db->get('groups_roles')->num_rows();
		// $row = array();
		
		// $this->db->limit($rows,$offset);
		// $this->db->order_by($sort,$order);
		// $criteria = $this->db->get('groups_roles');
		
		// foreach($criteria->result_array() as $data)
		// {	
			// $row[] = array(
				// 'groups_roles_id'	=>$data['groups_roles_id'],
				// 'modul'				=>$data['modul'],
				// 'view'				=>$data['view'],
				// 'create'			=>$data['create'],
				// 'update'			=>$data['update'],
				// 'delete'			=>$data['delete']
			// );
		// }
		// $result=array_merge($result,array('rows'=>$row));
		// return json_encode($result);
	// }
	
	
	
	
	
	
	public function getJsonUsers()
	{
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'user_id';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
		$offset = ($page-1) * $rows;
		
		$sql = "SELECT * FROM tbl_user WHERE status_id!='99' AND site='" . site() . "'";
		
		if ( isset($_POST['nmuser'])) 
		{
			$sql .= " AND nmuser LIKE '%" . $_POST['nmuser'] . "%'";
		}
		
		
		
		$result = array();
		$result['total'] = $this->db->query( $sql )->num_rows();
		$row = array();
		
		$sql .= " ORDER BY $sort $order limit $offset,$rows";
		$criteria = $this->db->query( $sql );
		
		foreach($criteria->result() as $data)
		{	
			$row[] = array(
				'user_id'			=> $data->user_id,
				'iduser'			=> $data->iduser,
				'nmuser'			=> $data->nmuser,
				'fullname'			=> $data->fullname,
				'pass'				=> $data->pwd,
				'pwd'				=> gie_encode($data->pwd),
				'group_id'			=> $data->group_id,
				'status_id'			=> $data->status_id,
				'input_by'			=> $data->input_by,
				'input_date'		=> date('Y-m-d', strtotime($data->input_date)),
				'update_by'			=> $data->update_by,
				'update_date'		=> date('Y-m-d', strtotime($data->update_date))
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return json_encode($result);
	}
	
	
	
	
	
	
	public function getJsonGroup()
	{
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'group_id';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
		$offset = ($page-1) * $rows;
		
		$sql = "SELECT * FROM `group`";
		
		
		$sql .= " WHERE site='" . site() . "'";
		
		if ( !empty($_POST['group_name'])) 
		{
			$sql .= " AND group_name LIKE '%" . $_POST['group_name'] . "%'";
		}
		
		if ( !empty($_POST['desc'])) 
		{
			$sql .= " AND `desc` LIKE '%" . $_POST['desc'] . "%'";
		}
		
		$result = array();
		$result['total'] = $this->db->query( $sql )->num_rows();
		$row = array();
		
		$sql .= " ORDER BY $sort $order limit $offset,$rows";
		$criteria = $this->db->query( $sql );
		
		foreach($criteria->result() as $data)
		{	
			$row[] = array(
				'group_id'			=> $data->group_id,
				'group_name'		=> $data->group_name,
				'desc'				=> $data->desc,
				'input_by'			=> $data->input_by,
				'input_date'		=> date('Y-m-d', strtotime($data->input_date)),
				'update_by'			=> $data->update_by,
				'update_date'		=> date('Y-m-d', strtotime($data->update_date))
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return json_encode($result);
	}
	
	
	
	
	
	
	
	
	
	public function getJsonDept()
	{
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 1000;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'dept_id';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
		$offset = ($page-1) * $rows;
		
		$sql = "SELECT * FROM `tbl_departemen`";
		
		$result = array();
		$result['total'] = $this->db->query( $sql )->num_rows();
		$row = array();
		
		$sql .= " ORDER BY $sort $order limit $offset,$rows";
		$criteria = $this->db->query( $sql );
		
		foreach($criteria->result() as $data)
		{	
			$row[] = array(
				'dept_id'			=> $data->dept_id,
				'departemen'		=> $data->departemen,
				'input_by'			=> $data->input_by,
				'input_date'		=> datex(date('Y-m-d', strtotime($data->input_date))),
				'update_by'			=> $data->update_by,
				'update_date'		=> datex(date('Y-m-d', strtotime($data->update_date)))
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return json_encode($result);
	}
	
	public function getJsonJbt( $dept_id )
	{
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 1000;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'level';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
		
		$sql = "SELECT * FROM `tbl_jabatan` WHERE dept_id='" . $dept_id . "'";
		
		$result = array();
		$result['total'] = $this->db->query( $sql )->num_rows();
		$row = array();
		
		$sql .= " ORDER BY $sort $order limit $offset,$rows";
		$criteria = $this->db->query( $sql );
		
		foreach($criteria->result() as $data)
		{	
			$row[] = array(
				'jabatan_id'		=> $data->jabatan_id,
				'jabatan'			=> $data->jabatan,
				'level'				=> $data->level,
				'input_by'			=> $data->input_by,
				'input_date'		=> datex(date('Y-m-d', strtotime($data->input_date))),
				'update_by'			=> $data->update_by,
				'update_date'		=> datex(date('Y-m-d', strtotime($data->update_date)))
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return json_encode($result);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function check_menu_id($menu_id, $group_id) {
		$this->db->select('*'); //value that used to update column  
		$this->db->where('menu_id', $menu_id); //which row want to upgrade  
		$this->db->where('group_id', $group_id); //which row want to upgrade 
		$sql = "SELECT * FROM groups_roles WHERE menu_id='" . $menu_id . "' AND group_id='" . $group_id . "'";
		$query = $this->db->query($sql);
		
		//foreach( $query->result() as $cek ) {}
		
		if ( $query->num_rows() > 0 ) {
			return 1;
		} else {
			return 0;
		}
	}
	
	
	
	
	
	
	public function combogrid_user() {
		$db_sql = $this->load->database('sql_svr', true);
		$result['total'] = $db_sql->get('tbl_user')->num_rows();
		//$this->db->where('groups_roles_id', $id);
		$criteria = $db_sql->get('tbl_user');;
		
		foreach($criteria->result_array() as $data)
		{	
			$row[] = array(
				'user_id'			=>$data['iduser'],
				'user_name'			=>$data['nmuser'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return json_encode($result);
	}
	
	
	
	
	
	
	public function combogrid_group() {
		$result['total'] = $this->db->get( 'group' )->num_rows();
		//$this->db->where('groups_roles_id', $id);
		$criteria = $this->db->get( 'group' );
		
		foreach($criteria->result() as $data)
		{	
			$row[] = array(
				'group_id'		=>$data->group_id,
				'group_name'	=>$data->group_name,
				'desc'			=>$data->desc,
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return json_encode($result);
	}
	
	
	
	
	
	
	public function combogrid_dept() {
		$result['total'] = $this->db->get('tbl_departemen')->num_rows();
		//$this->db->where('groups_roles_id', $id);
		$criteria = $this->db->get('tbl_departemen');
		
		foreach($criteria->result() as $data)
		{	
			$row[] = array(
				'dept_id'		=>$data->dept_id,
				'departemen'	=>$data->departemen,
				'desc'			=>$data->desc,
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return json_encode($result);
	}
	
	
	
	
	
	
	public function combogrid_jabatan( $dept_id ) {
		$dept_id = $_POST['q'];
		$result['total'] = $this->db->get('tbl_jabatan')->num_rows();
		//$this->db->where('groups_roles_id', $id);
		
		if ( isset( $dept_id ) ) {
			$this->db->where('dept_id', $dept_id);
		}
		$criteria = $this->db->get('tbl_jabatan');
		
		foreach($criteria->result() as $data)
		{	
			$row[] = array(
				'jabatan_id'	=>$data->jabatan_id,
				'jabatan'		=>$data->jabatan,
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return json_encode($result);
	}
	
	
	
	
	
	
	function auto_users_id()
	{
		$q = $this->db->query("SELECT max(RIGHT(user_id, 6)) as user_id FROM tbl_user");
		foreach ($q->result() as $d)
		{
			$dt1 = 1 + $d->user_id ;
			$dt2 = "u" . sprintf("%06s", $dt1) ;
			return $dt2;
		}
	}
	
	
	
	
	
	
	function auto_groups_id()
	{
		$q = $this->db->query("SELECT max(RIGHT(group_id, 6)) as group_id FROM `group`");
		foreach ($q->result() as $d)
		{
			$dt1 = 1 + $d->group_id ;
			$dt2 = "" . sprintf("%06s", $dt1) ;
			return $dt2;
		}
	}
	
	
	
	
	
	function auto_dept_id()
	{
		$q = $this->db->query("SELECT max(RIGHT(dept_id, 6)) as dept_id FROM `tbl_departemen`");
		foreach ($q->result() as $d)
		{
			$dt1 = 1 + $d->dept_id ;
			$dt2 = "" . sprintf("%06s", $dt1) ;
			return $dt2;
		}
	}
	
	
	
	function auto_jbt_id()
	{
		$q = $this->db->query("SELECT max(RIGHT(jabatan_id, 6)) as jabatan_id FROM `tbl_jabatan`");
		foreach ($q->result() as $d)
		{
			$dt1 = 1 + $d->jabatan_id ;
			$dt2 = "" . sprintf("%06s", $dt1) ;
			return $dt2;
		}
	}
	
	
	public function update_permission($param)
	{

		$group_roles = @$this->db->get_Where('group_roles', array('group_id' => $param['group_to']));
		
		foreach( $group_roles->result() as $roles) {
			
		}
		if ( $group_roles != null ) {
			
			$this->db->where('water_meter_report_id', $param['water_meter_report_id']);
			$this->db->update('water_meter_count',
				array(
					'water_meter_report_id'	=> $param['water_meter_report_id'],
					'customer_id'			=> $param['customer_id'],
			));
			
		} else {
			$this->db->insert('water_meter_count',
				array(
					'water_meter_report_id'	=> $param['water_meter_report_id'],
					'customer_id'			=> $param['customer_id'],
			));
		}
		
		
	}
	
	
	
}

/* End of file Setting_model.php */
/* Location: ./application/controllers/Setting_model.php */