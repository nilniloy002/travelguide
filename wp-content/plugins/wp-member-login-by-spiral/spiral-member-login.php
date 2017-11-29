<?php
/**
 * @package   Spiral_Member_Login
 * @author    PIPED BITS Co.,Ltd.
 * @license   GPLv2
 * @link      http://www.pi-pe.co.jp
 * @copyright Copyright (c) PIPED BITS Co.,Ltd.
 * @copyright Portions copyright (c) Eric Mann
 *
 * @wordpress-plugin
 * Plugin Name: WP Member Login by SPIRAL
 * Description: Add membership management and secure authentication by SPIRAL&reg; into your WordPress site.
 * Version:     1.0.4
 * Author:      PIPED BITS Co.,Ltd.
 * Author URI:  http://www.pi-pe.co.jp
 * License:     GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: spiral-member-login
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'class-spiral-api.php' );
require_once( plugin_dir_path( __FILE__ ) . 'class-spiral-member-login-session.php' );
require_once( plugin_dir_path( __FILE__ ) . 'class-spiral-member-login-base.php' );
require_once( plugin_dir_path( __FILE__ ) . 'class-spiral-member-login.php' );
require_once( plugin_dir_path( __FILE__ ) . 'class-spiral-member-login-template.php' );
require_once( plugin_dir_path( __FILE__ ) . 'class-spiral-member-login-widget.php' );

register_uninstall_hook( __FILE__, array( 'Spiral_Member_Login', 'uninstall' ) );
register_activation_hook( __FILE__, array( 'Spiral_Member_Login', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Spiral_Member_Login', 'deactivate' ) );

Spiral_Member_Login::get_instance();

if ( ! function_exists( 'sml_is_logged_in' ) ) :
function sml_is_logged_in() {
	return Spiral_Member_Login::get_instance()->is_logged_in();
}
endif;

if ( ! function_exists( 'sml_user_prop' ) ) :
function sml_user_prop( $key ) {
	return Spiral_Member_Login::get_instance()->get_user_prop( $key );
}
endif;

