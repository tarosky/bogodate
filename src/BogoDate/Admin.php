<?php

namespace BogoDate;

/**
 * Customize the list table on the admin screen.
 *
 * @package BogoDate
 */
final class Admin
{
	private $prefix;

	public function __construct() {
		$this->prefix = \BogoDate::get_instance()->get_prefix();
	}

	public static function get_instance() {
		static $instance;
		if ( ! $instance ) {
			$instance = new Admin();
		}
		return $instance;
	}

	public function register() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	public function admin_menu() {
		add_submenu_page(
			'bogo',
			__( 'Date Format', 'bogodate' ),
			__( 'Date Format', 'bogodate' ),
			'edit_pages',
			$this->prefix,
			array( $this, 'display' )
		);
	}

	public function admin_init() {
		register_setting( $this->prefix . '-settings', $this->prefix );

		$available_locales = bogo_available_locales(
			array( 'current_user_can_access' => true )
		);
		foreach ( $available_locales as $locale ) {

			add_settings_section( 'settings-' . $locale, esc_html( bogo_get_language( $locale ) ), null, $this->prefix );

			add_settings_field(
				'date_format_' . $locale,
				__( 'Date Format', 'bogodate' ),
				function() use ( $locale ) {
					$value = esc_attr( \BogoDate::get_instance()->get_option( 'date_format_' . $locale ) );
					$prefix = esc_attr( $this->prefix );
					echo "<input
						class='dateformat'
						type='text'
						name='{$prefix}[date_format_{$locale}]'
						value='" . esc_attr( $value ) . "'
						placeholder='" . esc_attr( get_option( 'date_format' ) ) . "'
						data-default='" . esc_attr( get_option( 'date_format' ) ) . "'
						/>";
					echo "&nbsp";
					echo "<code class='example'>" . ( $value ? date_i18n( $value ) : date_i18n( get_option( 'date_format' ) ) ) . "</code>";
					echo "<span class='spinner'></span>";
				},
				$this->prefix,
				'settings-' . $locale
			);

			add_settings_field(
				'time_format_' . $locale,
				__( 'Time Format', 'bogodate' ),
				function() use ( $locale ) {
					$value = esc_attr( \BogoDate::get_instance()->get_option( 'time_format_' . $locale ) );
					$prefix = esc_attr( $this->prefix );
					echo "<input
						class='timeformat'
						type='text'
						name='{$prefix}[time_format_{$locale}]'
						value='" . esc_attr( $value ) . "'
						placeholder='" . esc_attr( get_option( 'time_format' ) ) . "'
						data-default='" . esc_attr( get_option( 'time_format' ) ) . "'
						/>";
					echo "&nbsp";
					echo "<code class='example'>" . ( $value ? date_i18n( $value ) : date_i18n( get_option( 'time_format' ) ) ) . "</code>";
					echo "<span class='spinner'></span>";
				},
				$this->prefix,
				'settings-' . $locale
			);
		}
	}

	public function admin_enqueue_scripts() {
		wp_enqueue_script(
			'bogodate-admin',
			plugins_url( '/assets/js/admin.js', dirname( dirname( __FILE__ ) ) ),
			array( 'jquery' ),
			filemtime( dirname( dirname( dirname( __FILE__ ) ) ) . '/assets/js/admin.js' )
		);
		wp_enqueue_style(
			'bogodate-admin',
			plugins_url( '/assets/css/admin.css', dirname( dirname( __FILE__ ) ) ),
			array(),
			filemtime( dirname( dirname( dirname( __FILE__ ) ) ) . '/assets/css/admin.css' )
		);
	}

	public function display() {
		$action = untrailingslashit( admin_url() ) . '/options.php';
?>
		<div class="wrap bogodate-settings">
			<h1 class="wp-heading-inline"><?php _e( 'Date Format Settings', 'bogodate' ); ?></h1>
			<form action="<?php echo esc_url( $action ); ?>" method="post">
<?php
			settings_fields( $this->prefix . '-settings' );
			do_settings_sections( $this->prefix );
			submit_button();
?>
			</form>
		</div>
<?php
	}
}
