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
?>
	<link rel="stylesheet" title="main-stylesheet" href="<?= get_bloginfo('stylesheet_url'); ?>">
<?php if( $user_ID ) : ?>
	<style>
		html {
			margin-top: 0 !important;
			padding-top: 28px;
		}
	</style>
<?php endif; ?>
</head>
<body>
<?php
	global $yesterday, $tomorrow;

	echo '<a id="navigation-backward" data-brush-direction="backward" class="navigate navigate--yesterday"';
	echo $yesterday ? ' href="' . site_url('/?date=' . $yesterday) . '">' : '>';
	echo '<span class="navigate--wrapper">←</span></a>';

	echo '<a id="navigation-forward" data-brush-direction="forward" class="navigate navigate--tomorrow"';
	echo $tomorrow ? ' href="' . site_url('/?date=' . $tomorrow) . '">' : '>';
	echo '<span class="navigate--wrapper">→</span></a>';
?>