<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Banks extends MX_Controller
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
        $this->load->model('Bank_model', 'bank');
        $this->load->model('Pelanggan_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['bank'] = $this->bank->getAllbank();
        // $data['transaksi']= $this->dashboard->getAlltransaksi();
        // $data['fitur']= $this->dashboard->getAllfitur();
        $this->headers();
        $this->load->view('banks/index', $data);
        $this->load->view('includes/footer');
    }

    public function detail($id)
    {
        $data = $this->bank->getcurrency();
        $data['bank'] = $this->bank->getbanksbyid($id);
        $data['countorder'] = $this->bank->countorder($id);
        $data['wallet'] = $this->bank->wallet($id);
        // $data['fitur']= $this->dashboard->getAllfitur();
        $this->headers();
        $this->load->view('banks/detailbanks', $data);
        $this->load->view('includes/footer');
    }

    public function block($id)
    {
        $this->bank->blockbanksById($id);
        $this->session->set_flashdata('block', 'blocked');
        redirect('banks');
    }

    public function unblock($id)
    {
        $this->bank->unblockbanksById($id);
        $this->session->set_flashdata('block', 'unblock');
        redirect('banks');
    }

    public function ubahid()
    {

        $this->form_validation->set_rules('fullnama', 'fullnama', 'trim|prep_for_form');
        $this->form_validation->set_rules('no_telepon', 'no_telepon', 'trim|prep_for_form');
        $this->form_validation->set_rules('email', 'email', 'trim|prep_for_form');
        $id = html_escape($this->input->post('id', TRUE));

        $countrycode = html_escape($this->input->post('countrycode', TRUE));
        $phone = html_escape($this->input->post('phone', TRUE));

        if ($this->form_validation->run() == TRUE) {
            $data             = [
                'phone'                     => html_escape($this->input->post('phone', TRUE)),
                'countrycode'               => html_escape($this->input->post('countrycode', TRUE)),
                'id'                        => html_escape($this->input->post('id', TRUE)),
                'fullnama'                    => html_escape($this->input->post('fullnama', TRUE)),
                'no_telepon'                => str_replace("+", "", $countrycode) . $phone,
                'email'                        => html_escape($this->input->post('email', TRUE)),
                'tgl_lahir'                        => html_escape($this->input->post('tgl_lahir', TRUE))
            ];


            if (demo == TRUE) {
                $this->session->set_flashdata('demo', 'NOT ALLOWED FOR DEMO');
                redirect('banks/detail/' . $id);
            } else {
                $this->bank->ubahdataid($data);
                $this->session->set_flashdata('ubah', 'Bank Has Been Change');
                redirect('banks/detail/' . $id);
            }
        } else {

            $data = $this->bank->getcurrency();
            $data['bank'] = $this->bank->getbanksbyid($id);
            $data['countorder'] = $this->bank->countorder($id);
            // $data['transaksi']= $this->dashboard->getAlltransaksi();
            // $data['fitur']= $this->dashboard->getAllfitur();
            $this->headers();
            $this->load->view('banks/detailbanks', $data);
            $this->load->view('includes/footer');
        }
    }

    public function ubahfoto()
    {

        $config['upload_path']     = './images/pelanggan/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']         = '10000';
        $config['file_name']     = 'name';
        $config['encrypt_name']     = true;
        $this->load->library('upload', $config);
        $id = $id = html_escape($this->input->post('id', TRUE));
        $data = $this->bank->getbanksbyid($id);

        if ($this->upload->do_upload('fotopelanggan')) {
            if ($data['fotopelanggan'] != 'noimage.jpg') {
                $gambar = $data['fotopelanggan'];
                unlink('images/pelanggan/' . $gambar);
            }

            $foto = html_escape($this->upload->data('file_name'));

            $data = [
                'fotopelanggan'       => $foto,
                'id'        => html_escape($this->input->post('id', TRUE))
            ];

            if (demo == TRUE) {
                $this->session->set_flashdata('demo', 'NOT ALLOWED FOR DEMO');
                redirect('banks/detail/' . $id);
            } else {
                $this->bank->ubahdatafoto($data);
                $this->session->set_flashdata('ubah', 'Bank Has Been Change');
                redirect('banks/detail/' . $id);
            }
        } else {

            $data = $this->bank->getcurrency();
            $data['bank'] = $this->bank->getbanksbyid($id);
            $data['countorder'] = $this->bank->countorder($id);
            // $data['transaksi']= $this->dashboard->getAlltransaksi();
            // $data['fitur']= $this->dashboard->getAllfitur();
            $this->headers();
            $this->load->view('banks/detailbanks', $data);
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
                redirect('banks/detail/' . $id);
            } else {
                $this->bank->ubahdatapassword($data);
                $this->session->set_flashdata('ubah', 'Bank Has Been Change');
                redirect('banks/detail/' . $id);
            }
        } else {
            $data = $this->bank->getcurrency();
            $data['bank'] = $this->bank->getbanksbyid($id);
            $data['countorder'] = $this->bank->countorder($id);
            // $data['transaksi']= $this->dashboard->getAlltransaksi();
            // $data['fitur']= $this->dashboard->getAllfitur();
            $this->headers();
            $this->load->view('banks/detailbanks', $data);
            $this->load->view('includes/footer');
        }
    }

    public function bankblock($id)
    {
        $this->bank->blockbankbyid($id);
        redirect('banks');
    }

    public function bankunblock($id)
    {
        $this->bank->unblockbankbyid($id);
        redirect('banks');
    }

    public function tambah()
    {


        $bank_code = html_escape($this->input->post('bank_code', TRUE));
        $bank_name = html_escape($this->input->post('bank_name', TRUE));
        $apply = html_escape($this->input->post('apply', TRUE));

        // $this->form_validation->set_rules('bank_code', 'bank_code', 'trim|prep_for_form');
        // $this->form_validation->set_rules('bank_name', 'bank_name', 'trim|prep_for_form');
        // $this->form_validation->set_rules('apply', 'apply', 'trim|prep_for_form');

        if ($this->input->post()) {


            $data             = [
                'bank_code'                     => html_escape($this->input->post('bank_code', TRUE)),
                'bank_name'               => html_escape($this->input->post('bank_name', TRUE)),
                'apply'                 => html_escape($this->input->post('apply', TRUE)),

            ];
            if (demo == TRUE) {
                $this->session->set_flashdata('demo', 'NOT ALLOWED FOR DEMO');
                redirect('bank/index');
            } else {

                $this->bank->tambahdatabanks($data);
                $this->session->set_flashdata('tambah', 'Bank Has Been Added');
                redirect('banks/index');
            }
        } else {
            $this->headers();
            $this->load->view('banks/tambahbank');
            $this->load->view('includes/footer');
            // }
        }
    }

    public function hapusbanks($id)
    {
        if (demo == TRUE) {
            $this->session->set_flashdata('demo', 'NOT ALLOWED FOR DEMO');
            redirect('banks/index');
        } else {
            $data = $this->bank->getbanksbyid($id);
            $gambar = $data['fotopelanggan'];
            unlink('images/pelanggan/' . $gambar);

            $this->bank->hapusdatabankbyid($id);

            $this->session->set_flashdata('hapus', 'Bank Has Been Deleted');
            redirect('banks/index');
        }
    }
}
