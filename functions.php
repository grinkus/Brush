<?php
class Brush_setup_headers {
	public function register_theme_scripts() {
		wp_register_script( 'brush-imageFit', get_bloginfo('template_directory') . '/assets/js/imageFit.js', false, false, true);
		wp_enqueue_script( 'brush-imageFit' );

		wp_register_script( 'brush-adjustBorderColour', get_bloginfo('template_directory') . '/assets/js/adjustBorderColour.js', false, false, true);
		wp_enqueue_script( 'brush-adjustBorderColour' );

		wp_register_script( 'brush-navigationPreload', get_bloginfo('template_directory') . '/assets/js/navigationPreload.js', false, false, true);
		wp_enqueue_script( 'brush-navigationPreload' );
	}

	public function append_theme_favicon() {
		echo '<link rel="shortcut icon" href="/favicon.ico">' . "\n";
		echo '<link rel="shortcut icon" href="/favicon.png">' . "\n";
	}

	public function remove_header_info() {
		remove_action( 'wp_head', 'wp_generator' );
	}

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_theme_scripts' ) );
		add_action( 'init', array( $this, 'remove_header_info' ) );

		// Skip this until the favicon is made
		// add_action( 'wp_head', array( $this, 'append_theme_favicon' ) );
	}
}

class Brush_theme_init {
	public function add_image_size() {
		add_image_size('featured-preview', 100, 100, true);
		add_image_size('brush-fhd', 1920, 0, false);
	}

	public function add_theme_support() {
		add_theme_support( 'post-thumbnails', array( 'post' ) );
	}

	public function remove_post_type_support() {
		remove_post_type_support( 'post', 'title' );
		remove_post_type_support( 'post', 'editor' );
		remove_post_type_support( 'post', 'excerpt' );
		remove_post_type_support( 'post', 'custom-fields' );
		remove_post_type_support( 'post', 'comments' );
		remove_post_type_support( 'post', 'revisions' );
		remove_post_type_support( 'post', 'page-attributes' );
		remove_post_type_support( 'post', 'post-formats' );
	}

	public function remove_menu_page() {
		remove_menu_page('edit.php?post_type=page');
		remove_menu_page('edit-comments.php');
	}

	public function manage_posts_columns( $defaults ) {
		$defaults['image'] = __( 'Image', 'brush' );
		return $defaults;
	}

	private function get_featured_image( $post_ID ) {
		$post_thumbnail_id = get_post_thumbnail_id( $post_ID );
		if ( $post_thumbnail_id ) {
			$post_thumbnail_img = wp_get_attachment_image( $post_thumbnail_id, 'featured-preview' );
			return $post_thumbnail_img;
		}
	}

	public function manage_posts_custom_column( $column_name, $post_ID ) {
		if ($column_name == 'image') {
			$post_featured_image = $this->get_featured_image( $post_ID );
			if ($post_featured_image) {
				echo $post_featured_image;
			}
		}
	}

	public function add_query_vars_filter( $vars ) {
		$vars[] = "date";
		return $vars;
	}

	public function __construct() {
		$this->add_image_size();

		add_action( 'after_setup_theme', array( $this, 'add_theme_support' ) );
		add_action( 'init', array( $this, 'remove_post_type_support' ) );
		add_action( 'admin_menu', array( $this, 'remove_menu_page' ) );

		add_filter( 'manage_posts_columns', array( $this, 'manage_posts_columns' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'manage_posts_custom_column' ), 10, 2 );

		add_filter( 'query_vars', array( $this, 'add_query_vars_filter' ) );
	}
}

class Brush_filter_content {
	public function by_date( $year = NULL, $monthnum = NULL, $day = NULL ) {
		$today = getdate();

		$year = isset($year) ? $year : $today["year"];
		$monthnum = isset($monthnum) ? $monthnum : $today["mon"];
		$day = isset($day) ? $day : $today["mday"];

		$author1 = $author2 = array(
			'year' => $year,
			'monthnum' => $monthnum,
			'day' => $day,
			'posts_per_page' => 1,
			'author' => 1
		);

		$author2['author'] = 2;

		$result = array(
			0 => get_posts( $author1 ),
			1 => get_posts( $author2 )
		);

		return array($result, date("Y-m-d", strtotime("$year-$monthnum-$day")));
	}
}

class Brush_image_colour {
	public function palette( $src, $numColours = 3, $granularity = 5 ) {
		$granularity = max( 1, abs( (int)$granularity ) );
		$colours = array();
		$size = @getimagesize( $src );
		if ( $size === false ) {
			user_error( "Unable to get image size data" );
			return false;
		}
		$img = @imagecreatefromstring( file_get_contents( $src ) );

		if( !$img ) {
			user_error("Unable to open image file");
			return false;
		}
		for ( $x = 0; $x < $size[0]; $x += $granularity ) {
			for ( $y = 0; $y < $size[1]; $y += $granularity ) {
				$thisColour = imagecolorat( $img, $x, $y );
				$rgb = imagecolorsforindex( $img, $thisColour );
				$red = round( round( ($rgb['red'] / 0x33) ) * 0x33 );
				$green = round( round( ($rgb['green'] / 0x33) ) * 0x33);
				$blue = round( round( ($rgb['blue'] / 0x33) ) * 0x33 );
				$thisRGB = sprintf( '%02X%02X%02X', $red, $green, $blue );
				if ( array_key_exists( $thisRGB, $colours ) ) {
					$colours[$thisRGB]++;
				} else {
					$colours[$thisRGB] = 1;
				}
			}
		}
		arsort( $colours );
		return array_slice( array_keys( $colours ), 0, $numColours);
	}

	public function hex2rgb( $hex ) {
		$hex = str_replace("#", "", $hex);

		if ( strlen($hex) == 3 ) {
			$r = hexdec( substr($hex, 0, 1).substr($hex, 0, 1) );
			$g = hexdec( substr($hex, 1, 1).substr($hex, 1, 1) );
			$b = hexdec( substr($hex, 2, 1).substr($hex, 2, 1) );
		} else {
			$r = hexdec( substr($hex, 0, 2) );
			$g = hexdec( substr($hex, 2, 2) );
			$b = hexdec( substr($hex, 4, 2) );
		}
		$rgb = array($r, $g, $b);
		return implode(", ", $rgb);
	}
}

class Brush {
	public function __construct() {
		$this->headers = new Brush_setup_headers();
		$this->init    = new Brush_theme_init();
		$this->filter  = new Brush_filter_content();
		$this->image   = new Brush_image_colour();
	}
}

$brush = new Brush();

?>