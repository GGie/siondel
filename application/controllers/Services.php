<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Services extends MX_Controller
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

        $this->load->model('service_model', 'service');
        // $this->load->model('news_model', 'news');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data = $this->service->getcurrency();
        $data['service'] = $this->service->getallservice();
        $data['driverjob'] = $this->service->getAlldriverjob();


        $this->headers();
        $this->load->view('services/index', $data);
        $this->load->view('includes/footer');
    }

    public function ubah($id)
    {
        $this->form_validation->set_rules('fitur', 'fitur', 'trim|prep_for_form');
        $this->form_validation->set_rules('home', 'home', 'trim|prep_for_form');
        $this->form_validation->set_rules('biaya', 'biaya', 'trim|prep_for_form');
        $this->form_validation->set_rules('keterangan_biaya', 'keterangan_biaya', 'trim|prep_for_form');
        $this->form_validation->set_rules('komisi', 'komisi', 'trim|prep_for_form');
        $this->form_validation->set_rules('driver_job', 'driver_job', 'trim|prep_for_form');
        $this->form_validation->set_rules('biaya_minimum', 'biaya_minimum', 'trim|prep_for_form');
        $this->form_validation->set_rules('jarak_minimum', 'jarak_minimum', 'trim|prep_for_form');
        $this->form_validation->set_rules('maks_distance', 'maks_distance', 'trim|prep_for_form');
        $this->form_validation->set_rules('wallet_minimum', 'wallet_minimum', 'trim|prep_for_form');
        $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|prep_for_form');

        $data = $this->service->getfiturbyid($id);
        $data['job'] = $this->service->getalldriverjob($id);
        $data['driverjob'] = $this->service->getAlldriverjob();

        $id = html_escape($this->input->post('id_fitur', TRUE));
        // $data['service'] = $this->service->getallservice();


        if ($this->form_validation->run() == TRUE) {
            $config['upload_path']     = './images/fitur/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']         = '10000';
            $config['file_name']     = 'name';
            $config['encrypt_name']     = true;
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('icon')) {
                if ($data['icon'] != 'noimage.jpg') {
                    $gambar = $data['icon'];
                    unlink('images/fitur/' . $gambar);
                }

                $gambar = html_escape($this->upload->data('file_name'));
            } else {
                $gambar = $data['icon'];
            }

            $biaya = html_escape($this->input->post('biaya', TRUE));
            $biaya_minimum = html_escape($this->input->post('biaya_minimum', TRUE));
            $wallet_minimum = html_escape($this->input->post('wallet_minimum', TRUE));

            $remove = array(".", ",");
            $add = array("", "");

			//schedule
			$schedule = array(
				'monday' => array (
						'start' => $this->input->post('mon_time_1', TRUE),
						'end' => $this->input->post('mon_time_2', TRUE),
				),
				'tuesday' => array (
						'start' => $this->input->post('tue_time_1', TRUE),
						'end' => $this->input->post('tue_time_2', TRUE),
				),
				'wednesday' => array (
						'start' => $this->input->post('wed_time_1', TRUE),
						'end' => $this->input->post('wed_time_2', TRUE),
				),
				'thursday' => array (
						'start' => $this->input->post('thu_time_1', TRUE),
						'end' => $this->input->post('thu_time_2', TRUE),
				),
				'friday' => array (
						'start' => $this->input->post('fri_time_1', TRUE),
						'end' => $this->input->post('fri_time_2', TRUE),
				),
				'saturday' => array (
						'start' => $this->input->post('sat_time_1', TRUE),
						'end' => $this->input->post('sat_time_2', TRUE),
				),
				'sunday' => array (
						'start' => $this->input->post('sun_time_1', TRUE),
						'end' => $this->input->post('sun_time_2', TRUE),
				)
			);
			$inputSchedule = json_encode($schedule);
			//schedule EOF
            $data             = [
                'icon'                          => $gambar,
                'id_fitur'                      => html_escape($this->input->post('id_fitur', TRUE)),
                'fitur'                         => html_escape($this->input->post('fitur', TRUE)),
                'home'                         	=> html_escape($this->input->post('home', TRUE)),
                'urutan'                        => html_escape($this->input->post('urutan', TRUE)),
                'biaya'                         => str_replace($remove, $add, $biaya),
				'fixed'                         => html_escape($this->input->post('fixed', TRUE)),
                'keterangan_biaya'              => html_escape($this->input->post('keterangan_biaya', TRUE)),
                'komisi'                        => html_escape($this->input->post('komisi', TRUE)),
                'schedule'						=> $inputSchedule,
                'driver_job'                    => html_escape($this->input->post('driver_job', TRUE)),
                'biaya_minimum'                 => str_replace($remove, $add, $biaya_minimum),
                'jarak_minimum'                 => html_escape($this->input->post('jarak_minimum', TRUE)),
                'maks_distance'                 => html_escape($this->input->post('maks_distance', TRUE)),
                'wallet_minimum'                => str_replace($remove, $add, $wallet_minimum),
                'keterangan'                    => html_escape($this->input->post('keterangan', TRUE)),
                'nilai'                         => html_escape($this->input->post('nilai', TRUE)),
                'keterangan'                    => html_escape($this->input->post('keterangan', TRUE)),
                'active'                        => html_escape($this->input->post('active', TRUE))
            ];

            if (demo == TRUE) {
                $this->session->set_flashdata('demo', 'NOT ALLOWED FOR DEMO');
                redirect('services/index');
            } else {

                $this->service->ubahdatafitur($data);
                $this->session->set_flashdata('ubah', 'Services Has Been Changed');
                redirect('services');
            }
        } else {

            $this->headers();
            $this->load->view('services/editservices' . $id, $data);
            $this->load->view('includes/footer');
        }
    }

    public function addservice()

    {
        $this->form_validation->set_rules('fitur', 'fitur', 'trim|prep_for_form');
        $this->form_validation->set_rules('home', 'home', 'trim|prep_for_form');
        $this->form_validation->set_rules('biaya', 'biaya', 'trim|prep_for_form');
        $this->form_validation->set_rules('keterangan_biaya', 'keterangan_biaya', 'trim|prep_for_form');
        $this->form_validation->set_rules('komisi', 'komisi', 'trim|prep_for_form');
        $this->form_validation->set_rules('driver_job', 'driver_job', 'trim|prep_for_form');
        $this->form_validation->set_rules('biaya_minimum', 'biaya_minimum', 'trim|prep_for_form');
        $this->form_validation->set_rules('jarak_minimum', 'jarak_minimum', 'trim|prep_for_form');
        $this->form_validation->set_rules('maks_distance', 'maks_distance', 'trim|prep_for_form');
        $this->form_validation->set_rules('wallet_minimum', 'wallet_minimum', 'trim|prep_for_form');
        $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|prep_for_form');
        $this->form_validation->set_rules('nilai', 'nilai', 'trim|prep_for_form');
        $this->form_validation->set_rules('active', 'active', 'trim|prep_for_form');

        if ($this->form_validation->run() == TRUE) {
            $config['upload_path']     = './images/fitur/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']         = '10000';
            $config['file_name']     = 'name';
            $config['encrypt_name']     = true;
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('icon')) {
                $gambar = html_escape($this->upload->data('file_name'));
            } else {
                $gambar = 'noimage.jpg';
            }

            $biaya = html_escape($this->input->post('biaya', TRUE));
            $biaya_minimum = html_escape($this->input->post('biaya_minimum', TRUE));
            $wallet_minimum = html_escape($this->input->post('wallet_minimum', TRUE));

            $remove = array(".", ",");
            $add = array("", "");

			//schedule
			$schedule = array(
				'monday' => array (
						'start' => $this->input->post('mon_time_1', TRUE),
						'end' => $this->input->post('mon_time_2', TRUE),
				),
				'tuesday' => array (
						'start' => $this->input->post('tue_time_1', TRUE),
						'end' => $this->input->post('tue_time_2', TRUE),
				),
				'wednesday' => array (
						'start' => $this->input->post('wed_time_1', TRUE),
						'end' => $this->input->post('wed_time_2', TRUE),
				),
				'thursday' => array (
						'start' => $this->input->post('thu_time_1', TRUE),
						'end' => $this->input->post('thu_time_2', TRUE),
				),
				'friday' => array (
						'start' => $this->input->post('fri_time_1', TRUE),
						'end' => $this->input->post('fri_time_2', TRUE),
				),
				'saturday' => array (
						'start' => $this->input->post('sat_time_1', TRUE),
						'end' => $this->input->post('sat_time_2', TRUE),
				),
				'sunday' => array (
						'start' => $this->input->post('sun_time_1', TRUE),
						'end' => $this->input->post('sun_time_2', TRUE),
				)
			);
			$inputSchedule = json_encode($schedule);
			//schedule EOF
            $data             = [
                'icon'                          => $gambar,
                'fitur'                         => html_escape($this->input->post('fitur', TRUE)),
                'home'                         	=> html_escape($this->input->post('home', TRUE)),
                'biaya'                         => str_replace($remove, $add, $biaya),
                'fixed'                         => html_escape($this->input->post('fixed', TRUE)),
                'keterangan_biaya'              => html_escape($this->input->post('keterangan_biaya', TRUE)),
                'komisi'                        => html_escape($this->input->post('komisi', TRUE)),
				'schedule'						=> $inputSchedule,
                'driver_job'                    => html_escape($this->input->post('driver_job', TRUE)),
                'biaya_minimum'                 => str_replace($remove, $add, $biaya_minimum),
                'jarak_minimum'                 => html_escape($this->input->post('jarak_minimum', TRUE)),
                'maks_distance'                 => html_escape($this->input->post('maks_distance', TRUE)),
                'wallet_minimum'                => str_replace($remove, $add, $wallet_minimum),
                'keterangan'                    => html_escape($this->input->post('keterangan', TRUE)),
                'active'                        => html_escape($this->input->post('active', TRUE))
            ];

            $datanilai = [

                'nilai'                         => html_escape($this->input->post('nilai', TRUE)),

            ];

            if (demo == TRUE) {
                $this->session->set_flashdata('demo', 'NOT ALLOWED FOR DEMO');
                redirect('services/index');
            } else {

                $this->service->tambahdatafitur($data, $datanilai);
                $this->session->set_flashdata('ubah', 'Services Has Been Added');
                redirect('services');
            }

        
        } else {
            
        $data['driverjob'] = $this->service->getAlldriverjob();

        $this->headers();
        $this->load->view('services/addservice', $data);
        $this->load->view('includes/footer');
        }
    }

    public function hapusservice($id)
    {
        if (demo == TRUE) {
            $this->session->set_flashdata('demo', 'NOT ALLOWED FOR DEMO');
            redirect('services/index');
        } else {
            $data = $this->service->getfiturbyid($id);

            if ($data['icon'] != 'noimage.jpg') {
                $gambar = $data['icon'];
                unlink('images/fitur/' . $gambar);
            }

            $this->service->hapusserviceById($id);
            $this->session->set_flashdata('hapus', 'Service Has Been deleted');
            redirect('services');
        }
    }
}
