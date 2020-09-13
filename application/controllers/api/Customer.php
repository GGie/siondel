<?php defined('BASEPATH') or exit('No direct script access allowed');



class Customer extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ci_ext_model', 'ci_ext');
        $ci_ext = $this->ci_ext->ciext();
        if (!$ci_ext) {
            redirect(gagal);
        }
        $this->load->helper("url");
        $this->load->database();
        // $this->load->model('Driver_model');
        $this->load->model('Pelanggan_model');
        date_default_timezone_set('Asia/Jakarta');
    }

    function index_get()
    {
        $this->response("Api for ouride!", 200);
    }

    public function login()
	{
			$data = file_get_contents('php://input');
			$result = json_decode($data, true);
		
			header('Content-Type: application/json');

			$uid 		= @$result["uid"];
			$signature 	= @$result["signature"];
			$email		= @$result["email"];
			$pass		= @$result["password"];
			$token		= @$result["token"];
			
			try
			{
				if (!$result)
					throw new Exception("API KEY DATA");
				if (!$uid)
					throw new Exception("uid null.");
				if (!$signature)
					throw new Exception("signature null.");
				if (!$email)
					throw new Exception("email null.");
				if (!$pass)
					throw new Exception("pass null.");
				if (!$token)
					throw new Exception("token null.");
				
				$reg_id = array(
					'token' => $token
				);
		
				$secret = @$this->db->get_Where('user_api', array('uid'=>$uid))->row()->secret;
				$signatureGenerate	= hash('sha256', $uid . $secret . $email . $pass);
				
				if ($signature != $signatureGenerate)
					throw new Exception("Wrong Signature!!!");
				
				if ( $email AND $pass ) {		// Cek apakah user telah menginput email dan password
					
							$condition = array(
								'password' => sha1($pass),
								'email' => $email,
								//'token' => $decoded_data->token
							);
							$check_banned = $this->Pelanggan_model->check_banned_user($email);
							if ($check_banned) {
								$returnValue = json_encode(array('status' => "01", 'message'=>'banned', 'data' => []));
							} else {
								$cek_login = $this->Pelanggan_model->get_data_pelanggan($condition);
								$message = array();

								if ($cek_login->num_rows() > 0) {
									
									$get_pelanggan = $this->Pelanggan_model->get_data_pelanggan($condition);
									$upd_regid = $this->Pelanggan_model->edit_profile($reg_id, $get_pelanggan->row()->no_telepon);
									
									$returnValue = json_encode(array('status' => "200", 'message'=> 'found', 'data' => $get_pelanggan->result() ));
								} else {
									$returnValue = json_encode(array('status' => "404", 'message'=> 'email or password', 'data' => [] ));
								}
							}
			
				} else {
					$returnValue = json_encode(array('status' => "01", 'message'=>'Email, Password Null'));
				}
		
			} catch(Exception $ex)
			{
				$data = array('status' => "01", "message" => $ex->getMessage(), 'data' => []);
				$returnValue = json_encode($data);
			}
			
			echo $returnValue;
			
	}
	
	
	
	public function register()
    {
		$data = file_get_contents('php://input');
		$result = json_decode($data, true);
	
		header('Content-Type: application/json');

		$uid 		= @$result["uid"];
		$signature 	= @$result["signature"];
		
		$email 				= @$result["email"];
        $phone 				= @$result["nohp"];
        $pin 				= @$result["pin"];
        $token 				= @$result["token"];
        $check_exist 		= $this->Pelanggan_model->check_exist($email, $phone);
        $check_exist_phone 	= $this->Pelanggan_model->check_exist_phone($phone);
        $check_exist_email 	= $this->Pelanggan_model->check_exist_email($email);
		
		try
		{
				if (!$token)
					throw new Exception("token null.");
				if (!$phone)
					throw new Exception("phone null.");
				if (!$pin)
					throw new Exception("pin null.");
				if (!$email)
					throw new Exception("email null.");
				
				$secret = @$this->db->get_Where('user_api', array('uid'=>$uid))->row()->secret;
				$signatureGenerate	= hash('sha256', $uid . $secret . $email);
				
				if ($signature != $signatureGenerate)
					throw new Exception("Wrong Signature!!!");
				
				if ($check_exist) {
					$data = array('status' => "201", "message" => 'email and phone number already exist', 'data' => []);
					$returnValue = json_encode($data);
				} else if ($check_exist_phone) {
					$data = array('status' => "201", "message" => 'phone already exist', 'data' => []);
					$returnValue = json_encode($data);
				} else if ($check_exist_email) {
					$data = array('status' => "201", "message" => 'email already exist', 'data' => []);
					$returnValue = json_encode($data);
				} else {
					if (@$result["checked"] == "true") {
						$data = array('status' => "200", "message" => 'next', 'data' => []);
						$returnValue = json_encode($data);
					} else {
						// $image = $dec_data->fotopelanggan;
						// $namafoto = time() . '-' . rand(0, 99999) . ".jpg";
						// $path = "images/pelanggan/" . $namafoto;
						// file_put_contents($path, base64_decode($image));
						$data_signup = array(
							'id' => 'P' . time(),
							'fullnama' => $email,
							'email' => $email,
							'no_telepon' => number_hp_prefix($phone),
							'phone' => $phone,
							'password' => sha1($pin),
							// 'tgl_lahir' => $dec_data->tgl_lahir,
							'countrycode' => "+62",
							// 'fotopelanggan' => $namafoto,
							'token' => $token,
						);
						$signup = $this->Pelanggan_model->signup($data_signup);
						if ($signup) {
							$condition = array(
								'password' => sha1($pin),
								'email' => $email
							);
							$datauser1 = $this->Pelanggan_model->get_data_pelanggan($condition);

							$data = array('status' => "200", "message" => 'success', 'data' => $datauser1->result() );
							$returnValue = json_encode($data);
						} else {
							$data = array('status' => "201", "message" => 'failed', 'data' => []);
							$returnValue = json_encode($data);
						}
					}
				}
				
		} catch(Exception $ex)
		{
			$data = array('status' => "01", "message" => $ex->getMessage(), 'data' => []);
			$returnValue = json_encode($data);
		}
		
		echo $returnValue;
    }
	
	
	public function samsat()
    {
		$data = file_get_contents('php://input');
		$result = json_decode($data, true);
	
		header('Content-Type: application/json');

		$uid 		= @$result["uid"];
		$signature 	= @$result["signature"];
		
		$samsatID	= @$result["samsatid"];
        $time		= @$result["time"];
		
		try
		{
				if (!$time)
					throw new Exception("time null.");
				
				$secret = @$this->db->get_Where('user_api', array('uid'=>$uid))->row()->secret;
				$signatureGenerate	= hash('sha256', $uid . $secret . $time);
				
				if ($signature != $signatureGenerate)
					throw new Exception("Wrong Signature!!!");
				
					if (@$result["checked"] == "true") {
						$data = array('status' => "200", "message" => 'next', 'data' => []);
						$returnValue = json_encode($data);
					} else {
						
						if ($samsatID != '') {
							$reg_id = array('id' => $samsatID);
							$this->db->where($reg_id);
						}
						
						$this->db->where('status', 1);
						$samsat = $this->db->get('samsat');
						// if ($signup) {
							$data = array('status' => "200", "message" => 'success', 'data' => $samsat->result() );
							$returnValue = json_encode($data);
						// } else {
							// $data = array('status' => "201", "message" => 'failed', 'data' => []);
							// $returnValue = json_encode($data);
						// }
					}
				
				
		} catch(Exception $ex)
		{
			$data = array('status' => "01", "message" => $ex->getMessage(), 'data' => []);
			$returnValue = json_encode($data);
		}
		
		echo $returnValue;
    }
	
	
	public function dataTransaksi()
    {
		$data = file_get_contents('php://input');
		$result = json_decode($data, true);
	
		header('Content-Type: application/json');

		$uid 		= @$result["uid"];
		$signature 	= @$result["signature"];
		
		$no_polis			= @$result["no_polis"];
        $nik 				= @$result["nik"];
        $no_rangka			= @$result["no_rangka"];
        $kode 				= @$result["kode"];
        $email 				= @$result["email"];
        $phone 				= @$result["phone"];
		
		try
		{
				if (!$no_polis)
					throw new Exception("no_polis null.");
				if (!$nik)
					throw new Exception("nik null.");
				if (!$no_rangka)
					throw new Exception("no_rangka null.");
				if (!$kode)
					throw new Exception("kode null.");
				
				$secret = @$this->db->get_Where('user_api', array('uid'=>$uid))->row()->secret;
				$signatureGenerate	= hash('sha256', $uid . $secret . $kode);
				
				if ($signature != $signatureGenerate)
					throw new Exception("Wrong Signature!!!");

						$data_transaksi = array(
							'id_transaksi'	=> time(),
							'no_polis'		=> $no_polis,
							'nik'			=> $nik,
							'no_rangka'		=> $no_rangka,
							'kode'			=> $kode,
							'email'			=> $email,
							'phone'			=> $phone,
						);
						$result = $this->Pelanggan_model->insert_dataTransaksi($data_transaksi);

						if ($result['status'] == true) {
							$data = array('status' => "200", "message" => 'success', 'data' => $data_transaksi );
							$returnValue = json_encode($data);
						} else {
							$data = array('status' => "201", "message" => 'failed', 'data' => []);
							$returnValue = json_encode($data);
						}
					
				// $data = array('status' => "201", "message" => 'test', 'data' => []);
				
		} catch(Exception $ex)
		{
			$data = array('status' => "01", "message" => $ex->getMessage(), 'data' => []);
			$returnValue = json_encode($data);
		}
		
		echo $returnValue;
    }
	
	public function validasiNopol(){
			// header('Content-Type: application/json');

				// $signature 			= md5($merchantCode . $merchantOrderId . $paymentAmount . $merchantKey);

				$params = array(
					'kdvoucher'		=> "ME35HRQL"
				);

				$params_string = json_encode($params);

				$url = 'https://dev.bankdki.co.id/getdatabykdvoucher'; // Localhost v2
				$ch = curl_init();

				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; U; Android 2.3.7; en-us; Nexus One Build/GRK39F) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1' );
				curl_setopt($ch, CURLOPT_URL, $url); 
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
				curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);                                                                  
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
					'Accept: application/json',                                                                      
					'Authorization: Bearer ' . $this->oauth2(),                                                                     
					// 'X-Originating-IP: 103.60.101.226',
					'Content-Type: application/json')                                                                    
				);   
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

				//execute post
				$request = curl_exec($ch);
				$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				var_dump($this->oauth2());
				if($httpCode == 200)
				{
					$result = json_decode($request, true);

					// header('location: '. $result['paymentUrl']);
					
					
					// echo $httpCode;
					$json_pretty = json_encode($result, JSON_PRETTY_PRINT);
					echo $json_pretty;

				}
				else {
					echo $httpCode . " - " . $request;
				}
				
	}
	
	public function test(){
		// header('Content-Type: application/json');
		header( 'Content-type: text/xml' );

				$params = array(
					'kdvoucher'		=> "ME35HRQL"
				);


				$params_string = json_encode($params);
				
				// file_put_contents('log.txt', "*** " . $params_string . " ***\r\n", FILE_APPEND | LOCK_EX);
				
				// $url = 'http://localhost:8080/public_html/api/customer/dataTransaksi';
				$url = 'https://dev.bankdki.co.id/getdatabykdvoucher';

				$ch = curl_init();
				
				$token = $this->oauth2();
				$header = array(                                                                          
					'Accept: application/json',                                                                      
					'Authorization: Bearer ' . $token,                                                                     
					// 'X-Originating-IP: 103.60.101.226',
					'Content-Type: application/json'                                                                   
				);
				
				curl_setopt($ch, CURLOPT_URL, $url); 
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
				curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);                                                                  
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);   
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

				//execute post
				$request = curl_exec($ch);
				$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				echo json_encode($header);
				if($httpCode == 200)
				{
					// header('Content-Type: application/json');
					
					// $result = json_decode($request, true);

					// $json_pretty = json_encode($result, JSON_PRETTY_PRINT);
					// echo $json_pretty;
					var_dump($request);
				}
				else {
					echo $httpCode . " - " . $request;
				}
	}
	
	public function oauth2()
	{
		header('Content-Type: application/json');
		
		$client_id = "f35ge8n6k5gt77qr4k8nprk7"; //authkeyForSMS;

		$apiLink = "http://portalapi.bankdki.co.id/io-docs/getoauth2accesstoken?apiId=22593&auth_flow=client_cred&client_id=" . $client_id . "&client_secret";
		$response = file_get_contents($apiLink);
		// var_dump($response); 
		if(!empty($response)){
			$result = json_decode($response, true);
					// echo ($request);
					 // echo "paymentUrl :". $result['result'] . "<br />";
					 return $result['result']['access_token'];
		  // echo $response;
		}else{
		  return "FAILED";
		}
		
	}
	
}
