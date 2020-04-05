<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php

	/**
	 * Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */

	wp_head();
	?>
</head>
<body <?php body_class(); ?>>
<?php do_action( 'thb_before_wrapper' ); ?>
<!-- Start Wrapper -->
<div id="wrapper" class="thb-page-transition-<?php echo esc_attr( ot_get_option( 'page_transition', 'on' ) ); ?>">

	<?php if ( ot_get_option( 'fixed_header', 'on' ) === 'on' && ! thb_is_mobile() ) { ?>
		<!-- Start Fixed Header -->
		<?php

		if ( ot_get_option( 'header_style', 'style1' ) === 'style4' ) {
			$fixed = 'fixed-style2';
		} else {
			$fixed = 'fixed-style1';
		}
		if ( is_singular( 'post' ) ) {
			$fixed = 'fixed-article';
		}
		get_template_part( 'inc/templates/header/' . $fixed );

		?>
		<!-- End Fixed Header -->
	<?php } ?>

	<?php do_action( 'thb_before_header' ); ?>
	<?php get_template_part( 'inc/templates/header/mobile-style1' ); ?>
	<?php if ( ! thb_is_mobile() ) { ?>
		<!-- Start Header -->
		<?php get_template_part( 'inc/templates/header/' . ot_get_option( 'header_style', 'style1' ) ); ?>
		<!-- End Header -->
	<?php } ?>
	<?php do_action( 'thb_before_main' ); ?>
	<div role="main">
