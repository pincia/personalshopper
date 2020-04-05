<?php
  $header_color = ot_get_option( 'header_color', 'light-header' );
  $header_class[] = 'header';
  $header_class[] = 'header-mobile';
  $header_class[] = 'header-mobile-style1';
  $header_class[] = ot_get_option( 'mobile_header_color', $header_color );
?>
<div class="mobile-header-holder">
  <header class="<?php echo esc_attr( implode( ' ', $header_class ) ); ?>">
    <div class="row">
      <div class="small-3 columns">
        <?php do_action( 'thb_mobile_toggle' ); ?>
      </div>
      <div class="small-6 columns">
        <?php do_action( 'thb_logo', 'mobile-logo' ); ?>
      </div>
      <div class="small-3 columns">
        <?php do_action( 'thb_secondary_area', true ); ?>
      </div>
    </div>
  </header>
</div>
