<?php
// thb top_reviews
class thb_top_reviews extends WP_Widget {
	function __construct() {
		$widget_ops = array(
			'classname'   => 'thb_widget_top_reviews',
			'description' => esc_html__('Display Your Top Reviews', 'theissue' )
		);

		parent::__construct(
			'thb_top_reviews_widget',
			esc_html__( 'The Issue - Top Reviews' , 'theissue' ),
			$widget_ops
		);

		$this->defaults = array(
			'title'              => '',
			'style'           	 => 'style1',
			'posts_per_page'     => 5,
			'orderby'            => 'date',
			'order'              => 'desc',
			'time_frame'         => '',
			'category'           => false
		);
	}

	function widget($args, $instance) {
		$params = array_merge( $this->defaults, $instance );

		$query_args = array(
			'posts_per_page'      => $params['posts_per_page'],
			'order'               => $params['order'],
			'no_found_rows'       => true,
			'post_type'						=> 'post',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'meta_query' => array(
        array(
           'key' => 'post_review',
           'value' => 'on'
        ),
  		),
			'meta_key' 						=> 'post_review_average',
    	'orderby' 						=> 'meta_value_num'
		);


		// Category
		if ( $params['category'] ) {
			$query_args['cat'] = $params['category'];
		}

		// Time Frame
		if ( $params['time_frame'] ) {
			$query_args['date_query'] = array(
				array(
					'column' => 'post_date_gmt',
					'after'  => $params['time_frame'] . ' ago',
				),
			);
		}
		// Before Widget.
		echo $args['before_widget'];

		// Title.
		if ( $params['title'] ) {
			echo wp_kses_post( $args['before_title'] . apply_filters( 'widget_title', $params['title'], $instance, $this->id_base ) . $args['after_title'] );
		}

		$widget_posts = new WP_Query($query_args);

		$i = 1; while ($widget_posts->have_posts()) : $widget_posts->the_post();
			$id = get_the_ID();
			$score = intval(get_post_meta($id, 'post_review_average', TRUE), 10);
			$text = thb_get_review_text($score);
			if ($params['style'] == 'style1') {
				$classes[] = 'widget-review';
				?>
				<div <?php post_class( $classes ); ?>>
					<?php if ($i == 1) { ?>
						<div class="post-gallery">
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail('theissue-squaresmall-x2'); ?>
							</a>
						</div>
					<?php } ?>
					<div class="widget-review-title-holder">
						<?php do_action( 'thb_displayTitle', 'h6'); ?>
						<div class="thb-widget-score">
							<strong><?php echo esc_html($score); ?></strong>
							<span><?php echo esc_html($text['state']); ?>
						</div>
					</div>
				</div>
				<?php
			} elseif ($params['style'] == 'style2') {
				$classes[] = 'widget-review-style2';
				?>
				<div <?php post_class( $classes ); ?>>
					<div class="widget-review-style2-header">
						<?php do_action( 'thb_displayTitle', 'h6'); ?>
						<div class="widget-review-style2-score"><?php echo esc_html($score); ?></div>
					</div>
					<div class="thb-review-style1-steps">
						<?php for ($j = 1; $j <= 7; $j++) { ?>
							<?php $active = $text['i'] >= $j ? 'active' : ''; ?>
							<span class="step step-<?php echo esc_attr($j.' '.$active); ?>"></span>
						<?php } ?>
					</div>
				</div>
				<?php
			}
		$i++; endwhile;

		wp_reset_postdata();
		echo $args['after_widget'];
	}
	function update( $new_instance, $old_instance ) {
		$instance = $new_instance;

		return $instance;
	}
	function form($instance) {
		$params = array_merge( $this->defaults, $instance );
		?>
			<!-- Title -->
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'theissue' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $params['title'] ); ?>" /></p>

			<!-- Style -->
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e( 'Style', 'theissue' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>" class="widefat">
					<option value="style1" <?php selected( $params['style'], 'style1' ); ?>><?php esc_html_e( 'Style - 1', 'theissue' ); ?></option>
					<option value="style2" <?php selected( $params['style'], 'style2' ); ?>><?php esc_html_e( 'Style - 2', 'theissue' ); ?></option>
				</select>
			</p>

			<!-- Number of Posts -->
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>"><?php esc_html_e( 'Number of Posts', 'theissue' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_per_page' ) ); ?>" type="number" value="<?php echo esc_attr( $params['posts_per_page'] ); ?>" /></p>

			<!-- Order -->
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Order', 'theissue' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" class="widefat">
					<option value="desc" <?php selected( $params['order'], 'desc' ); ?>><?php esc_html_e( 'Descending', 'theissue' ); ?></option>
					<option value="asc" <?php selected( $params['order'], 'asc' ); ?>><?php esc_html_e( 'Ascending', 'theissue' ); ?></option>
				</select>
			</p>

			<!-- Time Frame -->
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'time_frame' ) ); ?>"><?php esc_html_e( 'Time Frame', 'theissue' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'time_frame' ) ); ?>" placeholder="<?php esc_html_e( '3 months', 'theissue' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'time_frame' ) ); ?>" type="text" value="<?php echo esc_attr( $params['time_frame'] ); ?>" /></p>

			<!-- Category -->
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Category', 'theissue' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>[]" id="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>" class="widefat" style="height: auto !important;" multiple="multiple" size="8">
					<?php
						$cat_args = array(
							'hide_empty'   => 0,
							'hierarchical' => 1,
							'selected'     => (array) $params['category'],
							'walker'       => new THB_Posts_Categories_Tree_Walker(),
						);

						$allowed_html = array(
							'option' => array(
								'class'    => true,
								'value'    => true,
								'selected' => true,
							),
						);

						echo wp_kses( walk_category_dropdown_tree( get_categories( $cat_args ), 0, $cat_args ), $allowed_html );
					?>
				</select>
			</p>
		<?php
	}
}