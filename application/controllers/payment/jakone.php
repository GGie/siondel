<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jakone extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ci_ext_model', 'ci_ext');
        $ci_ext = $this->ci_ext->ciext();
        if (!$ci_ext) {
            redirect(gagal);
        }

    }

    public function index()
    {
       
    }
    
	public function check(){
		header('Content-Type: application/json');

				$params = array(
					'username'		=> 'budij2902118003529',
					'password'		=> "pw/hyLkFlQhdyq3DRvFdWw==",
					'enumChannel '	=> "ANCOL"
				);
				
				$body = array(
					'body'		=> $params
				);

				$params_string = json_encode($body);

				if ( sandbox ) {
					$urlEnv = JakOne_dev;
					$token = $this->oauth2_dev();
				} else {
					$urlEnv = JakOne;
					$token = $this->oauth2_prod();
				}
				
				$url = $urlEnv . '/akun';

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
					'Authorization: Bearer ' . $token,    
					'Cookie: dkib=!xg5DIn/nyySqAyVj8K6EwA1gVKp2h002j2Esn+gO0mAOy4rhTrkGy5ZZLp++8YKaP4+4zceb9kAC',
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
				}
				else {
					$data = array('status' => $httpCode, "message" => $request );
					$returnValue = json_encode($data); 
				}
				
			echo $returnValue;
				
	}
	
	
    public function register(){
		header('Content-Type: application/json');

				$params = array(
					'firstName'		=> 'Budi',
					'lastName'		=> "Januari3",
					'placeOfBirth'	=> "Jakarta",
					'dateOfBirth'	=> "1996-02-29",
					'password'		=> "pw/hyLkFlQhdyq3DRvFdWw==",
					'msisdn'		=> "0887912192",
					'email'			=> "anggi+1@gmail.com",
					'productName'	=> "ONDEL"
				);
				
				$body = array(
					'body'		=> $params
				);

				$params_string = json_encode($body);

				if ( sandbox ) {
					$urlEnv = JakOne_dev;
					$token = $this->oauth2_dev();
				} else {
					$urlEnv = JakOne;
					$token = $this->oauth2_prod();
				}
				
				$url = $urlEnv . '/register';

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
					'Authorization: Bearer ' . $token,    
					'Cookie: dkib=!xg5DIn/nyySqAyVj8K6EwA1gVKp2h002j2Esn+gO0mAOy4rhTrkGy5ZZLp++8YKaP4+4zceb9kAC',
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
				}
				else {
					$data = array('status' => $httpCode, "message" => $request );
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
