<?php
  $thb_i = get_query_var('thb_i') ? get_query_var('thb_i') : false;
  $classes[] = 'post';
  $classes[] = 'thumbnail-style8';
?>
<div <?php post_class( $classes ); ?>>
  <?php if ($thb_i) { ?>
    <span class="thumb_large_count"><?php echo esc_html($thb_i); ?></span>
  <?php } ?>
  <div class="thumbnail-style8-inner">
    <?php do_action( 'thb_categories'); ?>
    <?php do_action( 'thb_displayTitle', 'h6'); ?>
  </div>
</div>