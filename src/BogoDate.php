<?php

class BogoDate
{
	private $prefix = 'bogodate';

	public function __construct() {
	}

	public static function get_instance() {
		static $instance;
		if ( ! $instance ) {
			$instance = new BogoDate();
		}
		return $instance;
	}

	public function register() {
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		add_filter( 'get_the_date', array( $this, 'get_the_date' ), 10, 3 );
		add_filter( 'get_the_time', array( $this, 'get_the_time' ), 10, 3 );
	}

	public function plugins_loaded() {
		if ( is_admin() ) {
			BogoDate\Admin::get_instance()->register();
		}
	}

	public function get_the_date( $the_date, $d, $post ) {
		if ( $d ) {
			return $the_date;
		}

		$format = $this->get_option( 'date_format_' . get_locale() );

		if ( $format ) {
			$post = get_post( $post );
			if ( ! $post ) {
				return $the_date;
			}

			$the_date = mysql2date( $format, $post->post_date );
		}

		return $the_date;
	}

	public function get_the_time( $the_time, $d, $post ) {
		if ( $d ) {
			return $the_time;
		}

		$format = $this->get_option( 'time_format_' . get_locale() );

		if ( $format ) {
			$the_time = get_post_time( $format, false, $post, true );
		}

		return $the_time;
	}

	public function get_prefix() {
		return $this->prefix;
	}

	public function get_option( $key, $default = null ) {
		$option = get_option( $this->get_prefix(), array() );
		if ( ! empty( $option[ $key ] ) && trim( $option[ $key ] ) ) {
			return trim( $option[ $key ] );
		} else {
			return $default;
		}
	}
}
