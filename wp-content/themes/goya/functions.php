<?php

/**
 * Goya functions and definitions
 *
 * @link    https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Goya
 */

// Constants: Folder directories/uri's
define( 'GOYA_THEME_DIR', get_template_directory() );
define( 'GOYA_DIR', get_template_directory() . '/inc' );
define( 'GOYA_THEME_URI', get_template_directory_uri() );
define( 'GOYA_URI', get_template_directory_uri() . '/inc' );

// Constant: Framework namespace
define( 'GOYA_NAMESPACE', 'goya' );

// Constant: Theme version
define( 'GOYA_THEME_VERSION', '1.0.11.0' );


// Theme setup
if (! apply_filters('goya_disable_setup_wizard', false) == true) {

  // TGM Plugin Activation Class
  require GOYA_DIR .'/admin/plugins/plugins.php';

  // Imports
  require GOYA_DIR .'/admin/imports/import.php';

  // Theme Wizard
  require_once get_parent_theme_file_path( '/inc/merlin/vendor/autoload.php' );
  require_once get_parent_theme_file_path( '/inc/merlin/class-merlin.php' );
  require_once get_parent_theme_file_path( '/inc/admin/setup/merlin-config.php' );
  require_once get_parent_theme_file_path( '/inc/admin/setup/merlin-filters.php' );

}

// Frontend Functions
require GOYA_DIR .'/misc.php';
require GOYA_DIR .'/frontend/header.php';
require GOYA_DIR .'/frontend/footer.php';
require GOYA_DIR .'/frontend/panels.php';
require GOYA_DIR .'/frontend/entry.php';

// Script Calls
require GOYA_DIR .'/script-calls.php';

// Custom Modifications
require GOYA_DIR .'/custom-modifications.php';

// Ajax
require GOYA_DIR .'/ajax.php';

// Add Menu Support
require GOYA_DIR .'/mega-menu.php';

// Enable Sidebars
require GOYA_DIR .'/sidebar.php';

// Language/Currency switchers
require GOYA_DIR .'/switchers.php';

// WooCommerce related functions
require GOYA_DIR .'/woocommerce/wc-functions.php';
require GOYA_DIR .'/woocommerce/wc-elements.php';
require GOYA_DIR .'/woocommerce/category-image.php';

// Gutenberg related functions
require GOYA_DIR .'/gutenberg.php';

// CSS Output of Theme Options
require GOYA_DIR .'/custom-styles.php';

// Kirki: Load Config options
require GOYA_DIR .'/admin/settings/kirki.config.php';

// Custom showcase padding CSS
function marsey_custom_showcase_css() {
	$custom_css = get_option('marsey_custom_showcase_css', '');
	if (!empty($custom_css)) {
		echo '<style id="marsey-custom-showcase-css">' . $custom_css . '</style>';
	}
}
add_action('wp_head', 'marsey_custom_showcase_css', 100);

// Mobile Header Layout Fix - Added by script
add_action('wp_head', function() {
    ?>
    <style>
        @media only screen and (max-width: 991px) {
            /* Force header-main to use grid layout */
            .site-header .header-main .header-contents {
                display: grid !important;
                grid-template-columns: 50px 1fr 50px !important;
                gap: 0 !important;
                align-items: center !important;
                width: 100% !important;
            }
            /* Left items (menu) - column 1 */
            .site-header .header-main .header-left-items {
                grid-column: 1 !important;
                justify-self: start !important;
                margin: 0 !important;
            }
            /* Center items (logo) - column 2 */
            .site-header .header-main .header-center-items {
                grid-column: 2 !important;
                justify-self: center !important;
                margin: 0 !important;
            }
            /* Right items (cart etc) - column 3 */
            .site-header .header-main .header-right-items {
                grid-column: 3 !important;
                justify-self: end !important;
                margin: 0 !important;
                display: flex !important;
                justify-content: flex-end !important;
            }
        }
    </style>
    <?php
}, 999);

// Add GTranslate widget after cart icon
add_action('goya_quick_cart', function() {
    if (shortcode_exists('gtranslate')) {
        echo '<div class="gtranslate-header-widget" style="display: inline-flex; align-items: center; margin-left: 20px; vertical-align: middle;">';
        echo do_shortcode('[gtranslate]');
        echo '</div>';
        ?>
        <style>
            .gtranslate-header-widget {
                line-height: 1;
            }
            .gtranslate-header-widget a,
            .gtranslate-header-widget select,
            .gtranslate-header-widget .gt_switcher_wrapper {
                display: inline-flex !important;
                align-items: center !important;
                gap: 8px !important;
                font-size: 14px !important;
                line-height: 1 !important;
                padding: 8px 12px !important;
                border-radius: 4px !important;
                transition: all 0.3s ease !important;
            }
            .gtranslate-header-widget img {
                width: 24px !important;
                height: 24px !important;
                object-fit: contain !important;
                vertical-align: middle !important;
            }
            .gtranslate-header-widget a {
                text-decoration: none !important;
                color: #333 !important;
                background: rgba(0,0,0,0.05) !important;
            }
            .gtranslate-header-widget a:hover {
                background: rgba(0,0,0,0.1) !important;
            }
            .gtranslate-header-widget select {
                border: 1px solid rgba(0,0,0,0.1) !important;
                background: rgba(0,0,0,0.05) !important;
                cursor: pointer !important;
            }
            .gtranslate-header-widget .gt_selector {
                display: inline-flex !important;
                align-items: center !important;
            }
            @media (max-width: 991px) {
                .gtranslate-header-widget {
                    margin-left: 10px;
                }
            }
        </style>
        <?php
    }
}, 20);
