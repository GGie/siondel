<?php defined('BASEPATH') or exit('No direct script access allowed');



class Bank extends CI_Controller
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
        $this->load->model('Bank_model');
        date_default_timezone_set('Asia/Jakarta');
    }


	public function getAll()
    {
		$data = file_get_contents('php://input');
		$result = json_decode($data, true);
	
		header('Content-Type: application/json');

		$uid 		= @$result["uid"];
		$signature 	= @$result["signature"];
		
		$bankcode	= @$result["bankcode"];
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
						
						$param = array();
						if ($bankcode != '') {
							$param = array(
								'bankcode'	=> @$bankcode,
							);
						}
						
						
						
						$getbank = $this->Bank_model->getAll($param);
						if ($getbank->num_rows() > 0) {
							$data = array('status' => "200", "message" => 'success', 'data' => $getbank->result() );
							$returnValue = json_encode($data);
						} else {
							$data = array('status' => "201", "message" => 'Bank Not Found', 'data' => []);
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

	
}
