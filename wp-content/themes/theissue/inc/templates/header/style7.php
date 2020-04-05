<?php
  $header_class[] = 'header style7';
  $header_class[] = 'thb-main-header';
  $header_class[] = ot_get_option( 'header_color', 'light-header' );
?>
<header class="<?php echo esc_attr( implode( ' ', $header_class ) ); ?>">
  <div class="header-menu-row">
    <div class="row menu-row">
      <div class="small-12 columns nav-column">
        <div class="thb-navbar">
          <?php get_template_part( 'inc/templates/header/full-menu' ); ?>
          <?php do_action( 'thb_secondary_area' ); ?>
        </div>
      </div>
    </div>
  </div>
  <div class="row align-middle">
    <div class="small-4 columns">
      <?php do_action( 'thb_header_left' ); ?>
    </div>
		<div class="small-4 columns">
      <?php do_action( 'thb_logo' ); ?>
    </div>
    <div class="small-4 columns">
      <?php do_action( 'thb_header_right' ); ?>
    </div>
  </div>
</header>
