<?php
/**
QRCode Widget
PHP version 5
Plugin Name: QR Code Widget
Plugin URI: http://www.poluschin.info/qr-code-widget/
Description: QR Code generator for your blog with Widget support
Version: 3.0.beta3
Author: Wjatscheslaw Poluschin <wjatscheslaw@poluschin.info>
Author URI: http://www.poluschin.info
License: GPLv2+
Text Domain: qr-code-widget
Domain Path: /languages/
@category WP_Plugin
@package  QrCodeWidget
@author   Wjatscheslaw Poluschin <wjatscheslaw@poluschin.info>
@license  http://www.gnu.org/licenses/gpl-3.0 GNU General Public License (GPL) 3.0
@link     http://www.poluschin.info
**/

/* -- QCW Values -- */
define( 'QCW_VERSION', '3.0.beta3' );
define( 'QCW_DB_VERSION', '30' );

define( 'QCW_FOLDER', basename( dirname( __FILE__ ) ) );
define( 'QCW_ABSPATH', trailingslashit( str_replace( '\\','/', WP_PLUGIN_DIR . '/' . QCW_FOLDER ) ) );
define( 'QCW_URLPATH', trailingslashit( plugins_url( QCW_FOLDER ) ) );
define( 'QCW_IMAGE_CACHE', QCW_ABSPATH . 'cache' . DIRECTORY_SEPARATOR );

/* -- Pre-2.6 compatibility -- */
if ( !defined( 'WP_CONTENT_URL' ) ) define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( !defined( 'WP_CONTENT_DIR' ) ) define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );

/* -- Load Files -- */
require 'includes/phpqrcode.php';
require_once 'includes/qr-code-suite.php';

if ( is_admin() ) {
	include_once 'includes/qr-code-admin.php';
}

/* Load config */
load_plugin_textdomain( 'qr-code-widget', QCW_ABSPATH . '/languages/', 'qr-code-widget/languages' );
$widget_qrcode_widget = get_option( 'widget_qrcode_widget' );

/* Core */
register_activation_hook( __FILE__, 'qr_code_activate' );
register_deactivation_hook( __FILE__, 'qr_code_deactivate' );
register_uninstall_hook( __FILE__, 'qr_code_uninstall' );

if ( is_admin() ) {
	add_action( 'admin_init', 'qr_admin_loader' );
	add_action( 'admin_menu', 'qrcode_dashboard' );
}

/* Frontend */
add_action( 'widgets_init', 'widget_QrCodeWidget_init' );
add_shortcode( 'qr_code_display', 'qr_shortcode' );


if ( isset( $_POST[ 'qrcode_action_submit' ] ) ) {
	add_action( 'init', 'qrcode_request_action' );
}

if ( isset ( $_POST['qrcode_options_submit'] ) ) {
	add_action( 'init', 'qrcode_options_submit' );
}
