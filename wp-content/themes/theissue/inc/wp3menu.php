<?php

function thb_center_nav_menu_items($items, $args) {
	if ( $args->theme_location == 'nav-menu') {
		if (is_array($items) || is_object($items)) {
			$logo = ot_get_option( 'logo', Thb_Theme_Admin::$thb_theme_directory_uri. 'assets/img/logo.png');
			$logo_light = ot_get_option( 'logo_light', Thb_Theme_Admin::$thb_theme_directory_uri. 'assets/img/logo-light.png');

			ob_start();
			?>
			<div class="logo-holder"><a href="<?php echo esc_url(home_url()); ?>" class="logolink" title="<?php bloginfo('name'); ?>">
				<img src="<?php echo esc_url($logo); ?>" class="logoimg logo-dark" alt="<?php bloginfo('name'); ?>"/>
				<img src="<?php echo esc_url($logo_light); ?>" class="logoimg logo-light" alt="<?php bloginfo('name'); ?>"/>
			</a></div>
			<?php
			$logo_html = ob_get_clean();
			$menu_items = array();
			foreach ($items as $key => $item) {
				if ($item->menu_item_parent == 0) { $menu_items[] = $key; }
			}
			$new_item_array = array();
			$new_item = new stdClass;
			$new_item->ID = 0;
			$new_item->db_id = 0;
			$new_item->menu_item_parent = 0;
			$new_item->url = '';
			$new_item->title = $logo_html;
			$new_item->menu_order = 0;
			$new_item->object_id = 0;
			$new_item->description = '';
			$new_item->attr_title = '';
			$new_item->button = '';
			$new_item->megamenu = '';
			$new_item->logo = true;
			$new_item->classes = 'logo-menu-item';
			$new_item_array[] = $new_item;
			$get_position = count($menu_items) % 2 == 0 ? (count($menu_items) / 2) - 1 : floor(count($menu_items) / 2);
			array_splice($items, $menu_items[$get_position], 0, $new_item_array);
		}
	}

	return $items;
}
/**
 * Custom Walker - Mobile Menu
 *
 * @access      public
 * @since       1.0
 * @return      void
*/
class thb_mobileDropdown extends Walker_Nav_Menu {
	/**
	 * Start the element output.
	 *
	 * @see Walker::start_el()
	 *
	 * @since 3.0.0
	 * @since 4.4.0 'nav_menu_item_args' filter was added.
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 * @param int    $id     Current item ID.
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		$classes[] = ! empty( $item->menuanchor ) ? 'has-hash' : '';
		/**
		 * Filter the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param array  $args  An array of arguments.
		 * @param object $item  Menu item data object.
		 * @param int    $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		/**
		 * Filter the CSS class(es) applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
		 * @param object $item    The current menu item.
		 * @param array  $args    An array of wp_nav_menu() arguments.
		 * @param int    $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filter the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param object $item    The current menu item.
		 * @param array  $args    An array of wp_nav_menu() arguments.
		 * @param int    $depth   Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		/**
		 * Filter the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title  Title attribute.
		 *     @type string $target Target attribute.
		 *     @type string $rel    The rel attribute.
		 *     @type string $href   The href attribute.
		 * }
		 * @param object $item  The current menu item.
		 * @param array  $args  An array of wp_nav_menu() arguments.
		 * @param int    $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$menu_anchor  = ! empty( $item->menuanchor ) ? '#'.esc_attr( $item->menuanchor ) : '';

		$attributes = '';

		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value.$menu_anchor ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filter a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string $title The menu item's title.
		 * @param object $item  The current menu item.
		 * @param array  $args  An array of wp_nav_menu() arguments.
		 * @param int    $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= (in_array( 'menu-item-has-children', $item->classes) ? '<div class="thb-arrow"><i class="thb-icon-down-open-mini"></i></div>' : ''). '</a>';
		$item_output .= $args->after;

		/**
		 * Filter a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string $item_output The menu item's starting HTML output.
		 * @param object $item        Menu item data object.
		 * @param int    $depth       Depth of menu item. Used for padding.
		 * @param array  $args        An array of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

/* Mega Background */
class thb_custom_menu {

    /*--------------------------------------------*
     * Constructor
     *--------------------------------------------*/

    /**
     * Initializes the plugin by setting localization, filters, and administration functions.
     */
    function __construct() {


        // add custom menu fields to menu
        add_filter( 'wp_setup_nav_menu_item', array( $this, 'thb_add_custom_nav_fields' ) );

        // save menu custom fields
        add_action( 'wp_update_nav_menu_item', array( $this, 'thb_update_custom_nav_fields'), 10, 3 );

        // edit menu walker
        add_filter( 'wp_edit_nav_menu_walker', array( $this, 'thb_edit_walker'), 10, 2 );

    } // end constructor


    /**
     * Add custom fields to $item nav object
     * in order to be used in custom Walker
     *
     * @access      public
     * @since       1.0
     * @return      void
    */
    function thb_add_custom_nav_fields( $menu_item ) {

			$menu_item->menuanchor = get_post_meta( $menu_item->ID, '_menu_item_menuanchor', true );
			$menu_item->megamenu = get_post_meta( $menu_item->ID, '_menu_item_megamenu', true );
			$menu_item->megamenustyle = get_post_meta( $menu_item->ID, '_menu_item_megamenustyle', true );
      return $menu_item;

    }

    /**
     * Save menu custom fields
     *
     * @access      public
     * @since       1.0
     * @return      void
    */
    function thb_update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {
			$megamenustyle_value = 'mega-menu-style1';
	    // Check if element is properly sent
			if (!empty($_REQUEST['menu-item-menuanchor'])) {
		    $menuanchor_value = $_REQUEST['menu-item-menuanchor'][$menu_item_db_id];
		    update_post_meta( $menu_item_db_id, '_menu_item_menuanchor', $menuanchor_value );
			}
			if (!empty($_REQUEST['edit-menu-item-megamenustyle'])) {
	      $megamenustyle_value = $_REQUEST['edit-menu-item-megamenustyle'][$menu_item_db_id];
	      update_post_meta( $menu_item_db_id, '_menu_item_megamenustyle', $megamenustyle_value );
		  }
			if (!isset($_REQUEST['edit-menu-item-megamenu'][$menu_item_db_id])) {
			  $_REQUEST['edit-menu-item-megamenu'][$menu_item_db_id] = '';

			}
			$menu_mega_enabled_value = $_REQUEST['edit-menu-item-megamenu'][$menu_item_db_id];
			update_post_meta( $menu_item_db_id, '_menu_item_megamenu', $menu_mega_enabled_value );
    }

    /**
     * Define new Walker edit
     *
     * @access      public
     * @since       1.0
     * @return      void
    */
    function thb_edit_walker($walker,$menu_id) {
        return 'thb_Nav_Menu_Edit_Custom';
    }
}

// instantiate plugin's class
$GLOBALS['thb_custom_menu'] = new thb_custom_menu();

/**
 *  /!\ This is a copy of Walker_Nav_Menu_Edit class in core
 *
 * Create HTML list of nav menu input items.
 *
 * @package WordPress
 * @since 3.0.0
 * @uses Walker_Nav_Menu
 */
class thb_Nav_Menu_Edit_Custom extends Walker_Nav_Menu  {

	var $thb_icons;
	var $thb_mega_menu_styles;

	function __construct() {
	    $this->thb_icons = thb_getIconArray();
			$this->thb_mega_menu_styles = array(
				'mega-menu-style1' => esc_html__('Tabs Left', 'theissue' ),
				'mega-menu-style2' => esc_html__('Tabs Top', 'theissue' )
			);
	}

	/**
	 * @see Walker_Nav_Menu::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function start_lvl(&$output, $depth = 0, $args = array()) {
	}

	/**
	 * @see Walker_Nav_Menu::end_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function end_lvl(&$output, $depth = 0, $args = array()) {
	}
	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param object $args
	 */
	function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id = 0) {
		global $_wp_nav_menu_max_depth;

		$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		ob_start();
		$item_id = esc_attr( $item->ID );
		$removed_args = array(
		    'action',
		    'customlink-tab',
		    'edit-menu-item',
		    'menu-item',
		    'page-tab',
		    '_wpnonce',
		);



		$original_title = false;
		if ( 'taxonomy' == $item->type ) {
			$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
			if ( is_wp_error( $original_title ) )
				$original_title = false;
		} elseif ( 'post_type' == $item->type ) {
			$original_object = get_post( $item->object_id );
			$original_title = get_the_title( $original_object->ID );
		} elseif ( 'post_type_archive' == $item->type ) {
			$original_object = get_post_type_object( $item->object );
			if ( $original_object ) {
				$original_title = $original_object->labels->archives;
			}
		}

		$classes = array(
		    'menu-item menu-item-depth-' . $depth,
		    'menu-item-' . esc_attr( $item->object ),
		    'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
		);

		$title = $item->title;

		if ( ! empty( $item->_invalid ) ) {
			$classes[] = 'menu-item-invalid';
			/* translators: %s: title of menu item which is invalid */
			$title = sprintf( esc_html__( '%s (Invalid)', 'theissue' ), $item->title );
		} elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
			$classes[] = 'pending';
			/* translators: %s: title of menu item in draft status */
			$title = sprintf( esc_html__('%s (Pending)', 'theissue' ), $item->title );
		}

		$title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;

		$submenu_text = '';
		if ( 0 == $depth )
			$submenu_text = 'style="display: none;"';

		?>
		<li id="menu-item-<?php echo esc_attr($item_id); ?>" class="<?php echo esc_attr( implode(' ', $classes ) ); ?>">
			<dl class="menu-item-bar">
				<dt class="menu-item-handle">
				    <span class="item-title"><?php echo esc_html( $title ); ?></span>
				    <span class="item-controls">
				        <span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
				        <span class="item-order hide-if-js">
				            <a href="<?php
				                echo wp_nonce_url(
				                    add_query_arg(
				                        array(
				                            'action' => 'move-up-menu-item',
				                            'menu-item' => $item_id,
				                        ),
				                        remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
				                    ),
				                    'move-menu_item'
				                );
				            ?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up', 'theissue' ); ?>">&#8593;</abbr></a>
				            |
				            <a href="<?php
				                echo wp_nonce_url(
				                    add_query_arg(
				                        array(
				                            'action' => 'move-down-menu-item',
				                            'menu-item' => $item_id,
				                        ),
				                        remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
				                    ),
				                    'move-menu_item'
				                );
				            ?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down', 'theissue' ); ?>">&#8595;</abbr></a>
				        </span>
				        <a class="item-edit" id="edit-<?php echo esc_attr($item_id); ?>" title="<?php esc_attr_e('Edit Menu Item', 'theissue' ); ?>" href="<?php
				            echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
				        ?>"><span class="screen-reader-text"><?php esc_html_e( 'Edit', 'theissue' ); ?></span></a>
				    </span>
				</dt>
			</dl>

		  <div class="menu-item-settings wp-clearfix" id="menu-item-settings-<?php echo esc_attr($item_id); ?>">
	        <?php if( 'custom' == $item->type ) : ?>
	            <p class="field-url description description-wide">
	                <label for="edit-menu-item-url-<?php echo esc_attr($item_id); ?>">
	                    <?php esc_html_e( 'URL', 'theissue' ); ?><br />
	                    <input type="text" id="edit-menu-item-url-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
	                </label>
	            </p>
	        <?php endif; ?>
	        <p class="description description-thin">
	            <label for="edit-menu-item-title-<?php echo esc_attr($item_id); ?>">
	                <?php esc_html_e( 'Navigation Label', 'theissue' ); ?><br />
	                <input type="text" id="edit-menu-item-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
	            </label>
	        </p>
	        <p class="description description-thin">
	            <label for="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>">
	                <?php esc_html_e( 'Title Attribute', 'theissue' ); ?><br />
	                <input type="text" id="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
	            </label>
	        </p>
	        <p class="field-link-target description">
	            <label for="edit-menu-item-target-<?php echo esc_attr($item_id); ?>">
	                <input type="checkbox" id="edit-menu-item-target-<?php echo esc_attr($item_id); ?>" value="_blank" name="menu-item-target[<?php echo esc_attr($item_id); ?>]" <?php checked( $item->target, '_blank' ); ?> />
	                <?php esc_html_e( 'Open link in a new window/tab', 'theissue' ); ?>
	            </label>
	        </p>
	        <div class="thb_menu_options">
  					<p class="thb-field-link-mega description description-thin">
            	<label for="edit-menu-item-megamenu-<?php echo esc_attr($item_id); ?>">
            		<?php esc_html_e( 'Mega Menu', 'theissue'  ); ?><br />
                <?php $value = get_post_meta( $item_id, '_menu_item_megamenu', true); ?>
                <input type="checkbox" value="enabled" id="edit-menu-item-megamenu-<?php echo esc_attr($item_id); ?>" name="edit-menu-item-megamenu[<?php echo esc_attr($item_id); ?>]" <?php checked( $value, 'enabled' ); ?> />
                <?php esc_html_e( 'Enable', 'theissue'  ); ?>
              </label>
            </p>
						<div class="thb-field-link-mega-style description description-thin">
	            <?php esc_html_e( 'Mega Menu Style', 'theissue' ); ?><br />
	            <?php $saved = get_post_meta( $item_id, '_menu_item_megamenustyle', true); ?>
	            <select id="edit-menu-item-megamenustyle-<?php echo esc_attr($item_id); ?>" name="edit-menu-item-megamenustyle[<?php echo esc_attr($item_id); ?>]">

	            	<?php foreach ($this->thb_mega_menu_styles as $key => $value) { ?>
	            		<option value="<?php echo esc_attr($key); ?>" <?php selected( $key, $saved ); ?>><?php echo esc_html($value); ?></option>
	            	<?php } ?>
	            </select>
	          </div>
            <p class="field-menuanchor description-wide">
            	<label for="edit-menu-item-menuanchor-<?php echo esc_attr($item_id); ?>">
            		<strong><?php esc_html_e( 'Menu Anchor', 'theissue' ); ?></strong><br />
            		<input type="text" id="edit-menu-item-menuanchor-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-menuanchor" name="menu-item-menuanchor[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menuanchor ); ?>" />
            		<span class="description"><?php esc_html_e('Add your row ID without the hashtag.', 'theissue' ); ?></span>
            	</label>
            </p>
        </div>

	        <p class="field-css-classes description description-thin">
	            <label for="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>">
	                <?php esc_html_e( 'CSS Classes (optional)', 'theissue' ); ?><br />
	                <input type="text" id="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
	            </label>
	        </p>
	        <p class="field-xfn description description-thin">
	            <label for="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>">
	                <?php esc_html_e( 'Link Relationship (XFN)', 'theissue'  ); ?><br />
	                <input type="text" id="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
	            </label>
	        </p>
	        <p class="field-description description description-wide">
	            <label for="edit-menu-item-description-<?php echo esc_attr($item_id); ?>">
	                <?php esc_html_e( 'Description', 'theissue' ); ?><br />
	                <textarea id="edit-menu-item-description-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo esc_attr($item_id); ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
	                <span class="description"><?php esc_html_e('The description will be displayed in the menu if the current theme supports it.', 'theissue' ); ?></span>
	            </label>
	        </p>
					<?php do_action( 'wp_nav_menu_item_custom_fields', $item_id, $item, $depth, $args );?>

		  	  <fieldset class="field-move hide-if-no-js description description-wide">
		  	  	<span class="field-move-visual-label" aria-hidden="true"><?php esc_html_e( 'Move', 'theissue' ); ?></span>
		  	  	<button type="button" class="button-link menus-move menus-move-up" data-dir="up"><?php esc_html_e( 'Up one', 'theissue' ); ?></button>
		  	  	<button type="button" class="button-link menus-move menus-move-down" data-dir="down"><?php esc_html_e( 'Down one', 'theissue' ); ?></button>
		  	  	<button type="button" class="button-link menus-move menus-move-left" data-dir="left"></button>
		  	  	<button type="button" class="button-link menus-move menus-move-right" data-dir="right"></button>
		  	  	<button type="button" class="button-link menus-move menus-move-top" data-dir="top"><?php esc_html_e( 'To the top', 'theissue' ); ?></button>
		  	  </fieldset>

		      <div class="menu-item-actions description-wide submitbox">
		          <?php if( 'custom' != $item->type && $original_title !== false ) : ?>
		              <p class="link-to-original">
		                  <?php printf( __('Original: %s', 'theissue' ), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
		              </p>
		          <?php endif; ?>
		          <a class="item-delete submitdelete deletion" id="delete-<?php echo esc_attr($item_id); ?>" href="<?php
		          echo wp_nonce_url(
		              add_query_arg(
		                  array(
		                      'action' => 'delete-menu-item',
		                      'menu-item' => $item_id,
		                  ),
		                  remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
		              ),
		              'delete-menu_item_' . $item_id
		          ); ?>"><?php esc_html_e('Remove', 'theissue' ); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo esc_attr($item_id); ?>" href="<?php echo esc_url( add_query_arg( array('edit-menu-item' => $item_id, 'cancel' => time()), remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) ) ) );
		              ?>#menu-item-settings-<?php echo esc_attr($item_id); ?>"><?php esc_html_e('Cancel', 'theissue' ); ?></a>
		      </div>

		      <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item_id); ?>" />
		      <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
		      <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
		      <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
		      <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
		      <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
		  </div><!-- .menu-item-settings-->
		  <ul class="menu-item-transport"></ul>
		<?php

		$output .= ob_get_clean();

	}
}

/**
 * Mega Menu Walker
 *
 * @access      public
 * @since       1.0
 * @return      void
*/
class thb_MegaMenu_tagandcat_Walker extends Walker_Nav_Menu {
	private $in_sub_menu = 0;
	private $mega_menu_style = 0;
	var $active_megamenu = 0;
	var $mega_menu_content;

	/**
	* @see Walker::start_lvl()
	*
	* @param string $output Passed by reference. Used to append additional content.
	* @param int $depth Depth of page. Used for padding.
	*/
	function start_lvl(&$output, $depth = 0, $args = array()) {
	  $indent = str_repeat("\t", $depth);
	  if($depth === 0) $output .= "\n{replace_one}\n";
	  $output .= "\n$indent<ul class=\"sub-menu ".(($depth === 0) ? "{locate_class}": "")."\">\n";
	}

	/**
	* @see Walker::end_lvl()
	*
	* @param string $output Passed by reference. Used to append additional content.
	* @param int $depth Depth of page. Used for padding.
	*/
	function end_lvl(&$output, $depth = 0, $args = array()) {
	  $indent = str_repeat("\t", $depth);
	  $output .= "$indent</ul>\n";
	  if($depth === 0 && $this->active_megamenu) {
	  	$output.= '<div class="category-children">'.$this->mega_menu_content.'</div></div></div></div>';

	  }
		if($depth === 0) {
			if($this->active_megamenu) {
				$output = str_replace("{replace_one}", '<div class="thb_mega_menu_holder sub-menu '.$this->mega_menu_style.'"><div class="row no-row-padding max_width"><div class="small-12 columns">', $output);
				$output = str_replace("{locate_class}", "thb_mega_menu", $output);
			}
			else {
				$output = str_replace("{replace_one}", "", $output);
			  $output = str_replace("{locate_class}", "", $output);

			}
		}
		$this->mega_menu_content = '';
	}
	function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id = 0) {
	  $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

	  $classes = empty( $item->classes ) ? array() : (array) $item->classes;
	  $classes[] = 'menu-item-' . $item->ID;

	  if( $depth == 1 ) {
      if( ! $this->in_sub_menu ) {
        $this->in_sub_menu = 1;
        array_push($classes, 'active');
      }
	  }
	  if( $depth == 0 ) {
      $this->in_sub_menu = 0;
      $this->active_megamenu = get_post_meta( $item->ID, '_menu_item_megamenu', true);
			$this->mega_menu_style = get_post_meta( $item->ID, '_menu_item_megamenustyle', true);
	  }

	  if($depth === 0 && $this->active_megamenu)
	  {
	  	 array_push($classes, 'menu-item-mega-parent');
	  }

	  $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
	  $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';



	  $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names .'>';

    $atts = array();
    $menu_icon_tag  = ! empty( $item->menuicon ) ? '<i class="fa '.esc_attr( $item->menuicon ).'"></i>' : '';
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

	    $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

	  $item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		if ( $depth == 1 && $this->active_megamenu ) {

		} else {
		   $this->in_sub_menu = 1;
		}
		$item_output .= $menu_icon_tag;
		$item_output .= $args->link_before . '<span>' . apply_filters( 'the_title', $item->title, $item->ID ) .'</span>'. $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

	  $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( $depth == 1 && $this->in_sub_menu ) {

			if (in_array($item->object, array('post_tag','category', 'product_cat', 'product_tag'))) {
				$count = $this->mega_menu_style === 'mega-menu-style1' ? '4' : '3';
				if ($item->object === 'post_tag') {
					$args = array(
				    'tag_id'         => $item->object_id,
				    'post_status'    => 'publish',
				    'posts_per_page' => $count,
				    'no_found_rows'  => true
					);
				} elseif ($item->object === 'category') {
					$args = array(
				    'cat'            => $item->object_id,
				    'post_status'    => 'publish',
				    'posts_per_page' => $count,
				    'no_found_rows'  => true
					);
				} elseif ($item->object === 'product_cat') {
					$cat = get_term($item->object_id, 'product_cat');
					$args = array(
						'post_type'      => 'product',
						'post_status'    => 'publish',
						'product_cat'    => $cat->slug,
						'posts_per_page' => $count,
						'no_found_rows'  => true
					);
				} elseif ($item->object === 'product_tag') {
					$tag = get_term($item->object_id, 'product_tag');
					$args = array(
						'post_type'      => 'product',
						'post_status'    => 'publish',
				    'product_tag'    => $tag->slug,
				    'posts_per_page' => $count,
				    'no_found_rows'  => true
					);

				}
				$is_product_related = in_array($item->object, array('product_cat', 'product_tag'));

				$thb_cache_key = 'thb-mega-menu-' . $item->object_id . '-' . $this->mega_menu_style;
				$html = get_transient( $thb_cache_key );
				if ( !$html ) {
					ob_start();
					$mega_query = new WP_Query($args);

					$classes[] = 'tab-holder';
					$classes[] = 'row';
					$classes[] = $this->mega_menu_style;
					$classes[] = $is_product_related ? 'products' : false;

					$column = $count == '3' ? 'small-4' : 'small-3';
					echo '<div class="'.esc_attr(implode(' ', $classes)).'">';
					if ($mega_query->have_posts()) {
						while ($mega_query->have_posts()) : $mega_query->the_post();
							echo '<div class="'.esc_attr($column).' columns">';
								if ( $is_product_related && thb_wc_supported() ) {
									wc_get_template_part( 'content', 'product-menu' );
								} else {
									get_template_part( 'inc/templates/post-styles/mega-menu/' . $this->mega_menu_style );
								}
							echo '</div>';
						endwhile;
					}
					echo '</div>';

					$html = ob_get_clean();
					wp_reset_query();
					set_transient( $thb_cache_key, $html, HOUR_IN_SECONDS );
				}
				$this->mega_menu_content .= $html;
			}
		}
		$output .= "</li>\n";
	}
}