<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 

class ApiTesting extends CI_Controller {

	public function APIanterinlogin(){
		header('Content-Type: application/json');
				
				$uid		= "10005"; 
				$secret		= "2b261a8987c004580e24c2d8137485b425b6a0170257447b6745edaa6c073f8f"; 
				$pin		= "222222"; 
				$phone		= "+6281224469860";
				$signature	= hash('sha256', $uid . $secret . $pin . $phone);

				$params = array(
					'uid'		=> $uid,
					'signature'	=> $signature,
					
					'pin'		=> $pin,
					'phone'		=> $phone,		
				);

				$params_string = json_encode($params);
				
				// file_put_contents('log.txt', "*** " . $params_string . " ***\r\n", FILE_APPEND | LOCK_EX);
				
				$url = 'http://localhost:8080/public_apps/api/driver/loginPinAnterin';
				// $url = 'http://project.opus-id.com/siondel/api/driver/loginPin';
				// $url = 'http://103.41.206.172/ondel/api/driver/loginPin';
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
	
	public function APIcapture(){
		header('Content-Type: application/json');
				
				$uid			= ""; 
				$id_transaksi	= ""; 
				$type			= "1"; // 1 => FOTO SAMSAT OR 2 FOTO FINISH CUSTOMER
				$imgfile		= ""; //base64_encode
				$signature	= hash('sha256', $uid . $secret . $id_transaksi);

				$params = array(
					'uid'			=> $uid,	
					'id_transaksi'	=> $id_transaksi,	
					'type'			=> $type,	
					'imgfile'		=> $imgfile,	
					'signature'		=> $signature,	
				);

				$params_string = json_encode($params);
				
				// file_put_contents('log.txt', "*** " . $params_string . " ***\r\n", FILE_APPEND | LOCK_EX);
				
				$url = 'http://103.41.206.172/ondel/api/driver/APIcapture'; //production
				// $url = 'http://103.41.206.187/ondel/api/driver/APIcapture'; //sandbox
				// $url = 'http://localhost:8080/public_apps/api/driver/APIcapture';

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
	
	public function APIlogin(){
		header('Content-Type: application/json');
				
				$uid		= "10005"; 
				$secret		= "2b261a8987c004580e24c2d8137485b425b6a0170257447b6745edaa6c073f8f"; 
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
				
				// $url = 'http://localhost:8080/public_apps/api/customer/login';
				$url = 'http://project.opus-id.com/siondel/api/customer/login';

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
				
				$uid		= "10005"; 
				$secret		= "2b261a8987c004580e24c2d8137485b425b6a0170257447b6745edaa6c073f8f"; 
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
				
				// $url = 'http://localhost:8080/public_apps/api/customer/register';
				$url = 'http://project.opus-id.com/siondel/api/customer/register';

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
				$time		= time(); 
				$token		= "123123123123"; 
				
				$signature	= hash('sha256', $uid . $secret . $time);

				$params = array(
					'uid'		=> $uid,
					'signature'	=> $signature,
					
					'samsatid'	=> $samsatID,
					'time'		=> $time,	
					'token'		=> $token,	
				);

				$params_string = json_encode($params);
				
				// file_put_contents('log.txt', "*** " . $params_string . " ***\r\n", FILE_APPEND | LOCK_EX);
				
				// $url = 'http://localhost:8080/public_apps/api/customer/samsat';
				// $url = 'http://project.opus-id.com/siondel/api/customer/samsat';
				$url = 'http://103.41.206.172/ondel/api/customer/samsat';
				// $url = 'http://103.41.206.187/ondel/api/customer/samsat';

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
	
	
	public function APIdataTransaksi(){
		header('Content-Type: application/json');
				
				$uid		= "10005"; 
				$secret		= "2b261a8987c004580e24c2d8137485b425b6a0170257447b6745edaa6c073f8f"; 
				$no_polis	= "123"; 
				$nik		= "123"; 
				$no_rangka	= "123"; 
				$kode		= "123"; 
				$email		= "123"; 
				$phone		= "123"; 
				
				$signature	= hash('sha256', $uid . $secret . $kode);

				$params = array(
					'uid'		=> $uid,
					'signature'	=> $signature,
					
					'no_polis'	=> $no_polis,	
					'nik'		=> $nik	,
					'no_rangka'	=> $no_rangka,
					'kode'		=> $kode,
					'email'		=> $email,
					'phone'		=> $phone,
				);

				$params_string = json_encode($params);
				
				// file_put_contents('log.txt', "*** " . $params_string . " ***\r\n", FILE_APPEND | LOCK_EX);
				
				// $url = 'http://localhost:8080/public_apps/api/customer/dataTransaksi';
				$url = 'http://project.opus-id.com/siondel/api/customer/dataTransaksi';

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
					echo $params_string;
					
				}
				else {
					echo $httpCode . " - " . $request;
				}
	}
	
	
	public function APIgetdriver(){
		header('Content-Type: application/json');
				
				$uid		= "10005"; 
				$secret		= "2b261a8987c004580e24c2d8137485b425b6a0170257447b6745edaa6c073f8f"; 
				$latitude	= "-7.3296243"; 
				$longitude	= "108.2362242";
				$fitur		= "21"; //ID_FITUR SIONDEL
				$signature	= hash('sha256', $uid . $secret . $latitude . $longitude);

				$params = array(
					'uid'		=> $uid,
					'signature'	=> $signature,
					
					'latitude'		=> $latitude,
					'longitude'		=> $longitude,		
					'fitur'			=> $fitur,		
				);

				$params_string = json_encode($params);
				
				// file_put_contents('log.txt', "*** " . $params_string . " ***\r\n", FILE_APPEND | LOCK_EX);
				
				$url = 'http://localhost:8080/public_apps/api/customer/getDriver';
				// $url = 'http://project.opus-id.com/siondel/api/customer/getDriver';

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
	
	
	public function APIvoucher(){
		header('Content-Type: application/json');
				
				$uid		= "10005"; 
				$secret		= "2b261a8987c004580e24c2d8137485b425b6a0170257447b6745edaa6c073f8f"; 
				// $kdvoucher	= "ME35HRQL"; 
				$kdvoucher	= "2WKO3LEE"; 
				$phone		= "08211234567890"; //yang benar 08211234567890
				
				$signature	= hash('sha256', $uid . $secret . $kdvoucher);

				$params = array(
					'uid'		=> $uid,
					'signature'	=> $signature,
					
					'kdvoucher' => $kdvoucher,
					'phone' 	=> $phone,
				);

				$params_string = json_encode($params);
				
				// file_put_contents('log.txt', "*** " . $params_string . " ***\r\n", FILE_APPEND | LOCK_EX);
				
				// $url = 'http://project.opus-id.com/siondel/api/customer/kdvoucher';
				$url = 'http://localhost:8080/public_apps/api/customer/kdvoucher';
				// $url = 'http://103.41.206.172/ondel/api/customer/kdvoucher';
				// $url = 'http://103.41.206.187/ondel/api/customer/kdvoucher';

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
	
	
	public function APIrequestTransaksi(){
		header('Content-Type: application/json');
				
				$uid		= "10005"; 
				$secret		= "2b261a8987c004580e24c2d8137485b425b6a0170257447b6745edaa6c073f8f"; 
				$email		= "anggietriejast@gmail.com"; 
				$kdvoucher	= "ME35HRQL"; 
				
				$id_driver			= "D1593692590";
				$id_pelanggan		= "P1592190960";
				$order_fitur		= 21;
				$start_latitude		= "123123123";
				$start_longitude	= "123123123";
				$end_latitude		= "123123123";
				$end_longitude		= "123123123";
				$jarak				= "50";
				$harga				= "40000";
				$estimasi_time		= "100";
				$alamat_asal		= "Bekasi";
				$alamat_tujuan		= "Samsat Bekasi";
				$biaya_akhir		= "";
				$kredit_promo		= "";
				$pakai_wallet		= true;
				
				$nama_pengirim		= "PRIYANA ANGGIYAWAN";
				$telepon_pengirim	= "085718159655";
				$nama_penerima		= "ANGGIYAWAN";
				$telepon_penerima	= "085718159656";
				$nama_barang		= "BERKAS";
				$signature	= hash('sha256', $uid . $secret . $start_longitude . $start_longitude);

				$params = array(
					'uid'				=> $uid,
					'signature'			=> $signature,
					'email'				=> $email,
					'kdvoucher'			=> $kdvoucher,
					
					'id_driver'			=> $id_driver,
					'id_pelanggan'		=> $id_pelanggan,
					'order_fitur'		=> $order_fitur,
					'start_latitude'	=> $start_latitude,
					'start_longitude'	=> $start_longitude,
					'end_latitude'		=> $end_latitude,
					'end_longitude'		=> $end_longitude,
					'jarak'				=> $jarak,
					'harga'				=> $harga,
					'estimasi_time'		=> $estimasi_time,
					'alamat_asal'		=> $alamat_asal,
					'alamat_tujuan'		=> $alamat_tujuan,
					'biaya_akhir'		=> $biaya_akhir,
					'kredit_promo'		=> $kredit_promo,
					'pakai_wallet'		=> $pakai_wallet,
					
					'nama_pengirim'		=> $nama_pengirim,
					'telepon_pengirim'	=> $telepon_pengirim,
					'nama_penerima'		=> $nama_penerima,
					'telepon_penerima'	=> $telepon_penerima,
					'nama_barang'		=> $nama_barang,
				);

				$params_string = json_encode($params);
				
				// file_put_contents('log.txt', "*** " . $params_string . " ***\r\n", FILE_APPEND | LOCK_EX);
				
				// $url = 'http://project.opus-id.com/siondel/api/customer/requestTransaksi';
				// $url = 'http://localhost:8080/public_apps/api/customer/requestTransaksi';
				$url = 'http://103.41.206.172/ondel/api/customer/requestTransaksi';

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
	
	public function APIupdateTransaksi(){
		header('Content-Type: application/json');
				
				$uid			= "10005"; 
				$secret			= "2b261a8987c004580e24c2d8137485b425b6a0170257447b6745edaa6c073f8f"; 
				$id_transaksi	= "476"; 
				$id_driver		= "D1591934475";
				$status			= "1"; // 1 near, 2 accept, 3 start, 4 finish, 5 cancel
				$token			= "123456"; //ID_FITUR SIONDEL
				$signature		= hash('sha256', $uid . $secret . $id_transaksi . $id_driver);

				$params = array(
					'uid'			=> $uid,
					'signature'		=> $signature,
					
					'id_transaksi'	=> $id_transaksi,
					'id_driver'		=> $id_driver,		
					'status'		=> $status,		
					'token'			=> $token,		
				);

				$params_string = json_encode($params);
				
				// file_put_contents('log.txt', "*** " . $params_string . " ***\r\n", FILE_APPEND | LOCK_EX);
				
				$url = 'http://localhost:8080/public_apps/api/customer/updateTransaksi';
				// $url = 'http://project.opus-id.com/siondel/api/customer/updateTransaksi';

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
					
				}
				else {
					// echo $httpCode . " - " . $request;
				}
	}
	
	public function APIQrstring(){
		header('Content-Type: application/json');
				
				$uid		= "10005"; 
				$secret		= "2b261a8987c004580e24c2d8137485b425b6a0170257447b6745edaa6c073f8f"; 
				$kdvoucher	= "ME35HRQL"; 
				
				$signature	= hash('sha256', $uid . $secret . $kdvoucher);

				$params = array(
					'uid'		=> $uid,
					'signature'	=> $signature,
					
					'kdvoucher'	=> $kdvoucher,		
				);

				$params_string = json_encode($params);
				
				// file_put_contents('log.txt', "*** " . $params_string . " ***\r\n", FILE_APPEND | LOCK_EX);
				
				// $url = 'http://localhost:8080/public_apps/api/customer/QRstring';
				$url = 'http://103.41.206.172/ondel/api/customer/QRstring';

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
					
				}
				else {
					echo $httpCode . " - " . $request;
				}
	}
	
	public function APIcheck(){
		header('Content-Type: application/json');
				
				$uid			= "10005"; 
				$secret			= "2b261a8987c004580e24c2d8137485b425b6a0170257447b6745edaa6c073f8f"; 
				$id_transaksi	= "711";
				
				$signature	= hash('sha256', $uid . $secret . $id_transaksi);

				$params = array(
					'uid'		=> $uid,
					'signature'	=> $signature,
					
					'id_transaksi' => $id_transaksi,
				);

				$params_string = json_encode($params);
				
				// file_put_contents('log.txt', "*** " . $params_string . " ***\r\n", FILE_APPEND | LOCK_EX);
				
				// $url = 'http://project.opus-id.com/siondel/api/customer/transaksi_log';
				// $url = 'http://localhost:8080/public_apps/api/customer/transaksi_log';
				$url = 'http://103.41.206.172/ondel/api/customer/transaksi_log';

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
	
	function test(){
		
		$array = array();
		
		$data = $this->db->get_where('transaksi', array('id' => '300') );
		$array['parameter'] = "test";
		$json = array_merge($array, $data->result());
		// echo json_encode($json);
		
		header('Content-Type: application/json');
					
					$json_pretty = json_encode($json, JSON_PRETTY_PRINT);
					echo $json_pretty;
		
	}
	
	
	public function APIbank(){
		header('Content-Type: application/json');
				
				$uid		= "10005"; 
				$secret		= "2b261a8987c004580e24c2d8137485b425b6a0170257447b6745edaa6c073f8f"; 
				$bankcode	= "014";
				$time		= time(); 
				
				$signature	= hash('sha256', $uid . $secret . $time);

				$params = array(
					'uid'		=> $uid,
					'signature'	=> $signature,
					
					'bankcode'	=> $bankcode,
					'time'		=> $time,	
				);

				$params_string = json_encode($params);
				
				// file_put_contents('log.txt', "*** " . $params_string . " ***\r\n", FILE_APPEND | LOCK_EX);
				
				// $url = 'http://localhost:8080/public_apps/api/bank/getAll';
				// $url = 'http://103.41.206.187/ondel/api/bank/getAll'; //sandbox
				$url = 'http://103.41.206.172/ondel/api/bank/getAll'; //production

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
	
	public function APIcheckJakone(){
		header('Content-Type: application/json');
				
				$uid		= "10005"; 
				$secret		= "2b261a8987c004580e24c2d8137485b425b6a0170257447b6745edaa6c073f8f"; 
				$phone		= "085156447932"; // 085218159668
				$time		= time(); 
				
				$signature	= hash('sha256', $uid . $secret . $phone);

				$params = array(
					'uid'		=> $uid,
					'signature'	=> $signature,
					
					'phone'		=> $phone,	
				);

				$params_string = json_encode($params);
				
				// file_put_contents('log.txt', "*** " . $params_string . " ***\r\n", FILE_APPEND | LOCK_EX);
				
				// $url = 'http://localhost:8080/public_apps/payment/jakone/infochannel';
				$url = 'http://103.41.206.187/ondel/payment/jakone/infochannel'; //sandbox
				// $url = 'https://103.41.206.172/ondel/payment/jakone/infochannel'; //production

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
	
	public function APIregisterJakone(){
		header('Content-Type: application/json');
				
				$uid			= "10005"; 
				$secret			= "2b261a8987c004580e24c2d8137485b425b6a0170257447b6745edaa6c073f8f"; 
				$firstName		= "Anggi";
				$lastName		= "Yawan";
				$placeOfBirth	= "Jakarta";
				$dateOfBirth	= "1992-06-25"; 
				$password		= "111111";
				$msisdn			= "085156447932";
				$email			= "gietriejast@gmail.com";
				
				$signature	= hash('sha256', $uid . $secret . $msisdn);

				$params = array(
					'uid'			=> $uid,
					'signature'		=> $signature,
					
					'firstName'		=> $firstName,	
					'lastName'		=> $lastName,	
					'placeOfBirth'	=> $placeOfBirth,	
					'dateOfBirth'	=> $dateOfBirth,	
					'password'		=> $password,	
					'msisdn'		=> $msisdn,	
					'email'			=> $email,	
				);

				$params_string = json_encode($params);
				
				// file_put_contents('log.txt', "*** " . $params_string . " ***\r\n", FILE_APPEND | LOCK_EX);
				
				$url = 'http://localhost:8080/public_apps/payment/jakone/register';
				// $url = 'http://103.41.206.187/ondel/payment/jakone/register'; //sandbox
				// $url = 'https://103.41.206.172/ondel/payment/jakone/register'; //production

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
	
	public function APIlistJakone(){
		header('Content-Type: application/json');
				
				$uid		= "10005"; 
				$secret		= "2b261a8987c004580e24c2d8137485b425b6a0170257447b6745edaa6c073f8f"; 
				$iduser		= "P1600573252";
				$password	= "111111";
				$datefrom	= "20201001"; //year month day
				$dateto		= "20201026"; //year month day
				
				$signature	= hash('sha256', $uid . $secret . $iduser);

				$params = array(
					'uid'		=> $uid,
					'signature'	=> $signature,
						
					'iduser'	=> $iduser,	
					'password'	=> $password,	
					'datefrom'	=> $datefrom,	
					'dateto'	=> $dateto,	
				);

				$params_string = json_encode($params);
				
				// file_put_contents('log.txt', "*** " . $params_string . " ***\r\n", FILE_APPEND | LOCK_EX);
				
				$url = 'http://localhost:8080/public_apps/payment/jakone/transaksilist';
				// $url = 'http://103.41.206.187/ondel/payment/jakone/transaksilist'; //sandbox
				// $url = 'https://103.41.206.172/ondel/payment/jakone/transaksilist'; //production

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
	
	public function APIpaymentJakone(){
		header('Content-Type: application/json');
				
				$uid		= "10005"; 
				$secret		= "2b261a8987c004580e24c2d8137485b425b6a0170257447b6745edaa6c073f8f"; 
				$iduser		= "P1600573252";
				$password	= "111111";
				
				$signature	= hash('sha256', $uid . $secret . $iduser);

				$params = array(
					'uid'		=> $uid,
					'signature'	=> $signature,
					
					'iduser'		=> $iduser,	
					'password'		=> $password,	
				);

				$params_string = json_encode($params);
				
				// file_put_contents('log.txt', "*** " . $params_string . " ***\r\n", FILE_APPEND | LOCK_EX);
				
				$url = 'http://localhost:8080/public_apps/payment/jakone/payment';
				// $url = 'http://103.41.206.187/ondel/payment/jakone/payment'; //sandbox
				// $url = 'https://103.41.206.172/ondel/payment/jakone/payment'; //production

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
	
	public function APIGuide(){
		header('Content-Type: application/json');
				
				$uid		= "10005"; 
				$secret		= "2b261a8987c004580e24c2d8137485b425b6a0170257447b6745edaa6c073f8f"; 
				$time		= time(); 
				
				$signature	= hash('sha256', $uid . $secret . $time);

				$params = array(
					'uid'		=> $uid,
					'signature'	=> $signature,
						
					'time'		=> $time
				);

				$params_string = json_encode($params);
				
				// file_put_contents('log.txt', "*** " . $params_string . " ***\r\n", FILE_APPEND | LOCK_EX);
				
				// $url = 'http://localhost:8080/public_apps/api/customer/guide';
				$url = 'http://103.41.206.187/ondel/api/customer/guide'; //sandbox
				// $url = 'https://103.41.206.172/ondel/api/customer/guide'; //production

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