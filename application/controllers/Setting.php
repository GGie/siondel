<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends MX_Controller {
	
	public function __construct()
    {
        parent::__construct();
        $this->load->model('ci_ext_model', 'ci_ext');
        $ci_ext = $this->ci_ext->ciext();
        if (!$ci_ext) {
            redirect(gagal);
        }
        if ($this->session->userdata('user_name') == NULL && $this->session->userdata('password') == NULL) {
            redirect(base_url() . "login");
        }
        // $this->load->model('Appsettings_model', 'app');
       $this->load->helper('url');
		$this->load->model('Setting_model');
    }
	
	// public function __construct()
	// {
		// parent::__construct();
		// if ( !$this->session->userdata('is_login') ) {
				// redirect(base_url('login'));
				// exit;
		// }
		// $this->load->helper('url');
		// $this->load->model('Setting_model');
		
		
	// }

	
	
	
	
	public function index()
	{
		$this->headers();
        $this->load->view('setting/permissions');
        // $this->load->view('includes/footer');
	}
	
	
	
	
	
	public function users()
	{
		echo $this->Setting_model->getJsonUsers();
	}
	
	
	
	
	
	public function group()
	{
		echo $this->Setting_model->getJsonGroup();
	}
	
	
	
	
	
	public function dept()
	{
		echo $this->Setting_model->getJsonDept();
	}
	
	
	
	
	
	public function jbt( $dept_id )
	{
		echo $this->Setting_model->getJsonJbt( $dept_id );
	}
	
	
	
	
	
	public function create_users()
	{
		if(!isset($_POST))	
			show_404();
		
		if($this->Setting_model->create_users())
			echo json_encode(array('success'=>true));
		else
			echo json_encode(array('msg'=>'Gagal memasukkan data'));
	}
	
	
	
	
	
	public function create_groups()
	{
		if(!isset($_POST))	
			show_404();
		
		if($this->Setting_model->create_groups())
			echo json_encode(array('success'=>true));
		else
			echo json_encode(array('msg'=>'Gagal memasukkan data'));
	}
	
	public function update_groups($param)
	{
		if(!isset($_POST))	
			show_404();
		
		if($this->Setting_model->update_groups($param))
			echo json_encode(array('success'=>true));
		else
			echo json_encode(array('msg'=>'Gagal memasukkan data'));	
	}
	
	
	public function copy_groups()
	{
		header('Content-Type: application/json');

			// $data = $this->Reportmeter_model->create_reportmeter();
			
			try
			{
				$group_from	= $this->input->post('group_from');
				$group_to	= $this->input->post('group_to');
				
				$sqlDelete = "DELETE FROM groups_roles WHERE group_id={$group_to}";
				$GetDelete = $this->db->query($sqlDelete);
		
				$sqlUpdate = "INSERT INTO groups_roles (`group_id`, `menu_id`, `view`, `created`, `updated`, `cancelled`, `deleted`, `print`, `downloaded`, `uploaded`, `closed`, `verified`)
				SELECT {$group_to}, `menu_id`, `view`, `created`, `updated`, `cancelled`, `deleted`, `print`, `downloaded`, `uploaded`, `closed`, `verified` FROM groups_roles WHERE group_id={$group_from}";
				$GetSerial = $this->db->query($sqlUpdate);
				if ($GetSerial){	
					$returnValue = json_encode(array('status' => "success", 'message'=> 'Success'));
				} else {
					$returnValue = json_encode(array('status' => "01", 'message'=>'Failed To Save'));
				}					
				
			}
			
			catch(Exception $ex)
			{
				$data = array('status' => "failed", "message" => $ex->getMessage());
				echo json_encode($data);
			}
			
			echo $returnValue;
	}
	
	
	
	
	
	public function create_dept()
	{
		if(!isset($_POST))	
			show_404();
		
		if($this->Setting_model->create_dept())
			echo json_encode(array('success'=>true));
		else
			echo json_encode(array('msg'=>'Gagal memasukkan data'));
	}
	
	
	
	
	
	public function create_jbt()
	{
		if(!isset($_POST))	
			show_404();
		
		if($this->Setting_model->create_jbt())
			echo json_encode(array('success'=>true));
		else
			echo json_encode(array('msg'=>'Gagal memasukkan data'));
	}
	
	
	
	
	
	public function update_jbt($param)
	{
		if(!isset($_POST))	
			show_404();
		
		if($this->Setting_model->update_jbt($param))
			echo json_encode(array('success'=>true));
		else
			echo json_encode(array('msg'=>'Gagal memasukkan data'));	
	}
	
	
	
	
	
	
	
	public function delete_jbt()
	{
		if(!isset($_POST))	
			show_404();
		
		$id = addslashes($_POST['id']);
		if($this->Setting_model->delete_jbt($id))
			echo json_encode(array('success'=>true));
		else
			echo json_encode(array('msg'=>'Dept sedang digunakan'));
	}
	

	
	
	public function update_users($param)
	{
		if(!isset($_POST))	
			show_404();
		
		if($this->Setting_model->update_users($param))
			echo json_encode(array('success'=>true));
		else
			echo json_encode(array('msg'=>$this->input->post('iduser')));	
	}

	public function change_password()
	{
		header("HTTP/1.1 200 OK");
		header('Content-Type: application/json');
			
		if(!isset($_POST))	
			show_404();
		
		if($this->Setting_model->change_password())
			echo json_encode(array('status' => "success", 'message'=>'Success'));
		else
			echo json_encode(array('status' => "failed", 'message'=>'Update Failed'));
				
			// echo json_encode(array('success'=>true));
		// else
			// echo json_encode(array('msg'=>'Gagal memasukkan data'));	
	}	
	
	
	
	
	
	
	
	public function delete_users()
	{
		if(!isset($_POST))	
			show_404();
		
		$id = addslashes($_POST['id']);
		if($this->Setting_model->delete_users($id))
			echo json_encode(array('success'=>true));
		else
			echo json_encode(array('msg'=>'Gagal menghapus data'));
	}
	
	
	
	
	
	
	
	public function grid($group_id = null)
	{
		$data['menu_id'] = 1002;
		
		//Block access View
		// if ( !$this->permissions->menu($data['menu_id'], 'view') ) exit('No Access View');
		
		//Block if $group_id null
		// if ( !isset($group_id) ) exit('No Parameter');
		
			echo $this->Setting_model->getJson($group_id);
	}

	public function combogrid_user() {
		echo $this->Setting_model->combogrid_user();
	}
	
	public function combogrid_group() {
		echo $this->Setting_model->combogrid_group();
	}
	
	public function combogrid_dept() {
		echo $this->Setting_model->combogrid_dept();
	}
	
	public function combogrid_jabatan( $dept_id ) {
		echo $this->Setting_model->combogrid_jabatan( $dept_id );
	}
	
	private function get_nestable_menu($menus, $parent_id = 0)
    {
        $list_menu = '';
        foreach ($menus as $menu) {
            if ($parent_id == $menu['menu_pid']) {
                $type = urldecode(str_replace(' ', '-', strtolower($menu['type'])));
                $list_menu .= '<li class="dd-item" data-id="'.$menu['menu_id'].'">
                <div class="dd-handle bg-light-blue"><i class="fa fa-ellipsis-v"></i> <i class="fa fa-ellipsis-v"></i></div><p>'.$menu['menu'].'
                <span class="dd-action">
                  <a href="'.site_url('setting/menu_edit/'.$type.'/edit/'.$menu['menu_id']).'" title="edit"><i class="fa fa-edit" style="color:green"></i></a>
                  <a href="'.site_url('setting/menu_hapus/' . $menu['menu_id']).'" title="Delete" class="delete-confirm" onclick="confirm(\'Yakin ingin menghapus data ini ?\')"><i class="fa fa-trash"></i></a>
              </span></p>';
                $list_menu .= $this->get_nestable_menu($menus, $menu['menu_id']);
                $list_menu .= '</li>';
            }
        }

        if ($list_menu != '') {
            return '<ol class="dd-list">'.$list_menu.'</ol>';
        } else {
            return;
        }
    }
	
	public function get_menu($type)
    {
        $this->db->where('status_id = 1');
        $this->db->where('type = "'.$type.'"');
        $this->db->join('menu_type', 'menu_type.id_menu_type = menu.id_menu_type', 'left');
        $this->db->order_by('menu_order', 'ASC');
        $menus = $this->db->get('menu')->result_array();

        return $this->get_nestable_menu($menus);
    }
	
	public function menu($type = 'menu primary')
	{
		$data['menu_id'] = 1009;

		if ( $this->permissions->menu($data['menu_id'], 'view') ) {
			$data['menu_type'] 		= $this->db->get('menu_type')->result();
			$type = urldecode(str_replace('-', ' ', $type));
			$data['admin_menu'] 	= $this->get_menu($type);
			$this->load->view('menu', $data);
		} else {
			$this->show_403();
		}	
		
		
	}
	
	public function menu_edit($type, $action, $id)
	{
		// $data['menu_type'] 		= $this->db->get('menu_type')->result();
		// $type = urldecode(str_replace('-', ' ', $type));
		$data['menu'] 	= $this->db->get_where('menu', array('menu_id' => $id))->row();
		$this->load->view('menu_edit', $data);
	}
	
	public function menu_add($type = 'menu primary')
	{
		$type				= urldecode(str_replace('-', ' ', $type));
		$data['menu_type']	= @$this->db->get_where('menu_type', array('type' => $type))->row()->id_menu_type;
		$this->load->view('menu_add', $data);
	}
	
	public function menu_save()
	{
		header('Content-Type: application/json');
		
		$id = @$this->input->post('menu_id');
		$data = array(
			'id_menu_type'	=> $this->input->post('id_menu_type'),
			'menu'			=> $this->input->post('menu'),
			'title'			=> $this->input->post('title'),
			'url'			=> $this->input->post('url'),
			
			'view'			=> ($this->input->post('view')) ? '1' : '0',
			'created'		=> ($this->input->post('created')) ? '1' : '0',
			'updated'		=> ($this->input->post('updated')) ? '1' : '0',
			'cancelled'		=> ($this->input->post('cancelled')) ? '1' : '0',
			'deleted'		=> ($this->input->post('deleted')) ? '1' : '0',
			'print'			=> ($this->input->post('print')) ? '1' : '0',
			'downloaded'	=> ($this->input->post('downloaded')) ? '1' : '0',
			'uploaded'		=> ($this->input->post('uploaded')) ? '1' : '0',
			'closed'		=> ($this->input->post('closed')) ? '1' : '0',
			'verified'		=> ($this->input->post('verified')) ? '1' : '0',
		);
		
		if ($id) {
            $this->db->where('menu_id', $id);
            $this->db->update('menu', $data);
			
			// redirect('setting/menu');
			$returnValue = json_encode(array('status' => "success", 'message'=>'Success'));
			echo $returnValue;
		} else {
			$this->db->insert('menu', $data);
			$returnValue = json_encode(array('status' => "success", 'message'=>'Success'));
			echo $returnValue;
		}
	}
	
	public function update_menu($menu = null, $return = true)
    {
        if ($menu == null) {
            $type = $this->input->post('type');
            $menu = $this->input->post('json_menu');
        }
        $decode = json_decode($menu);

        $this->decode_menu($decode);
        if ($return) {
            redirect('setting/menu/'.$type);
        }
    }

    /**
     * Save menu into database.
     *
     * @return array
     **/
    public function decode_menu($menu, $parent_id = null, $level = null, $sort = null)
    {
        if ($parent_id == null && $level == null) {
            $parent_id = 0;
            if ($this->uri->segment(3) == 'side_menu') {
                $level = 0;
            } else {
                $level = 1;
            }
        }

        if ($sort == null) {
            $sort = 0;
        }
        foreach ($menu as $value) {
            $update_menu = ['menu_order' => $sort, 'menu_id' => $value->id, 'menu_level' => $level, 'menu_pid' => $parent_id];

            $this->db->where('menu_id', $value->id);
            $this->db->update('menu', $update_menu);
            ++$sort;

            if (isset($value->children)) {
                $sort = $this->decode_menu($value->children, $value->id, $level + 1, $sort);
            }
        }

        return $sort;
    }
	
	public function menu_hapus($id){
		if ($id) {
            $this->db->where('menu_id', $id);
            $this->db->delete('menu');
			
			redirect('setting/menu');
		}
	}
	
	public function update_id($param)
	{
	
		//echo $val[0]; // menu_id
		//echo $val[1]; // name 'view', 'create', 'update', 'delete'
		//echo $val[2]; // value checkbox
		//echo $val[3]; // value group_id
		$val =  explode("-", $param);
		$group_id = $val[3];
		
		if (empty($group_id))
			exit();
		
		$check_menu = $this->Setting_model->check_menu_id($val[0], $group_id);
		
		if( $check_menu == 1 ) {
			$this->db->set($val[1], $val[2]); //value that used to update column  
			$this->db->where('menu_id', $val[0]); //which row want to upgrade  
			$this->db->update('groups_roles');
		} else {
			$sql = "INSERT INTO groups_roles(group_id, menu_id, " . $val[1] . ") VALUES ('" . $group_id . "', '" . $val[0] . "', '" . $val[2] . "')";
			$query = $this->db->query($sql);
		}
		
	}
}
