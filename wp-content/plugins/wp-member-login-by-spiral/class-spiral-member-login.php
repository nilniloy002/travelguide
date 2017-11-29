<?php
/**
 * WP Member Login by SPIRAL.
 *
 * @package   Spiral_Member_Login
 * @author    PIPED BITS Co.,Ltd.
 */

if ( ! class_exists( 'Spiral_Member_Login' ) ) :
/**
 * Plugin class.
 *
 * @package Spiral_Member_Login
 * @author  PIPED BITS Co.,Ltd.
 */
class Spiral_Member_Login extends Spiral_Member_Login_Base {

	/**
	 * Plugin version
	 *
	 * @since   1.0.0
	 *
	 * @const     string
	 */
	const version = '1.0.0';

	/**
	 * Plugin slug
	 *
	 * @since   1.0.0
	 * @var     string
	 */
	protected $plugin_slug = 'spiral-member-login';

	/**
	 * Holds options key
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var string
	 */
	protected $options_key = 'spiral_member_login';

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @const      string
	 */
	const domain = 'spiral-member-login';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Holds errors object
	 *
	 * @since 1.0.0
	 * @access public
	 * @var object
	 */
	public $errors;

	/**
	 * Holds current page being requested
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $request_page;

	/**
	 * Holds current action being requested
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $request_action;

	/**
	 * Holds current template being requested
	 *
	 * @since 1.0.0
	 * @access public
	 * @var int
	 */
	public $request_template_num;

	/**
	 * Holds loaded template instances
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var array
	 */
	protected $loaded_templates = array();

	/**
	 * WP Session for SML
	 */
	public $session;

	/**
	 * SPIRAL API
	 */
	public $spiral;


	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		$this->load_options();
		$this->load_template();
		$this->load_plugin_textdomain();

		// wp actions
		add_action( 'init', array( &$this, 'init' ) );
		add_action( 'admin_init', array( &$this, 'admin_init') );
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_admin_scripts' ) );

		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts' ) );
		add_action( 'widgets_init', array( &$this, 'widgets_init' ) );
		add_action( 'wp', array( &$this, 'wp' ) );
		add_action( 'template_redirect', array( &$this, 'template_redirect' ) );
		add_action( 'wp_head', array( &$this, 'wp_head' ) );
		add_action( 'wp_footer', array( &$this, 'wp_footer' ) );
		add_action( 'wp_print_footer_scripts', array( &$this, 'wp_print_footer_scripts' ) );

		// wp filters
		add_filter( 'wp_setup_nav_menu_item', array( &$this, 'wp_setup_nav_menu_item' ) );
		add_filter( 'wp_list_pages_excludes', array( &$this, 'wp_list_pages_excludes' ) );
		add_filter( 'page_link', array( &$this, 'page_link' ), 10, 2 );

		// wp shortcodes
		add_shortcode( 'sml-show-template', array( &$this, 'shortcode_show_template' ) );
		add_shortcode( 'sml-is-logged-in', array( &$this, 'shortcode_is_logged_in' ) );
		add_shortcode( 'sml-user-prop', array( &$this, 'shortcode_user_prop' ) );

		// setup session
		$this->session = new Spiral_Member_Login_Session();
		$this->spiral = new Spiral_Api( $this->get_option( 'api_token' ), $this->get_option( 'api_token_secret' ) );
	}


	/************************************************************************************************************************
	 * Hooks
	 ************************************************************************************************************************/

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
	}

	/**
	 * Uninstall hook
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function uninstall() {
		global $wpdb;

		if ( is_multisite() ) {
			if ( isset( $_GET['networkwide'] ) && ( $_GET['networkwide'] == 1 ) ) {
				$blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
				foreach ( $blogids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::_uninstall();
				}
				restore_current_blog();
				return;
			}
		}
		self::_uninstall();
	}


	/************************************************************************************************************************
	 * Actions
	 ************************************************************************************************************************/

	/**
	 * Initilizes the plugin
	 *
	 * @since    1.0.0
	 */
	public function init() {
		$this->errors = new WP_Error();
	}

	/**
	 * Register plugin's setting and Install
	 *
	 * @since    1.0.0
	 */
	public function admin_init() {
		register_setting( $this->options_key, $this->options_key, array( &$this, 'save_settings' ) );

		if ( version_compare( $this->get_option( 'version', 0 ), self::version, '<' ) ) {
			$this->install();
		}
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {
		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ), false, self::version );
		}
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {
		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			//wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), self::version );
		}
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		//wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'css/public.css', __FILE__ ), false, self::version );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		//wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'js/public.js', __FILE__ ), array( 'jquery' ), self::version );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function admin_menu() {
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'WP Member Login by SPIRAL', self::domain ),
			__( 'WP Member Login by SPIRAL', self::domain ),
			'read',
			$this->options_key,
			array( $this, 'display_plugin_admin_page' )
		);

		add_settings_section( 'api', __( 'Spiral API Token', self::domain ), '__return_false', $this->options_key );
		add_settings_section( 'auth', __( 'Authentication Settings', self::domain ), '__return_false', $this->options_key );
		add_settings_section( 'userprop', __( 'Member information Settings', self::domain ), '__return_false', $this->options_key );
		add_settings_section( 'link', __( 'Link Settings', self::domain ), '__return_false', $this->options_key );

		// api
		add_settings_field( 'api_token', __( 'API token', self::domain ), array( &$this, 'settings_field_api_token' ), $this->options_key, 'api' );
		add_settings_field( 'api_token_secret', __( 'API token secret', self::domain ), array( &$this, 'settings_field_api_token_secret' ), $this->options_key, 'api' );

		// auth
		add_settings_field( 'area_title', __( 'Area Title', self::domain ), array( &$this, 'settings_field_area_title' ), $this->options_key, 'auth' );
		add_settings_field( 'auth_form_url', __( 'Authentication form URL', self::domain ), array( &$this, 'settings_field_auth_form_url' ), $this->options_key, 'auth' );

		// userprop
		add_settings_field( 'member_list_search_title', __( 'Member List Search Title', self::domain ), array( &$this, 'settings_field_member_list_search_title' ), $this->options_key, 'userprop' );
		add_settings_field( 'default_name_key', __( 'Default Name Key', self::domain ), array( &$this, 'settings_field_default_name_key' ), $this->options_key, 'userprop' );

		// link
		add_settings_field( 'register_url', __( 'Register URL', self::domain ), array( &$this, 'settings_field_register_url' ), $this->options_key, 'link' );
		add_settings_field( 'lostpassword_url', __( 'Lost Password URL', self::domain ), array( &$this, 'settings_field_lostpassword_url' ), $this->options_key, 'link' );
		add_settings_field( 'profile_page_id', __( 'Profile Page ID', self::domain ), array( &$this, 'settings_field_profile_page_id' ), $this->options_key, 'link' );
		add_settings_field( 'resetpass_page_id', __( 'Reset Password Page ID', self::domain ), array( &$this, 'settings_field_resetpass_page_id' ), $this->options_key, 'link' );
		add_settings_field( 'withdrawal_page_id', __( 'Withdrawal Page ID', self::domain ), array( &$this, 'settings_field_withdrawal_page_id' ), $this->options_key, 'link' );
	}

	/**
	 * Registers the widget
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function widgets_init() {
		if ( class_exists( 'Spiral_Member_Login_Widget' ) ) {
			register_widget( 'Spiral_Member_Login_Widget' );
		}
	}

	/**
	 * Used to add/remove filters from login page
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wp() {
		if ( self::is_sml_page() ) {
			do_action( 'login_init' );

			remove_action( 'wp_head', 'feed_links',                       2 );
			remove_action( 'wp_head', 'feed_links_extra',                 3 );
			remove_action( 'wp_head', 'rsd_link'                            );
			remove_action( 'wp_head', 'wlwmanifest_link'                    );
			remove_action( 'wp_head', 'parent_post_rel_link',            10 );
			remove_action( 'wp_head', 'start_post_rel_link',             10 );
			remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
			remove_action( 'wp_head', 'rel_canonical'                       );

			// Don't index any of these forms
			add_action( 'login_head', 'wp_no_robots' );

			if ( force_ssl_admin() && ! is_ssl() ) {
				if ( 0 === strpos( $_SERVER['REQUEST_URI'], 'http' ) ) {
					wp_redirect( preg_replace( '|^http://|', 'https://', $_SERVER['REQUEST_URI'] ) );
					exit;
				} else {
					wp_redirect( 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
					exit;
				}
			}
		}
	}

	/**
	 * Proccesses the request
	 *
	 * Callback for "template_redirect" hook in template-loader.php
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function template_redirect() {
		$this->request_action = isset( $_REQUEST['action'] ) ? sanitize_key( $_REQUEST['action'] ) : '';
		if ( ! $this->request_action && self::is_sml_page() ) {
			$this->request_action = self::get_page_action( get_the_ID() );
		}
		$this->request_template_num = isset( $_REQUEST['template_num'] ) ? sanitize_key( $_REQUEST['template_num'] ) : 0;

		if ( $this->is_settings_imcomplete() ) {
			if ( $this->request_action ) {
				wp_redirect( get_home_url( '/' ) );
				exit;
			}
			return;
		}

		do_action_ref_array( 'sml_request', array( &$this ) );

		if ( has_action( 'sml_request_' . $this->request_action ) ) {
			do_action_ref_array( 'sml_request_' . $this->request_action, array( &$this ) );
		} else {
			$is_post = ( 'POST' == $_SERVER['REQUEST_METHOD'] );

			switch ( $this->request_action ) {
				case 'logout' :
					$sml_sid = $this->session->get( 'sml_sid' );
					$area_title = $this->get_option( 'area_title' );
					if ( $sml_sid ) {
						$result = $this->spiral->logout_area( $area_title, $sml_sid );
						$this->session->set( 'sml_sid', null );
					}
					//$url = self::get_page_link( 'login', 'loggedout=true' );
					$url = get_home_url( '/' );
					$redirect_to = apply_filters( 'sml_logout_redirect', $url, isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '' );
					wp_redirect( $redirect_to );
					exit;
				case 'register' :
					if ( $register_url = $this->get_option( 'register_url' ) ) {
						wp_redirect( $register_url );
						exit;
					} else {
						wp_redirect( get_home_url( '/' ) );
						exit;
					}
					break;
				case 'lostpassword' :
					if ( $lostpassword_url = $this->get_option( 'lostpassword_url' ) ) {
						wp_redirect( $lostpassword_url );
						exit;
					} else {
						wp_redirect( get_home_url( '/' ) );
						exit;
					}
					break;
				case 'resetpass' :
				case 'withdrawal' :
				case 'profile' :
					$page_id = $this->get_option( $this->request_action . '_page_id' );
					if ( $this->is_logged_in() ) {
						$sml_sid = $this->session->get( 'sml_sid' );
						$area_title = $this->get_option( 'area_title' );
						if ( $page_id ) {
							$result = $this->spiral->get_area_mypage( $area_title, $sml_sid, $page_id );
							if ( $result ) {
								wp_redirect( $result );
								exit;
							}
						}
						wp_redirect( get_home_url( '/' ) );
						exit;
					} else {
						if ( $page_id ) {
							wp_redirect( self::get_page_link( 'login', 'expired=true' ) );
						} else {
							wp_redirect( get_home_url( '/' ) );
						}
						exit;
					}
					break;
				case 'login' :
				default:
					if ( $is_post && isset( $_REQUEST['sml-sid'] ) ) {
						$sml_sid = $_REQUEST['sml-sid'];
						if ( preg_match( "/^[0-9A-F]+$/", $sml_sid ) !== 1 || !$this->is_logged_in( $sml_sid ) ) {
							$username = isset( $_REQUEST['login_id'] ) ? esc_html( stripslashes( $_REQUEST['login_id'] ) ) : '';
							$this->errors = new WP_Error( 'incorrect_password', sprintf( __( '<strong>ERROR</strong>: The password you entered for the username <strong>%1$s</strong> is incorrect. <a href="%2$s" title="Password Lost and Found">Lost your password</a>?' ), $username, self::get_page_link( 'lostpassword' ) ) );
							break;
						}
						$this->session->set( 'sml_sid', $sml_sid );

						$redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';
						if ( empty( $redirect_to ) || strpos( $redirect_to, '/' ) != 0 ) {
							$redirect_to = get_home_url( '/' );
						}
						wp_redirect( $redirect_to );
						exit;
					}

					if ( isset( $_REQUEST['sml-error'] ) && isset( $_REQUEST['code'] ) ) {
						$error_code = (int)$_REQUEST['code'];
						switch ( $error_code ) {
							case 10:
								$this->errors->add( 'error', __( 'Error occurred', self::domain ) );
								break;
							case 20:
								$this->errors->add( 'error', __( 'Enter Username', self::domain ) );
								break;
							case 21:
								$this->errors->add( 'error', __( 'Enter Password', self::domain ) );
								break;
							case 30:
								$this->errors->add( 'error', __( 'Authentication failed', self::domain ) );
								break;
						}
					}

					if ( !$this->is_logged_in() ) {
						$this->session->set( 'sml_sid', null );

						if ( self::is_member_page( get_the_ID() ) ) {
							// for member page
							$args = array(
								'memberpage' => 'true',
								'redirect_to' => self::get_current_path()
							);
							wp_redirect( self::get_page_link( 'login', $args ) );
							exit;
						}
					}

					if ( isset( $_GET['loggedout'] ) && true == $_GET['loggedout'] )
						$this->errors->add( 'loggedout', __( 'You are now logged out.' ), 'message' );
					elseif ( isset( $_GET['expired'] ) && true == $_GET['expired'] )
						$this->errors->add('expired', __('Session expired. Please log in again. You will not move away from this page.'), 'message');

					break;
			} // end switch
		}
	}

	/**
	 * Calls "login_head" hook on login page
	 *
	 * Callback for "wp_head" hook
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wp_head() {
		if ( self::is_sml_page() ) {
			// This is already attached to "wp_head"
			remove_action( 'login_head', 'wp_print_head_scripts', 9 );

			do_action( 'login_head' );
		}
	}

	/**
	 * Calls "login_footer" hook on login page
	 *
	 * Callback for "wp_footer" hook
	 *
	 * @since 1.0.0
	 */
	public function wp_footer() {
		if ( self::is_sml_page() ) {
			// This is already attached to "wp_footer"
			remove_action( 'login_footer', 'wp_print_footer_scripts', 20 );

			do_action( 'login_footer' );
		}
	}

	/**
	 * Prints javascript in the footer
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wp_print_footer_scripts() {
		if ( ! self::is_sml_page() ) {
			return;
		}
	}


	/************************************************************************************************************************
	 * Filters
	 ************************************************************************************************************************/

	/**
	 * Alters menu item title & link according to whether user is logged in or not
	 *
	 * Callback for "wp_setup_nav_menu_item" hook in wp_setup_nav_menu_item()
	 *
	 * @see wp_setup_nav_menu_item()
	 * @since 1.0.0
	 * @access public
	 *
	 * @param object $menu_item The menu item
	 * @return object The (possibly) modified menu item
	 */
	public function wp_setup_nav_menu_item( $menu_item ) {
		if ( is_admin() )
			return $menu_item;

		if ( 'page' == $menu_item->object && self::is_sml_page( 'login', $menu_item->object_id ) ) {
			if ( $this->is_logged_in() ) {
				$menu_item->title = $this->get_template()->get_title( 'logout' );
				$menu_item->url   = self::get_page_link( 'logout' );
			}
		}
		return $menu_item;
	}

	/**
	 * Excludes pages from wp_list_pages
	 *
	 * @since 1.0.0
	 *
	 * @param array $exclude Page IDs to exclude
	 * @return array Page IDs to exclude
	 */
	public function wp_list_pages_excludes( $exclude ) {
		$pages = get_posts( array(
			'post_type'      => 'page',
			'post_status'    => 'any',
			'meta_key'       => '_sml_action',
			'posts_per_page' => -1
		) );
		$pages = wp_list_pluck( $pages, 'ID' );

		return array_merge( $exclude, $pages );
	}

	/**
	 * Adds nonce to logout link
	 *
	 * @since 1.0.0
	 *
	 * @param string $link Page link
	 * @param int $post_id Post ID
	 * @return string Page link
	 */
	public function page_link( $link, $post_id ) {
		if ( self::is_sml_page( 'logout', $post_id ) )
			$link = add_query_arg( '_wpnonce', wp_create_nonce( 'log-out' ), $link );
		return $link;
	}


	/************************************************************************************************************************
	 * Utilities
	 ************************************************************************************************************************/

	/**
	 * Is this plugin with imcomplete settings
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return bool True if settings is imcomplete
	 */
	public function is_settings_imcomplete() {
		$token = $this->get_option( 'api_token' );
		$token_secret = $this->get_option( 'api_token_secret' );
		$auth_form_url = $this->get_option( 'auth_form_url' );

		return ( empty( $token ) || empty( $token_secret ) || empty( $auth_form_url ) );
	}

	/**
	 * Handler for "sml-show-template" shortcode
	 *
	 * Optional $atts contents:
	 *
	 * - template_num - A unqiue template number for this instance.
	 * - default_action - The action to display. Defaults to "login".
	 * - login_template - The template used for the login form. Defaults to "login-form.php".
	 * - user_template - The templated used for when a user is logged in. Defalts to "user-panel.php".
	 * - show_title - True to display the current title, false to hide. Defaults to true.
	 * - show_reg_link - True to display the register link, false to hide. Defaults to true.
	 * - show_pass_link - True to display the lost password link, false to hide. Defaults to true.
	 * - logged_in_widget - True to display the widget when logged in, false to hide. Defaults to true.
	 * - logged_out_widget - True to display the widget when logged out, false to hide. Defaults to true.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string|array $atts Attributes passed from the shortcode
	 * @return string HTML output from Spiral_Member_Login_Template->display()
	 */
	public function shortcode_show_template( $atts = '' ) {
		static $did_main_template = false;

		$atts = wp_parse_args( $atts );

		if ( ! isset( $atts['name_key'] ) && $this->get_option( 'default_name_key' ) ) {
			$atts['name_key'] = $this->get_option( 'default_name_key' );
		}
		if ( ! $this->get_option( 'register_url' ) ) {
			$atts['show_reg_link'] = false;
		}
		if ( ! $this->get_option( 'lostpassword_url' ) ) {
			$atts['show_pass_link'] = false;
		}
		if ( ! $this->get_option( 'profile_page_id' ) ) {
			$atts['show_profile_link'] = false;
		}
		if ( ! $this->get_option( 'resetpass_page_id' ) ) {
			$atts['show_resetpass_link'] = false;
		}
		if ( ! $this->get_option( 'withdrawal_page_id' ) ) {
			$atts['show_withdrawal_link'] = false;
		}

		if ( self::is_sml_page() && in_the_loop() && is_main_query() && ! $did_main_template ) {
			$template = $this->get_template();

			if ( ! empty( $this->request_template_num ) )
				$template->set_active( false );

			if ( ! empty( $this->request_action ) )
				$atts['default_action'] = $this->request_action;

			if ( ! isset( $atts['show_title'] ) )
				$atts['show_title'] = false;

			foreach ( $atts as $option => $value ) {
				$template->set_option( $option, $value );
			}

			$did_main_template = true;
		} else {
			$template = $this->load_template( $atts );
		}
		return $template->display();
	}

	public function shortcode_is_logged_in( $atts, $content = null ) {
		if ( $this->is_settings_imcomplete() ) {
			return '';
		}

		if ( !$this->is_logged_in() ) {
			return '';
		}
		return do_shortcode( $content );
	}

	public function shortcode_user_prop( $atts ) {
		if ( $this->is_settings_imcomplete() ) {
			return '';
		}

		extract( shortcode_atts( array(
			'key' => 'name'
		), $atts ) );
		return $this->get_user_prop( $key );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Returns default options
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Default options
	 */
	public static function default_options() {
		return apply_filters( 'sml_default_options', array(
			'api_token' => '',
			'api_token_secret' => '',
			'area_title' => 'wpmls_area',
			'auth_form_url' => '',
			'member_list_search_title' => 'wpmls_searchform',
			'default_name_key' => 'name',
			'register_url' => '',
			'lostpassword_url' => '',
			'profile_page_id' => '',
			'resetpass_page_id' => '',
			'withdrawal_page_id' => ''
		) );
	}

	/**
	 * Returns default pages
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Default pages
	 */
	public static function default_pages() {
		return apply_filters( 'sml_default_pages', array(
			'login'        => __( 'Log In' ),
			'logout'       => __( 'Log Out' ),
			'profile'      => __( 'Profile', self::domain ),
			'lostpassword' => __( 'Lost Password', self::domain ),
			'resetpass'    => __( 'Reset Password', self::domain ),
			'register'     => __( 'Register', self::domain ),
			'withdrawal'   => __( 'Withdrawal', self::domain )
		) );
	}

	/**
	 * Retrieves active template object
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return object Instance object
	 */
	public function get_active_template() {
		return $this->get_template( (int) $this->request_template_num );
	}

	/**
	 * Retrieves a loaded template object
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param int $num Instance number
	 * @return object Instance object

	 */
	public function get_template( $num = 0 ) {
		if ( isset( $this->loaded_templates[$num] ) )
			return $this->loaded_templates[$num];
	}

	/**
	 * Sets an template object
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param object $object Instance object
	 */
	public function set_template( $object ) {
		$this->loaded_templates[] =& $object;
	}

	/**
	 * Instantiates an template
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array|string $args Array or query string of arguments

	 * @return object Instance object
	 */
	public function load_template( $args = '' ) {
		$args['template_num'] = count( $this->loaded_templates );

		$template = new Spiral_Member_Login_Template( $args );

		if ( $args['template_num'] == $this->request_template_num ) {
			$template->set_active();
			$template->set_option( 'default_action', $this->request_action );
		}

		$this->loaded_templates[] = $template;

		return $template;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), self::domain );

		load_textdomain( self::domain, WP_LANG_DIR . '/' . self::domain . '/' . self::domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( self::domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
	}

	/**
	 * Save plugin settings
	 *
	 * This is the callback for register_setting()
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string|array $inputs Settings passed in from filter
	 * @return string|array Sanitized settings
	 */
	public function save_settings( $inputs ) {
		$options = $this->get_options();

		$options['api_token'] = sanitize_text_field( trim( $inputs['api_token'] ) );
		$options['api_token_secret'] = sanitize_text_field( trim( $inputs['api_token_secret'] ) );

		$token_pattern = '/^[0-9a-zA-Z_\-]+$/';
		$secret_pattern = '/^[0-9a-zA-Z_\-]+$/';

		$error_messages = array();

		if ( !preg_match( $token_pattern, $options['api_token'] ) ) {
			$options['api_token'] = '';
			$error_messages[] = __( 'Enter a valid API token', self::domain );
		}

		if ( !preg_match( $secret_pattern, $options['api_token_secret'] ) ) {
			$options['api_token_secret'] = '';
			$error_messages[] = __( 'Enter a valid API token secret', self::domain );
		}

		$options['area_title'] = sanitize_text_field( trim( $inputs['area_title'] ) );
		$options['auth_form_url'] = sanitize_text_field( trim( $inputs['auth_form_url'] ) );

		if ( !$options['area_title'] ) {
			unset( $options['area_title'] );
			$error_messages[] = __( 'Enter area title', self::domain );
		}
		if ( !$options['auth_form_url'] ) {
			$options['auth_form_url'] = '';
			$error_messages[] = __( 'Enter authentication form url', self::domain );
		}

		$options['member_list_search_title'] = sanitize_text_field( trim( $inputs['member_list_search_title'] ) );
		$options['default_name_key'] = sanitize_text_field( trim( $inputs['default_name_key'] ) );

		if ( !$options['member_list_search_title'] ) {
			unset( $options['member_list_search_title'] );
			$error_messages[] = __( 'Enter member list search title', self::domain );
		}
		if ( !$options['default_name_key'] ) {
			unset( $options['default_name_key'] );
			$error_messages[] = __( 'Enter default name key', self::domain );
		}

		$options['register_url'] = sanitize_text_field( trim( $inputs['register_url'] ) );
		$options['lostpassword_url'] = sanitize_text_field( trim( $inputs['lostpassword_url'] ) );
		$options['profile_page_id'] = ( $inputs['profile_page_id'] != '' ) ? absint( trim( $inputs['profile_page_id'] ) ) : '';
		$options['resetpass_page_id'] = ( $inputs['resetpass_page_id'] != '' ) ? absint( trim( $inputs['resetpass_page_id'] ) ) : '';
		$options['withdrawal_page_id'] = ( $inputs['withdrawal_page_id'] != '' ) ? absint( trim( $inputs['withdrawal_page_id'] ) ) : '';

		if ( !empty( $error_messages ) ) {
			$error_message = implode( '<br/>', $error_messages );
			add_settings_error( $this->options_key, $this->plugin_slug, $error_message );
		}

		return $options;
	}

	/**
	 * Install plugin
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function install() {
		// Current version
		$version = $this->get_option( 'version', self::version );

		// Setup default pages
		foreach ( self::default_pages() as $action => $title ) {
			if ( ! $page_id = self::get_page_id( $action ) ) {
				$page_id = wp_insert_post( array(
					'post_title'     => $title,
					'post_name'      => $action,
					'post_status'    => 'publish',
					'post_type'      => 'page',
					'post_content'   => '[sml-show-template]',
					'comment_status' => 'closed',
					'ping_status'    => 'closed'
				) );
				update_post_meta( $page_id, '_sml_action', $action );
			}
		}

		$this->set_option( 'version', self::version );
		$this->save_options();
	}

	/**
	 * Returns current URL
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $query Optionally append query to the current URL
	 * @return string URL with optional path appended
	 */
	public static function get_current_url( $query = '' ) {
		$url = remove_query_arg( array( 'template_num', 'action', 'error', 'loggedout', 'redirect_to', 'updated', 'key', '_wpnonce', 'login' ) );

		if ( ! empty( $_REQUEST['template_num'] ) )
			$url = add_query_arg( 'template_num', $_REQUEST['template_num'] );

		if ( ! empty( $query ) ) {
			$r = wp_parse_args( $query );
			foreach ( $r as $k => $v ) {
				if ( strpos( $v, ' ' ) !== false )
					$r[$k] = rawurlencode( $v );
			}
			$url = add_query_arg( $r, $url );
		}
		return $url;
	}

	public static function get_current_path( $query = '' ) {
		$url = self::get_current_url( $query );
		$home_url = get_home_url( '/' );
		return str_replace( $home_url, '', $url );
	}

	/**
	 * Returns link for a login page
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $action The action
	 * @param string|array $query Optional. Query arguments to add to link
	 * @return string Login page link with optional $query arguments appended
	 */
	public static function get_page_link( $action, $query = '' ) {
		$page_id = self::get_page_id( $action );

		if ( $page_id ) {
			$link = get_permalink( $page_id );
		} elseif ( $page_id = self::get_page_id( 'login' ) ) {
			$link = add_query_arg( 'action', $action, get_permalink( $page_id ) );
		} else {
			$link = get_home_url( '/' );
		}

		if ( ! empty( $query ) ) {
			$args = wp_parse_args( $query );

			if ( isset( $args['action'] ) && $action == $args['action'] ) {
				unset( $args['action'] );
			}

			$link = add_query_arg( array_map( 'rawurlencode', $args ), $link );
		}

		// Respect FORCE_SSL_LOGIN
		if ( 'login' == $action && force_ssl_login() ) {
			$link = preg_replace( '|^http://|', 'https://', $link );
		}

		return apply_filters( 'sml_page_link', $link, $action, $query );
	}

	/**
	 * Retrieves a page ID for an action
	 *
	 * @since 1.0.0
	 *
	 * @param string $action The action
	 * @return int|bool The page ID if exists, false otherwise
	 */
	public static function get_page_id( $action ) {
		global $wpdb;

		if ( ! $page_id = wp_cache_get( $action, 'sml_page_ids' ) ) {
			$page_id = $wpdb->get_var( $wpdb->prepare( "SELECT p.ID FROM $wpdb->posts p LEFT JOIN $wpdb->postmeta pmeta ON p.ID = pmeta.post_id WHERE p.post_type = 'page' AND pmeta.meta_key = '_sml_action' AND pmeta.meta_value = %s", $action ) );
			if ( ! $page_id ) {
				return null;
			}
			wp_cache_add( $action, $page_id, 'sml_page_ids' );
		}
		return $page_id;
	}

	/**
	 * Get the action for a page
	 *
	 * @since 1.0.0
	 *
	 * @param int|object Post ID or object
	 * @return string|bool Action name if exists, false otherwise
	 */
	public static function get_page_action( $page ) {
		if ( ! $page = get_post( $page ) )
			return false;

		return get_post_meta( $page->ID, '_sml_action', true );
	}

	/**
	 * Determines if $action is for $page
	 *
	 * @since 1.0.0
	 *
	 * @param string $action The action to check
	 * @param int|object Post ID or object
	 * @return bool True if $action is for $page, false otherwise
	 */
	public static function is_sml_page( $action = '', $page = '' ) {
		if ( ! $page = get_post( $page ) )
			return false;

		if ( 'page' != $page->post_type )
			return false;

		if ( ! $page_action = self::get_page_action( $page->ID ) )
			return false;

		if ( empty( $action ) || $action == $page_action )
			return true;

		return false;
	}

	public static function is_member_page( $page = '' ) {
		if ( !$post = get_post( $page ) ) {
			return false;
		}

		if ( $post->post_type != 'page' ) {
			return false;
		}

		return get_post_meta( $post->ID, 'sml-member-page', true ) == 'true';
	}

	/**
	 * Renders api token settings field
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function settings_field_api_token() {
		?>
		<input name="spiral_member_login[api_token]" type="text" id="spiral_member_login_api_token" class="sml_token_field" value="<?php esc_attr_e( $this->get_option( 'api_token' ) ); ?>" />
		<?php
	}

	/**
	 * Renders api token secret settings field
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function settings_field_api_token_secret() {
		?>
		<input name="spiral_member_login[api_token_secret]" type="text" id="spiral_member_login_api_token_secret" class="sml_token_field" value="<?php esc_attr_e( $this->get_option( 'api_token_secret' ) ); ?>" />
		<?php
	}

	public function settings_field_register_url() {
		?>
		<input name="spiral_member_login[register_url]" type="text" id="spiral_member_login_register_url" class="sml_url_field" value="<?php esc_attr_e( $this->get_option( 'register_url' ) ); ?>" />
		<?php
	}

	public function settings_field_lostpassword_url() {
		?>
		<input name="spiral_member_login[lostpassword_url]" type="text" id="spiral_member_login_lostpassword_url" class="sml_url_field" value="<?php esc_attr_e( $this->get_option( 'lostpassword_url' ) ); ?>" />
		<?php
	}

	public function settings_field_area_title() {
		?>
		<input name="spiral_member_login[area_title]" type="text" id="spiral_member_login_area_title" class="sml_title_field" value="<?php esc_attr_e( $this->get_option( 'area_title' ) ); ?>" />
		<?php
	}

	public function settings_field_default_name_key() {
		?>
		<input name="spiral_member_login[default_name_key]" type="text" id="spiral_member_login_default_name_key" class="sml_title_field" value="<?php esc_attr_e( $this->get_option( 'default_name_key' ) ); ?>" />
		<?php
	}

	public function settings_field_profile_page_id() {
		?>
		<input name="spiral_member_login[profile_page_id]" type="text" id="spiral_member_login_profile_page_id" class="sml_id_field" value="<?php esc_attr_e( $this->get_option( 'profile_page_id' ) ); ?>" />
		<?php
	}

	public function settings_field_resetpass_page_id() {
		?>
		<input name="spiral_member_login[resetpass_page_id]" type="text" id="spiral_member_login_resetpass_page_id" class="sml_id_field" value="<?php esc_attr_e( $this->get_option( 'resetpass_page_id' ) ); ?>" />
		<?php
	}

	public function settings_field_withdrawal_page_id() {
		?>
		<input name="spiral_member_login[withdrawal_page_id]" type="text" id="spiral_member_login_withdrawal_page_id" class="sml_id_field" value="<?php esc_attr_e( $this->get_option( 'withdrawal_page_id' ) ); ?>" />
		<?php
	}

	public function settings_field_member_list_search_title() {
		?>
		<input name="spiral_member_login[member_list_search_title]" type="text" id="spiral_member_login_member_list_search_title" class="sml_title_field" value="<?php esc_attr_e( $this->get_option( 'member_list_search_title' ) ); ?>" />
		<?php
	}

	public function settings_field_auth_form_url() {
		?>
		<input name="spiral_member_login[auth_form_url]" type="text" id="spiral_member_login_auth_form_url" class="sml_url_field" value="<?php esc_attr_e( $this->get_option( 'auth_form_url' ) ); ?>" />
		<?php
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Uninstall the plugin
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected static function _uninstall() {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		$pages = get_posts( array(
			'post_type'      => 'page',
			'post_status'    => 'any',
			'meta_key'       => '_sml_action',
			'posts_per_page' => -1
		) );

		// Delete pages
		foreach ( $pages as $page ) {
			wp_delete_post( $page->ID, true );
		}

		// Delete options
		delete_option( 'spiral_member_login' );
		delete_option( 'widget_spiral_member_login' );
	}

	public function is_logged_in( $area_session_id = null ) {
		if ( $area_session_id == null ) {
			$area_session_id = $this->session->get( 'sml_sid' );
			if ( $area_session_id == null ) {
				return false;
			}
		}
		$area_title = $this->get_option( 'area_title' );
		$result = $this->spiral->get_area_status( $area_title, $area_session_id );
		return $result === true;
	}

	public function get_user_props() {
		if ( !$this->is_logged_in() ) {
			return null;
		}

		$area_title = $this->get_option( 'area_title' );
		$area_session_id = $this->session->get( 'sml_sid' );
		$search_title = $this->get_option( 'member_list_search_title' );
		$result = $this->spiral->get_table_data( $area_title, $area_session_id, $search_title );
		if ( $result == null || (int)$result['count'] != 1 ) {
			return null;
		}

		$header = $result['header'];
		$data = $result['data'][0];

		$user_props = array();
		foreach ( $header as $i => $key ) {
			$user_props[$key] = $data[$i];
		}
		return $user_props;
	}

	public function get_user_prop( $key = 'name' ) {
		$props = $this->get_user_props();
		if ( !$props ) {
			return null;
		}
		if ( $key == 'name' ) {
			if ( isset( $props['firstName'] ) && isset( $props['lastName'] ) ) {
				return $props['lastName'] . ' ' . $props['firstName'];
			} elseif ( isset( $props['name'] ) ) {
				return $props['name'];
			} else {
				return null;
			}
		}
		return isset( $props[$key] ) ? $props[$key] : null;
	}
}

endif; // Class exists

