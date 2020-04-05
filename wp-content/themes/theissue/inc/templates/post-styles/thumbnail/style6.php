<?php
  $classes[] = 'post';
  $classes[] = 'thumbnail-style6';
?>
<div <?php post_class( $classes ); ?> data-id="<?php the_ID(); ?>">
  <figure class="post-gallery">
    <a href="<?php the_permalink(); ?>">
      <?php the_post_thumbnail('theissue-thumbnail'); ?>
      <div class="post-video-play">
        <?php get_template_part( 'assets/img/svg/video.svg'); ?>
      </div>
      <span class="now-playing"><?php esc_html_e('Now Playing', 'theissue' ); ?></span>
    </a>
  </figure>
  <div class="thumbnail-style6-inner">
    <?php do_action( 'thb_displayTitle', 'h6'); ?>
  </div>
</div>