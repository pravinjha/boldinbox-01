<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('force_ssl'))
{
    function force_ssl()
    {
       /*  $CI =& get_instance();
        $CI->config->config['base_url'] = str_replace('http://', 'https://', $CI->config->config['base_url']);
        if ($_SERVER['SERVER_PORT'] != 443)
        {
           redirect($CI->uri->uri_string(), 'location', 301);
        } */
    }
 
	function remove_ssl(){
        $CI =& get_instance();
        $CI->config->config['base_url'] = str_replace('https://', 'http://', $CI->config->config['base_url']);
        if ($_SERVER['SERVER_PORT'] == 443){
            redirect($CI->uri->uri_string(), 'location', 301);
        }
    }
} 

if ( ! function_exists('site_url'))
{
	function site_url($uri = '')
	{
		$CI =& get_instance();
		return str_replace('.html','',$CI->config->site_url($uri));
	}
}
/* End of file url_helper.php */
/* Location: ./system/helpers/url_helper.php */