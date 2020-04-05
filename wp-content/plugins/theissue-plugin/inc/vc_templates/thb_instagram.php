<?php function thb_instagram( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_instagram', $atts );
	extract( $atts );

	$out ='';
	ob_start();

	$el_class[] = 'row thb-instagram-row';
	$el_class[] = $column_padding;

	if ( '' === $username ) {
		esc_html_e( 'Please check your Instagram username.', 'theissue' );
		return;
	}
	if ( '' === $access_token ) {
		esc_html_e( 'Please enter your IG Access Token. You can get yours from: https://instagram.pixelunion.net', 'theissue' );
		return;
	}

	$instagram = thb_getInstagramPhotos( $number, $username, $access_token );
	$col       = thb_translate_columns( $columns );

	?>
	<div class="<?php echo esc_attr( implode( ' ', $el_class ) ); ?>">
		<?php if ( $display_username ) { ?>
			<div class="small-12 thb-instagram-name-holder <?php echo esc_attr( $display_username_alignment ); ?> columns">
				<a class="instagram-row-username" target="_blank" href="https://instagram.com/<?php echo esc_attr( $username );?>"><i class="thb-icon-instagram"></i><?php echo esc_html( $username ); ?></a>
			</div>
		<?php } ?>

		<?php if ( array_key_exists('error', $instagram ) ) { echo esc_html( $instagram['error'] ); } else { ?>
			<?php if ( array_key_exists('data', $instagram ) ) { foreach ($instagram['data'] as $item) { ?>
				<div class="small-6 <?php echo esc_attr($col); ?> columns">
					<figure>
						<img src="<?php echo esc_url($item['image_url']); ?>" alt="<?php echo esc_url($item['caption']); ?>" loading="lazy"/>
						<?php if ($link == 'true') { ?>
							<a href="<?php echo esc_attr($item['link']); ?>" target="_blank" class="instagram-link"></a>
						<?php } ?>
						<span><?php get_template_part( 'assets/img/svg/like.svg'); ?><em><?php echo thb_numberAbbreviation($item['likes']); ?></em> <?php get_template_part( 'assets/img/svg/comment.svg'); ?><em><?php echo thb_numberAbbreviation($item['comments']); ?></em></span>
					</figure>
				</div>
			<?php } } ?>
		<?php } ?>
	</div>
	<?php

	$out = ob_get_clean();

	return $out;
}
thb_add_short( 'thb_instagram', 'thb_instagram' );