<?php function thb_postcarousel( $atts, $content = null ) {
	$autoplay = true;
  $atts = vc_map_get_attributes( 'thb_postcarousel', $atts );
  extract( $atts );

	ob_start();
	$pagi = ( $pagination == 'true' ? ( $carousel_button == 'true' ? 'false' : 'true' ) : 'false' );
	$nav  = ( $navigation == 'true' ? 'true' : 'false' );

	if ( $offset ) {
		$source .= '|offset:' . $offset;
	}
	$source_data    = VcLoopSettings::parseData( $source );
	$query_builder  = new ThbLoopQueryBuilder( $source_data );
	$carousel_posts = $query_builder->build();
	$carousel_posts = $carousel_posts[1];

	$classes[] = 'thb-carousel';
	$classes[] = 'thb-post-carousel';
	$classes[] = 'thb-post-carousel-' . $style;
	$classes[] = in_array( $style, array( 'style1', 'style2', 'style4', 'style6' ) ) ? 'row' :  false;
	$classes[] = in_array( $style, array( 'style1' ) ) ? 'bottom-arrows' : false;
	$classes[] = in_array( $style, array( 'style2', 'style6' ) ) ? 'center-arrows overflow-visible equal-height-carousel' : false;
	$classes[] = in_array( $style, array( 'style4' ) ) ? 'center-arrows overflow-visible-only' : false;
	$classes[] = in_array( $style, array( 'style5' ) ) ? 'center-arrows no-padding large-arrows' : false;
	$classes[] = in_array( $style, array( 'style3' ) ) ? 'right-arrows' : false;
	$classes[] = in_array( $style, array( 'style2' ) ) ? 'mini-padding large-arrows' : false;
	$classes[] = $carousel_button == 'true' ? 'has-carousel-button' : false;

	$infinite = '' !== $infinite ? $infinite : 'false';

	if ( $carousel_button == 'true' ) {
		$link = ( $link == '||' ) ? '' : $link;
		$link = vc_build_link( $link  );

		$link_to  = $link['url'];
		$a_title  = $link['title'];
		$a_target = $link['target'] ? $link['target'] : '_self';
	}
	set_query_var( 'thb_excerpt_length', false );
	if ( $carousel_posts->have_posts() ) { ?>
		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" data-pagination="<?php echo esc_attr( $pagi ); ?>" data-navigation="<?php echo esc_attr( $nav ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-autoplay-speed="<?php echo esc_attr( $autoplay_speed ); ?>" data-infinite="<?php echo esc_attr( $infinite ); ?>">
			<?php while ( $carousel_posts->have_posts() ) : $carousel_posts->the_post(); ?>
				<div class="columns">
					<?php if ( $style == 'style1' ) { ?>
						<?php if ('video' == get_post_format() ) { ?>
							<?php set_query_var( 'thb_title_size', 'h5' ); ?>
							<?php get_template_part( 'inc/templates/post-styles/style11' ); ?>
						<?php } else { ?>
							<?php set_query_var( 'thb_excerpt', $excerpts); ?>
							<?php get_template_part( 'inc/templates/post-styles/style1' ); ?>
						<?php } ?>
					<?php } ?>
					<?php if ( $style == 'style2' ) { ?>
						<?php get_template_part( 'inc/templates/post-styles/style6' ); ?>
					<?php } ?>
					<?php if ( $style == 'style3' ) { ?>
						<?php get_template_part( 'inc/templates/post-styles/thumbnail/style4' ); ?>
					<?php } ?>
					<?php if ( $style == 'style4' ) { ?>
						<?php set_query_var( 'thb_excerpt', $excerpts ); ?>
						<?php get_template_part( 'inc/templates/post-styles/style1' ); ?>
					<?php } ?>
					<?php if ( $style == 'style5' ) { ?>
						<?php get_template_part( 'inc/templates/post-styles/style12-center' ); ?>
					<?php } ?>
					<?php if ( $style == 'style6' ) { ?>
						<?php get_template_part( 'inc/templates/post-styles/style6-bg' ); ?>
					<?php } ?>
				</div>
			<?php endwhile; ?>
			<?php if ( $carousel_button == 'true' ) { ?>
			<a class="btn small accent pill-radius" href="<?php echo esc_attr( $link_to ); ?>" target="<?php echo sanitize_text_field( $a_target ); ?>" role="button">
			  <span><?php echo esc_attr( $a_title ); ?></span></a>
			<?php } ?>
		</div>
	<?php }
	set_query_var( 'thb_excerpts', false );
	$out = ob_get_clean();

	wp_reset_query();
	wp_reset_postdata();
	return $out;
}
thb_add_short( 'thb_postcarousel', 'thb_postcarousel' );