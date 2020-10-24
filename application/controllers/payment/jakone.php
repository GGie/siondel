<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jakone extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ci_ext_model', 'ci_ext');
        $ci_ext = $this->ci_ext->ciext();
        if (!$ci_ext) {
            redirect(gagal);
        }
		
		$this->load->model('Jakone_model');
		$this->load->model('Voucher_model');
		
    }

	public function test(){
		echo $this->Voucher_model->generateVoucher();
	}
	
	public function enc($string, $action = "encrypt"){
		$ciphertext_b64 = "";
		$plaintext = mb_convert_encoding($string, "UTF-8");
		$password = mb_convert_encoding("173E07B43874E39883A978C810E63FBF403858B87CB8A32C3A3E665F03C788ED", "UTF-8");
		$salt = hex2bin("3FF2EC019C627B945225DEBAD71A01B6985FE84C95A70EB132882F88C0A59A55");

		$iv = hex2bin("F27D5C9927726BCEFE7510B1BDD3D137");
		$iterations = 10000;
		$keyLength = 128;

		$prepared_key = openssl_pbkdf2($password, $salt, $keyLength, $iterations, "sha1");

		if ($action == 'encrypt') {
			$ciphertext_b64 = base64_encode(openssl_encrypt($plaintext,"AES-128-CBC", $prepared_key,OPENSSL_RAW_DATA, $iv));
			$output = $ciphertext_b64;
		} else if ($action == 'decrypt') {
			$plaintext = openssl_decrypt(base64_decode($ciphertext_b64),"AES-128-CBC", $prepared_key,OPENSSL_RAW_DATA, $iv);
			$output = $plaintext;
		}
		
		return $output;
	}

	public function index()
    {
		$string = "pw/hyLkFlQhdyq3DRvFdWw==";
		// $key 	= "173E07B43874E39883A978C810E63FBF403858B87CB8A32C3A3E665F03C788ED";
		$encrypted_string = $this->enc($string, "decrypt");
		echo $encrypted_string;
    }
    
	public function check(){

		header('Content-Type: application/json');

				$params = array(
					'username'		=> 'anggi2902870003564',
					'password'		=> "pw/hyLkFlQhdyq3DRvFdWw==",
					'enumChannel'	=> "ANTERIN",
				);
				
				$body = array(
					'body'		=> $params
				);

				$params_string = json_encode($body);

				$paramJSON['params_string'] = $params_string;
				$paramJSON['url'] = '/akun';
				$response = $this->HTTP_POST_REQUEST($paramJSON);
						 
				if($response['httpCode'] == "200")
				{
					header('Content-Type: application/json');
					
					$data = json_decode($response['request'], true);
					
					if ( empty(@$data['message']) ) {
						$result['status'] = "200";
						$result['message'] = "Success";
						$result=array_merge($result,array('data'=>$data['body']));
						$returnValue =  json_encode($result);
					} else {
						$data = array('status' => "201", "message" => $data['message'] );
						$returnValue = json_encode($data); 
					}
				}
				else {
					$data = array('status' => $httpCode, "message" => $request );
					$returnValue = json_encode($data); 
				}
				
			echo $returnValue;
				
	}
	
	public function payment(){
		//transaction
		header('Content-Type: application/json');
		$dataParam = file_get_contents('php://input');
		$result = json_decode($dataParam, true);
			
			$iduser		= @$result["iduser"];
			$password	= @$result["password"];
			$uid 		= @$result["uid"];
			$signature 	= @$result["signature"];
			
			try
			{
				if (!$result)
					throw new Exception("API KEY DATA");
				if (!$iduser)
					throw new Exception("iduser null.");
				if (!$password)
					throw new Exception("password null.");
				if (!$uid)
					throw new Exception("uid null.");
				if (!$signature)
					throw new Exception("signature null.");
				
				$secret = @$this->db->get_Where('user_api', array('uid'=>$uid))->row()->secret;
				$signatureGenerate	= hash('sha256', $uid . $secret . $iduser);
				
				if ($signature != $signatureGenerate)
					throw new Exception("Wrong Signature!!!");
				
				$saldo = @$this->db->get_where('saldo', array('id_user' => $iduser) );
				
				if ($saldo->num_rows() <= 0) {
					throw new Exception("saldo user Not Register Ondel.");
				}
				
				$json 	= json_decode($saldo->row()->jakone_json, true);
				$voucher = $this->Voucher_model->generateVoucher();
				$params = array(
					'username'			=> $json['username'], //'anggi2506824003624',
					'password'			=> $this->enc($password), //"pw/hyLkFlQhdyq3DRvFdWw==",
					'acctFromNumber'	=> $saldo->row()->jakone_phone, //"085156447932",
					'payRef'			=> "VOUCHER ONDEL : " . $voucher,
					'amount'			=> "40000",
					'channel'			=> "ONDEL",
					'enumChannel'		=> "ANTERIN",
				);
				
				$body = array(
					'body'		=> $params
				);

				$params_string = json_encode($body);

				$paramJSON['params_string'] = $params_string;
				$paramJSON['url'] = '/ochannel';
				$response = $this->HTTP_POST_REQUEST($paramJSON);
				
				
				if($response['httpCode'] == "200")
				{
					
					header('Content-Type: application/json');
					
					$data = json_decode($response['request'], true);
					
					if ( !empty(@$data['status']['code']) AND $data['status']['code'] == "200" AND @$data['body']['ConfirmTransactionOtherChannelResponse']['responseCode']['_text'] == "00" ) {
						$return['status'] = "200";
						$return['message'] = "Success";
						
						$dataResult = array(
							'reference' => $data['body']['ConfirmTransactionOtherChannelResponse']['noRefferenceTrx']['_text'],
							'transaction' => $data['body']['ConfirmTransactionOtherChannelResponse']['transaction']['acctFromNumber']['_text'],
							'amount' => $data['body']['ConfirmTransactionOtherChannelResponse']['transaction']['amount']['_text'],
							'description' => $data['body']['ConfirmTransactionOtherChannelResponse']['transaction']['payRef']['_text'],
						);
						
						// $saldo['id']			= $pelanggan->row()->id;
						// $saldo['jakone']		= $dataResult['availableBalance'];
						// $saldo['jakone_phone']	= $dataResult['msisdn'];
						// $saldo['jakone_active']	= 1;
						// $saldo['jakone_json']	= json_encode($dataResult);
						// $this->Jakone_model->update_saldo($pelanggan->row()->id, $saldo);
								$addvoucher	= array(
									'kdvoucher'		=> $voucher,
									'json'			=> "",
									'channel'		=> "JAKONE",
									'status'		=> 1,
								);
								$this->Voucher_model->addvoucher($addvoucher);
								
						$return=array_merge($return,array('data'=>$dataResult));
						$returnValue =  json_encode($return);
						
					} else if ( !empty(@$data['body']['Fault']['faultstring']['_text']) ) {
						$data = array('status' => "404", "message" => $data['body']['Fault']['faultstring']['_text'] );
						$returnValue = json_encode($data);
					} else if ( !empty(@$data['body']['ConfirmTransactionOtherChannelResponse']['responseDesc']) ) {
						$data = array('status' => "201", "message" => @$data['body']['ConfirmTransactionOtherChannelResponse']['responseDesc']['_text'] );
						$returnValue = json_encode($data);
					} else {
						$data = array('status' => "201", "message" => "Error" );
						$returnValue = json_encode($data); 
					}
				}
				else {
					$data = array('status' => $response['httpCode'], "message" => $request );
					$returnValue = json_encode($data); 
				}
			
			} catch(Exception $ex)
			{
				$data = array('status' => "01", "message" => $ex->getMessage() );
				$returnValue = json_encode($data);
			}
			
			echo $returnValue;
				
	}
	
	public function infochannel(){
		header('Content-Type: application/json');
		$dataParam = file_get_contents('php://input');
		$result = json_decode($dataParam, true);
			
			$phone = @$result["phone"]; //'0887912193';
			$uid 		= @$result["uid"];
			$signature 	= @$result["signature"];
			
			try
			{
				if (!$result)
					throw new Exception("API KEY DATA");
				if (!$phone)
					throw new Exception("phone null.");
				
				$secret = @$this->db->get_Where('user_api', array('uid'=>$uid))->row()->secret;
				$signatureGenerate	= hash('sha256', $uid . $secret . $phone);
				
				if ($signature != $signatureGenerate)
					throw new Exception("Wrong Signature!!!");
				
				$pelanggan = @$this->db->get_where('pelanggan', array('phone' => $phone) );
				
				if ($pelanggan->num_rows() <= 0) {
					throw new Exception("Phone Not Register Ondel.");
				}
				
				$params = array(
					'msisdn'		=> $phone,
					'enumChannel'	=> "ANTERIN",
				);
				
				$body = array(
					'body'		=> $params
				);

				$params_string = json_encode($body);

				$paramJSON['params_string'] = $params_string;
				$paramJSON['url'] = '/infochannel';
				$response = $this->HTTP_POST_REQUEST($paramJSON);
				
				
				if($response['httpCode'] == "200")
				{
					
					header('Content-Type: application/json');
					
					$data = json_decode($response['request'], true);
					
					if ( !empty(@$data['status']['code']) AND $data['status']['code'] == "200" AND empty(@$data['body']['Fault']['faultstring']['_text']) AND !empty(@$data['body']['GetAccountInformationOtherChannelResponse']['AccountInformation']) ) {
						$return['status'] = "200";
						$return['message'] = "Success";
						
						$dataResult = array(
							'account' => $data['body']['GetAccountInformationOtherChannelResponse']['AccountInformation']['accountNumber']['_text'],
							'availableBalance' => $data['body']['GetAccountInformationOtherChannelResponse']['AccountInformation']['availableBalance']['_text'],
							'id' => $data['body']['GetAccountInformationOtherChannelResponse']['id']['_text'],
							
							'username' => $data['body']['GetAccountInformationOtherChannelResponse']['username']['_text'],
							'firstName' => $data['body']['GetAccountInformationOtherChannelResponse']['firstName']['_text'],
							'lastName' => $data['body']['GetAccountInformationOtherChannelResponse']['lastName']['_text'],
							'placeOfBirth' => $data['body']['GetAccountInformationOtherChannelResponse']['placeOfBirth']['_text'],
							'dateOfBirth' => $data['body']['GetAccountInformationOtherChannelResponse']['dateOfBirth']['_text'],
							'msisdn' => $data['body']['GetAccountInformationOtherChannelResponse']['msisdn']['_text'],
							'email' => $data['body']['GetAccountInformationOtherChannelResponse']['email']['_text'],
						);
						
						// $saldo['id']			= $pelanggan->row()->id;
						$saldo['jakone']		= $dataResult['availableBalance'];
						$saldo['jakone_phone']	= $dataResult['msisdn'];
						$saldo['jakone_active']	= 1;
						$saldo['jakone_json']	= json_encode($dataResult);
						$this->Jakone_model->update_saldo($pelanggan->row()->id, $saldo);
						
						$return=array_merge($return,array('data'=>$dataResult));
						$returnValue =  json_encode($return);
						
					} else if ( !empty(@$data['body']['Fault']['faultstring']['_text']) ) {
						$data = array('status' => "404", "message" => $data['body']['Fault']['faultstring']['_text'] );
						$returnValue = json_encode($data);
					} else if ( !empty(@$data['body']['GetAccountInformationOtherChannelResponse']['responseDesc']) ) {
						$data = array('status' => "201", "message" => @$data['body']['GetAccountInformationOtherChannelResponse']['responseDesc']['_text'] );
						$returnValue = json_encode($data);
					} else {
						$data = array('status' => "201", "message" => "Error" );
						$returnValue = json_encode($data); 
					}
				}
				else {
					$data = array('status' => $httpCode, "message" => $request );
					$returnValue = json_encode($data); 
				}
			
			} catch(Exception $ex)
			{
				$data = array('status' => "01", "message" => $ex->getMessage() );
				$returnValue = json_encode($data);
			}
			
			echo $returnValue;
				
	}
	
	public function transaksilist(){
		header('Content-Type: application/json');
		$dataParam = file_get_contents('php://input');
		$result = json_decode($dataParam, true);
			
			$iduser		= @$result["iduser"];
			$password	= @$result["password"];
			$datefrom	= @$result["datefrom"];
			$dateto		= @$result["dateto"];
			$uid 		= @$result["uid"];
			$signature 	= @$result["signature"];
			
			try
			{
				if (!$result)
					throw new Exception("API KEY DATA");
				if (!$iduser)
					throw new Exception("iduser null.");
				if (!$password)
					throw new Exception("password null.");
				if (!$datefrom)
					throw new Exception("dateFrom null.");
				if (!$dateto)
					throw new Exception("dateTo null.");
				
				$secret = @$this->db->get_Where('user_api', array('uid'=>$uid))->row()->secret;
				$signatureGenerate	= hash('sha256', $uid . $secret . $iduser);
				
				if ($signature != $signatureGenerate)
					throw new Exception("Wrong Signature!!!");
				
				$pelanggan = @$this->db->get_where('pelanggan', array('id' => $iduser) );
				
				if ($pelanggan->num_rows() <= 0) {
					throw new Exception("iduser Not Register Ondel.");
				}
				
				$saldo = @$this->db->get_where('saldo', array('id_user' => $iduser) );
				
				if ($saldo->num_rows() <= 0) {
					throw new Exception("saldo user Not Register Ondel.");
				}
				
				$json = json_decode($saldo->row()->jakone_json, true);
				
				$params = array(
					'username'		=> $json['username'],
					'password'		=> $this->enc($password),
					'account'		=> $json['msisdn'],
					'dateFrom'		=> $datefrom,
					'dateTo'		=> $dateto,
					'enumChannel'	=> "ANTERIN",
				);
				
				
				$body = array(
					'body'		=> $params
				);

				$params_string = json_encode($body);

				$paramJSON['params_string'] = $params_string;
				$paramJSON['url'] = '/transaksi';
				$response = $this->HTTP_POST_REQUEST($paramJSON);
				
				
				if($response['httpCode'] == "200")
				{
					
					// header('Content-Type: application/json');
					
					$data = json_decode($response['request'], true);
					
					if ( !empty(@$data['status']['code']) AND $data['status']['code'] == "200" AND !empty(@$data['body']['TrxHistoryEnquiryResponse']['trxHistory'] )) {
						$return['status'] = "200";
						$return['message'] = "Success";
						
						$trans = $data['body']['TrxHistoryEnquiryResponse']['trxHistory'];

							if ( count($trans) > 0 ) {
								foreach($trans as $tr)
								{	
									$row[] = array(
										'reference'			=> @$tr['noRefferenceTrx']['_text'],
										'description'		=> @$tr['description']['_text'],
										'debit'				=> !empty(@$tr['debit']['_text']) ? @$tr['debit']['_text'] : 0,
										'credit'			=> !empty(@$tr['credit']['_text']) ? @$tr['credit']['_text'] : 0,
										'transactionDate'	=> date("Y-m-d H:i:s", strtotime(@$tr['transactionDate']['_text'])),
									);
								}
							} else {
								$row[] = array();
							}
							
						$return=array_merge($return,array('data'=>$row));
						// return json_encode($result);
						
						// $return=array_merge($return,array('data'=>$data['body']['TrxHistoryEnquiryResponse']['trxHistory']));
						$returnValue =  json_encode($return);
						
					} else if ( empty(@$data['body']['TrxHistoryEnquiryResponse']['trxHistory'] ) ) {
						$data = array('status' => "404", "message" => "Transaction Not Found");
						$returnValue = json_encode($data);
					} else {
						$data = array('status' => "201", "message" => "Error" );
						$returnValue = json_encode($data); 
					}
				}
				else {
					$data = array('status' => $httpCode, "message" => $request );
					$returnValue = json_encode($data); 
				}
			
			} catch(Exception $ex)
			{
				$data = array('status' => "01", "message" => $ex->getMessage() );
				$returnValue = json_encode($data);
			}
			
			// $trans = $data['body']['TrxHistoryEnquiryResponse']['trxHistory'];
			
			// foreach($trans as $tr){
				// $param['desc'][0] = @$tr['description']['_text'];
				// $param['debit'][0] = @$tr['debit']['_text'];
				// $param['credit'][0] = @$tr['credit']['_text'];
			// }
			
			// if ( count($trans) > 0 ) {
				// foreach($trans as $tr)
				// {	
					// $row[] = array(
						// 'reference'			=> @$tr['noRefferenceTrx']['_text'],
						// 'description'		=> @$tr['description']['_text'],
						// 'debit'				=> !empty(@$tr['debit']['_text']) ? @$tr['debit']['_text'] : 0,
						// 'credit'			=> !empty(@$tr['credit']['_text']) ? @$tr['credit']['_text'] : 0,
						// 'transactionDate'	=> date("Y-m-d H:i:s", strtotime(@$tr['transactionDate']['_text'])),
					// );
				// }
			// } else {
				// $row[] = array();
			// }
		// $result=array_merge($result,array('rows'=>$row));
		// return json_encode($result);
		
			// $data = array('status' => 200, "message" => $row );
					// $returnValue = json_encode($data);
			echo $returnValue;
				
	}
	
    public function register(){
		header('Content-Type: application/json');
		$dataParam = file_get_contents('php://input');
		$result = json_decode($dataParam, true);
			
			$firstName		= @$result["firstName"];
			$lastName		= @$result["lastName"];
			$placeOfBirth	= @$result["placeOfBirth"];
			$dateOfBirth	= @$result["dateOfBirth"];
			$password		= @$result["password"];
			$msisdn			= @$result["msisdn"];
			$email			= @$result["email"];
			$uid 		= @$result["uid"];
			$signature 	= @$result["signature"];
			
			try
			{
				if (!$result)
					throw new Exception("API KEY DATA");
				if (!$firstName)
					throw new Exception("firstName null.");
				if (!$lastName)
					throw new Exception("lastName null.");
				if (!$placeOfBirth)
					throw new Exception("placeOfBirth null.");
				if (!$dateOfBirth)
					throw new Exception("dateOfBirth null.");
				if (!$password)
					throw new Exception("password null.");
				if (!$email)
					throw new Exception("email null.");
				
				$secret = @$this->db->get_Where('user_api', array('uid'=>$uid))->row()->secret;
				$signatureGenerate	= hash('sha256', $uid . $secret . $msisdn);
				
				if ($signature != $signatureGenerate)
					throw new Exception("Wrong Signature!!!");
				
				$pelanggan = @$this->db->get_where('pelanggan', array('phone' => $msisdn) );
				
				if ($pelanggan->num_rows() <= 0) {
					throw new Exception("Phone Not Register Ondel.");
				}
				
				// if ($pelanggan->num_rows() <= 0) {
					// throw new Exception("Phone Not Register Ondel.");
				// }

				$params = array(
					'firstName'		=> $result["firstName"],
					'lastName'		=> $result["lastName"],
					'placeOfBirth'	=> $result["placeOfBirth"],
					'dateOfBirth'	=> $result["dateOfBirth"],
					'password'		=> $this->enc($password), //$this->enc("123456", "encrypt"), //$this->enc("123456"), //"pw/hyLkFlQhdyq3DRvFdWw==",
					'msisdn'		=> $result["msisdn"],
					'email'			=> $result["email"],
					'productName'	=> "ANTERIN"
				);
				
				$body = array(
					'body'		=> $params
				);

				$params_string = json_encode($body);
				
				$paramJSON['params_string'] = $params_string;
				$paramJSON['url'] = '/register';
				$response = $this->HTTP_POST_REQUEST($paramJSON);
				
				if($response['httpCode'] == "200")
				{
					header('Content-Type: application/json');
					
					$data = json_decode($response['request'], true);
					
					if ( !empty(@$data['status']['code']) AND $data['status']['code'] == "200" AND !empty(@$data['body']['RegisterNonCustomerJomiResponse']['customer'] )) {
						$return['status'] = "200";
						$return['message'] = "Success";
						
						$dataResult = array(
							'id' => $data['body']['RegisterNonCustomerJomiResponse']['customer']['id']['_text'],
							
							'username' => $data['body']['RegisterNonCustomerJomiResponse']['customer']['name']['_text'],
							'firstName' => $data['body']['RegisterNonCustomerJomiResponse']['customer']['username']['_text'],
							'msisdn' => $data['body']['RegisterNonCustomerJomiResponse']['customer']['msisdn']['_text'],
							'email' => $data['body']['RegisterNonCustomerJomiResponse']['customer']['email']['_text'],
						);
						
						
						$saldo['jakone_phone']		= $dataResult['msisdn'];
						$saldo['jakone_password']	= $password;
						$saldo['jakone_active']		= 1;
						$saldo['jakone_json']		= json_encode($dataResult);
						$this->Jakone_model->update_saldo($pelanggan->row()->id, $saldo);
						
						$return=array_merge($return,array('data' => $dataResult));
						$returnValue =  json_encode($return);
					} else if ( !empty(@$data['body']['Fault']['faultstring']['_text']) ) {
						$data = array('status' => "404", "message" => $data['body']['Fault']['faultstring']['_text'] );
						$returnValue = json_encode($data);
					} else {
						$data = array('status' => "201", "message" => "Error" );
						$returnValue = json_encode($data); 
					}
				}
				else {
					$data = array('status' => $httpCode, "message" => $request );
					$returnValue = json_encode($data); 
				}
				
			} catch(Exception $ex)
			{
				$data = array('status' => "01", "message" => $ex->getMessage() );
				$returnValue = json_encode($data);
			}
			
			echo $returnValue;
				
	}
	
	public function oauth2_devTEST()
	{

		header('Content-Type: application/json');
		
		$client_id = client_id_dev;
		$client_secret = client_secret_dev;

		$apiLink = "http://dev.bankdki.co.id/io-docs/getoauth2accesstoken?apiId=22593&auth_flow=client_cred&client_id=" . $client_id . "&client_secret=" . $client_secret;
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
	
	public function HTTP_POST_REQUEST( $param ){
				if ( sandbox ) {
					$urlEnv = JakOne_dev;
					$token = $this->oauth2_dev();
				} else {
					$urlEnv = JakOne;
					$token = $this->oauth2_prod();
				}
				
				$url = $urlEnv . $param['url'];

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($ch, CURLOPT_POSTFIELDS, $param['params_string']);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_HTTPHEADER, [   
					'Authorization: Bearer ' . $token,    
					'Cookie: dkib=!xg5DIn/nyySqAyVj8K6EwA1gVKp2h002j2Esn+gO0mAOy4rhTrkGy5ZZLp++8YKaP4+4zceb9kAC',
					'Content-Type: application/json' 
				]);
				$request = curl_exec($ch);
				$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				 
				curl_close($ch);

				$data = array("httpCode" => $httpCode, "request" => $request );
				
				file_put_contents('jakoone.txt', "*** request : " . $param['params_string'] . " ***\r\n", FILE_APPEND | LOCK_EX);
				file_put_contents('jakoone.txt', "*** response : " . $httpCode . " " . ($data) . " ***\r\n\r\n", FILE_APPEND | LOCK_EX);
				return $data;
	}
	
	function oauth2_dev($clientid = client_id_dev, $client_secret = client_secret_dev) {
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
			
			$url = 'https://dev.bankdki.co.id/oauth2';
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

    
}
