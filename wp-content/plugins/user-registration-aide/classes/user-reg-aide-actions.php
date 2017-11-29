<?php

/**
 * User Registration Aide - Actions & Filters
 * Plugin URI: http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/
 * Version: 1.5.0.2
 * Since Version 1.3.0
 * Author: Brian Novotny
 * Author URI: http://creative-software-design-solutions.com/
*/

//For Debugging and Testing Purposes ------------



// ----------------------------------------------

/*
 * Couple of includes for functionality
 *
 * @since 1.2.0
 * @updated 1.3.0
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

/**
 * Class for better functionality
 *
 * @category Class
 * @since 1.3.0
 * @updated 1.3.0
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class CSDS_URA_ACTIONS
{

	public static $instance;

	public function __construct() {
		$this->CSDS_URA_ACTIONS();
	}
		
	function CSDS_URA_ACTIONS() { //constructor
			
		self::$instance = $this;
	}
	
	/**
	 * Creates array for menu tabs titles
	 *
     * @since 1.3.0
     * @updated 1.4.0.0
	 * @returns array $tabs - array of menu tabs Titles for plugin
     * @access private
     * @author Brian Novotny
     * @website http://creative-software-design-solutions.com
    */
	
	function menu_tabs_array(){
		$tabs = array(
			'registration_fields' 			=> __( 'Registration Fields', 'csds_userRegAide' ),
			'edit_new_fields' 				=> __( 'Edit New Fields', 'csds_userRegAide' ),
			'registration_form_options' 	=> __( 'Registration Form Options', 'csds_userRegAide' ),
			'registration_form_css_options' => __( 'Registration Form Messages & CSS Options', 'csds_userRegAide' ),
			'custom_options'				=> __( 'Custom Options', 'csds_userRegAide' )
		);
		
		return $tabs;
	}
	
	/**
	 * Creates array for menu tabs titles
	 *
     * @since 1.4.0.0
     * @updated 1.5.0.2
	 * @returns array $tabs - array of menu tabs Titles for plugin
     * @access private
     * @author Brian Novotny
     * @website http://creative-software-design-solutions.com
    */
	
	function menu_titles_array(){
		$tabs = array(
			'registration_fields' 			=> __( 'Set Dashboard Widget Options & Select Fields to add to Registration Form or Add New Custom Fields Here', 'csds_userRegAide' ),
			'edit_new_fields' 				=> __( 'Edit New Fields For Registration Form/User Profile Here Like Field Order, Field Titles Or Delete Fields', 'csds_userRegAide' ),
			'registration_form_options' 	=> __( 'Customize Bottom Registration Form Message, Password Strength Options, Custom Redirects, Agreement Message, Anti-Bot Spammer & Title for Profile Pages Here', 'csds_userRegAide' ),
			'registration_form_css_options' => __( 'Customize Registration Form Messages & Custom Registration Form CSS Options Here', 'csds_userRegAide' ),
			'custom_options'				=> __( 'Password Change Settings Options, Change Display Name Options or URA Admin Page Style Sheet Settings Here', 'csds_userRegAide' )
		);
		
		return $tabs;
	}
	
	/**
	 * Creates array for menu tabs links
	 *
     * @since 1.3.0
     * @updated 1.4.0.0
	 * @returns array $menu_links - array of links for menu tabs
     * @access private
     * @author Brian Novotny
     * @website http://creative-software-design-solutions.com
    */
	
	function menu_links_array(){
		$menu_links = array(
			'registration_fields' 			=> 'admin.php?page=user-registration-aide', 
			'edit_new_fields' 				=> 'admin.php?page=edit-new-fields', 
			'registration_form_options'     => 'admin.php?page=registration-form-options', 
			'registration_form_css_options' => 'admin.php?page=registration-form-css-options',
			'custom_options' 				=> 'admin.php?page=custom-options'
		);
		
		return $menu_links;
	}
	
	/**
	 * Shows tabs menu at top of options pages with admin messages for easier user access -- wont work for different pages so i do it separately on each page
	 * @handles action for menu tabs 'create tabs' $ura line 246
     * @since 1.5.1.0
     * @updated 1.5.1.0
	 * @accepts string $current_page (current menu page)
	 * @accepts string $msg 
     * @access private
     * @author Brian Novotny
     * @website http://creative-software-design-solutions.com
    */
	
	function msg_options_tabs_page( $current_page, $msg ){
		
		$tabs = $this->menu_tabs_array();  // line 71 &$this
		$menu_links = $this->menu_links_array(); // line 88 &$this
		$tab_links = array();
		$titles = $this->menu_titles_array();
		foreach( $menu_links as $menu_key => $menu_name ){
			foreach( $tabs as $tab_key => $tab_name ){
			
				if( $menu_key == $tab_key && $tab_key == $current_page ){
					$tab_links[$tab_key] = '<a class="nav-tab nav-tab-active" title="'.$titles[$tab_key].'" href="'.admin_url($menu_name).'">'.$tab_name.'</a>';
				}elseif( $menu_key == $tab_key ){
					$tab_links[$tab_key] = '<a class="nav-tab" title="'.$titles[$tab_key].'" href="'.admin_url($menu_name).'">'.$tab_name.'</a>';
				}
			}
		}
		echo '<br/>';
		echo $msg;
		echo '</div>';
		echo '<h2>';
		foreach( $tab_links as $link_key => $link_name ){
			echo $link_name;
		}
		echo '</h2>';
		echo '<br/>';
	}
	
	/**
	 * Shows tabs menu at top of options pages for easier user access -- wont work for different pages so i do it separately on each page
	 * @handles action for menu tabs 'create tabs' $ura line 246
     * @since 1.3.0
     * @updated 1.4.0.0
	 * @accepts $current_page (current menu page)
     * @access private
     * @author Brian Novotny
     * @website http://creative-software-design-solutions.com
    */
	
	function options_tabs_page( $current_page){
		
		$tabs = $this->menu_tabs_array();  // line 71 &$this
		$menu_links = $this->menu_links_array(); // line 88 &$this
		$tab_links = array();
		$titles = $this->menu_titles_array();
		foreach( $menu_links as $menu_key => $menu_name ){
			foreach( $tabs as $tab_key => $tab_name ){
			
				if( $menu_key == $tab_key && $tab_key == $current_page ){
					$tab_links[$tab_key] = '<a class="nav-tab nav-tab-active" title="'.$titles[$tab_key].'" href="'.admin_url($menu_name).'">'.$tab_name.'</a>';
				}elseif( $menu_key == $tab_key ){
					$tab_links[$tab_key] = '<a class="nav-tab" title="'.$titles[$tab_key].'" href="'.admin_url($menu_name).'">'.$tab_name.'</a>';
				}
			}
		}
		echo '<h2>';
		foreach( $tab_links as $link_key => $link_name ){
			echo $link_name;
		}
		echo '</h2>';
		echo '<br/>';
	}
	
	/**
	 * One action for adding admin page wrappers
	 * @handles action for menu tabs 'create tabs' $ura line 246
     * @since 1.4.0.0
     * @updated 1.4.0.0
	 * @accepts $current_page (current menu page)
     * @access private
     * @author Brian Novotny
     * @website http://creative-software-design-solutions.com
    */
	
	function start_wp_wrapper( $tab, $form, $h2, $span, $nonce ){
		?>
		<div id="wpbody">
			<div class="wrap"> <?php
			do_action( 'create_tabs', $tab ); // Line 255 user-registration-aide.php
			?>
				<form method="<?php echo $form[0]; ?>" name="<?php echo $form[1]; ?>" id="<?php echo $form[1]; ?>">
				<h2 class="<?php echo $h2[0]; ?>"><?php _e( $h2[1], $h2[2] ); ?></h2>
					<div id="poststuff">
					<?php  //Form for dashboard widget options ?>
						<div class="stuffbox">
						<span class="<?php echo $span[0]; ?>"><?php _e( $span[1], $span[2] ); ?> </span>
							<div class="inside">
							<?php
							wp_nonce_field( $nonce[0], $nonce[1] );
	}
	
	/**
	 * One action for adding admin page wrappers with messages
	 * @handles action for menu tabs 'create tabs' $ura line 246
     * @since 1.4.0.0
     * @updated 1.4.0.0
	 * @accepts $current_page (current menu page)
     * @access private
     * @author Brian Novotny
     * @website http://creative-software-design-solutions.com
    */
	
	function start_wp_msg_wrapper( $msg, $tab, $form, $h2, $span, $nonce ){
		
		?>
		<div id="wpbody">
			
			<div class="wrap"> <?php
			
			do_action( 'create_msg_tabs', $tab, $msg ); // Line 255 user-registration-aide.php
			?>
				<form method="<?php echo $form[0]; ?>" name="<?php echo $form[1]; ?>" id="<?php echo $form[1]; ?>">
				<h2 class="<?php echo $h2[0]; ?>"><?php _e( $h2[1], $h2[2] ); ?></h2>
					<div id="poststuff">
					<?php  //Form for dashboard widget options ?>
						<div class="stuffbox">
						<span class="<?php echo $span[0]; ?>"><?php _e( $span[1], $span[2] ); ?> </span>
							<div class="inside">
							<?php
							wp_nonce_field( $nonce[0], $nonce[1] );
	}
	
	/**
	 * One action for ending admin page wrappers
	 * @handles action for menu tabs 'create tabs' $ura line 246
     * @since 1.4.0.0
     * @updated 1.4.0.0
	 * @accepts $current_page (current menu page)
     * @access private
     * @author Brian Novotny
     * @website http://creative-software-design-solutions.com
    */
	
	function end_wp_wrapper(){
		?>
							<div class="clear"></div></div> <?php // inside ?>
						<div class="clear"></div></div> <?php // stuffbox ?>
						<?php
						do_action('show_support');
						?>
					<div class="clear"></div></div> <?php // poststuff ?>
				</form>
			<div class="clear"></div></div> <?php // wrap ?>
		<div class="clear"></div></div> <?php // wpbody ?>
		
		<?php
		
	}
	
	/**
	 * One action for adding admin page mini wrappers
	 * @handles action for menu tabs 'create tabs' $ura line 246
     * @since 1.4.0.0
     * @updated 1.4.0.0
	 * @accepts $current_page (current menu page)
     * @access private
     * @author Brian Novotny
     * @website http://creative-software-design-solutions.com
    */
	
	function start_mini_wp_wrapper( $span ){
		?>
		<div class="stuffbox">
			<span class="<?php echo $span[0]; ?>"><?php _e( $span[1], $span[2] );?></span>
					<div class="inside">
		<?php
	}
	
	/**
	 * One action for ending admin page wrappers
	 * @handles action for menu tabs 'create tabs' $ura line 246
     * @since 1.4.0.0
     * @updated 1.4.0.0
	 * @accepts $current_page (current menu page)
     * @access private
     * @author Brian Novotny
     * @website http://creative-software-design-solutions.com
    */
	
	function end_mini_wp_wrapper(){
		?>
			<div class="clear"></div></div> <?php // stuffbox ?>
		<div class="clear"></div></div> <?php // inside ?>
		<?php
	}
	
	/**
	 * Shows support section for options pages
	 * @handles custom action for show support 'show_support' $ura line 240
     * @since 1.3.0
     * @updated 1.3.0
	 * @accepts $current_page (current menu page)
     * @access private
     * @author Brian Novotny
     * @website http://creative-software-design-solutions.com
    */
	
	function show_support_section(){ 
		$options = get_option('csds_userRegAide_Options');
		$span = array( 'regForm', __( 'Plugin Support and Configuration Information:', 'csds_userRegAide' ), 'csds_userRegAide' );
		do_action( 'start_mini_wrap',  $span ); ?>
		
			<table class="csds_support">
			<tr>
			<th class="csds_support_links" colspan="4"><a href="http://creative-software-design-solutions.com" target="_blank">Creative Software Design Solutions</a></th>
			</tr>
			<tr>
			<th class="csds_support_th" colspan="4"><?php _e( 'Please show your support & appreciation and help us out with a donation!', 'csds_userRegAide' );?></th>
			</tr>
			<tr>
			<td>
			<p><?php _e('Show Plugin Support: ', 'csds_userRegAide');?><input type="radio" id="csds_userRegAide_support" name="csds_userRegAide_support"  value="1" <?php
				if ($options['show_support'] == 1) echo 'checked' ;?>/><?php _e( 'Yes', 'csds_userRegAide' );?> 
				<input type="radio" id="csds_userRegAide_support" name="csds_userRegAide_support"  value="2"<?php
				if ($options['show_support'] == 2) echo 'checked' ;?>/><?php _e( 'No', 'csds_userRegAide' );?>
			
			<div class="submit">
			<input name="csds_userRegAide_support_submit" id="csds_userRegAide_support_submit" lang="publish" class="button-primary" value="Update" type="Submit" />
			</div>
			</td>
			<td>
			<h2 class="support"><?php _e( 'Plugin Configuration Help', 'csds_userRegAide' );?></h2>
			<?php
			echo '<ul>';
			echo '<li><a href="http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/" target="_blank">Plugin Page & Screenshots</a></li>';
			echo '</ul>';
			echo '</td>';
			echo '<td>';
			echo '<h2 class="support">'.__( 'Coming Soon 1.6.0.0 Lots of New Features!', 'csds_userRegAide' ).'</h2>';
			echo '<h2 class="support">'.__( 'Check Official Website', 'csds_userRegAide' ).'</h2>';
			echo '<ul>';
			echo '<li><a href="http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/" target="_blank">Check official website for live demo</a></li></ul></td>';?>
			<td>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_s-xclick" />
				<input type="hidden" name="hosted_button_id" value="6BCZESUXLS9NN" />
				<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" />
				<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1" />
				</form>
			</td>
			</tr>
			</table>
			
		<?php
		
	}
	
	/**
	 * Admin approval of user
	 * @since 1.5.1.1
	 * @updated 1.5.1.3
	 * @uses new_user_approve_approve_user
	 */
	public function ura_approve_user( $user_id ) {
		global $wpdb, $wp_hasher;
		
		$user = new WP_User( $user_id );
		$options = get_option('csds_userRegAide_Options');
		$user = new WP_User( $user_id );
		$fields = get_option('csds_userRegAide_registrationFields');
		$login_url = (string) '';
		$url = ( string ) '';
		$page = $options['xwrd_change_name'];
		$user_login = stripslashes( $user->user_login );
		$user_email = stripslashes( $user->user_email );
		$xwrd = ( string ) 'User Entered';
		$msg = ( string ) '';
		$message = ( string ) '';
		$blogname = get_option( 'blogname' );
		
		if( $options['xwrd_change_on_signup'] == 1 ){
			$url = site_url();
			$login_url = $url.'/'.$page.'/?action=new-register';
		}elseif( $options['xwrd_change_on_signup'] == 2 ){
			$login_url = wp_login_url() . "\r\n";
		}
		
		wp_cache_delete( $user->ID, 'users' );
		wp_cache_delete( $user->data->user_login, 'userlogins' );

		
		
		// format the message
		//$message = nua_default_approve_user_message();

		/*
		$message = nua_do_email_tags( $message, array(
			'context' => 'approve_user',
			'user' => $user,
			'user_login' => $user_login,
			'user_email' => $user_email,
		) );
		*/
		
		// send email to user telling of approval
		$message = sprintf( __( 'You have been approved to access %s', 'csds_userRegAide' ), $blogname ) . "\r\n\r\n";
		if( in_array( 'Password', $fields ) ){
				$message .= sprintf( __( 'Username: %s', 'csds_userRegAide' ), $user_login ) . "\r\n";
				$message .= sprintf( __( 'Password: %s', 'csds_userRegAide' ), $xwrd ) . "\r\n";
				$message .= sprintf( __( 'Login URL: %s', 'csds_userRegAide' ), $login_url ) ."\r\n\r\n";
		}elseif( !in_array( 'Password', $fields ) ){
			// Generate something random for a password reset key.
			$key = wp_generate_password( 20, false );

			/** This action is documented in wp-login.php */
			do_action( 'retrieve_password_key', $user->user_login, $key );

			// Now insert the key, hashed, into the DB.
			if ( empty( $wp_hasher ) ) {
				require_once ABSPATH . WPINC . '/class-phpass.php';
				$wp_hasher = new PasswordHash( 8, true );
			}
			
			$hashed = time() . ':' . $wp_hasher->HashPassword( $key );
			$wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user->user_login ) );
			$message .= sprintf( __( 'Username: %s', 'csds_userRegAide' ), $user->user_login ) . "\r\n\r\n";
			$url = network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user->user_login ), 'login' ) . "\r\n\r\n";
			$message .= sprintf( __( 'To set your password and validate your new user account, visit the following address: %s', 'csds_userRegAide'  ), $url );
		}
				 
		$message = apply_filters( 'new_user_approve_approve_user_message', $message, $user );
		$subject = sprintf( __( '[%s] Registration Approved', 'csds_userRegAide' ), get_option( 'blogname' ) );
		$subject = apply_filters( 'new_user_approve_approve_user_subject', $subject );

		// send the mail
		$pw_nua = pw_new_user_approve::instance();
		wp_mail( $user_email, $subject, $message, $pw_nua->email_message_headers() );
		unset( $pw_nua );
		// change usermeta tag in database to approved
		update_user_meta( $user->ID, 'pw_user_status', 'approved' );

		do_action( 'new_user_approve_user_approved', $user );
	}
	
	/**
	 * Message for New User Approve to of Registration form after users successfully registers
	 * @since 1.5.1.1
	 * @updated 1.5.1.3
	 * @uses new_user_approve_approve_user
	 */
	
	function ura_register_message( ){
		if( isset( $_GET['checkemail'] ) && 'registered' == $_GET['checkemail'] ){
			$message = '<p class="message register">'. __( 'After you register, your request will be sent to the site administrator for approval. You will then receive an email with further instructions.', 'csds_userRegAide' ).'</p>';
			return $message;
		}
	}
} // end class