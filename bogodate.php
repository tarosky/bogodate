<?php
/**
 * Plugin Name:     Bogo Date
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     bogodate
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Bogo_Date
 */

require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );

register_activation_hook( __FILE__, function() {
	if ( ! defined( 'BOGO_VERSION' ) ) {
		deactivate_plugins( __FILE__ );
		exit( __( 'Sorry, Bogo is not installed.', 'bogodate' ) );
	}
});

BogoDate::get_instance()->register();
