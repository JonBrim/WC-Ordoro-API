<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class WC_Ordoro_Resource {
	private $url;
	private $username;
	private $password;

	protected $id;
	protected $endpoint;
	protected $server;
	protected $method;
	protected $path;
	protected $body;
	protected $warehouse;

	const TRANSIENT = false;

	public function __construct( $server ) {
		$this->server     = $server;
		$this->url        = get_option( 'wc_ordoro_url' );
		$this->username   = get_option( 'wc_ordoro_username' );
		$this->password   = get_option( 'wc_ordoro_password' );
		$this->warehouse  = get_option( 'wc_ordoro_warehouse' );
	}
	
	protected function set_id( $id ) {
		$this->id = $id;
	}
	
	protected function has_id( $id ) {
		$this->id ? true : false;
	}
	
	protected function get_id( $id ) {
		return $this->id;
	}

	protected function set_method( $method ) {
		$this->method = $method;
	}

	protected function has_method() {
		return $this->method ? true : false;
	}

	protected function get_method() {
		return $this->method;
	}

	protected function set_path( $path ) {
		array_unshift( $path, $this->endpoint );
		if( !isset( $path[1] ) ) {
			unset( $path[1] );
		}
		$path = implode( '/', $path );
		$this->path = $path;
	}
	
	protected function has_path() {
		return $this->path ? true : false;
	}

	protected function get_path() {
		return $this->path;
	}

	protected function set_body( $body ) {
		if( !isset( $body ) ) {
			$body = null;
		}
		$this->body = $body;
	}

	protected function has_body() {
		return $this->body ? true : false;
	}

	protected function get_body() {
		return $this->body;
	}

	protected function get_authorization() {
		return 'Basic ' . base64_encode( $this->username . ':'. $this->password );
	}

	public function do_remote_request( ) {
		$args = array(
			'method'  => $this->get_method(),
			'headers' => array(
				'Authorization' => $this->get_authorization()
			),
			'body' => $this->get_body()
		);
		$response = wp_remote_request( esc_url_raw( $this->url . '/' . $this->get_path() ), $args );
		$response = json_decode( wp_remote_retrieve_body( $response ) );
		return $response;
	}

	/**
	 * Has Transient
	 *
	 * @since 1.0
	 * @param string $id product SKU, or order number
	 * @return array
	 */
	public function has_transient( $id ) {
		$transient = get_transient( 'ordoro_' . $this->get_method() . '_' . $id );
		if( ! empty( $transient ) && self::TRANSIENT == true ) {
			return $transient;
		} else {
			$response = $this->do_remote_request();
			set_transient( 'ordoro_' . $this->method . '_' . $id, $response, HOUR_IN_SECONDS );
			return $response;
		}
	}

	/**
	 * Get item
	 *
	 * @since 1.0
	 * @param string $id product SKU, or order number
	 * @param array $path sets specific path to update
	 * @param array $args
	 * @return array
	 */
	public function get( $id = null, $path = null, $args = array() ) {
		$this->set_method( 'GET' );
		if( ! $this->has_id && isset( $id ) ) {
			$this->set_id( $id );
		}
		if( ! $this->has_path() ) {
			$this->set_path( array( $this->get_id(), $path ) );
		}
		if( ! $this->has_body() ) {
			$this->set_body( $args );
		}
		$response = $this->has_transient( $this->get_id() );
		return $response;
	}

	/**
	 * Put item
	 *
	 * @since 1.0
	 * @param int $id product SKU, or order number
	 * @param array $path sets specific path to update
	 * @param array $args product data to update
	 * @return array
	 */
	public function put( $id = null, $args = array() ) {
		$this->set_method( 'PUT' );
		if( ! $this->has_id && isset( $id ) ) {
			$this->set_id( $id );
		}
		if( ! $this->has_path() ) {
			$this->set_path( array( $this->get_id(), null ) );
		}
		if( ! $this->has_body() ) {
			$this->set_body( $args );
		}
		return $this->do_remote_request();
	}

	/**
	 * Post item
	 *
	 * @since 1.0
	 * @param int $id product SKU, or order number
	 * @param array $path sets specific path to add
	 * @return array
	 */
	public function post( $id = null, $path = null ) {
		$this->set_method( 'POST' );
		if( ! $this->has_id && isset( $id ) ) {
			$this->set_id( $id );
		}
		if( ! $this->has_path() ) {
			$this->set_path( array( $this->get_id(), $path ) );
		}
		return $this->do_remote_request();
	}

	/**
	 * Delete item
	 *
	 * @since 1.0
	 * @param int $id product SKU, or order number
	 * @param array $path sets specific path to delete
	 * @return array
	 */
	public function delete( $id = null, $path = null ) {
		$this->set_method( 'DELETE' );
		if( ! $this->has_id && isset( $id ) ) {
			$this->set_id( $id );
		}
		if( ! $this->has_path() ) {
			$this->set_path( array( $this->get_id(), $path ) );
		}
		return $this->do_remote_request();
	}
	
	/**
	 * Update item
	 *
	 * @since 1.0
	 */
	public function update() {
		
	}
}