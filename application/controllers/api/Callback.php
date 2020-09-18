<?php defined('BASEPATH') or exit('No direct script access allowed');
// require APPPATH . '/libraries/REST_Controller.php';

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
        $this->load->helper("url");
        $this->load->database();
        // $this->load->model('Driver_model');
        // $this->load->model('Pelanggan_model');
        date_default_timezone_set('Asia/Jakarta');
    }
	
	
	public function samsat(){
				// header('Content-Type: application/json');
				// header( 'Content-type: text/xml' );

				$data = file_get_contents('php://input');
				$result = json_decode($data, true);
			
				// $pin		= @$result["pin"];
				// $phone		= @$result["phone"];
			
				// $params = array(
					// 'pin'		=> $pin,
					// 'phone'		=> $phone
				// );


				// $params_string = json_encode($params);
				
				file_put_contents('log.txt', "*** " . json_encode($result) . " ***\r\n", FILE_APPEND | LOCK_EX);
				
				// $url = 'https://newdriver.anterin.id/api/driver/loginPin';

				// $ch = curl_init();
				// curl_setopt($ch, CURLOPT_URL, $url);
				// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				// curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
				// curl_setopt($ch, CURLOPT_HEADER, false);
				// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				// curl_setopt($ch, CURLOPT_HTTPHEADER, [    
					// 'Content-Type: application/json' 
				// ]);
				// $request = curl_exec($ch);
				// $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				 
				// curl_close($ch);
						 
				// if($httpCode == 200)
				// {
					// header('Content-Type: application/json');
					
					// $data = json_decode($request, true);
					
					// if ( empty(@$data['message']) ) {
						// $json_pretty = json_encode($result, JSON_PRETTY_PRINT);
						// echo $json_pretty;
						// $result['status'] = "200";
						// $result['message'] = "Success";
						// $result=array_merge($result,array('data'=>$data));
						// $returnValue =  json_encode($result);
					// } else {
						// $data = array('status' => "201", "message" => $data['message'] );
						// $returnValue = json_encode($data); 
					// }
					
				// }
				// else {
					// $data = array('status' => $httpCode, "message" => $request );
					// $returnValue = json_encode($data); 
				// }

			
			
			// echo $returnValue;

				
	}
	
}
