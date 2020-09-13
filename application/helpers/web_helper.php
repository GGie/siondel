<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Helper untuk pengaturan web
 */


function number_hp_prefix($originalNumber){
  $countryCode = '62'; // Replace with known country code of user.
  $internationalNumber = preg_replace('/^0/', $countryCode, $originalNumber);
  return $internationalNumber;
}