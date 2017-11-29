<?php
/*
Plugin Name: login-form
Description: Login Form with security and style features
Version: 1.0.8
Author: www.wpadm.com
*/

define ('WPA_LOGIN_DIR', __DIR__);
define ('WPA_LOGIN_DIR_URL', plugin_dir_url(__FILE__)); 
define('WPA_SERVER_URL', 'http://secure.wpadm.com/');

require WPA_LOGIN_DIR . '/class/class-login.php';
if (file_exists(WPA_LOGIN_DIR . '/class/class-pro.php')) {
    require WPA_LOGIN_DIR . '/class/class-pro.php';
    define('WPA_LOGIN_PRO', true);
} else {
    define('WPA_LOGIN_PRO', false);
}


$wpadm_login = new wpadm_login;

add_action('admin_notices', array($wpadm_login, 'notice'));

function wpadm_login() {
 
    global $wpadm_login;
     
    return $wpadm_login -> show();
}

            
add_shortcode('wpadm-login', 'wpadm_login');

function wpadm_login_init_before_headers(){
    
    global $wpadm_login;
    
    $wpadm_login -> actions(); 
}

add_action('template_redirect', 'wpadm_login_init_before_headers');



if ( is_admin() ){ 
    
     require ( WPA_LOGIN_DIR . '/include/admin/custom_fields_functions.php' ); 
     require ( WPA_LOGIN_DIR . '/include/admin/main-setting.php' ); 
}


function hide_login_form() {
 
   if(get_option("form-stealth-hide-wplogin") == 1 ) {
		if(requestURI1() == 'wp-login.php') {
		
            $url = home_url(); 
                    
            wp_redirect($url, 302);
			exit;
		}
	} 
}

function requestURI1()
{
	$part = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$part = trim($part, "/");
	$part = strtolower($part);
	$part = explode("/", $part);
	return $part[0];
}

add_action('init', 'hide_login_form');

  