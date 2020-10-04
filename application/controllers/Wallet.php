<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wallet extends MX_Controller
{

    public function  __construct()
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
        // $this->load->library('form_validation');
        $this->load->model('wallet_model', 'wallet');
        $this->load->model('users_model', 'user');
    }

    public function index()
    {

        $data['jumlahdiskon'] = $this->wallet->getjumlahdiskon();
        $data['orderplus'] = $this->wallet->gettotalorderplus();
        $data['ordermin'] = $this->wallet->gettotalordermin();
        $data['withdraw'] = $this->wallet->gettotalwithdraw();
        $data['topup'] = $this->wallet->gettotaltopup();
        $data['saldo'] = $this->wallet->getallsaldo();
        $data['currency'] = $this->user->getcurrency();
        $data['wallet'] = $this->wallet->getwallet();

        $this->headers();
        $this->load->view('wallet/index', $data);
        $this->load->view('includes/footer');
    }

    public function wconfirm($id, $id_user, $amount)
    {
        $token = $this->wallet->gettoken($id_user);
        $regid = $this->wallet->getregid($id_user);
        $tokenmerchant = $this->wallet->gettokenmerchant($id_user);

        if ($token == NULL and $tokenmerchant == NULL and $regid != NULL) {
            $topic = $regid['reg_id'];
        } else if ($regid == NULL and $tokenmerchant == NULL and $token != NULL) {
            $topic = $token['token'];
        } else if ($regid == NULL and $token == NULL and $tokenmerchant != NULL) {
            $topic = $tokenmerchant['token_merchant'];
        }

        $title = 'Withdraw Success';
        $message = 'Withdraw Has Been Confirmed';
        $saldo = $this->wallet->getsaldo($id_user);



        $this->wallet->ubahsaldo($id_user, $amount, $saldo);
        $this->wallet->ubahstatuswithdrawbyid($id);
        $this->wallet->send_notif($title, $message, $topic);
        $this->session->set_flashdata('ubah', 'Withdraw Has Been Confirmed');
        redirect('wallet/index');
    }

    public function wcancel($id, $id_user)
    {
        $token = $this->wallet->gettoken($id_user);
        $regid = $this->wallet->getregid($id_user);
        $tokenmerchant = $this->wallet->gettokenmerchant($id_user);

        if ($token == NULL and $tokenmerchant == NULL and $regid != NULL) {
            $topic = $regid['reg_id'];
        } else if ($regid == NULL and $tokenmerchant == NULL and $token != NULL) {
            $topic = $token['token'];
        } else if ($regid == NULL and $token == NULL and $tokenmerchant != NULL) {
            $topic = $tokenmerchant['token_merchant'];
        }

        $title = 'Withdraw Cancel';
        $message = 'Withdraw Has Been Canceled';

        $this->wallet->cancelstatuswithdrawbyid($id);
        $this->wallet->send_notif($title, $message, $topic);
        $this->session->set_flashdata('ubah', 'Withdraw Has Been Canceled');
        redirect('wallet/index');
    }
    
    
   

    public function tconfirm($id, $id_user, $amount)
    {
        $token = $this->wallet->gettoken($id_user);
        $regid = $this->wallet->getregid($id_user);
        $tokenmerchant = $this->wallet->gettokenmerchant($id_user);

        if ($token == NULL and $tokenmerchant == NULL and $regid != NULL) {
            $topic = $regid['reg_id'];
        } else if ($regid == NULL and $tokenmerchant == NULL and $token != NULL) {
            $topic = $token['token'];
        } else if ($regid == NULL and $token == NULL and $tokenmerchant != NULL) {
            $topic = $tokenmerchant['token_merchant'];
        }

        $title = 'Topup success';
        $message = 'We Have Confirmed Your Topup';
        $saldo = $this->wallet->getsaldo($id_user);

        $this->wallet->ubahsaldotopup($id_user, $amount, $saldo);
        $this->wallet->ubahstatuswithdrawbyid($id);
        $this->wallet->send_notif($title, $message, $topic);
        $this->session->set_flashdata('ubah', 'topup has been confirmed');
        redirect('wallet/index');
    }

    public function tcancel($id, $id_user)
    {
        $token = $this->wallet->gettoken($id_user);
        $regid = $this->wallet->getregid($id_user);
        $tokenmerchant = $this->wallet->gettokenmerchant($id_user);

        if ($token == NULL and $regid != NULL) {
            $topic = $regid['reg_id'];
        }

        if ($regid == NULL and $token != NULL) {
            $topic = $token['token'];
        }

        if ($regid == NULL and $token == NULL and $tokenmerchant != NULL) {
            $topic = $tokenmerchant['token_merchant'];
        }

        $title = 'Topup canceled';
        $message = 'Sorry, topup has been canceled';

        $this->wallet->cancelstatuswithdrawbyid($id);
        $this->wallet->send_notif($title, $message, $topic);
        $this->session->set_flashdata('ubah', 'topup has been canceled');
        redirect('wallet/index');
    }

    public function tambahtopup()
    {
        $data['currency'] = $this->user->getcurrency();
        $data['saldo'] = $this->wallet->getallsaldouser();


        if ($_POST != NULL) {


            if ($this->input->post('type_user') == 'pelanggan') {
                $id_user = $this->input->post('id_pelanggan');
            } elseif ($this->input->post('type_user') == 'mitra') {
                $id_user = $this->input->post('id_mitra');
            } else {
                $id_user = $this->input->post('id_driver');
            }

            $saldo = html_escape($this->input->post('saldo', TRUE));
            $new_saldo = str_replace(array(".00", ","), array("", ""), $saldo);
            
    
            
           // $remove = array(".", ",");
            //$add = array("", "");

            $data = [
                'id_user'                       => $id_user,
                //'saldo'                         => str_replace($remove, $add, $saldo),
                'saldo'                         =>      $new_saldo,
                'type_user'                     => $this->input->post('type_user')
            ];


            $this->wallet->updatesaldowallet($data);
            $this->session->set_flashdata('ubah', 'Top Up Has Been Added');
            redirect('wallet');
        } else {
            $this->headers();
            $this->load->view('wallet/tambahtopup', $data);
            $this->load->view('includes/footer');
        }
    }

    public function tambahwithdraw()
    {
        $data['currency'] = $this->user->getcurrency();
        $data['saldo'] = $this->wallet->getallsaldouser();

        if ($_POST != NULL) {


            if ($this->input->post('type_user') == 'pelanggan') {
                $id_user = $this->input->post('id_pelanggan');
            } elseif ($this->input->post('type_user') == 'mitra') {
                $id_user = $this->input->post('id_mitra');
            } else {
                $id_user = $this->input->post('id_driver');
            }


            $saldo = html_escape($this->input->post('saldo', TRUE));
            $remove = array(".", ",");
            $add = array("", "");

            $data = [
                'id_user'                       => $id_user,
                'saldo'                         => str_replace($remove, $add, $saldo),
                'type_user'                     => $this->input->post('type_user')
            ];

            $data2 = [
                'bank'                          => $this->input->post('bank'),
                'nama_pemilik'                  => $this->input->post('nama_pemilik'),
                'rekening'                      => $this->input->post('rekening'),
            ];


            $this->wallet->updatesaldowalletwithdraw($data, $data2);
            $this->session->set_flashdata('ubah', 'Withdraw Has Been Added');
            redirect('wallet');
        } else {
            $this->headers();
            $this->load->view('wallet/tambahwithdraw', $data);
            $this->load->view('includes/footer');
        }
    }
	
	public function pdf_pay()
    {
		// $sql = "SELECT a.*,
				// (SELECT klasifikasi FROM tbl_klasifikasi AS b WHERE b.id_klasifikasi=a.id_klasifikasi) AS klasifikasi
				// FROM water_meter_report AS a";
		
		// $sql .= " WHERE a.status!=0";
		
		// if ( !empty($_GET['status_id'])) 
		// {
			// $sql .= " AND a.status='" . $_GET['status_id'] . "'";
		// }
		
		// if ( !empty($_GET['reference']) ) {
			// $sql .= " AND a.reference LIKE '%" . $_GET['reference'] . "%'";
		// }
		
		// if ( !empty($_GET['customer_id']) ) {
			// $sql .= " AND a.customer_id LIKE '%" . $_GET['customer_id'] . "%'";
		// }
		
		// if ( !empty($_GET['customer_name']) ) {
			// $sql .= " AND a.customer_name LIKE '%" . $_GET['customer_name'] . "%'";
		// }
		
		// if ( !empty($_GET['area']) ) {
			// $sql .= " AND a.area LIKE '%" . $_GET['area'] . "%'";
		// }
		
		// if ( !empty($_GET['address']) ) {
			// $sql .= " AND a.address LIKE '%" . $_GET['address'] . "%'";
		// }
		
		// $getPeriod = @$this->db->get_Where('period', array('status'=>1))->row()->id;
		// if ( !empty($_GET['period_id'])) 
		// {
			// $sql .= " AND a.period_id='" . trim($_GET['period_id']) . "'";
		// }
		// else if ( !empty($this->input->get('period')) AND $this->input->get('period') == 'all') 
		// {
			// if ( $this->input->get('period') != 'all' ) {
				// $sql .= " AND a.period_id='" . trim($this->input->get('period')) . "'";
				// $doubleCheck .= " AND a.period_id='" . trim($this->input->get('period')) . "'";
			// }
		// }
		// else {
		    // $sql .= " AND a.period_id='" . $getPeriod . "'";
		// }
		

		// $data['data']		= $this->db->query($sql);
        //load the view and saved it into $html variable
		
		$data['data'] = $this->wallet->getwalletbyid(883);
		
			$html = $this->load->view('wallet/pdf_pay', $data, true);
			// $html = $this->load->view('reportmeter/pdf_reportmeter', $data, true);
        //this the the PDF filename that user will get to download
        $pdfFilePath = "PDF_" . date('Ymd_His') . ".pdf";
 
        //load mPDF library
        $this->load->library('m_pdf');
 
		
       //generate the PDF from the given html
        $this->m_pdf->pdf->AddPage('P', // L - landscape, P - portrait
        '', '', '', '',
        4, // margin_left
        4, // margin right
        4, // margin top
        0, // margin bottom
        18, // margin header
        12); // margin footer
		$css = base_url('assets/css/style.css');
        $this->m_pdf->pdf->SetTitle($pdfFilePath);
        $this->m_pdf->pdf->WriteHTML($css, 1);
        $this->m_pdf->pdf->WriteHTML($html, 2);
 
        //download it.
        //$this->m_pdf->pdf->Output($pdfFilePath, "D");        
        $this->m_pdf->pdf->Output($pdfFilePath, "I");     		
    }
}
