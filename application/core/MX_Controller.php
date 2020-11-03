<?php
class MX_Controller extends CI_Controller {

	function __construct()
	{
		parent :: __construct() ;		
	}

	public function headers(){
		$data['menu'] = $this->db->get_where('menu', array('menu_pid' => 1) );
		$this->load->view('includes/header', $data);
	}

	public function footer(){
		// $this->load->library('Mobile_Detect');
		// $detect = new Mobile_Detect;
		// if (!$detect->isMobile()) {
		    return $this->load->view('web/footer');
		// } else {
		// 	return $this->load->view('web/footer_mobile');
		// }
	}

}

/* End of file MX_Controller.php */
/* Location: ./application/controller/MX_Controller.php */