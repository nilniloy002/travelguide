<?php
/**
 * Holds the Spiral Member Login Template class
 *
 * @package   Spiral_Member_Login
 * @author    PIPED BITS Co.,Ltd.
 */

if ( ! class_exists( 'Spiral_Member_Login_Template' ) ) :
/*
 * Spiral Member Login Template class
 *
 * This class contains properties and methods common to displaying output.
 *
 * @since 1.0.0
 */
class Spiral_Member_Login_Template extends Spiral_Member_Login_Base {
	/**
	 * Holds active instance flag
	 *
	 * @since 1.0.0
	 * @access private
	 * @var bool
	 */
	private $is_active = false;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $options Instance options
	 */
	public function __construct( $options = '' ) {
		$options = wp_parse_args( $options );
		$options = shortcode_atts( self::default_options(), $options );

		$this->set_options( $options );
	}

	/**
	 * Retrieves default options
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Default options
	 */
	public static function default_options() {
		return array(
			'template_num'          => 0,
			'default_action'        => '',
			'login_template'        => '',
			'user_template'         => '',
			'name_key'              => 'name',
			'show_name'             => true,
			'show_title'            => true,
			'show_reg_link'         => true,
			'show_pass_link'        => true,
			'show_profile_link'     => true,
			'show_resetpass_link'   => true,
			'show_withdrawal_link'  => true,
			'logged_in_widget'      => true,
			'logged_out_widget'     => true,
			'before_widget'         => '',
			'after_widget'          => '',
			'before_title'          => '',
			'after_title'           => ''
		);
	}

	/**
	 * Displays output according to current action
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string HTML output
	 */
	public function display( $action = '' ) {
		if ( empty( $action ) )
			$action = $this->get_option( 'default_action' );

		$spiral_member_login = Spiral_Member_Login::get_instance();

		if ( $spiral_member_login->is_settings_imcomplete() ) {
			if ( current_user_can( 'manage_options' ) ) {
				ob_start();
				echo $this->get_option( 'before_widget' );
				echo '<div class="error"><strong>Error</strong>: WP Member Login by SPIRAL plugin error. </div>';
				echo $this->get_option( 'after_widget' ) . "\n";
				$output = ob_get_contents();
				ob_end_clean();
				return apply_filters_ref_array( 'sml_display', array( $output, $action, &$this ) );
			}
			return;
		}

		ob_start();
		echo $this->get_option( 'before_widget' );
		if ( $this->get_option( 'show_title' ) ) {
			echo $this->get_option( 'before_title' ) . $this->get_title( $action ) . $this->get_option( 'after_title' ) . "\n";
		}
		if ( has_action( 'sml_display_' . $action ) ) {
			do_action_ref_array( 'sml_display_' . $action, array( &$this ) );
		} else {
			$templates = array();
			if ( $spiral_member_login->is_logged_in() ) {
				if ( $this->get_option( 'user_template' ) ) {
					$templates[] = $this->get_option( 'user_template' );
				}
				$templates[] = 'user_info.php';
			} else {
				if ( $this->get_option( 'login_template' ) ) {
					$templates[] = $this->get_option( 'login_template' );
				}
				$templates[] = 'login.php';
			}
			$this->get_template( $templates );
		}
		echo $this->get_option( 'after_widget' ) . "\n";
		$output = ob_get_contents();
		ob_end_clean();

		return apply_filters_ref_array( 'sml_display', array( $output, $action, &$this ) );
	}

	/**
	 * Returns action title
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $action The action to retrieve. Defaults to current action.
	 * @return string Title of $action
	 */
	public function get_title( $action = '' ) {
		if ( empty( $action ) )
			$action = $this->get_option( 'default_action' );

		if ( is_admin() )
			return;

		$spiral_member_login = Spiral_Member_Login::get_instance();
		if ( $spiral_member_login->is_logged_in() && 'login' == $action && $action == $this->get_option( 'default_action' ) ) {
			$title = __( 'Log In' );
		} else {
			if ( $page_id = Spiral_Member_Login::get_page_id( $action ) ) {
				$title = get_post_field( 'post_title', $page_id );
			} else {
				switch ( $action ) {
					case 'register':
						$title = __( 'Register', Spiral_Member_Login::domain );
						break;
					case 'lostpassword':
						$title = __( 'Lost Password', Spiral_Member_Login::domain );
						break;
					case 'profile':
						$title = __( 'Profile', Spiral_Member_Login::domain );
						break;
					case 'resetpass':
						$title = __( 'Reset Password', Spiral_Member_Login::domain );
						break;
					case 'withdrawal':
						$title = __( 'Withdrawal', Spiral_Member_Login::domain );
						break;
					case 'login':
					default:
						$title = __( 'Log In' );
				}
			}
		}
		return apply_filters( 'sml_title', $title, $action );
	}

	/**
	 * Outputs action title
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $action The action to retieve. Defaults to current action.
	 */
	public function the_title( $action = '' ) {
		echo $this->get_title( $action );
	}

	/**
	 * Returns plugin errors
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_errors() {
		global $error;

		$spiral_member_login = Spiral_Member_Login::get_instance();

		$wp_error =& $spiral_member_login->errors;

		if ( empty( $wp_error ) )
			$wp_error = new WP_Error();

		// Incase a plugin uses $error rather than the $errors object
		if ( ! empty( $error ) ) {
			$wp_error->add('error', $error);
			unset($error);
		}

		$output = '';
		if ( $this->is_active() ) {
			if ( $wp_error->get_error_code() ) {
				$errors = '';
				$messages = '';
				foreach ( $wp_error->get_error_codes() as $code ) {
					$severity = $wp_error->get_error_data( $code );
					foreach ( $wp_error->get_error_messages( $code ) as $error ) {
						if ( 'message' == $severity )
							$messages .= '    ' . $error . "<br />\n";
						else
							$errors .= '    ' . $error . "<br />\n";
					}
				}
				if ( ! empty( $errors ) )
					$output .= '<p class="error">' . apply_filters( 'login_errors', $errors ) . "</p>\n";
				if ( ! empty( $messages ) )
					$output .= '<p class="message">' . apply_filters( 'login_messages', $messages ) . "</p>\n";
			}
		}
		return $output;
	}

	/**
	 * Prints plugin errors
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function the_errors() {
		echo $this->get_errors();
	}

	/**
	 * Returns requested action URL
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $action Action to retrieve
	 * @return string The requested action URL
	 */
	public function get_action_url( $action = '' ) {

		$template_num = $this->get_option( 'template_num' );

		if ( $action == $this->get_option( 'default_action' ) ) {
			$args = array();
			if ( $template_num )
				$args['template_num'] = $template_num;
			$url = Spiral_Member_Login::get_current_url( $args );
		} else {
			$url = Spiral_Member_Login::get_page_link( $action );
		}

		// Respect FORCE_SSL_LOGIN
		if ( 'login' == $action && force_ssl_login() )
			$url = preg_replace( '|^http://|', 'https://', $url );

		return apply_filters( 'sml_action_url', $url, $action, $template_num );
	}

	/**
	 * Outputs requested action URL
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $action Action to retrieve
	 */
	public function the_action_url( $action = 'login' ) {
		echo esc_url( $this->get_action_url( $action ) );
	}

	/**
	 * Returns the action links
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $args Optionally specify which actions to include/exclude. By default, all are included.
	 */
	public function get_action_links( $args = '' ) {
		$args = wp_parse_args( $args, array(
			'register'     => true,
			'lostpassword' => true
		) );
		
		$action_links = array();
		if ( $args['register'] && $this->get_option( 'show_reg_link' ) ) {
			$action_links[] = array(
				'title' => $this->get_title( 'register' ),
				'url'   => $this->get_action_url( 'register' )
			);
		}
		if ( $args['lostpassword'] && $this->get_option( 'show_pass_link' ) ) {
			$action_links[] = array(
				'title' => $this->get_title( 'lostpassword' ),
				'url'   => $this->get_action_url( 'lostpassword' )
			);
		}
		return apply_filters( 'sml_action_links', $action_links, $args );
	}

	/**
	 * Outputs the action links
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $args Optionally specify which actions to include/exclude. By default, all are included.
	 */
	public function the_action_links( $args = '' ) {
		if ( $action_links = $this->get_action_links( $args ) ) {
			echo '<ul class="sml-action-links">' . "\n";
			foreach ( (array) $action_links as $link ) {
				echo '<li><a href="' . esc_url( $link['url'] ) . '" rel="nofollow">' . esc_html( $link['title'] ) . '</a></li>' . "\n";
			}
			echo '</ul>' . "\n";
		}
	}

	/**
	 * Returns logged-in user links
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Logged-in user links
	 */
	public function get_user_links() {
		$user_links = array();
		if ( $this->get_option( 'show_profile_link') ) {
			$user_links[] = array(
				'title' => self::get_title( 'profile' ),
				'url'   => $this->get_action_url( 'profile' )
			);
		}
		if ( $this->get_option( 'show_resetpass_link' ) ) {
			$user_links[] = array(
				'title' => $this->get_title( 'resetpass' ),
				'url'   => $this->get_action_url( 'resetpass' )
			);
		}
		if ( $this->get_option( 'show_withdrawal_link' ) ) {
			$user_links[] = array(
				'title' => $this->get_title( 'withdrawal' ),
				'url'   => $this->get_action_url( 'withdrawal' )
			);
		}
		return apply_filters( 'sml_user_links', $user_links );
	}

	/**
	 * Outputs logged-in user links
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function the_user_links() {
		$spiral_member_login = Spiral_Member_Login::get_instance();

		echo '<ul class="sml-user-links">';
		foreach ( (array) $this->get_user_links() as $link ) {
			echo '<li><a href="' . esc_url( $link['url'] ) . '">' . esc_html( $link['title'] ) . '</a></li>' . "\n";
		}
		echo '<li><a href="' . esc_url( Spiral_Member_Login::get_page_link( 'logout' ) ) . '">' . self::get_title( 'logout' ) . '</a></li>' . "\n";
		echo '</ul>';
	}

	/**
	 * Outputs logged-in user name
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function the_user_name() {
		if ( !$this->get_option( 'show_name' ) ) {
			return;
		}
		$spiral_member_login = Spiral_Member_Login::get_instance();
		$user_name = $spiral_member_login->get_user_prop( $this->get_option( 'name_key' ) );
		if ( $user_name ) {
			echo '<p>' . esc_html( $user_name ) . '</p>' . "\n";
		}
	}

	/**
	 * URL for authentication form
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function the_auth_form_url() {
		$spiral_member_login = Spiral_Member_Login::get_instance();
		$user_name = $spiral_member_login->get_option( 'auth_form_url' );
		if ( $user_name ) {
			echo esc_attr( $user_name );
		}
	}

	/**
	 * Locates specified template
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string|array $template_names The template(s) to locate
	 * @param bool $load If true, the template will be included if found
	 * @param array $args Array of extra variables to make available to template
	 * @return string|bool Template path if found, false if not
	 */
	public function get_template( $template_names, $load = true, $args = array() ) {

		$spiral_member_login = Spiral_Member_Login::get_instance();

		// User friendly access to this
		$template =& $this;

		// Easy access to current user
		$current_user = wp_get_current_user();

		extract( apply_filters_ref_array( 'sml_template_args', array( $args, &$this ) ) );

		if ( ! is_array( $template_names ) )
			$template_names = array( $template_names );

		if ( ! $found_template = locate_template( $template_names ) ) {
			foreach ( $template_names as $template_name ) {
				if ( file_exists( plugin_dir_path( __FILE__ ) . 'views/' . $template_name ) ) {
					$found_template = plugin_dir_path( __FILE__ ) . 'views/' . $template_name;
					break;
				}
			}
		}

		$found_template = apply_filters_ref_array( 'sml_template', array( $found_template, $template_names, &$this ) );

		if ( $load && $found_template ) {
			include( $found_template );
		}

		return $found_template;
	}

	/**
	 * Returns the proper redirect URL according to action
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $action The action
	 * @return string The redirect URL
	 */
	public function get_redirect_url( $action = '' ) {

		$spiral_member_login = Spiral_Member_Login::get_instance();

		if ( empty( $action ) )
			$action = $this->get_option( 'default_action' );

		$redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';

		$url = !empty( $redirect_to ) ? $redirect_to : Spiral_Member_Login::get_current_path();

		return apply_filters( 'sml_redirect_url', $url, $action );
	}

	/**
	 * Outputs redirect URL
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $action The action
	 */
	public function the_redirect_url( $action = '' ) {
		echo esc_attr( $this->get_redirect_url( $action ) );
	}

	/**
	 * Outputs current template instance ID
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function the_template_num() {
		if ( $this->get_option( 'template_num' ) )
			echo esc_attr( $this->get_option( 'template_num' ) );
	}

	/**
	 * Returns requested $value
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $value The value to retrieve
	 * @return string|bool The value if it exists, false if not
	 */
	public function get_posted_value( $value ) {
		if ( $this->is_active() && isset( $_REQUEST[$value] ) )
			return stripslashes( $_REQUEST[$value] );
		return false;
	}

	/**
	 * Outputs requested value
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $value The value to retrieve
	 */
	public function the_posted_value( $value ) {
		echo esc_attr( $this->get_posted_value( $value ) );
	}

	/**
	 * Returns active status
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return bool True if instance is active, false if not
	 */
	public function is_active() {
		return $this->is_active;
	}

	/**
	 * Sets active status
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param bool $active Active status
	 */
	public function set_active( $active = true ) {
		$this->is_active = $active;
	}
}

endif; // Class exists

