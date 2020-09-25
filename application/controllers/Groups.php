<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Groups extends MX_Controller
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
        $this->load->model('Group_model', 'group');
        $this->load->model('Pelanggan_model');
        $this->load->model('Group_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['group'] = $this->group->getAllgroups();
        // $data['transaksi']= $this->dashboard->getAlltransaksi();
        // $data['fitur']= $this->dashboard->getAllfitur();
        $this->headers();
        $this->load->view('groups/index', $data);
        $this->load->view('includes/footer');
    }

    public function detail($id)
    {
        // $data = $this->group->getcurrency();
        $data['user'] = $this->group->getgroupsbyid($id);
        // $data['countorder'] = $this->group->countorder($id);
        // $data['wallet'] = $this->group->wallet($id);
        // $data['fitur']= $this->dashboard->getAllfitur();
        $this->headers();
        $this->load->view('groups/detailgroup', $data);
        $this->load->view('includes/footer');
    }

    public function block($id)
    {
        $this->group->blockgroupsById($id);
        $this->session->set_flashdata('block', 'blocked');
        redirect('groups');
    }

    public function unblock($id)
    {
        $this->group->unblockgroupsById($id);
        $this->session->set_flashdata('block', 'unblock');
        redirect('groups');
    }

    public function ubahid()
    {

		$this->form_validation->set_rules('group_name', 'NAME', 'trim|prep_for_form');
		$this->form_validation->set_rules('desc', 'NAME', 'trim|prep_for_form');
        $id = html_escape($this->input->post('id', TRUE));


        if ($this->form_validation->run() == TRUE) {
            $data             = [
                'group_id'		=> html_escape($this->input->post('id', TRUE)),
                'group_name'	=> html_escape($this->input->post('group_name', TRUE)),
                'desc'			=> html_escape($this->input->post('desc', TRUE))
            ];


            if (demo == TRUE) {
                $this->session->set_flashdata('demo', 'NOT ALLOWED FOR DEMO');
                redirect('groups/detail/' . $id);
            } else {
                $this->group->ubahdataid($data);
                $this->session->set_flashdata('ubah', 'Group Has Been Change');
                redirect('groups/detail/' . $id);
            }
        } else {

            $data['user'] = $this->group->getgroupsbyid($id);
            $this->headers();
            $this->load->view('groups/detailgroup', $data);
            $this->load->view('includes/footer');
        }
    }

    public function ubahfoto()
    {

        $config['upload_path']     = './images/groups/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']         = '10000';
        $config['file_name']     = 'name';
        $config['encrypt_name']     = true;
        $this->load->library('upload', $config);
        $id = $id = html_escape($this->input->post('id', TRUE));
        $data = $this->group->getgroupsbyid($id);

        if ($this->upload->do_upload('fotopelanggan')) {
            if ($data['fotopelanggan'] != 'noimage.jpg') {
                $gambar = $data['fotopelanggan'];
                unlink('images/groups/' . $gambar);
            }

            $foto = html_escape($this->upload->data('file_name'));

            $data = [
                'image'	=> $foto,
                'id'			=> html_escape($this->input->post('id', TRUE))
            ];

            if (demo == TRUE) {
                $this->session->set_flashdata('demo', 'NOT ALLOWED FOR DEMO');
                redirect('groups/detail/' . $id);
            } else {
                $this->group->ubahdatafoto($data);
                $this->session->set_flashdata('ubah', 'Group Has Been Change');
                redirect('groups/detail/' . $id);
            }
        } else {

            $data = $this->group->getcurrency();
            $data['user'] = $this->group->getgroupsbyid($id);
            $this->headers();
            $this->load->view('groups/detailgroup', $data);
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
                redirect('groups/detail/' . $id);
            } else {
                $this->group->ubahdatapassword($data);
                $this->session->set_flashdata('ubah', 'Group Has Been Change');
                redirect('groups/detail/' . $id);
            }
        } else {
            $data = $this->group->getcurrency();
            $data['user'] = $this->group->getgroupsbyid($id);
            $data['countorder'] = $this->group->countorder($id);
            // $data['transaksi']= $this->dashboard->getAlltransaksi();
            // $data['fitur']= $this->dashboard->getAllfitur();
            $this->headers();
            $this->load->view('groups/detailgroup', $data);
            $this->load->view('includes/footer');
        }
    }

    public function groupblock($id)
    {
        $this->group->blockuserbyid($id);
        redirect('group');
    }

    public function groupunblock($id)
    {
        $this->group->unblockuserbyid($id);
        redirect('group');
    }

    public function tambah()
    {

        $this->form_validation->set_rules('group_name', 'NAME', 'trim|prep_for_form');
        $this->form_validation->set_rules('desc', 'EMAIL', 'trim|prep_for_form');

        if ($this->form_validation->run() == TRUE) {





            $data             = [
                'group_name'		=> html_escape($this->input->post('group_name', TRUE)),
                'desc'				=> html_escape($this->input->post('desc', TRUE)),

            ];
            if (demo == TRUE) {
                $this->session->set_flashdata('demo', 'NOT ALLOWED FOR DEMO');
                redirect('groups/index');
            } else {

                $this->group->tambahdatagroups($data);
                $this->session->set_flashdata('tambah', 'Group Has Been Added');
                redirect('groups/index');
            }
        } else {
			$data['group'] = $this->Group_model->getAllgroups();
			
            $this->headers();
            $this->load->view('groups/tambahgroup', $data);
            $this->load->view('includes/footer');
            // }
        }
    }

    public function hapusgroups($id)
    {
        if (demo == TRUE) {
            $this->session->set_flashdata('demo', 'NOT ALLOWED FOR DEMO');
            redirect('groups/index');
        } else {
            $this->group->hapusdatauserbyid($id);

            $this->session->set_flashdata('hapus', 'Group Has Been Deleted');
            redirect('groups/index');
        }
    }
}
