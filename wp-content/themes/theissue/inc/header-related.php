<?php
/* Thb Mobile Toggle */
function thb_mobile_toggle() {
	?>
	<div class="mobile-toggle-holder">
		<div class="mobile-toggle">
			<span></span><span></span><span></span>
		</div>
	</div>
	<?php
}
add_action( 'thb_mobile_toggle', 'thb_mobile_toggle',3 );

/* Logo */
function thb_logo( $section = false ) {
	$logo = ot_get_option( 'logo', Thb_Theme_Admin::$thb_theme_directory_uri. 'assets/img/logo.png' );
	$loading = 'auto';
	$classes[] = 'logo-holder';
	if ($section == 'fixed-logo') {
		$logo = ot_get_option( 'logo_fixed', $logo );
		$classes[] = 'fixed-logo-holder';
		$loading = 'lazy';
	} elseif ($section == 'mobile-logo') {
		$logo = ot_get_option( 'mobile_logo', $logo );
		$classes[] = 'mobile-logo-holder';
		$loading = 'lazy';
	} elseif ($section == 'logo_mobilemenu') {
		$logo = ot_get_option( 'logo_mobilemenu', $logo );
		$classes[] = 'mobilemenu-logo-holder';
		$loading = 'lazy';
	}
	?>
	<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
		<a href="<?php echo esc_url( home_url('/') ); ?>" class="logolink" title="<?php bloginfo('name'); ?>">
			<img src="<?php echo esc_url( $logo ); ?>" loading="<?php echo esc_attr( $loading ); ?>" class="logoimg logo-dark" alt="<?php bloginfo('name'); ?>" />
		</a>
	</div>
	<?php
}
add_action( 'thb_logo', 'thb_logo', 2, 1 );

/* Secondary Area */
function thb_secondary_area($mobile = false) {
	?>
	<div class="secondary-area">
		<?php
			if (!$mobile) {
			  do_action( 'thb_secondary_follow' );
				do_action( 'thb_secondary_trending' );
			}
			do_action( 'thb_quick_cart' );
			do_action( 'thb_quick_search' );
		?>
	</div>
	<?php
}
add_action('thb_secondary_area', 'thb_secondary_area', 10, 1);

/* Thb Header Follow */
function thb_secondary_follow() {
	if ( ! class_exists('TheIssue_plugin' ) ) { return; }

	$header_follow           = ot_get_option( 'header_follow', 'on' );

	if ( $header_follow === 'off' ) { return; }

	$header_follow_subscribe = ot_get_option( 'header_follow_subscribe', 'on' );
	$full_menu_hover_style   = ot_get_option( 'full_menu_hover_style', 'thb-standard' );
	$header_follow_counts    = 'on' === ot_get_option( 'header_follow_counts', 'on' );
	?>
 	<div class="thb-follow-holder">
		<ul class="thb-full-menu <?php echo esc_attr($full_menu_hover_style); ?>">
			<li class="menu-item-has-children">
				<a><span><?php esc_html_e( 'Follow', 'theissue' ); ?></span></a>
				<ul class="sub-menu">
					<li><?php thb_get_social_list( true, $header_follow_counts ); ?></li>
					<?php if ( $header_follow_subscribe === 'on' ) { ?>
					<li class="subscribe_part">
						<?php get_template_part( 'inc/templates/post-detail-bits/subscribe'); ?>
					</li>
				<?php } ?>
				</ul>
			</li>
		</ul>
	</div>
	<?php
}
add_action( 'thb_secondary_follow', 'thb_secondary_follow', 3 );

/* Thb Header Trending */
function thb_secondary_trending() {
	$header_trending       = ot_get_option( 'header_trending', 'on' );
	$full_menu_hover_style = ot_get_option( 'full_menu_hover_style', 'thb-standard' );
	$header_trending_count = ot_get_option( 'header_trending_count', 5 );
	$header_trending_icon  = ot_get_option( 'header_trending_icon', 'v1' );
	if ( 'off' === $header_trending ) { return; }
	?>
 	<div class="thb-trending-holder">
		<ul class="thb-full-menu">
			<li class="menu-item-has-children">
				<a><span><?php get_template_part( 'assets/img/svg/trending_' . $header_trending_icon . '.svg' ); ?></span></a>
				<div class="sub-menu">
					<div class="thb-trending" data-security="<?php echo esc_attr( wp_create_nonce( 'thb_trending_ajax' ) ); ?>">
						<div class="thb-trending-tabs">
							<a data-time="2" class="active"><?php esc_html_e( 'Now', 'theissue' ); ?></a>
							<a data-time="7"><?php esc_html_e( 'Week', 'theissue' ); ?></a>
							<a data-time="30"><?php esc_html_e( 'Month', 'theissue' ); ?></a>
						</div>
						<div class="thb-trending-content">
							<div class="thb-trending-content-inner">
								<?php do_action( 'thb_trending_posts', 2, $header_trending_count ); ?>
							</div>
							<?php thb_preloader(); ?>
						</div>
					</div>
				</div>
			</li>
		</ul>
	</div>
	<?php
}
add_action( 'thb_secondary_trending', 'thb_secondary_trending', 3 );


/* Thb Header Search */
function thb_quick_search() {
	$header_search = ot_get_option( 'header_search', 'on' );
	if ($header_search === 'off') { return; }
	?>
 	<div class="thb-search-holder">
		<?php get_template_part( 'assets/img/svg/search.svg' ); ?>
	</div>

	<?php
}
add_action( 'thb_quick_search', 'thb_quick_search', 3 );

function thb_add_searchform() {
	$header_search = ot_get_option( 'header_search', 'on' );

	if  ($header_search === 'off' ) { return; }
?>
<aside class="thb-search-popup" data-security="<?php echo esc_attr( wp_create_nonce( 'thb_autocomplete_ajax' ) ); ?>">
	<a class="thb-mobile-close"><div><span></span><span></span></div></a>
	<div class="thb-close-text"><?php esc_html_e('PRESS ESC TO CLOSE', 'theissue' ); ?></div>
	<div class="row align-center align-middle search-main-row">
		<div class="small-12 medium-8 columns">
			<?php get_search_form(); ?>
			<div class="thb-autocomplete-wrapper">
				<?php thb_preloader(); ?>
			</div>
		</div>
	</div>
</aside>
<?php
}
add_action( 'wp_footer', 'thb_add_searchform', 100 );

function thb_trending_posts( $time = false, $count = false ) {
	if ( wp_doing_ajax() ) {
		check_ajax_referer( 'thb_trending_ajax', 'security' );
		$time  = filter_input( INPUT_POST, 'time', FILTER_VALIDATE_INT );
		$count = filter_input( INPUT_POST, 'count', FILTER_VALIDATE_INT );
	}

	$posts = thb_most_viewed( $time, $count );

	$args = array(
		'post__in'            => $posts,
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'no_found_rows'       => true,
		'ignore_sticky_posts' => true,
		'posts_per_page'      => 5,
		'orderby'             => 'post__in',
	);

	$cache_name     = 'thb_most_viewed_' . $time . '_' . $count .'_query';
	$trending_posts = get_transient( $cache_name );

	if ( ! $trending_posts ) {
		$trending_posts = new WP_Query( $args );
		set_transient( $cache_name, $trending_posts, HOUR_IN_SECONDS );
	}

	add_filter( 'wp_get_attachment_image_attributes', 'thb_lazy_low_quality', 10, 3 );
	if ( $trending_posts->have_posts()) :  while ( $trending_posts->have_posts() ) : $trending_posts->the_post();
		get_template_part( 'inc/templates/post-styles/thumbnail/style3' );
	endwhile; endif;
	wp_reset_postdata();

	if ( wp_doing_ajax() ) { wp_die(); }
}
add_action( 'thb_trending_posts', 'thb_trending_posts', 10, 2 );
add_action( 'wp_ajax_nopriv_thb_trending', 'thb_trending_posts', 10, 2 );
add_action( 'wp_ajax_thb_trending', 'thb_trending_posts', 10, 2 );