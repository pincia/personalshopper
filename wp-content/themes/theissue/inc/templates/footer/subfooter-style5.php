<?php
	$subfooter_classes[]   = 'subfooter';
	$subfooter_classes[]   = 'style5';
	$subfooter_classes[]   = ot_get_option( 'subfooter_color', 'light' );
	$subfooter_classes[]   = 'subfooter-full-width-'.ot_get_option( 'subfooter_full_width', 'off' );
	$subfooter_social_link = ot_get_option( 'subfooter_social_link', 'on' );
	$full_menu_hover_style = ot_get_option( 'full_menu_hover_style', 'thb-standard' );
	$subfooter_menu        = ot_get_option( 'subfooter_menu' );
?>
<!-- Start subfooter -->
<div class="<?php echo esc_attr( implode( ' ', $subfooter_classes ) ); ?>">
	<div class="row subfooter-row align-middle">
		<div class="small-12 medium-9 columns text-center medium-text-left">
			<div class="subfooter-text-container"><?php echo wp_kses_post( ot_get_option( 'subfooter_text' ) ); ?></div>
			<?php if ($subfooter_menu) { wp_nav_menu( array( 'menu' => $subfooter_menu, 'depth' => 1, 'menu_class' => 'thb-full-menu '. $full_menu_hover_style ) ); } ?>
		</div>
		<div class="small-12 medium-3 columns text-center medium-text-right">
			<?php if ($subfooter_social_link === 'on' ) { thb_get_social_list(true, false, 'thb-social-horizontal', 'mono-icons'); } ?>
			<?php do_action( 'thb_footer_payment' ); ?>
		</div>
	</div>
</div>
<!-- End Subfooter -->