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

class Brush_theme_init {
	public function add_theme_support() {
		add_theme_support( 'post-thumbnails', array( 'post' ) );
	}

	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'add_theme_support' ) );
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

		return $result;
	}
}

class Brush_image_colour {
	function palette( $src, $numColours = 3, $granularity = 5 ) {
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

	function hex2rgb( $hex ) {
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
		return implode(",", $rgb);
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