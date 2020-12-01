<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Helper untuk pengaturan web
 */


function number_hp_prefix($originalNumber){
  $countryCode = '62'; // Replace with known country code of user.
  $internationalNumber = preg_replace('/^0/', $countryCode, $originalNumber);
  return $internationalNumber;
}

function number_hp_zero($originalNumber){
  $countryCode = '0'; // Replace with known country code of user.
  $internationalNumber = preg_replace('/^62/', $countryCode, $originalNumber);
  
  if ( substr($internationalNumber, 0, 1) == 0 ) {
	$result = $internationalNumber;
  } else {
	$result = "0" . $internationalNumber;
  }
  return $result;
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

function JakoneTranslate($text)
{
	$text = strtolower($text);
	$returnText = $text;
	
	if($text == "account blocked.")
		$returnText = "Akun Jakone Terblokir";
	if($text == "Customer not found.")
		$returnText = "Merchant Code (CIF CASA) dari request tidak di temukan";
	if($text == "Account From not found.")
		$returnText = "No Account tidak ditemukan";
	if($text == "Your account is not allowed to make transaction in this Merchant")
		$returnText = "No Account Bukan Account Casa";
	if($text == "Process successful.")
		$returnText = "Proses Berhasil";
	if($text == "Data not found.")
		$returnText = "Data transaksi tidak di temukan (reference nya salah)";
	if($text == "Transaction failed with exception")
		$returnText = "Proses transaksi gagal";
	if($text == "Transaction not found.")
		$returnText = "Transaksi tidak di temukan";
	if($text == "username not found.")
		$returnText = "Akun Jakone Tidak di temukan";
	if($text == "bad credential.")
		$returnText = "Password Jakone Salah";
	
	return $returnText;
	
}

function nopol($nopol){
	
	$str_nospace = str_replace(" ", "", $nopol); // replace space
	
	$str_first = substr($str_nospace, 0, 1);
	
	if (is_numeric($str_first)){
		//Jika huruf pertama angka
		$str = $str_nospace;
	} else {
		//Jika huruf pertama bukan angka
		//remove 1 caracter
		$str = substr($str_nospace, 1);
		
		//cek karakter pertama numerik atau bukan
		$str_first = substr($str, 0, 1);
		if (is_numeric($str_first)){
			//Jika huruf pertama angka
			$str = $str;
		} else {
			$str = substr($str, 1);
		}
	}

	return $str;
}