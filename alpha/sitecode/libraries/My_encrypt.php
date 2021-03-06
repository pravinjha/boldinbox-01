<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
* This library assumes that you have already loaded the default CI Upload Library seperately
* 
* Functions is based upon CI_Upload, Feel free to modify this 
*   library to function as an extension to CI_Upload
* 
* Library modified by: Alvin Mites
* http://www.mitesdesign.com
* 
*/
class MY_Encrypt extends CI_Encrypt
{

   var $skey 	= "13@SuPravin@Dded5uprEncKey2012"; // you can change it
 
    public  function safe_b64encode($string) {
 
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }
 
	public function safe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
 	public  function encode($value = "", $method = "", $mode = "") {
	  $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
	  $encrypted = openssl_encrypt($value, 'aes-256-cbc', $this->skey, 0, $iv);
	  return trim($this->safe_b64encode($encrypted . '::' . $iv));  
	}
	public function decode($value = "", $method = "", $mode = "") {
		list($encrypted_data, $iv) = explode('::', $this->safe_b64decode($value), 2);
		return openssl_decrypt($encrypted_data, 'aes-256-cbc', $this->skey, 0, $iv);
	}
	
	
    public  function encode_NOT_IN_USE($value = "", $method = "", $mode = ""){ 
 
	    if(!$value){return false;}
        $text = $value;
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->skey, $text, MCRYPT_MODE_ECB, $iv);
        return trim($this->safe_b64encode($crypttext)); 
    }
 
 	
    public function decode_NOT_IN_USE($value = "", $method = "", $mode = ""){
 
        if(!$value){return false;}
        $crypttext = $this->safe_b64decode($value); 
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->skey, $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
    }
}
?>
