<?php

/*
 * Plugin Name: Language Downloader
 * Plugin URI:  http://www.brandonkraft.com/
 * Description: Allows on-the-fly language additions
 * Version:     0.4
 * Author:      Brandon Kraft
 * Author URI:  http://www.brandonkraft.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: language-downloader
 * Domain Path: /languages
 */

add_action( 'init', 'ld_kraft_load_textdomain' );
  
/**
 * Load plugin textdomain.
 */
function ld_kraft_load_textdomain() {
  load_plugin_textdomain( 'language-downloader', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

function ld_add_settings_menu(){
	add_management_page( __('Language Downloader', 'language-downloader'), __('Language Downloader', 'language-downloader'), 'manage_options', 'ld_language_downloader', 'ld_options_page' );
}

add_action( 'admin_menu', 'ld_add_settings_menu' );

function ld_options_page(){
	require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );

	if ( ! wp_can_install_language_pack() ) {
		_e('You cannot install language packs. Sorry :-(', 'language-downloader' );
		return;
	}

	if ( isset( $_GET['lang'] ) ) {
		$langtoadd = $_GET['lang'];
		wp_download_language_pack( $langtoadd );
		$success = sprintf( __('Congrats! If everything worked, now %s has been downloaded!', 'language-downloader'), $langtoadd );
		echo '<div class="success"><p>' . $success . '</p></div>';

	}

	$languages = wp_get_available_translations();
	echo '<h3>' . __( 'Possible Languages', 'language-downloader' ) . '</h3><ul>';
	echo '<form action="" method="get" name="ld_language_downloader"><input type="hidden" name="page" value="ld_language_downloader"><select name="lang">';
	foreach ( $languages as $language ) {
		printf(
			'<option value="%s" lang="%s"%s>%s</option>',
			esc_attr( $language['language'] ),
			esc_attr( $language['lang'] ),
			$selected,
			esc_html( $language['native_name'] )
			);
	}
	echo '</select><input type="submit"></form>';

}
