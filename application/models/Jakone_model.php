<?php

class Jakone_model extends CI_model
{
	
	public function update_saldo($id, $data)
    {
       $this->db->where('id_user', $id);
       return $this->db->update('saldo', $data);
    }
	
	//kdvoucher_payment
	public function kdvoucherPaymentId()
    {
		$increment = 100001;
		
        $this->db->select('id');
        $this->db->order_by('id', 'desc');
        $id = @$this->db->get('kdvoucher_payment')->row()->id;
		if ( isset($id) )
			return ($id+1);
		else{
			return $increment;
		}
    }
	
	//kdvoucher_payment
	public function kdvoucherPaymentUpdate($param)
    {
		$param['channel'] = "JAKONE";
        $this->updateOnDuplicate('kdvoucher_payment', $param);
    }
	
	public function updateOnDuplicate($table, $data ) {
		 if (empty($table) || empty($data)) return false;
		 $duplicate_data = array();
		 
		 foreach($data AS $key => $value) {
			$duplicate_data[] = sprintf("%s='%s'", $key, addslashes($value));
		 }

		 $sql = sprintf("%s ON DUPLICATE KEY UPDATE %s", $this->db->insert_string($table, $data), implode(',', $duplicate_data));
		 
		 $this->db->query($sql);
		 return $this->db->insert_id();
	}
	
    public function getAll($param = "")
    {
		if ( !empty($param['bankcode']) ) {
			$this->db->where('bank_code', $param['bankcode']);
		}
		
        $this->db->select('id, bank_code, bank_name, apply');
        return  $this->db->get('bank');
    }
	
	public function getAllbank()
    {
        return $this->db->get('bank')->result_array();
    }
	
	public function getbankbyid($id)
    {
        $this->db->select('*');
        return $this->db->get_where('bank', ['id' => $id]);
    }
	
    public function getbanksbyid_active($id)
    {
        return  $this->db->get_where('bank', ['bank.id' => $id, 'bank.apply' => 1])->row_array();
    }

    public function getcurrency()
    {
        $this->db->select('app_currency as duit');
        $this->db->where('id', '1');
        return $this->db->get('app_settings')->row_array();
    }

    public function wallet($id)
    {
        $this->db->order_by('wallet.id', 'DESC');
        return $this->db->get_where('wallet', ['id_bank' => $id])->result_array();
    }

    public function countorder($id)
    {

        $this->db->select('status_transaksi.*');
        $this->db->select('history_transaksi.*');
        $this->db->select('fitur.*');
        $this->db->select('transaksi.*');
        $this->db->join('history_transaksi', 'transaksi.id = history_transaksi.id_transaksi', 'left');
        $this->db->join('status_transaksi', 'history_transaksi.status = status_transaksi.id', 'left');
        $this->db->join('fitur', 'transaksi.order_fitur = fitur.id_fitur', 'left');
        $this->db->where('history_transaksi.status != 1');
        $this->db->order_by('transaksi.id', 'DESC');
        $query =    $this->db->get_where('transaksi', ['id_pelanggan' => $id])->result_array();
        return $query;
    }

    public function blockbanksbyid($id)
    {
        $this->db->set('status', '0');
        $this->db->where('id', $id);
        $this->db->Update('pelanggan');
    }

    public function unblockbanksbyid($id)
    {
        $this->db->set('status', '1');
        $this->db->where('id', $id);
        $this->db->Update('pelanggan');
    }

    public function ubahdataid($data)
    {
        $this->db->set('fullnama', $data['fullnama']);
        $this->db->set('no_telepon', $data['no_telepon']);
        $this->db->set('email', $data['email']);
        $this->db->set('countrycode', $data['countrycode']);
        $this->db->set('phone', $data['phone']);
        $this->db->set('tgl_lahir', $data['tgl_lahir']);


        $this->db->where('id', $data['id']);
        $this->db->update('pelanggan', $data);
    }

    public function ubahdatafoto($data)
    {
        $this->db->set('fotopelanggan', $data['fotopelanggan']);

        $this->db->where('id', $data['id']);
        $this->db->update('pelanggan', $data);
    }

    public function ubahdatapassword($data)
    {
        $this->db->set('password', $data['password']);

        $this->db->where('id', $data['id']);
        $this->db->update('pelanggan', $data);
    }

    public function blockbankbyid($id)
    {
        $this->db->set('apply', 0);
        $this->db->where('id', $id);
        $this->db->update('bank');
    }

    public function unblockbankbyid($id)
    {
        $this->db->set('apply', 1);
        $this->db->where('id', $id);
        $this->db->update('bank');
    }

    public function tambahdatabanks($data)
    {
        $this->db->insert('bank', $data);
        $data2 = [
            'id' => $data['id'],
            'saldo'   => 0,
        ];
        $this->db->insert('saldo', $data2);
    }

    public function hapusdatabankbyid($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('pelanggan');

        $this->db->where('id_pelanggan', $id);
        $this->db->delete('transaksi');

        $this->db->where('id_bank', $id);
        $this->db->delete('saldo');

        $this->db->where('bankid', $id);
        $this->db->delete('forgot_password');

        $this->db->where('id_pelanggan', $id);
        $this->db->delete('rating_driver');

        $this->db->where('id_bank', $id);
        $this->db->delete('wallet');
    }

}