<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Helper untuk pengaturan web
 */


function number_hp_prefix($originalNumber){
  $countryCode = '62'; // Replace with known country code of user.
  $internationalNumber = preg_replace('/^0/', $countryCode, $originalNumber);
  return $internationalNumber;
}

function update_transaksi_log($id, $desc){
    $init =& get_instance();
		$transaksi_log = array(
			'id_transaksi'	=> $id,
			'keterangan'	=> $desc,
			'input_date'	=> date('Y-m-d H:i:s')
		);

      $init->db->insert('transaksi_log', $transaksi_log);
	
}

function update_transaksi($id){
		$init =& get_instance();
		
		if ( sandbox ) {
			$urlEnv = DashboardSamsat_dev;
		} else {
			$urlEnv = DashboardSamsat;
		}
				
		$apiLink = $urlEnv . "/api/batal_kuota/1";
		$response = file_get_contents($apiLink);
		
		$data = array(
			'harga'			=> 0,
			'biaya_akhir'	=> 0,
		);
		
	  $init->db->where('id', $id);
        $edit = $init->db->update('transaksi', $data);
        if ($init->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
	
}

function menu($menu_id, $akses)
{
	$CI =& get_instance();
	
	$sql = "SELECT * FROM groups_roles WHERE menu_id='" . $menu_id . "' AND group_id='" . $CI->session->userdata('group_id') . "'";
	$data = $CI->db->query($sql);
	foreach ( $data->result() as $user )
	{
		return $user->$akses;
	}
	
}