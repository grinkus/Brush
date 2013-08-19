<?php
class Brush_setup_headers {
	public function register_theme_styles() {
		wp_register_style( 'brush_css', get_bloginfo('stylesheet_url'), false, false, 'all' );
		wp_enqueue_style( 'brush_css' );
	}

	public function append_theme_favicon() {
		echo '<link rel="shortcut icon" href="/favicon.ico">' . "\n";
		echo '<link rel="shortcut icon" href="/favicon.png">' . "\n";
	}

	public function remove_header_info() {
		remove_action( 'wp_head', 'wp_generator' );
	}

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_theme_styles' ) );
		add_action( 'init', array( $this, 'remove_header_info' ) );

		// Skip this until the favicon is made
		// add_action( 'wp_head', array( $this, 'append_theme_favicon' ) );
	}
}

class Brush {
	public function __construct() {
		$this->headers = new Brush_setup_headers();
	}
}

$brush = new Brush();

?>