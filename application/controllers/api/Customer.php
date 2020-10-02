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
        $this->load->model('Driver_model');
        $this->load->model('Pelanggan_model');
        $this->load->model('Voucher_model');
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
								$returnValue = json_encode(array('status' => "01", 'message'=>'banned' ));
							} else {
								$cek_login = $this->Pelanggan_model->get_data_pelanggan($condition);
								$message = array();

								if ($cek_login->num_rows() > 0) {
									
									$get_pelanggan = $this->Pelanggan_model->get_data_pelanggan($condition);
									$upd_regid = $this->Pelanggan_model->edit_profile($reg_id, $get_pelanggan->row()->no_telepon);
									
									$returnValue = json_encode(array('status' => "200", 'message'=> 'found', 'data' => $get_pelanggan->result() ));
								} else {
									$returnValue = json_encode(array('status' => "404", 'message'=> 'email or password' ));
								}
							}
			
				} else {
					$returnValue = json_encode(array('status' => "01", 'message'=>'Email, Password Null'));
				}
		
			} catch(Exception $ex)
			{
				$data = array('status' => "01", "message" => $ex->getMessage() );
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
				if (!$result)
					throw new Exception("API KEY DATA");
				if (!$uid)
					throw new Exception("uid null.");
				if (!$signature)
					throw new Exception("signature null.");
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
					$data = array('status' => "201", "message" => 'email and phone number already exist' );
					$returnValue = json_encode($data);
				} else if ($check_exist_phone) {
					$data = array('status' => "201", "message" => 'phone already exist' );
					$returnValue = json_encode($data);
				} else if ($check_exist_email) {
					$data = array('status' => "201", "message" => 'email already exist' );
					$returnValue = json_encode($data);
				} else {
					if (@$result["checked"] == "true") {
						$data = array('status' => "200", "message" => 'next' );
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
							$data = array('status' => "201", "message" => 'failed' );
							$returnValue = json_encode($data);
						}
					}
				}
				
		} catch(Exception $ex)
		{
			$data = array('status' => "01", "message" => $ex->getMessage() );
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
		$nama		= @$result["nama"];
		$address	= @$result["address"];
        $time		= @$result["time"];
		$token		= @$result["token"];
		
		try
		{
				if (!$time)
					throw new Exception("time null.");
				if (!$token)
					throw new Exception("token null.");
				
				$secret = @$this->db->get_Where('user_api', array('uid'=>$uid))->row()->secret;
				$signatureGenerate	= hash('sha256', $uid . $secret . $time);
				
				if ($signature != $signatureGenerate)
					throw new Exception("Wrong Signature!!!");
				
					if (@$result["checked"] == "true") {
						$data = array('status' => "200", "message" => 'next' );
						$returnValue = json_encode($data);
					} else {
						
						// if ($samsatID != '') {
							// $reg_id = array('id' => $samsatID);
							// $this->db->where($reg_id);
						// }
						
						$param = array(
							'id'		=> @$samsatID,
							'nama'		=> @$nama,
							'address'	=> @$address
						);
						
						$samsat = $this->getsamsat($param);
						if ($samsat != "01") {
							$data = array('status' => "200", "message" => 'success', 'data' => json_decode($samsat)	 );
							$returnValue = json_encode($data);
						} else {
							$data = array('status' => "201", "message" => 'failed', 'data' => []);
							$returnValue = json_encode($data);
						}
							
					}
				
				
		} catch(Exception $ex)
		{
			$data = array('status' => "01", "message" => $ex->getMessage() );
			$returnValue = json_encode($data);
		}
		
		echo $returnValue;
    }
	
	
	public function getsamsat($param = "")
    {
		header('Content-Type: application/json');
		
		if ( sandbox ) {
			$urlsamsat = DashboardSamsat_dev;
		} else {
			$urlsamsat = DashboardSamsat;
		}
		
		$apiLink = $urlsamsat . "/api/samsat?nama=" . @$param['nama'] . "&id=" . @$param['id'] . "&address=" . @$param['address'];
		$response = file_get_contents($apiLink);
		// var_dump($response); 
		if(!empty($response)){
			$result = json_encode($response);
					return json_decode($result);
					 // echo "paymentUrl :". $result['result'] . "<br />";
					 // return $result['result']['access_token'];
		  // echo $response;
		}else{
		  return "01";
		}
		
			
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
				if (!$email)
					throw new Exception("email null.");
				if (!$phone)
					throw new Exception("phone null.");
				
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
							$data = array('status' => "201", "message" => 'failed' );
							$returnValue = json_encode($data);
						}
					
				// $data = array('status' => "201", "message" => 'test' );
				
		} catch(Exception $ex)
		{
			$data = array('status' => "01", "message" => $ex->getMessage() );
			$returnValue = json_encode($data);
		}
		
		echo $returnValue;
    }
	
	public function anterin(){
		// header('Content-Type: application/json');
		header( 'Content-type: text/xml' );

				$params = array(
					'pin'		=> "222222",
					'phone'		=> "+6281224469860"
				);


				$params_string = json_encode($params);
				
				// file_put_contents('log.txt', "*** " . $params_string . " ***\r\n", FILE_APPEND | LOCK_EX);
				
				// $url = 'http://localhost:8080/public_html/api/customer/dataTransaksi';
				$url = 'https://newdriver.anterin.id/api/driver/loginPin';

				$token = $this->oauth2();
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
					// 'Accept: application/json',                                                                      
					// 'Authorization: Bearer ' . $token,     
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
						// $json_pretty = json_encode($result, JSON_PRETTY_PRINT);
						// echo $json_pretty;
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
					// echo $httpCode . " - " . $request;
				}
				
			echo $returnValue;
				
	}
	
	public function kdvoucher(){
		header('Content-Type: application/json');
		
		$data = file_get_contents('php://input');
		$result = json_decode($data, true);
	

		$uid 		= @$result["uid"];
		$signature 	= @$result["signature"];
		
		$kdvoucher	= @$result["kdvoucher"]; //ME35HRQL
		$phone		= @$result["phone"]; //ME35HRQL
		$validasi	= validasiVoucher;
		
		try
		{
				if (!$result)
					throw new Exception("API KEY DATA");
				if (!$uid)
					throw new Exception("uid null.");
				if (!$signature)
					throw new Exception("signature null.");
				if (!$kdvoucher)
					throw new Exception("kdvoucher null.");
				
				$secret = @$this->db->get_Where('user_api', array('uid'=>$uid))->row()->secret;
				$signatureGenerate	= hash('sha256', $uid . $secret . $kdvoucher);
				
				if ($signature != $signatureGenerate)
					throw new Exception("Wrong Signature!!!");
				
				$params = array(
					'kdvoucher'		=> $kdvoucher
				);


				$params_string = json_encode($params);
				
				// file_put_contents('log.txt', "*** " . $params_string . " ***\r\n", FILE_APPEND | LOCK_EX);

				if ( sandbox ) {
					$token = $this->oauth2_dev();
					$url = VoucherDki_dev;
				} else {
					$token = $this->oauth2_prod();
					$url = VoucherDki;
				}

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
					// 'Accept: application/json',                                                                      
					'Authorization: Bearer ' . $token,                                                                     
					// 'X-Originating-IP: 103.60.101.226',
					'Cookie: dkib=!xg5DIn/nyySqAyVj8K6EwA1gVKp2h002j2Esn+gO0mAOy4rhTrkGy5ZZLp++8YKaP4+4zceb9kAC',
					'Content-Type: application/json' 
				]);
				$request = curl_exec($ch);
				$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				 
				curl_close($ch);
						 
				if($httpCode == 200)
				{
					header('Content-Type: application/json');
					
					$result = json_decode($request, true);

					$json_pretty = json_encode($result, JSON_PRETTY_PRINT);

					if (empty(@$result['error'])) {
						
						if ( $result['nohp'] == $phone OR $validasi != true) {
							
							$cekvoucher = $this->Voucher_model->getvoucher($kdvoucher);
							if ($cekvoucher->num_rows() > 0){
								//get dari table kdvoucher
								$JSON = json_decode($cekvoucher->row()->json);
								$data = array('status' => "200", "message" => "Success", 'data' => $JSON );
							}else{
								//insert dan munculin dari bankdki
								$JSON = json_encode($result);
								$addvoucher	= array(
									'kdvoucher'		=> $kdvoucher,
									'json'			=> $JSON,
									'status'		=> 1,
								);
								$this->Voucher_model->addvoucher($addvoucher);
								$data = array('status' => "200", "message" => "Success", 'data' =>  json_decode($JSON) );
							}
							
						} else {
							$data = array('status' => "404", "message" => "Phone do no match" );
						}
						
						$returnValue = json_encode($data);
					} else {
						$data = array('status' => "404", "message" => "FAILED" );
						$returnValue = json_encode($data);
					}
					
				}
				else {
					// echo $httpCode . " - " . $request;
					$result = json_decode($request, true);
					
					if (!empty(@$result['message'])) {
						$data = array('status' => "01", "message" => $result['message'] );
						$returnValue = json_encode($data);
					} else {
						$data = array('status' => "01", "message" => $request );
						$returnValue = json_encode($data);
					}
					
				}
				
				// $data = array('status' => "200", "message" => "Success", 'data' =>  $json );
				// $returnValue = '{
    // "status": "200",
    // "message": "Success",
    // "data": {
        // "kodesah": "200902Z17200902 ",
        // "iic": "111 ",
        // "code": "00",
        // "pnpb": "0000000",
        // "tahunbuat": "2000",
        // "pkbdenda": "000036500",
        // "kdvoucher": "ME35HRQL",
        // "jrdenda": "0035000",
        // "nik": "3175085103560002",
        // "kendke-": "001",
        // "bbnpokok": "00000000000",
        // "pkbpokok": "001824500",
        // "jrpokok": "0143000",
        // "masapajak": "29082021",
        // "cc": "02498",
        // "amount": "000002039000",
        // "merk": "MITSUBISHI",
        // "bbndenda": "00000000000",
        // "tnkb": "0000000",
        // "mt": "6017",
        // "nopol": "0888PO ",
        // "alamat": "JL.ZENI G-109 RT7/6 JAKTIM",
        // "nama": "ETTY TRIMURTI",
        // "jenis": "SEDAN",
        // "nohp": "08211234567890"
    // }
// }';
		} catch(Exception $ex)
		{
			$data = array('status' => "01", "message" => $ex->getMessage() );
			$returnValue = json_encode($data);
		}
		
		
		
		echo $returnValue;
				
	}
		
	public function oauth2_dev()
	{

		header('Content-Type: application/json');
		
		$client_id = client_id_dev;
		$client_secret = client_secret_dev;

		$apiLink = "http://portalapi.bankdki.co.id/io-docs/getoauth2accesstoken?apiId=22593&auth_flow=client_cred&client_id=" . $client_id . "&client_secret=" . $client_secret;
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
	
	
	function oauth2_prod($clientid = client_id, $client_secret = client_secret) {
        $cacheFile = APPPATH . '/cache/' . md5($clientid.$client_secret) . '.json';

        if (file_exists($cacheFile)) {
            $fh = fopen($cacheFile, 'r');
            $cacheTime = trim(fgets($fh));
        if ($cacheTime > strtotime('-' . 10 . ' minutes')) {
                $createCache = false;
                $return = str_replace($cacheTime,'',fread($fh,filesize($cacheFile)));
				
				if ( $return == "<h1>596 Service Not Found</h1>" )
					$createCache = true;
				
                fclose($fh);
            } else {
                $createCache = true;
            }
        } else {
            $createCache = true;
        }

        if($createCache == true){
            //get API
			
			$url = 'https://api.bankdki.co.id/sistem-oauth2/';
			$body = 'grant_type=client_credentials&client_id='.$clientid.'&client_secret='.$client_secret;
						
            $ch = curl_init();
            // $header[] = "Content-Type: application/json";
            // $query = http_build_query($params);
			// $headers = array( 
                 // "Cache-Control: no-cache",
				 // "contentType: application/xml",
				 // "muteHttpExceptions: " . true
				// 'Content-Type: application/json' 				 
                // ); 
			// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			// $headers[] = 'Content-Type: application/xml';
			// $headers[] = 'Content-Type: application/x-www-form-urlencoded';
			$headers[] = 'Accept: */*';
			$headers[] = 'Cookie: dkib=!xg5DIn/nyySqAyVj8K6EwA1gVKp2h002j2Esn+gO0mAOy4rhTrkGy5ZZLp++8YKaP4+4zceb9kAC';

			// curl_setopt( $ch, CURLOPT_COOKIESESSION, true );
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_ENCODING, "gzip");
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
				curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $request = curl_exec($ch);
            $return = ($request === FALSE) ? curl_error($ch) : $request;
            $err = curl_error($ch);
            curl_close($ch);
            //get API EOF

            if ($err) {
              return $err;
              exit;
            } else {
              $fh = fopen($cacheFile, 'w');
              fwrite($fh, time() . "\n");
              fwrite($fh, $return);
              fclose($fh);
            }
        }

		$result = json_decode($return, true);
        return $result['access_token'];
    }
	
	public function getDriver()
	{
			$data = file_get_contents('php://input');
			$result = json_decode($data, true);
		
			header('Content-Type: application/json');

			$uid 		= @$result["uid"];
			$signature 	= @$result["signature"];
			$latitude	= @$result["latitude"];
			$longitude	= @$result["longitude"];
			$fitur		= @$result["fitur"];
			
			try
			{
				if (!$result)
					throw new Exception("API KEY DATA");
				if (!$uid)
					throw new Exception("uid null.");
				if (!$signature)
					throw new Exception("signature null.");
				if (!$latitude)
					throw new Exception("latitude null.");
				if (!$longitude)
					throw new Exception("longitude null.");
				if (!$fitur)
					throw new Exception("fitur null.");

				$secret = @$this->db->get_Where('user_api', array('uid'=>$uid))->row()->secret;
				$signatureGenerate	= hash('sha256', $uid . $secret . $latitude . $longitude);
				
				if ($signature != $signatureGenerate)
					throw new Exception("Wrong Signature!!!");
				
				if ( $latitude AND $longitude ) {		// Cek apakah user telah menginput email dan password
					
							$near = $this->Pelanggan_model->get_driver_ride($latitude, $longitude, $fitur);
							if ($near->num_rows() > 0) {
								$returnValue = json_encode(array('status' => "200", 'message'=>'Success', 'data' => $near->result() ));
							} else {
								$returnValue = json_encode(array('status' => "404", 'message'=> 'Not Found' ));
							}
			
				} else {
					$returnValue = json_encode(array('status' => "01", 'message'=>'FAILED'));
				}
		
			} catch(Exception $ex)
			{
				$data = array('status' => "01", "message" => $ex->getMessage() );
				$returnValue = json_encode($data);
			}
			
			echo $returnValue;
			
	}

	function requestTransaksi()
    {
		$data = file_get_contents('php://input');
		$result = json_decode($data, true);
	
		header('Content-Type: application/json');

		$uid 		= @$result["uid"];
		$signature 	= @$result["signature"];
		$email		= @$result["email"];
		$kdvoucher	= @$result["kdvoucher"];
		
		$id_driver			= @$result["id_driver"];
		$id_pelanggan		= @$result["id_pelanggan"];
		$order_fitur		= @$result["order_fitur"];
		$start_latitude		= @$result["start_latitude"];
		$start_longitude	= @$result["start_longitude"];
		$end_latitude		= @$result["end_latitude"];
		$end_longitude		= @$result["end_longitude"];
		$jarak				= @$result["jarak"];
		$harga				= @$result["harga"];
		$estimasi_time		= @$result["estimasi_time"];
		$alamat_asal		= @$result["alamat_asal"];
		$alamat_tujuan		= @$result["alamat_tujuan"];
		$biaya_akhir		= @$result["biaya_akhir"];
		$kredit_promo		= @$result["kredit_promo"];
		$pakai_wallet		= @$result["pakai_wallet"];
		
		$nama_pengirim		= @$result["nama_pengirim"];
		$telepon_pengirim	= @$result["telepon_pengirim"];
		$nama_penerima		= @$result["nama_penerima"];
		$telepon_penerima	= @$result["telepon_penerima"];
		$nama_barang		= @$result["nama_barang"];
		
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
				if (!$kdvoucher)
					throw new Exception("kdvoucher null.");
				
				if (!$id_pelanggan)
					throw new Exception("id_pelanggan null.");
				if (!$id_driver)
					throw new Exception("id_driver null.");
				// if (!$order_fitur)
					// throw new Exception("order_fitur null.");
				if (!$start_latitude)
					throw new Exception("start_latitude null.");
				if (!$start_longitude)
					throw new Exception("start_longitude null.");
				// if (!$end_latitude)
					// throw new Exception("end_latitude null.");
				// if (!$end_longitude)
					// throw new Exception("end_longitude null.");
				// if (!$jarak)
					// throw new Exception("jarak null.");
				// if (!$harga)
					// throw new Exception("harga null.");
				// if (!$estimasi_time)
					// throw new Exception("estimasi_time null.");
				// if (!$alamat_asal)
					// throw new Exception("alamat_asal null.");
				// if (!$alamat_tujuan)
					// throw new Exception("alamat_tujuan null.");
				// if (!$biaya_akhir)
					// throw new Exception("biaya_akhir null.");
				// if (!$kredit_promo)
					// throw new Exception("kredit_promo null.");
				// if (!$pakai_wallet)
					// throw new Exception("pakai_wallet null.");
				// if (!$nama_pengirim)
					// throw new Exception("nama_pengirim null.");
				// if (!$telepon_pengirim)
					// throw new Exception("telepon_pengirim null.");
				// if (!$nama_penerima)
					// throw new Exception("nama_penerima null.");
				// if (!$telepon_penerima)
					// throw new Exception("telepon_penerima null.");
				// if (!$nama_barang)
					// throw new Exception("nama_barang null.");

				$secret = @$this->db->get_Where('user_api', array('uid'=>$uid))->row()->secret;
				$signatureGenerate	= hash('sha256', $uid . $secret . $start_latitude . $start_longitude);
				
				if ($signature != $signatureGenerate)
					throw new Exception("Wrong Signature!!!");

				
					$cek = $this->Pelanggan_model->check_banned_user($email);
					if ($cek) {
						throw new Exception("Status User Banned.");
					}

				$data_req = array(
					'id_driver' => $id_driver,
					'id_pelanggan' => $id_pelanggan,
					'order_fitur' => $order_fitur,
					'start_latitude' => $start_latitude,
					'start_longitude' => $start_longitude,
					'end_latitude' => $end_latitude,
					'end_longitude' => $end_longitude,
					'jarak' => $jarak,
					'harga' => $harga,
					'estimasi_time' => $estimasi_time,
					'waktu_order' => date('Y-m-d H:i:s'),
					'alamat_asal' => $alamat_asal,
					'alamat_tujuan' => $alamat_tujuan,
					'biaya_akhir' => $harga,
					'kredit_promo' => $kredit_promo,
					'pakai_wallet' => $pakai_wallet
				);
				
				$dataDetail = array(
					'kdvoucher' => $kdvoucher,
					'nama_pengirim' => $nama_pengirim,
					'telepon_pengirim' => $telepon_pengirim,
					'nama_penerima' => $nama_penerima,
					'telepon_penerima' => $telepon_penerima,
					'nama_barang' => $nama_barang
				);

				$request = $this->Pelanggan_model->insert_transaksi_send($data_req, $dataDetail);
				if ($request['status']) {
					
					foreach($request['data']->result() as $trans)
					{	
						$qrstring = base64_encode($kdvoucher.'.'.$trans->id.'.'.$trans->id_pelanggan.'.'.$trans->id_driver);
						$transaksi['qrstring'] = $qrstring;
						$this->db->where('id', $trans->id);
						$this->db->update('transaksi', $transaksi);
						
						$datatransaksi[] = array(
							'id'			=> $trans->id,
							'id_pelanggan'	=> $trans->id_pelanggan,
							'id_driver'		=> $trans->id_driver,
							'qrstring'		=> ($qrstring),
						);
					}
					
					// $row[] = array(
							// 'token'					=> "test",
						// );
					// $datatransaksi=array_push($datatransaksi,$row);

					$data = array('status' => "200", "message" => 'Success', 'data' => $datatransaksi );
					$returnValue = json_encode($data);
				} else {
					$data = array('status' => "01", "message" => 'FAILED' );
					$returnValue = json_encode($data);
				}
				
				
		
			} catch(Exception $ex)
			{
				$data = array('status' => "01", "message" => $ex->getMessage() );
				$returnValue = json_encode($data);
			}
			
			echo $returnValue;
			
    }
	
	public function updateTransaksi()
    {
		$data = file_get_contents('php://input');
		$result = json_decode($data, true);
	
		header('Content-Type: application/json');

		$uid			= @$result["uid"];
		$signature		= @$result["signature"];
		
		$id_transaksi	= @$result["id_transaksi"];
		$token			= @$result["token"];
		
		$id_driver		= @$result["id_driver"];
		$status			= @$result["status"];
		
		try
		{
				if (!$token)
					throw new Exception("token null.");
				if (!$id_driver)
					throw new Exception("id_driver null.");
				if (!$id_transaksi)
					throw new Exception("id_transaksi null.");
				if (!$status)
					throw new Exception("status null.");
				
				$secret = @$this->db->get_Where('user_api', array('uid'=>$uid))->row()->secret;
				$signatureGenerate	= hash('sha256', $uid . $secret . $id_transaksi . $id_driver);
				
				if ($signature != $signatureGenerate)
					throw new Exception("Wrong Signature!!!");

						if ( $id_driver )
							$data_req['id_driver'] = $id_driver;
						if ( $id_transaksi )
							$data_req['id_transaksi'] = $id_transaksi;
						if ( $status )
							$data_req['status'] = $status;
						
						// $this->db->where('id_transaksi' => $id_transaksi);
						// $edit = $this->db->update('transaksi', $data);
						// $data_req = array(
							// 'id_driver' => $dec_data->id,
							// 'id_transaksi' => $dec_data->id_transaksi
						// );

						$condition = array(
							'id_driver' => $id_driver,
							// 'status' => $status
						);

						$cek_login = $this->Driver_model->get_status_driver($condition);
						if ($cek_login->num_rows() > 0) {

							$acc_req = $this->Driver_model->accept_request($data_req);
							if ($acc_req['status']) {
								$data = array('status' => "200", "message" => "Success" );
								$returnValue = json_encode($data);
								
								// $message = array(
									// 'message' => 'berhasil',
									// 'data' => 'berhasil'
								// );
								// $this->response($message, 200);
							} else {
									$data = array('status' => "201", "message" => "Status Already" );
									$returnValue = json_encode($data);
							}
						} else {
							$data = array('status' => "404", "message" => "Driver Not Found" );
							$returnValue = json_encode($data);
							// $message = array(
								// 'message' => 'unknown fail',
								// 'data' => 'canceled'
							// );
							// $this->response($message, 200);
						}
						
				
				
		} catch(Exception $ex)
		{
			$data = array('status' => "01", "message" => $ex->getMessage() );
			$returnValue = json_encode($data);
		}
		
		echo $returnValue;
    }

	public function checkDriver()
    {
		$condition = array(
            'driver.status' => 1
        );
        $data = $this->Driver_model->get_data_driver($condition)->result();
		// $request = $this->Pelanggan_model->get_data_driver_sync();
		// var_dump($data);
		
		foreach($data as $driver)
		{	
			$result[] = array(
				'id'					=> $driver->id,
				// 'roken'					=> $driver->reg_id,
				// 'customer_id'			=> $data->customer_id,
			);
		}
		
		$row[] = array(
				'id'					=> "test",
				// 'roken'					=> $driver->reg_id,
				// 'customer_id'			=> $data->customer_id,
			);
		$result=array_merge($result,$row);
		echo json_encode($result);
		
    }
	
	
	public function QRstring()
	{
			$data = file_get_contents('php://input');
			$result = json_decode($data, true);
		
			header('Content-Type: application/json');

			$uid 		= @$result["uid"];
			$signature 	= @$result["signature"];
			$kdvoucher	= @$result["kdvoucher"];
			
			try
			{
				if (!$result)
					throw new Exception("API KEY DATA");
				if (!$uid)
					throw new Exception("uid null.");
				if (!$kdvoucher)
					throw new Exception("kdvoucher null.");
				
				// $reg_id = array(
					// 'token' => $token
				// );
		
				$secret = @$this->db->get_Where('user_api', array('uid'=>$uid))->row()->secret;
				$signatureGenerate	= hash('sha256', $uid . $secret . $kdvoucher);
				
				if ($signature != $signatureGenerate)
					throw new Exception("Wrong Signature!!!");
				
			
				$check_voucher = $this->Pelanggan_model->getKdVoucher($kdvoucher);
				// $check_voucher = @$this->db->get_where('kdvoucher', array('kdvoucher' => $kdvoucher));
				if ($check_voucher->num_rows() > 0) {
					$returnValue = json_encode(array('status' => "200", 'message'=>'Success', 'data'=> $check_voucher->result() ));
				} else {
					$returnValue = json_encode(array('status' => "404", 'message'=> 'kdvoucher not found' ));
				}

		
			} catch(Exception $ex)
			{
				$data = array('status' => "01", "message" => $ex->getMessage() );
				$returnValue = json_encode($data);
			}
			
			echo $returnValue;
			
	}
	
	
	public function transaksi_log()
	{
			$data = file_get_contents('php://input');
			$result = json_decode($data, true);
		
			header('Content-Type: application/json');

			$uid			= @$result["uid"];
			$signature		= @$result["signature"];
			$id_transaksi	= @$result["id_transaksi"];
			
			try
			{
				if (!$result)
					throw new Exception("API KEY DATA");
				if (!$uid)
					throw new Exception("uid null.");
				if (!$signature)
					throw new Exception("signature null.");
				if (!$id_transaksi)
					throw new Exception("id_transaksi null.");
				
				$secret = @$this->db->get_Where('user_api', array('uid'=>$uid))->row()->secret;
				$signatureGenerate	= hash('sha256', $uid . $secret . $id_transaksi);
				
				if ($signature != $signatureGenerate)
					throw new Exception("Wrong Signature!!!");
				
					$this->db->order_by('id', 'DESC');
					$trans_log = $this->db->get_where('transaksi_log', array('id_transaksi' => $id_transaksi));
					if ($trans_log->num_rows() > 0) {
						$returnValue = json_encode(array('status' => "200", 'message'=>'Success', 'data' => $trans_log->result() ));
					} else {
						$returnValue = json_encode(array('status' => "404", 'message'=> 'Transaction not found' ));
					}
							
			} catch(Exception $ex)
			{
				$data = array('status' => "01", "message" => $ex->getMessage() );
				$returnValue = json_encode($data);
			}
			
			echo $returnValue;
			
	}
	
	  public function check_transaksi()
  	{
    			  $data = file_get_contents('php://input');
    			  $result = json_decode($data, true);

     			  header('Content-Type: application/json');

    			  $uid			= @$result["uid"];
    			  $signature		= @$result["signature"];
    			  $id_transaksi	        = @$result["id_transaksi"];

    			  try
    		          {
      			  	if (!$result)
        				throw new Exception("API KEY DATA");
      				if (!$uid)
        				throw new Exception("uid null.");
      				if (!$signature)
        				throw new Exception("signature null.");
      				if (!$id_transaksi)
        				throw new Exception("id_transaksi null.");

      				$secret = @$this->db->get_Where('user_api', array('uid'=>$uid))->row()->secret;
      				$signatureGenerate	= hash('sha256', $uid . $secret . $id_transaksi);

      				if ($signature != $signatureGenerate)
        				throw new Exception("Wrong Signature!!!");

        				$dataTrans = array(
            					'id_transaksi' => $id_transaksi
        				);

        			$trans_hs = $this->Pelanggan_model->check_status($dataTrans);;

        			if ($trans_hs != null) {
          				$returnValue = json_encode($trans_hs);
        			} else {
          				$returnValue = json_encode(array('status' => "404", 'message'=> 'Transaction not found' ));
        			}

    			} catch(Exception $ex)
    			{
      				$data = array('status' => "01", "message" => $ex->getMessage() );
      				$returnValue = json_encode($data);
    			}

    			echo $returnValue;
  	}
}
