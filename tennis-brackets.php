<?php
/**
 * Plugin Name:     Tennis Brackets
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     tennis-brackets
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Tennis_Brackets
 */

if ( defined( 'TENNIS_BRACKETS_VERSION' ) ) {
	return;
}
define( 'TENNIS_BRACKETS_VERSION', '0.1' );
define( 'TENNIS_BRACKETS_PATH', dirname( __FILE__ ) );
define( 'TENNIS_BRACKETS_URL', plugin_dir_url( __FILE__ ) );
define( 'TENNIS_BRACKETS_VIEWS_PATH', dirname( __FILE__ ) . '/application/views' );
define( 'TENNIS_BRACKETS_TEXT_DOMAIN', 'tennis-brackets' );
require_once TENNIS_BRACKETS_PATH . '/application/bootstrap.php';
