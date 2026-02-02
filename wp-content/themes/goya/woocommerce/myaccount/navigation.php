<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $current_user;

do_action( 'woocommerce_before_account_navigation' );
$endpoint = !empty( WC()->query->get_current_endpoint() ) ? WC()->query->get_current_endpoint() : 'dashboard';
?>

<nav class="woocommerce-MyAccount-navigation account-endpoint endpoint-<?php echo esc_attr($endpoint) ?>" aria-label="<?php esc_html_e( 'Account pages', 'woocommerce' ); ?>">
	<div class="et-MyAccount-user">
		<div class="et-MyAccount-user-image">
		   <?php echo get_avatar( $current_user->user_email, '60', null, null, $args = array( 'class' => array( 'lazyload' ) ) ); ?>
		</div>
		
		<div class="et-MyAccount-user-info">
			<span class="et-username">
				<?php
					printf(
					    __( 'Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'woocommerce' ),
					    '<strong>' . esc_html( $current_user->display_name ) . '</strong><span class="hide">',
					    esc_url( wc_logout_url( wc_get_page_permalink( 'myaccount' ) ) )
					);
					echo '</span></span>';
				?>
			</span>
		</div>
	</div>
	
	<ul>
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
			<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" <?php echo wc_is_current_account_menu_item( $endpoint ) ? 'aria-current="page"' : ''; ?>><?php echo esc_html( $label ); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
