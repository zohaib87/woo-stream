<?php
/**
 * Functions that helps to ease plugin development.
 *
 * @package Woo Stream
 */

function woo_stream_directory() {
	return ABSPATH . 'wp-content/plugins/woo-stream';
}

function woo_stream_directory_uri() {
	return plugins_url() . '/woo-stream';
}

function woo_stream_file() {
	return woo_stream_directory() . '/woo-stream.php';
}

function woo_stream_data() {
	return get_plugin_data( woo_stream_file() );
}
