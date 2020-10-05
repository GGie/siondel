<?php

class Bank_account_model extends CI_model
{
    public function getAllDriver($param = "")
    {
		if ( !empty($param['id_driver']) ) {
			$this->db->where('id_driver', $param['id_driver']);
		}
		
        $this->db->select('id, account_username, account_number, bank_code, bank_name');
        return  $this->db->get('bank_account');
    }
	
	public function getAllbank()
    {
        return $this->db->get('bank_account')->result_array();
    }
	
}