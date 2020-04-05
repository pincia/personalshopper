<?php
	$subfooter_classes[]   = 'subfooter';
	$subfooter_classes[]   = 'style1';
	$subfooter_classes[]   = ot_get_option( 'subfooter_color', 'light');
	$subfooter_classes[]   = 'subfooter-full-width-' . ot_get_option( 'subfooter_full_width', 'off' );
	$subfooter_social_link = ot_get_option( 'subfooter_social_link', 'on' );
?>
<!-- Start subfooter -->
<div class="<?php echo esc_attr( implode( ' ', $subfooter_classes ) ); ?>">
	<div class="row subfooter-row align-middle">
		<div class="small-12 medium-6 columns text-center medium-text-left">
			<?php echo wp_kses_post( ot_get_option( 'subfooter_text' ) ); ?>
		</div>
		<div class="small-12 medium-6 columns text-center medium-text-right">
			<?php if ($subfooter_social_link === 'on' ) { thb_get_social_list(true, false, 'thb-social-horizontal', 'mono-icons'); } ?>
			<?php do_action( 'thb_footer_payment' ); ?>
		</div>
	</div>
</div>
<!-- End Subfooter -->