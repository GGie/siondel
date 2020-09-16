<?php defined('BASEPATH') or exit('No direct script access allowed');



class Driver extends CI_Controller
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
		define('AES_256_CBC', 'aes-256-cbc');
    }
	
	
	public function loginPin(){
				header('Content-Type: application/json');
				// header( 'Content-type: text/xml' );

				$data = file_get_contents('php://input');
				$result = json_decode($data, true);
			
				$pin		= @$result["pin"];
				$phone		= @$result["phone"];
			
				$params = array(
					'pin'		=> $pin,
					'phone'		=> $phone
				);


				$params_string = json_encode($params);
				
				// file_put_contents('log.txt', "*** " . $params_string . " ***\r\n", FILE_APPEND | LOCK_EX);
				
				// $url = 'http://localhost:8080/public_html/api/customer/dataTransaksi';
				$url = 'https://newdriver.anterin.id/api/driver/loginPin';

				// $token = $this->oauth2();
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
	
	
	public function QR(){
		echo $this->encrypt('duitku'); 
		echo "<br>";
		echo $this->decrypt('8TKLQKDYiglYi3BALRZzHg==');      
					
	}
	
	
	function encrypt($plaintext, $password = "ondel", $encoding = null) {
		$iv = openssl_random_pseudo_bytes(16);
		$ciphertext = openssl_encrypt($plaintext, "AES-256-CBC", hash('sha256', $password, true), OPENSSL_RAW_DATA, $iv);
		$hmac = hash_hmac('sha256', $ciphertext.$iv, hash('sha256', $password, true), true);
		return $encoding == "hex" ? bin2hex($iv.$hmac.$ciphertext) : ($encoding == "base64" ? base64_encode($iv.$hmac.$ciphertext) : $iv.$hmac.$ciphertext);
	}

	function decrypt($ciphertext, $password = "ondel", $encoding = null) {
		$ciphertext = $encoding == "hex" ? hex2bin($ciphertext) : ($encoding == "base64" ? base64_decode($ciphertext) : $ciphertext);
		if (!hash_equals(hash_hmac('sha256', substr($ciphertext, 48).substr($ciphertext, 0, 16), hash('sha256', $password, true), true), substr($ciphertext, 16, 32))) return null;
		return openssl_decrypt(substr($ciphertext, 48), "AES-256-CBC", hash('sha256', $password, true), OPENSSL_RAW_DATA, substr($ciphertext, 0, 16));
	}
	
}
