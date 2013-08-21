<!doctype html>
<!--[if (IE 8)&!(IEMobile)]><html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"><!--<![endif]-->
<head>
	<title><?php wp_title('–', true, 'right'); bloginfo('name'); ?></title>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="description" content="<?php bloginfo('description'); ?>">
	<meta name="viewport" content="width=device-width">
<?php
	wp_head();
	global $colours;
	if ( isset($colours) ) {
?>
	<style>
		.halves--half__first {
			border-color: rgba(<?= $colours[0]; ?>, .3);
		}

		.halves--half__second {
			border-color: rgba(<?= $colours[1]; ?>, .3);
		}
<?php if( $user_ID ) : ?>
		html {
			margin-top: 0 !important;
			padding-top: 28px;
		}
<?php endif; ?>
	</style>
<?php
	}
?>
</head>
<body>
<?php
	global $yesterday, $tomorrow;

	if ($yesterday) {
		echo '<a class="navigate navigate--yesterday" ';
		echo 'href="' . site_url('/?date=' . $yesterday) . '">';
		echo '<span class="navigate--wrapper">←</span>';
		echo '</a>';
	} else {
		echo '<span class="navigate navigate--yesterday"><span class="navigate--wrapper">←</span></span>';
	}

	if ($tomorrow) {
		echo '<a class="navigate navigate--tomorrow" ';
		echo 'href="' . site_url('/?date=' . $tomorrow) . '">';
		echo '<span class="navigate--wrapper">→</span>';
		echo '</a>';
	} else {
		echo '<span class="navigate navigate--tomorrow"><span class="navigate--wrapper">→</span></span>';
	}
?>