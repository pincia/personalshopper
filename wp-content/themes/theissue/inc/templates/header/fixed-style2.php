<?php
  $fixed_header_fullwidth = ot_get_option( 'fixed_header_fullwidth', 'on' );
  $header_class[] = 'header fixed fixed-style2';
  if ($fixed_header_fullwidth === 'on' ) {
    $header_class[] = 'header-full-width';
  }
  $header_class[] = 'fixed-header-full-width-'.$fixed_header_fullwidth;
  $header_class[] = ot_get_option( 'fixed_header_color', 'light-header' );
  $header_class[] = ot_get_option( 'fixed_header_shadow', 'thb-fixed-shadow-style1');
?>
<header class="<?php echo esc_attr( implode( ' ', $header_class ) ); ?>">
  <div class="row<?php if ('on' == $fixed_header_fullwidth) { ?> full-width-row<?php } ?>">
    <div class="small-12 columns">
      <div class="thb-navbar">
        <div>
          <?php do_action( 'thb_mobile_toggle' ); ?>
        </div>
        <?php do_action( 'thb_logo', 'fixed-logo'); ?>
        <?php do_action( 'thb_secondary_area' ); ?>
      </div>
    </div>
  </div>
</header>
