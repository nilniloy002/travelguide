<?php
/**
 * Holds the Base class
 *
 * @package   Spiral_Member_Login
 * @author    PIPED BITS Co.,Ltd.
 */

if ( ! class_exists( 'Spiral_Member_Login_Base' ) ) :
/*
 * Base class
 *
 * This class is the base class to be extended.
 *
 * @since 1.0.0
 */
abstract class Spiral_Member_Login_Base {

	/**
	 * Holds options key
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var string
	 */
	protected $options_key;

	/**
	 * Holds options array
	 *
	 * Extending classes should explicity define options here
	 * or create a method named default_options() which returns
	 * an array of options.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var object
	 */
	protected $options = array();

	/**
	 * Loads options from DB
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array|string
	 */
	public function load_options() {
		if ( method_exists( $this, 'default_options' ) ) {
			$this->options = (array) $this->default_options();
		}

		if ( ! $this->options_key ) {
			return;
		}

		$options = get_option( $this->options_key, array() );
		$options = wp_parse_args( $options, $this->options );

		$this->options = $options;
	}

	/**
	 * Saves options to DB
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function save_options() {
		if ( $this->options_key ) {
			update_option( $this->options_key, $this->options );
		}
	}

	/**
	 * Retrieves an option
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string|array $option Name of option to retrieve or an array of hierarchy for multidimensional options
	 * @param mixed $default Default value to return if $option is not set
	 * @return mixed Value of requested option or $default if option is not set
	 */
	public function get_option( $option, $default = false ) {
		if ( ! is_array( $option ) ) {
			$option = array( $option );
		}
		return self::_get_option( $option, $default, $this->options );
	}

	/**
	 * Recursively retrieves a multidimensional option
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param array $option Array of hierarchy
	 * @param mixed $default Default value to return
	 * @param array Options to search
	 * @return mixed Value of requested option or $default if option is not set
	 */
	private function _get_option( $option, $default, &$options ) {
		$key = array_shift( $option );
		if ( ! isset( $options[$key] ) ) {
			return $default;
		}
		if ( ! empty( $option ) ) {
			return self::_get_option( $option, $default, $options[$key] );
		}
		return $options[$key];
	}

	/**
	 * Retrieves all options
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Options
	 */
	public function get_options() {
		return $this->options;
	}

	/**
	 * Sets an option
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $option Name of option to set or an array of hierarchy for multidimensional options
	 * @param mixed $value Value of new option
	 */
	public function set_option( $option, $value = '' ) {
		if ( ! is_array( $option ) ) {
			$option = array( $option );
		}

		self::_set_option( $option, $value, $this->options );
	}

	/**
	 * Recursively sets a multidimensional option
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param array $option Array of hierarchy
	 * @param mixed $value Value of new option
	 * @param array $options Options to update
	 */
	private function _set_option( $option, $value, &$options ) {
		$key = array_shift( $option );
		if ( ! empty( $option ) ) {
			if ( ! isset( $options[$key] ) ) {
				$options[$key] = array();
			}
			return self::_set_option( $option, $value, $options[$key] );
		}
		$options[$key] = $value;
	}

	/**
	 * Sets all options
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $options Options array
	 */
	public function set_options( $options ) {
		$this->options = (array) $options;
	}

	/**
	 * Deletes an option
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $option Name of option to delete
	 */
	public function delete_option( $option ) {
		if ( ! is_array( $option ) ) {
			$option = array( $option );
		}

		self::_delete_option( $option, $this->options );
	}

	/**
	 * Recursively finds and deletes a multidimensional option
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param array $option Array of hierarchy
	 * @param array $options Options to update
	 */
	private function _delete_option( $option, &$options ) {
		$key = array_shift( $option );
		if ( ! empty( $option ) ) {
			return self::_delete_option( $option, $options[$key] );
		}
		unset( $options[$key] );
	}
}

endif;

