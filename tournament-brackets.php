<?php
/**
 * Plugin Name: Tournament Brackets
 * Plugin URI: https://wordpress.org/plugins/tournament-brackets/
 * Description: Create beautiful, functional and feature rich brackets for your tournament.
 * Author: Konstantinos Galanakis
 * Author URI: https://github.com/kmgalanakis
 * Text Domain: tournament-brackets
 * Domain Path: /languages
 * Version:  0.1.0
 *
 * @package tournament-brackets
 */

if ( defined( 'TOURNAMENT_BRACKETS_VERSION' ) ) {
	return;
}
define( 'TOURNAMENT_BRACKETS_VERSION', '0.1' );
define( 'TOURNAMENT_BRACKETS_PATH', dirname( __FILE__ ) );
define( 'TOURNAMENT_BRACKETS_URL', plugin_dir_url( __FILE__ ) );
define( 'TOURNAMENT_BRACKETS_VIEWS_PATH', dirname( __FILE__ ) . '/application/views' );
define( 'TOURNAMENT_BRACKETS_TEXT_DOMAIN', 'tournament-brackets' );
require_once TOURNAMENT_BRACKETS_PATH . '/application/bootstrap.php';
