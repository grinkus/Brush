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
