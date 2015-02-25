<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2013
 * @package yii2-helpers
 * @version 1.0.0
 */

namespace hscstudio\heart\helpers;

use Yii;

/**
 * Provides implementation for various helper functions
 *
 */
class Kalkun
{
   
   public function xss_filter($val) 
   {
		$val	= htmlspecialchars($val);
		$val	= htmlentities($val);	
		$val	= strip_tags($val);
		$val 	= filter_var($val, FILTER_SANITIZE_STRING);
	 
		return $val;
   }
   
   public function AsciiToHex($ascii)
   {
      $hex = '';

      for($i = 0; $i < strlen($ascii); $i++)
         $hex .= str_pad(base_convert(ord($ascii[$i]), 10, 16), 2, '0', STR_PAD_LEFT);

      return $this->xss_filter($hex);
   }

   // convert a hex string to ascii, prepend with '0' if input is not an even number
   // of characters in length   
   public function HexToAscii($hex)
   {
      $ascii = '';
   
      if (strlen($hex) % 2 == 1)
         $hex = '0'.$hex;
   
      for($i = 0; $i < strlen($hex); $i += 2)
         $ascii .= chr(base_convert(substr($hex, $i, 2), 16, 10));
   
      return $this->xss_filter($ascii);
   }	
}