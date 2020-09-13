<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Callback extends CI_Controller
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
        $this->load->model('driver_model', 'driver');
        $this->load->model('appsettings_model', 'app');
        $this->load->model('Pelanggan_model');
        $this->load->library('form_validation');
        $this->load->library('upload');
    }

    public function index()
    {
        $data['driver'] = $this->driver->getalldriver();


        $this->load->view('includes/header');
        $this->load->view('drivers/index', $data);
        $this->load->view('includes/footer');
    }
    
    public function confirm_pembayaran()
    {
      
            $apiKey = '68054f1fd975eed352deb7ccddab6227'; // Your api key
            $merchantCode = isset($_POST['merchantCode']) ? $_POST['merchantCode'] : null; 
            $amount = isset($_POST['amount']) ? $_POST['amount'] : null; 
            $merchantOrderId = isset($_POST['merchantOrderId']) ? $_POST['merchantOrderId'] : null; 
            $productDetail = isset($_POST['productDetail']) ? $_POST['productDetail'] : null; 
            $additionalParam = isset($_POST['additionalParam']) ? $_POST['additionalParam'] : null; 
            $paymentMethod = isset($_POST['paymentCode']) ? $_POST['paymentCode'] : null; 
            $resultCode = isset($_POST['resultCode']) ? $_POST['resultCode'] : null; 
            $merchantUserId = isset($_POST['merchantUserId']) ? $_POST['merchantUserId'] : null; 
            $reference = isset($_POST['reference']) ? $_POST['reference'] : null; 
            $signature = isset($_POST['signature']) ? $_POST['signature'] : null; 
            $vaNumber 			= isset($_POST['vaNumber']) ? $_POST['vaNumber'] : null; 
            $issuer_name 		= isset($_POST['issuer_name']) ? $_POST['issuer_name'] : null; // Hanya untuk ATM Bersama
            $issuer_bank 		= isset($_POST['issuer_bank']) ? $_POST['issuer_bank'] : null; // Hanya untuk ATM Bersama
            
            
            
            if(!empty($merchantCode) && !empty($amount) && !empty($merchantOrderId) && !empty($signature))
            {
                $params = $merchantCode . $amount . $merchantOrderId . $apiKey;
                $calcSignature = md5($params);
            
                if($signature == $calcSignature)
                {
                    
                            //write log
            			    file_put_contents('duitku.txt', "*** Logs virtual Account ***\r\n", FILE_APPEND | LOCK_EX);
            				file_put_contents('duitku.txt', "STATUS : " . $resultCode . "\r\n", FILE_APPEND | LOCK_EX);
            				file_put_contents('duitku.txt', "\r\n***************************\r\n\r\n", FILE_APPEND | LOCK_EX);
            				file_put_contents('duitku.txt', "*** " . date("Y-m-d H:i:s") . " ***\r\n", FILE_APPEND | LOCK_EX);
            				file_put_contents('duitku.txt', "merchantOrderId : " . $merchantOrderId . " \r\n", FILE_APPEND | LOCK_EX);
            				file_put_contents('duitku.txt', "amount : " . $amount . " \r\n", FILE_APPEND | LOCK_EX);
            				file_put_contents('duitku.txt', "merchantUserId : " . $merchantUserId . " \r\n", FILE_APPEND | LOCK_EX);
            				file_put_contents('duitku.txt', "merchantCode : " . $merchantCode . " \r\n", FILE_APPEND | LOCK_EX);
            				file_put_contents('duitku.txt', "productDetail : " . $productDetail . " \r\n", FILE_APPEND | LOCK_EX);
            				file_put_contents('duitku.txt', "additionalParam : " . $additionalParam . " \r\n", FILE_APPEND | LOCK_EX);
            				file_put_contents('duitku.txt', "resultCode : " . $resultCode . " \r\n", FILE_APPEND | LOCK_EX);
            				file_put_contents('duitku.txt', "signature : " . $signature . " \r\n", FILE_APPEND | LOCK_EX);
            				file_put_contents('duitku.txt', "paymentCode : " . $paymentMethod . " \r\n", FILE_APPEND | LOCK_EX);
            				file_put_contents('duitku.txt', "merchantUserId : " . $merchantUserId . " \r\n", FILE_APPEND | LOCK_EX);
            				file_put_contents('duitku.txt', "reference : " . $reference . " \r\n", FILE_APPEND | LOCK_EX);
            				file_put_contents('duitku.txt', "vaNumber : " . $vaNumber . " \r\n", FILE_APPEND | LOCK_EX);
            				file_put_contents('duitku.txt', "\r\n***************************\r\n\r\n", FILE_APPEND | LOCK_EX);
                    
                    //Your code here
                    echo "SUCCESS"; // Please response with success
                }
                else
                {
                    throw new Exception('Bad Signature');
                } 
                
            }
            else
            {
                throw new Exception('Bad Parameter');
            }


      
      
      
      
    }

    
}
