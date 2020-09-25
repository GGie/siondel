<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends MX_Controller
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
        // $this->load->model('Appsettings_model', 'app');
        $this->load->model('Admin_model', 'admin');
        $this->load->model('Pelanggan_model');
        $this->load->model('Group_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['admin'] = $this->admin->getAlladmin();
        // $data['transaksi']= $this->dashboard->getAlltransaksi();
        // $data['fitur']= $this->dashboard->getAllfitur();
        $this->headers();
        $this->load->view('admin/index', $data);
        $this->load->view('includes/footer');
    }

    public function detail($id)
    {
        // $data = $this->admin->getcurrency();
		$data['group'] = $this->Group_model->getAllgroups();
        $data['user'] = $this->admin->getusersbyid($id);
        // $data['countorder'] = $this->admin->countorder($id);
        // $data['wallet'] = $this->admin->wallet($id);
        // $data['fitur']= $this->dashboard->getAllfitur();
        $this->headers();
        $this->load->view('admin/detailadmin', $data);
        $this->load->view('includes/footer');
    }

    public function block($id)
    {
        $this->admin->blockusersById($id);
        $this->session->set_flashdata('block', 'blocked');
        redirect('users');
    }

    public function unblock($id)
    {
        $this->admin->unblockusersById($id);
        $this->session->set_flashdata('block', 'unblock');
        redirect('users');
    }

    public function ubahid()
    {

        $this->form_validation->set_rules('user_name', 'user_name', 'trim|prep_for_form');
        $this->form_validation->set_rules('email', 'email', 'trim|prep_for_form');
        $id = html_escape($this->input->post('id', TRUE));


        if ($this->form_validation->run() == TRUE) {
            $data             = [
                'id'			=> html_escape($this->input->post('id', TRUE)),
                'user_name'		=> html_escape($this->input->post('user_name', TRUE)),
                'group_id'		=> html_escape($this->input->post('group_id', TRUE)),
                'email'			=> html_escape($this->input->post('email', TRUE))
            ];


            if (demo == TRUE) {
                $this->session->set_flashdata('demo', 'NOT ALLOWED FOR DEMO');
                redirect('admin/detail/' . $id);
            } else {
                $this->admin->ubahdataid($data);
                $this->session->set_flashdata('ubah', 'Admin Has Been Change');
                redirect('admin/detail/' . $id);
            }
        } else {

            $data = $this->admin->getcurrency();
            $data['user'] = $this->admin->getusersbyid($id);
            $data['countorder'] = $this->admin->countorder($id);
            // $data['transaksi']= $this->dashboard->getAlltransaksi();
            // $data['fitur']= $this->dashboard->getAllfitur();
            $this->headers();
            $this->load->view('admin/detailadmin', $data);
            $this->load->view('includes/footer');
        }
    }

    public function ubahfoto()
    {

        $config['upload_path']     = './images/admin/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']         = '10000';
        $config['file_name']     = 'name';
        $config['encrypt_name']     = true;
        $this->load->library('upload', $config);
        $id = $id = html_escape($this->input->post('id', TRUE));
        $data = $this->admin->getusersbyid($id);

        if ($this->upload->do_upload('fotopelanggan')) {
            if ($data['fotopelanggan'] != 'noimage.jpg') {
                $gambar = $data['fotopelanggan'];
                unlink('images/admin/' . $gambar);
            }

            $foto = html_escape($this->upload->data('file_name'));

            $data = [
                'image'	=> $foto,
                'id'			=> html_escape($this->input->post('id', TRUE))
            ];

            if (demo == TRUE) {
                $this->session->set_flashdata('demo', 'NOT ALLOWED FOR DEMO');
                redirect('admin/detail/' . $id);
            } else {
                $this->admin->ubahdatafoto($data);
                $this->session->set_flashdata('ubah', 'Admin Has Been Change');
                redirect('admin/detail/' . $id);
            }
        } else {

            $data = $this->admin->getcurrency();
            $data['user'] = $this->admin->getusersbyid($id);
            $this->headers();
            $this->load->view('admin/detailadmin', $data);
            $this->load->view('includes/footer');
        }
    }

    public function ubahpass()
    {

        $this->form_validation->set_rules('password', 'password', 'trim|prep_for_form');

        if ($this->form_validation->run() == TRUE) {
            $id = $this->input->post('id');
            $data = $this->input->post('password');
            $dataencrypt = sha1($data);

            $data             = [
                'id'            => html_escape($this->input->post('id', TRUE)),
                'password'      => $dataencrypt
            ];

            if (demo == TRUE) {
                $this->session->set_flashdata('demo', 'NOT ALLOWED FOR DEMO');
                redirect('admin/detail/' . $id);
            } else {
                $this->admin->ubahdatapassword($data);
                $this->session->set_flashdata('ubah', 'User Has Been Change');
                redirect('admin/detail/' . $id);
            }
        } else {
            $data = $this->admin->getcurrency();
            $data['user'] = $this->admin->getusersbyid($id);
            $data['countorder'] = $this->admin->countorder($id);
            // $data['transaksi']= $this->dashboard->getAlltransaksi();
            // $data['fitur']= $this->dashboard->getAllfitur();
            $this->headers();
            $this->load->view('admin/detailadmin', $data);
            $this->load->view('includes/footer');
        }
    }

    public function adminblock($id)
    {
        $this->admin->blockuserbyid($id);
        redirect('admin');
    }

    public function adminunblock($id)
    {
        $this->admin->unblockuserbyid($id);
        redirect('admin');
    }

    public function tambah()
    {


        $password = html_escape($this->input->post('password', TRUE));
        $email = html_escape($this->input->post('email', TRUE));

        $this->form_validation->set_rules('user_name', 'NAME', 'trim|prep_for_form');
        $this->form_validation->set_rules('email', 'EMAIL', 'trim|prep_for_form');
        $this->form_validation->set_rules('password', 'PASSWORD', 'trim|prep_for_form');

        if ($this->form_validation->run() == TRUE) {

            $config['upload_path']     = './images/admin/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']         = '10000';
            $config['file_name']     = 'name';
            $config['encrypt_name']     = true;
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('fotopelanggan')) {

                $foto = html_escape($this->upload->data('file_name'));
            } else {
                $foto = 'noimage.jpg';
            }





            $data             = [
                'image'						=> $foto,
                'user_name'					=> html_escape($this->input->post('user_name', TRUE)),
                'email'                     => html_escape($this->input->post('email', TRUE)),
                'group_id'                     => html_escape($this->input->post('group_id', TRUE)),
                'password'                  => sha1($password),

            ];
            if (demo == TRUE) {
                $this->session->set_flashdata('demo', 'NOT ALLOWED FOR DEMO');
                redirect('admin/index');
            } else {

                $this->admin->tambahdatausers($data);
                $this->session->set_flashdata('tambah', 'Admin Has Been Added');
                redirect('admin/index');
            }
        } else {
			$data['group'] = $this->Group_model->getAllgroups();
			
            $this->headers();
            $this->load->view('admin/tambahadmin', $data);
            $this->load->view('includes/footer');
            // }
        }
    }

    public function hapususers($id)
    {
        if (demo == TRUE) {
            $this->session->set_flashdata('demo', 'NOT ALLOWED FOR DEMO');
            redirect('admin/index');
        } else {
            $data = $this->admin->getusersbyid($id);
            $gambar = $data['fotopelanggan'];
            unlink('images/admin/' . $gambar);

            $this->admin->hapusdatauserbyid($id);

            $this->session->set_flashdata('hapus', 'User Has Been Deleted');
            redirect('admin/index');
        }
    }
}
