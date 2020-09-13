<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 

class ApiTesting extends CI_Controller {

	public function APIlogin(){
		header('Content-Type: application/json');
				
				$uid		= "10006"; 
				$secret		= "eca646133ad7a463b0c82fe569b3e12ff4a7e279908db461f0b38fa82c2436b8"; 
				$email		= "anggietriejast+123@gmail.com"; 
				$pass		= "6285718159655";
				$signature	= hash('sha256', $uid . $secret . $email . $pass);

				$params = array(
					'uid'		=> $uid,
					'signature'	=> $signature,
					
					'email'		=> $email,
					'pass'		=> $pass,		
				);

				$params_string = json_encode($params);
				
				// file_put_contents('log.txt', "*** " . $params_string . " ***\r\n", FILE_APPEND | LOCK_EX);
				
				// $url = 'http://localhost:8080/public_html/api/samsat/login';
				$url = 'http://project.opus-id.com/siondel/api/samsat/login';

				$ch = curl_init();

				curl_setopt($ch, CURLOPT_URL, $url); 
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
				curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);                                                                  
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
					'Content-Type: application/json',                                                                                
					'Content-Length: ' . strlen($params_string))                                                                       
				);   
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

				//execute post
				$request = curl_exec($ch);
				$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				// var_dump($request);
				if($httpCode == 200)
				{
					header('Content-Type: application/json');
					
					$result = json_decode($request, true);

					$json_pretty = json_encode($result, JSON_PRETTY_PRINT);
					// echo $json_pretty;
					var_dump($request);
					
				}
				else {
					echo $httpCode . " - " . $request;
				}
	}
	
	
	public function APIRegister(){
		header('Content-Type: application/json');
				
				$uid		= "10006"; 
				$secret		= "eca646133ad7a463b0c82fe569b3e12ff4a7e279908db461f0b38fa82c2436b8"; 
				$email		= "anggietriejast+125@gmail.com"; 
				$nohp		= "085718159657";
				$pin		= "123";
				$token		= "123";
				$signature	= hash('sha256', $uid . $secret . $email);

				$params = array(
					'uid'		=> $uid,
					'signature'	=> $signature,
					
					'email'		=> $email,
					'nohp'		=> $nohp,	
					'pin'		=> $pin,	
					'token'		=> $token,	
				);

				$params_string = json_encode($params);
				
				// file_put_contents('log.txt', "*** " . $params_string . " ***\r\n", FILE_APPEND | LOCK_EX);
				
				// $url = 'http://localhost:8080/public_html/api/samsat/register';
				$url = 'http://project.opus-id.com/siondel/api/samsat/register';

				$ch = curl_init();

				curl_setopt($ch, CURLOPT_URL, $url); 
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
				curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);                                                                  
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
					'Content-Type: application/json',                                                                                
					'Content-Length: ' . strlen($params_string))                                                                       
				);   
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

				//execute post
				$request = curl_exec($ch);
				$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				// var_dump($request);
				if($httpCode == 200)
				{
					header('Content-Type: application/json');
					
					$result = json_decode($request, true);

					$json_pretty = json_encode($result, JSON_PRETTY_PRINT);
					// echo $json_pretty;
					var_dump($request);
					
				}
				else {
					echo $httpCode . " - " . $request;
				}
	}
	
	
	public function APIsamsat(){
		header('Content-Type: application/json');
				
				$uid		= "10005"; 
				$secret		= "2b261a8987c004580e24c2d8137485b425b6a0170257447b6745edaa6c073f8f"; 
				$samsatID	= ""; // kalau dikosongkan get all samsat
				$time	= time(); 
				
				$signature	= hash('sha256', $uid . $secret . $time);

				$params = array(
					'uid'		=> $uid,
					'signature'	=> $signature,
					
					'samsatid'	=> $samsatID,
					'time'		=> $time	
				);

				$params_string = json_encode($params);
				
				// file_put_contents('log.txt', "*** " . $params_string . " ***\r\n", FILE_APPEND | LOCK_EX);
				
				// $url = 'http://localhost:8080/public_html/api/customer/samsat';
				$url = 'http://project.opus-id.com/siondel/api/customer/samsat';

				$ch = curl_init();

				curl_setopt($ch, CURLOPT_URL, $url); 
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
				curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);                                                                  
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
					'Content-Type: application/json',                                                                                
					'Content-Length: ' . strlen($params_string))                                                                       
				);   
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

				//execute post
				$request = curl_exec($ch);
				$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				// var_dump($request);
				if($httpCode == 200)
				{
					header('Content-Type: application/json');
					
					$result = json_decode($request, true);

					$json_pretty = json_encode($result, JSON_PRETTY_PRINT);
					echo $json_pretty;
					// var_dump($request);
					
				}
				else {
					echo $httpCode . " - " . $request;
				}
	}
	
}