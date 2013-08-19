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
		.half--first {
			border-color: rgba(<?= $colours[0]; ?>, .2);
		}

		.half--second {
			border-color: rgba(<?= $colours[1]; ?>, .2);
		}
	</style>
<?php
	}
?>
</head>
<body>
