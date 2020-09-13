<?php
defined('BASEPATH') or exit('No direct script access allowed');

class transaction extends CI_Controller
{

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
        $this->load->model('Appsettings_model', 'app');
        $this->load->model('Dashboard_model', 'dashboard');
        // $this->load->library('form_validation');
    }

    public function index()
    {

        $data['currency'] = $this->app->getappbyid();
        $data['transaksi'] = $this->dashboard->getAlltransaksi();
        $data['fitur'] = $this->dashboard->getAllfitur();
        $data['saldo'] = $this->dashboard->getallsaldo();

        $this->load->view('includes/header');
        $this->load->view('transaction/index', $data);
        $this->load->view('includes/footer');
    }
}
