<?php

class Voucher_model extends CI_model
{
    public function getAllpromocode()
    {
        $this->db->join('fitur', 'kdvoucher.fitur = fitur.id_fitur', 'left');
        return  $this->db->get('kdvoucher')->result_array();
    }

    public function getpromocodebyid($id)
    {
        return $this->db->get_where('kdvoucher', ['id_promo' => $id])->row_array();
    }

    public function hapuspromocodebyId($id)
    {
        $this->db->where('id_promo', $id);
        $this->db->delete('kdvoucher');
    }

    public function addvoucher($data)
    {
        return $this->db->insert('kdvoucher', $data);
    }

    public function getvoucher($code)
    {
        $this->db->select('*');
        $this->db->from('kdvoucher');
        $this->db->where('kdvoucher',$code);
        return $this->db->get(); 
    }

    public function getpromobyid($id)
    {
        $this->db->select('*');
        $this->db->from('kdvoucher');
        $this->db->where('id_promo',$id);
        return $this->db->get(); 
    }

    public function editpromocode($data)
    {
        $this->db->where('id_promo', $data['id_promo']);
        return $this->db->update('kdvoucher', $data);
    }

}