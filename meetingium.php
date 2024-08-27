<?php
/**
 * @package meetingium
 *
 * Plugin Name: Meetingium
 * Description: Manage and share meetings with your users using power of wordpress and woocommerce.
 * Version: 0.0.0
 * Plugin URI: https://github.com/NimaKeshavarzian/meetingium
 * Author: Nima Keshavarzian
 * Author URI: https://github.com/NimaKeshavarzian
 * License: GPL v3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
*/

defined( 'ABSPATH' ) || exit;

// Require packages using composer autoloader
if(! file_exists( __DIR__ . '/vendor/autoload.php' )) {
  wp_die(esc_html__("The Meetingium Plugin is not installed correctly"));
}
require_once __DIR__ . '/vendor/autoload.php';

// Import main class
if(!class_exists('Meetingium')) {
  require_once __DIR__ . '/includes/class-meetingium.php';
}

Meetingium::init();
