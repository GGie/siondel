<?php


class Admin_model extends CI_model
{
    public function getAlladmin()
    {
        return $this->db->get('admin')->result_array();
    }

	public function getadminbyid($id)
    {
        $this->db->select('*');
        return $this->db->get_where('admin', ['id' => $id]);
    }
	
    public function getusersbyid($id)
    {
        $this->db->select('admin.*');
        return  $this->db->get_where('admin', ['admin.id' => $id])->row_array();
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
        return $this->db->get_where('wallet', ['id_user' => $id])->result_array();
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

    public function blockusersbyid($id)
    {
        $this->db->set('status', '0');
        $this->db->where('id', $id);
        $this->db->Update('admin');
    }

    public function unblockusersbyid($id)
    {
        $this->db->set('status', '1');
        $this->db->where('id', $id);
        $this->db->Update('admin');
    }

    public function ubahdataid($data)
    {
        $this->db->set('user_name', $data['user_name']);
        $this->db->set('email', $data['email']);
        $this->db->set('group_id', $data['group_id']);


        $this->db->where('id', $data['id']);
        $this->db->update('admin', $data);
    }

    public function ubahdatafoto($data)
    {
        $this->db->set('image', $data['fotopelanggan']);

        $this->db->where('id', $data['id']);
        $this->db->update('admin', $data);
    }

    public function ubahdatapassword($data)
    {
        $this->db->set('password', $data['password']);

        $this->db->where('id', $data['id']);
        $this->db->update('admin', $data);
    }

    public function blockuserbyid($id)
    {
        $this->db->set('status', 0);
        $this->db->where('id', $id);
        $this->db->update('admin');
    }

    public function unblockuserbyid($id)
    {
        $this->db->set('status', 1);
        $this->db->where('id', $id);
        $this->db->update('admin');
    }

    public function tambahdatausers($data)
    {
        $this->db->insert('admin', $data);
    }

    public function hapusdatauserbyid($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('admin');
    }
}
