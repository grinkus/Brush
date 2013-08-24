<?php
	$queryDate = strtotime( get_query_var('date') );
	$currentDate = strtotime( date('Y-m-d') );
	$newestPostDate = strtotime( get_lastpostdate() );
	$tomorrow = true;

	if ( !$queryDate || $queryDate > $currentDate ) {
		$queryDate = $currentDate;
	}

	if ( $queryDate == $currentDate ) {
		if ( $currentDate != $newestPostDate ) {
			$queryDate = strtotime( date('Y-m-d', $currentDate) . ' yesterday');
		}

		$tomorrow = false;
	}

	$date = date('Y-m-d', $queryDate);
	$by_date = explode( "-", $date );

	$yesterday = date('Y-m-d', strtotime($date . ' yesterday'));
	$tomorrow = $tomorrow ? date('Y-m-d', strtotime($date . ' tomorrow')) : false;
	
	$posts = $brush->filter->by_date( $by_date[0], $by_date[1], $by_date[2] );
	$by_date = $posts[1];
	$posts = $posts[0];
	$colours = array();
	$output = "";
	$classes = "";

	$output .= '<div class="halves">';
	foreach( $posts as $post ) {
		$post = $post[0];
		$o = '';

		if ( strpos($classes, 'halves--half__first' ) !== false) {
			$classes = ' halves--half__second';
		} else {
			$classes = ' halves--half__first';
		}

		if ( isset( $post ) && has_post_thumbnail( $post->ID ) ) {
			$src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'brush-fhd' );
			$src = $src[0];
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
			$classes .= ' author-' . get_the_author_meta( 'nickname', $post->post_author );
			$o .= '<img class="image portrait" src="' . $src . '">';
		} else {
			if (substr( current_time('mysql'), 0, 10 ) == $date) {
				$o .= get_bloginfo('name');
				$classes .= ' halves--half__blank';
				$colours[] = '255, 255, 255';
			} else {
				$o .= 'SHAME ON YOU!';
				$classes .= ' halves--half__shame';
				$colours[] = '255, 0, 0';
			}
		}

		$pre = '<article class="halves--half' . $classes . '">';

		$output .= $pre . $o;

		$output .= '</article>';
	}
	$output .= '</div>';

	get_header();

	echo $output;

	get_footer();
?>