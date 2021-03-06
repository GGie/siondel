<?php defined('BASEPATH') or exit('No direct script access allowed');



class Samsat extends CI_Controller
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
        $this->load->model('Petugas_model');
        date_default_timezone_set('Asia/Jakarta');
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
							$check_banned = $this->Petugas_model->check_banned_user($email);
							if ($check_banned) {
								$returnValue = json_encode(array('status' => "01", 'message'=>'banned', 'data' => []));
							} else {
								$cek_login = $this->Petugas_model->get_data_petugas($condition);
								$message = array();

								if ($cek_login->num_rows() > 0) {
									
									$get_petugas = $this->Petugas_model->get_data_petugas($condition);
									$upd_regid = $this->Petugas_model->edit_profile($reg_id, $get_petugas->row()->no_telepon);
									
									$returnValue = json_encode(array('status' => "200", 'message'=> 'found', 'data' => $get_petugas->result() ));
								} else {
									$returnValue = json_encode(array('status' => "404", 'message'=> 'Wrong email or password', 'data' => [] ));
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
        $check_exist 		= $this->Petugas_model->check_exist($email, $phone);
        $check_exist_phone 	= $this->Petugas_model->check_exist_phone($phone);
        $check_exist_email 	= $this->Petugas_model->check_exist_email($email);
		
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
						$signup = $this->Petugas_model->signup($data_signup);
						if ($signup) {
							$condition = array(
								'password' => sha1($pin),
								'email' => $email
							);
							$datauser1 = $this->Petugas_model->get_data_petugas($condition);

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
	
	public function ambil_kuota(){
		header('Content-Type: application/json');

				$params = array(
					'id_transaksi'		=> "100",
					'id_samsat'		=> "4"
				);

				$params_string = json_encode($params);
				
				if ( sandbox ) {
					$urlEnv = DashboardSamsat_dev;
				} else {
					$urlEnv = DashboardSamsat;
				}
				
				$url = $urlEnv . '/api/order/driver';

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_HTTPHEADER, [   
					'Content-Type: application/json' 
				]);
				$request = curl_exec($ch);
				$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				 
				curl_close($ch);
						 
				if($httpCode == 200)
				{
					header('Content-Type: application/json');
					
					$data = json_decode($request, true);
					
					if ( empty(@$data['message']) ) {
						$result['status'] = "200";
						$result['message'] = "Success";
						$result=array_merge($result,array('data'=>$data));
						$returnValue =  json_encode($result);
					} else {
						$data = array('status' => "201", "message" => $data['message'] );
						$returnValue = json_encode($data); 
					}
					// var_dump($request);
					
				}
				else {
					$data = array('status' => $httpCode, "message" => $request );
					$returnValue = json_encode($data); 
				}
				
			echo $returnValue;
				
	}
	
	public function test(){
		if ( sandbox ) {
			$urlEnv = DashboardSamsat_dev;
		} else {
			$urlEnv = DashboardSamsat;
		}
				
		$apiLink = base_url("api/samsat/ambil_kuota/1/100");
		$response = file_get_contents($apiLink);
		$return =  json_decode($response);
		if (@$return->status == 201) {
			echo "OK";
		} else { 
			echo "test";
		}
	}
	
	public function batal_kuota($id_samsat, $id_transaksi){
		header('Content-Type: application/json');

				$params = array(
					'id_transaksi'	=> $id_transaksi,
					'id_samsat'		=> $id_samsat
				);

				$params_string = json_encode($params);
				
				if ( sandbox ) {
					$urlEnv = DashboardSamsat_dev;
				} else {
					$urlEnv = DashboardSamsat;
				}
				
				$url = $urlEnv . '/api/order/driver';

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_HTTPHEADER, [   
					'Content-Type: application/json' 
				]);
				$request = curl_exec($ch);
				$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				 
				curl_close($ch);
						 
				if($httpCode == 200)
				{
					header('Content-Type: application/json');
					
					$data = json_decode($request, true);
					
					if ( empty(@$data['message']) ) {
						$result['status'] = "200";
						$result['message'] = "Success";
						$result=array_merge($result,array('data'=>$data));
						$returnValue =  json_encode($result);
					} else {
						$data = array('status' => "201", "message" => $data['message'] );
						$returnValue = json_encode($data); 
					}
					// var_dump($request);
					
				}
				else {
					$data = array('status' => $httpCode, "message" => $request );
					$returnValue = json_encode($data); 
				}
				
			echo $returnValue;
				
	}
	
	/*
	public function samsat()
    {
		$data = file_get_contents('php://input');
		$result = json_decode($data, true);
	
		header('Content-Type: application/json');

		$uid 		= @$result["uid"];
		$signature 	= @$result["signature"];
		
		$samsatID	= @$result["samsatid"];
		$nama		= @$result["nama"];
		$address	= @$result["address"];
        $time		= @$result["time"];
		$token		= @$result["token"];
		
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
						
						$param = array(
							'id'		=> @$samsatID,
							'nama'		=> @$nama,
							'address'	=> @$address
						);
						
						$samsat = $this->getsamsat($param);
						if ($samsat != "01") {
							$data = array('status' => "200", "message" => 'success', 'data' => $samsat );
							$returnValue = json_encode($data);
						} else {
							$data = array('status' => "201", "message" => 'failed', 'data' => []);
							$returnValue = json_encode($data);
						}
					}
				
				
		} catch(Exception $ex)
		{
			$data = array('status' => "01", "message" => $ex->getMessage(), 'data' => []);
			$returnValue = json_encode($data);
		}
		
		echo $returnValue;
    }
	
	
	public function getsamsat($param = "")
    {
		header('Content-Type: application/json');
		
		$apiLink = "http://103.41.206.172/rest/api/samsat?nama=" . @$param['nama'] . "&id=" . @$param['id'] . "&address=" . @$param['address'];
		$response = file_get_contents($apiLink);
		// var_dump($response); 
		if(!empty($response)){
			$result = json_encode($response);
					echo json_decode($result);
					 // echo "paymentUrl :". $result['result'] . "<br />";
					 // return $result['result']['access_token'];
		  // echo $response;
		}else{
		  return "01";
		}
		
			
    }
	
	
	public function test()
    {
		header('Content-Type: application/json');
		
		$param['id'] = 1;
		$apiLink = "http://localhost:8080/public_apps/api/samsat/getsamsat/1" . $param['id'];
		$response = file_get_contents($apiLink);
		// var_dump($response); 
		if(!empty($response)){
			$result = json_encode($response, JSON_FORCE_OBJECT);
					$test =  json_decode($result);
					
					foreach($test as $a ){ 
						echo $a->id;
					 }
		}else{
		  return "01";
		}
		
			
    }
	*/

	
	
}
