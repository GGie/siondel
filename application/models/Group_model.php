<?php


class Group_model extends CI_model
{
    public function getAllgroups()
    {
        return $this->db->get('group')->result_array();
    }

    public function getgroupsbyid($id)
    {
        return  $this->db->get_where('group', ['group.group_id' => $id])->row_array();
    }

    public function blockgroupsbyid($id)
    {
        $this->db->set('status', '0');
        $this->db->where('id', $id);
        $this->db->Update('pelanggan');
    }

    public function unblockgroupsbyid($id)
    {
        $this->db->set('status', '1');
        $this->db->where('id', $id);
        $this->db->Update('group');
    }

    public function ubahdataid($data)
    {
        $this->db->set('group_name', $data['group_name']);
        $this->db->set('desc', $data['desc']);
        $this->db->where('group_id', $data['group_id']);
        $this->db->update('group', $data);
    }

    public function ubahdatafoto($data)
    {
        $this->db->set('fotopelanggan', $data['fotopelanggan']);

        $this->db->where('group_id', $data['id']);
        $this->db->update('group', $data);
    }

    public function ubahdatapassword($data)
    {
        $this->db->set('password', $data['password']);

        $this->db->where('group_id', $data['id']);
        $this->db->update('group', $data);
    }

    public function blockuserbyid($id)
    {
        $this->db->set('status', 0);
        $this->db->where('group_id', $id);
        $this->db->update('group');
    }

    public function unblockuserbyid($id)
    {
        $this->db->set('status', 1);
        $this->db->where('group_id', $id);
        $this->db->update('group');
    }

    public function tambahdatagroups($data)
    {
        $this->db->insert('group', $data);
    }

    public function hapusdatauserbyid($id)
    {
        $this->db->where('group_id', $id);
        $this->db->delete('group');
    }
}
