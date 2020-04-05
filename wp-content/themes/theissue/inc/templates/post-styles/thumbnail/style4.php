<?php
  $thb_i = get_query_var('thb_i') ? get_query_var('thb_i') : false;
  $classes[] = 'post';
  $classes[] = 'thumbnail-style4';
?>
<div <?php post_class( $classes ); ?>>
  <figure class="post-gallery">
    <a href="<?php the_permalink(); ?>">
      <?php the_post_thumbnail('theissue-thumbnail-x2'); ?>
      <?php if ($thb_i) { ?>
        <span class="thumb_count"><?php echo esc_html($thb_i); ?></span>
      <?php } ?>
    </a>
  </figure>
  <div class="thumbnail-style4-inner">
    <?php do_action( 'thb_displayTitle', 'h6'); ?>
    <?php do_action( 'thb_post_bottom', false ); ?>
  </div>
</div>