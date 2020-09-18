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