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
	
	
}
