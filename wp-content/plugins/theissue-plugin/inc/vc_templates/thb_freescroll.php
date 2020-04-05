<?php function thb_freescroll( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_freescroll', $atts );
	extract( $atts );

	wp_enqueue_script( 'flickity' );

	$element_id = 'thb-freescroll-' . mt_rand(10, 999);
	$el_class[] = 'thb-freescroll';
	$el_class[] = $extra_class;
	$el_class[] = $thb_margins;
	$el_class[] = $type === 'instagram' ? 'thb-instagram-row' : '';
	$el_class[] = $type === 'portfolios' ? 'thb-portfolio' : '';
	$el_class[] = $type === 'images' ? $lightbox : '';
	$out ='';
	ob_start();
	$images = explode(',',$images);


	?>
	<div id="<?php echo esc_attr($element_id); ?>" class="<?php echo esc_attr(implode(' ', $el_class)); ?>" data-direction="<?php echo esc_attr($direction); ?>" data-pause="<?php echo esc_attr($pause_on_hover); ?>">
		<?php
			if ($type === 'text') {
				$content = force_balance_tags($atts['text_content']);
				$content_safe = preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content);
				?>
				<div class="small-12 columns text-content">
					<?php echo wp_kses_post($content_safe); ?>
				</div>
				<?php
			} elseif ( $type === 'images') {
				foreach ($images as $image) {
					$image_link = wp_get_attachment_image_src($image, 'full');

					?>

					<figure class="<?php echo esc_attr($thb_columns); ?> columns">
						<?php if ($lightbox) { ?><a href="<?php echo esc_attr($image_link[0]); ?>" class="thb-lightbox-link"><?php } ?>
						<?php echo wp_get_attachment_image($image, 'full', false,
							array(
								'class' => $box_shadow,
							)
						); ?>
						<?php if ($lightbox) { ?></a><?php } ?>
					</figure>
					<?php
				}
			} elseif ( $type === 'instagram') {
				if ( ! $username ) {
					esc_html_e( 'Please check your Instagram username.', 'theissue' );
					return;
				}
				$instagram = thb_getInstagramPhotos( $number, $username, $access_token );

				if ( ! is_array( $instagram ) ) {
					echo wp_kses_post( $instagram );
				} else {
					foreach ( $instagram as $item ) {
						?>
						<?php if (array_key_exists('data', $instagram)) { foreach ($instagram['data'] as $item) { ?>
							<div class="<?php echo esc_attr($thb_columns); ?> columns">
								<figure style="background-image:url(<?php echo esc_url($item['image_url']); ?>)">
									<a href="<?php echo esc_attr($item['link']); ?>" target="_blank" class="instagram-link"></a>
								<span><?php get_template_part( 'assets/img/svg/like.svg'); ?><em><?php echo thb_numberAbbreviation($item['likes']); ?></em> <?php get_template_part( 'assets/img/svg/comment.svg'); ?><em><?php echo thb_numberAbbreviation($item['comments']); ?></em></span>
								</figure>
							</div>
						<?php } } ?>
						<?php
					}
				}
			} elseif ( $type === 'blog-posts') {
				$free_posts = vc_build_loop_query($source);
				$free_posts = $free_posts[1];
				if ($free_posts->have_posts()) :  while ($free_posts->have_posts()) : $free_posts->the_post();
				?>
				<div class="<?php echo esc_attr($thb_columns); ?> columns">
					<?php get_template_part( 'inc/templates/post-styles/style1'); ?>
				</div>
				<?php
				endwhile; endif;
				wp_reset_postdata();
			} elseif ( $type === 'blog-categories') {
				$cats = explode(',', $cat);
				foreach($cats as $category) { $term = get_term($category); if ($term) { ?>
				<div>
					<a class="thb-category-card" href="<?php echo esc_url(get_term_link($term->term_id)); ?>">
						<figure>
							<?php
								$header_id = get_term_meta( $term->term_id, 'thb_cat_header_id', true );
								if ($header_id) {
									echo wp_get_attachment_image( $header_id, 'theissue-squaresmall-x2' );
								}
							?>
						</figure>
						<span class="category-title"><?php echo esc_html($term->name); ?></span>
					</a>
				</div>
				<?php } }
			}
		?>
	</div>
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short('thb_freescroll', 'thb_freescroll');
