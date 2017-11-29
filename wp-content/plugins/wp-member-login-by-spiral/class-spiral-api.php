<?php
/**
 * SPIRAL API class
 *
 * @package   Spiral_Member_Login
 * @author    PIPED BITS Co.,Ltd.
 */

if ( ! class_exists( 'Spiral_Api' ) ) :
/**
 *
 * SPIRAL API class.
 *
 * @package Spiral_Member_Login
 * @author  PIPED BITS Co.,Ltd.
 */
class Spiral_Api {

	private $token = null;
	private $token_secret = null;
	private $api_url = null;

	public function __construct($token, $token_secret) {
		$this->token = $token;
		$this->token_secret = $token_secret;
	}

	public function request_spiral_api($api_url, $api_path, $params) {
		if ( $api_url === null ) {
			return null;
		}

		$api_headers = array(
			"X-SPIRAL-API" => "${api_path}/request",
			"Content-Type" => "application/json; charset=UTF-8"
		);

		$args = array(
			'headers' => $api_headers,
			'body' => json_encode($params)
		);
		$response = wp_remote_post($api_url, $args);

		if ( is_wp_error( $response ) ) {
			return null;
		}

		$result = json_decode($response["body"], true);
		return $result;
	}

	protected function _sign_params(&$params) {
		$params["spiral_api_token"] = $this->token;
		$params["passkey"] = time();
		$key = $params["spiral_api_token"] . "&" . $params["passkey"];
		$params["signature"] = hash_hmac('sha1', $key, $this->token_secret, false);
	}

	public function get_api_url() {
		if ( $this->api_url === null ) {
			$locator_url = "http://www.pi-pe.co.jp/api/locator";

			$params = array();
			$params["spiral_api_token"] = $this->token;

			$result = $this->request_spiral_api($locator_url, "locator/apiserver", $params);

			if (!$result or $result["code"] != "0") {
				return null;
			}
			$this->api_url = $result["location"];
		}

		return $this->api_url;
	}

	public function login_area($area_title, $id = null, $key = null, $password = null) {
		$parameters = array();
		$parameters["my_area_title"] = $area_title;
		$parameters["url_type"] = 1;

		if ($id) {
			$parameters["id"] = $id;
		}
		if ($key) {
			$parameters["key"] = $key;
		}
		if ($password) {
			$parameters["password"] = $password;
		}

		$this->_sign_params($parameters);

		$result = $this->request_spiral_api($this->get_api_url(), "area/login", $parameters);
		return $result;
	}

	public function logout_area($area_title, $session_id) {
		$parameters = array();
		$parameters["my_area_title"] = $area_title;
		$parameters["jsessionid"] = $session_id;

		$this->_sign_params($parameters);

		$result = $this->request_spiral_api( $this->get_api_url(), "area/logout", $parameters);
		if ( $result !== null && isset( $result['code'] ) && (int)$result['code'] === 0 ) {
			return $result['url'];
		} else {
			return null;
		}
	}

	public function get_area_status($area_title, $session_id) {
		$parameters = array();
		$parameters["my_area_title"] = $area_title;
		$parameters["jsessionid"] = $session_id;

		$this->_sign_params($parameters);

		$result = $this->request_spiral_api( $this->get_api_url(), "area/status", $parameters);
		if ( $result !== null && isset( $result['code'] ) && (int)$result['code'] === 0 ) {
			return (int)$result['status'] === 1;
		} else {
			return null;
		}
	}

	public function get_area_mypage($area_title, $session_id, $mypage_id) {
		$parameters = array();
		$parameters["my_area_title"] = $area_title;
		$parameters["jsessionid"] = $session_id;
		$parameters["my_page_id"] = $mypage_id;
		$parameters["url_type"] = 1;

		$this->_sign_params($parameters);

		$result = $this->request_spiral_api( $this->get_api_url(), "area/mypage", $parameters);
		if ( $result !== null && isset( $result['code'] ) && (int)$result['code'] === 0 ) {
			return $result['url'];
		} else {
			return null;
		}
	}

	public function get_table_data($area_title, $session_id, $search_title, $options = null) {
		$parameters = array();
		if ( $options && is_array($options) ) {
			$parameters = $options;
		}
		$parameters["my_area_title"] = $area_title;
		$parameters["jsessionid"] = $session_id;
		$parameters["search_title"] = $search_title;

		$this->_sign_params($parameters);

		$result = $this->request_spiral_api( $this->get_api_url(), "table/data", $parameters);
		if ( $result !== null && isset( $result['code'] ) && (int)$result['code'] === 0 ) {
			return $result;
		} else {
			return null;
		}
	}
}

endif; // Class exists

