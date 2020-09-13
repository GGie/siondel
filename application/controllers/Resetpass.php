<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resetpass extends CI_Controller {
public function __construct()
    {
		parent::__construct();
		$this->load->model('ci_ext_model', 'ci_ext');
        $ci_ext = $this->ci_ext->ciext();
        if (!$ci_ext) {
            redirect(gagal);
        }
        $this->load->model('Resetpass_model');
        $this->load->library('form_validation');
    }
	
	public function index() {
		$this->load->view('nodata');
	}
	
	public function rest($token=null,$idkey=null) {
		$data['user'] = $this->Resetpass_model->check_token($token,$idkey);
		$this->form_validation->set_rules('password', 'password', 'required');
		if ($data['user']) {
			if ($this->form_validation->run() == false) {
				$this->load->view('resetpass',$data);
			} else {
			$reset = $this->Resetpass_model->resetpass();
			if($reset) {
				$this->Resetpass_model->deletetoken();
			$this->load->view('success');
			}
			}
		
		} else {
			$this->load->view('nodata');
		}
	}
}
