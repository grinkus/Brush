<?php
	$posts = $brush->filter->by_date();
	$colours = array();
	$output = "";
	$class = 'half--first';

	foreach( $posts as $post ) {
		$post = $post[0];
		$output .= '<article class="half author-' . get_the_author_meta( 'nickname', $post->post_author ) . ' ' . $class . '">';
		$class = ($class == 'half--first') ? 'half--second' : '';

		if ( isset( $post ) && has_post_thumbnail( $post->ID ) ) {
			$src = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
			$colour = $brush->image->palette( $src );

			if ( $colour[0] == "000000" || $colour[0] == "FFFFFF" ) {
				if ( $colour[1] == "000000" || $colour[1] == "FFFFFF" ) {
					$colour = $colour[2];
				} else {
					$colour = $colour[1];
				}
			} else {
				$colour = $colour[0];
			}

			$colours[] = $brush->image->hex2rgb( $colour );
			$output .= '<img src="' . $src . '">';
		} else {
			$output .= 'SHAME ON YOU!';
		}

		$output .= '</article>';
	}

	get_header();

	echo $output;

	get_footer();
?>